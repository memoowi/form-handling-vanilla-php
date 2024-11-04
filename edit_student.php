<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $sql = "SELECT * FROM students WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $rowStudent = $result->fetch_assoc();
                $name = $rowStudent["name"];
                $age = $rowStudent["age"];
                $class = $rowStudent["class_id"];
            } else {
                echo "No student found.";
                exit();
                // header("Location: index.php");
            }
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>

<body>
    <button type="button" onclick="toHome()">Home</button>
    <h1>Edit Student</h1>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo $_POST["name"] ?? $name ; ?>"> <br>
        <label for="age">Age</label>
        <input type="text" name="age" id="age" value="<?php echo $_POST["age"] ?? $age ; ?>"> <br>
        <label for="class">Class</label>
        <select name="class" id="class">
            <option value="" disabled>--- Select Class ---</option>
            <?php
            $sql = "SELECT * FROM class";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected = ($class == $row['id']) ? 'selected' : '';
                    echo "<option value='" . $row["id"] . "' " . $selected . ">" . $row["grade"] . "</option>";
                }
            }
            ?>
        </select> <br>
        <button type="submit">Update</button>
    </form>

    <script src="script.js"></script>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        echo "<i style='color:red'>Name is required</i>";
        exit();
    } else if (strlen($_POST["name"]) < 3) {
        echo "<i style='color:red'>Name must be at least 3 characters</i>";
        exit();
    }
    if (empty($_POST["age"])) {
        echo "<i style='color:red'>Age is required</i>";
        exit();
    } else if (!is_numeric($_POST["age"])) {
        echo "<i style='color:red'>Age must be a number</i>";
        exit();
    }
    if (empty($_POST["class"])) {
        echo "<i style='color:red'>Class is required</i>";
        exit();
    }

    try {
        $id = $_GET["id"];
        $name = $_POST["name"];
        $age = $_POST["age"];
        $class = $_POST["class"];
        $sql = "UPDATE students SET name = '$name', age = '$age', class_id = '$class' WHERE id = '$id'";
        $result = $conn->query($sql);
        if ($result) {
            header("Location: index.php");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}