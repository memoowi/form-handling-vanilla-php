<?php
include 'conn.php';
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
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
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

    try {
        $sql = "INSERT INTO students (name, age, class_id) VALUES ('$name', '$age', '$class')";
        $result = $conn->query($sql);

        if ($result) {
            // echo "New record created successfully";
            header("Location: index.php");
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
