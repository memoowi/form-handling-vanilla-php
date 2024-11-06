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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
</head>

<body>
    <button type="button" onclick="toHome()">Home</button>
    <h1>Add Student</h1>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name"> <br>
        <label for="age">Age</label>
        <input type="text" name="age" id="age"> <br>
        <label for="class">Class</label>
        <select name="class" id="class">
            <option value="" disabled selected>--- Select Class ---</option>
            <?php
            $sql = "SELECT * FROM class";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["grade"] . "</option>";
                }
            }
            ?>
        </select> <br>
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo" accept="image/*"> <br>
        <button type="submit">Add</button>
    </form>

    <script src="script.js"></script>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? "";
    $age = $_POST["age"] ?? "";
    $class = $_POST["class"] ?? "";
    $photo = $_FILES["photo"] ?? null;

    if (empty($name) || empty($age) || empty($class)) {
        echo "<i style='color:red'>All fields are required</i>";
        exit();
    } elseif (strlen($name) < 3) {
        echo "<i style='color:red'>Name must be at least 3 characters</i>";
        exit();
    } elseif (!is_numeric($age)) {
        echo "<i style='color:red'>Age must be a number</i>";
        exit();
    }
    // validation for extension name
    if (!in_array(pathinfo($photo["name"], PATHINFO_EXTENSION), ["jpg", "jpeg", "png", "gif"])) {
        /// do some validation
        echo "<i style='color:red'>Only jpg, jpeg, png, gif files are allowed</i>";
        exit();
    }

    // validation on size maximum 5MB
    if ($photo["size"] > 5120000) {
        // do something
        echo "<i style='color:red'>File size must be less than 5MB</i>";
        exit();
    }

    $target_dir = "uploads/";
    // $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    // renamed file
    $renamed_file = $target_dir . uniqid() . "-" . time() . "." . pathinfo($photo["name"], PATHINFO_EXTENSION);

    try {
        move_uploaded_file($photo["tmp_name"], $renamed_file);
        $sql = "INSERT INTO students (name, age, class_id, photo) VALUES ('$name', '$age', '$class', '$renamed_file')";
        $result = $conn->query($sql);

        if ($result) {
            // echo "New record created successfully";
            header("Location: index.php");
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}
