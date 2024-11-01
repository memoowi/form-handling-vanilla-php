<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        if(isset($_GET["id"])){
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
        <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
        <label for="grade">Grade :</label><br />
        <input type="text" name="grade" id="grade" value="<?php echo $row["grade"] ?>"><br />
        <button type="submit">Update</button>
    </form>

    <script src="script.js"></script>
</body>
</html>

