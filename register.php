<?php
session_start();
include "conn.php";
if (isset($_SESSION["token"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h1>Register New User</h1>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label for="name">Name: </label><br>
        <input type="text" name="name" id="name"><br>
        <label for="emaik">Email: </label><br>
        <input type="email" name="email" id="email"><br>
        <label for="password">Password: </label><br>
        <input type="password" name="password" id="password"><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
            echo "<i style='color:red'>All fields are required</i>";
            exit();
        }
        $name = trim($_POST["name"]);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // check email is exist or not
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // ada email terdaftar
            echo "<i style='color:red'>Email already registered</i>";
            exit();
        }

        // insert the new user to db
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if($stmt->execute()) {
            $userId = $stmt->insert_id;
            $token = bin2hex(random_bytes(32));
            $expiration = date("Y-m-d H:i:s", time() + 3600); // 1 jam

            $stmt = $conn->prepare("INSERT INTO tokens (user_id, token, expiration) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $token, $expiration);

            if($stmt->execute()) {
                $_SESSION["user_id"] = $userId;
                $_SESSION["token"] = $token;
                header("Location: index.php");
            }
        }

    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>