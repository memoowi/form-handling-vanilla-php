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
    <title>Login</title>
</head>

<body>
    <h1>Login New User</h1>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label for="emaik">Email: </label><br>
        <input type="email" name="email" id="email"><br>
        <label for="password">Password: </label><br>
        <input type="password" name="password" id="password"><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            echo "<i style='color:red'>All fields are required</i>";
            exit();
        }
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];

        // check email is exist or not
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // ada email terdaftar
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // generate token
                $token = bin2hex(random_bytes(32));
                $expiration = date("Y-m-d H:i:s", time() + 3600); // 1 jam

                $stmt = $conn->prepare("INSERT INTO tokens (user_id, token, expiration) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user["id"], $token, $expiration);

                if ($stmt->execute()) {
                    $_SESSION["is_admin"] = $user["is_admin"];
                    $_SESSION["token"] = $token;
                    header("Location: index.php");
                }
            } else {
                // Password salah
                echo "<i style='color:red'>Wrong password</i>";
                exit();
            }
        } else {
            // Email tidak terdaftar
            echo "<i style='color:red'>User not found</i>";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>