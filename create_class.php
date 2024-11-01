<?php
include "conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Class</title>
</head>

<body>
    <button type="button" onclick="toHome()">Home</button>
    <h1>Add New Class</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
        <label for="grade">Grade :</label><br />
        <input type="text" name="grade" id="grade"><br />
        <button type="submit">Add</button>
    </form>

    <script src="script.js"></script>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade = $_POST["grade"];

    if(empty($grade)){
        echo "<i style='color:red'>Grade cannot be empty</i>";
        exit();
    }

    try {
        $query = "INSERT INTO class (grade) VALUES ('$grade')";
        $result = $conn->query($query);

        if ($result) {
            // echo "<i style='color:green'>Class added successfully</i>";
            header("Location: index.php");
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}

?>