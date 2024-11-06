<?php
session_start();
include "conn.php";
include 'token_validation.php';
if (!isset($_SESSION["token"])) {
    header("Location: login.php");
    exit();
} else {
    $token = $_SESSION["token"];
    if (!validateToken($token)) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        $is_admin = $_SESSION["is_admin"];
        if (!$is_admin) {
            header("Location: index.php");
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $query = "SELECT * FROM class WHERE id = $id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                echo "No class found";
                exit();
            }
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
</head>

<body>
    <button type="button" onclick="toHome()">Home</button>
    <h1>Edit Class</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $_POST["id"] ?? $row["id"] ?>">
        <label for="grade">Grade :</label><br />
        <input type="text" name="grade" id="grade" value="<?php echo $row["grade"] ?? ""  ?>"><br />
        <button type="submit">Update</button>
    </form>

    <script src="script.js"></script>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (empty($_POST["grade"])) {
            echo "<i style='color:red'>Grade cannot be empty</i>";
            exit();
        }

        $id = $_POST["id"];
        $grade = $_POST["grade"];
        $query = "UPDATE class SET grade = '$grade' WHERE id = $id";
        $result =  $conn->query($query);
        if ($result) {
            // echo "Class updated successfully";
            header("Location: index.php");
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}

?>