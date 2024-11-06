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
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <form action="logout.php" method="POST">
        <input type="submit" name="logout" value="Logout" onclick="return confirm('Are you sure to Logout?')" />
    </form>
    <h1>IDN Backpacker School</h1>

    <h2>Classes :</h2>
    <button type="button" onclick="toNewClass()">Add New Class</button>
    <table border="1">
        <tr>
            <th>No.</th>
            <th>ID</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        <?php
        $query = "SELECT * FROM class";
        $result = $conn->query($query);
        $i = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["grade"] . "</td>";
                echo "<td>";
                echo "<button type='button' onclick='toEditClass(" . $row["id"] . ")'>Edit</button>";
                echo "<form action='delete_class.php' method='POST'><input type='hidden' name='id' value='" . $row["id"] . "'><button type='submit' name='delete' value='delete' onclick='return confirm(\"Are you sure to delete this class?\")'>Delete</button></form>";
                echo "</td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo "<td colspan='4'>No Data</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br /><br />

    <h2>Students :</h2>
    <button type="button" onclick="toNewStudent()">Add New Students</button>
    <table border="1">
        <tr>
            <th>No.</th>
            <th>ID</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Age</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        <?php
        $queryStudent = "SELECT * FROM class JOIN students ON students.class_id = class.id";
        $resultStudent = $conn->query($queryStudent);
        $i = 1;
        if ($resultStudent->num_rows > 0) {
            while ($rowStudent = $resultStudent->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $rowStudent["id"] . "</td>";
                echo "<td><img src='" . $rowStudent["photo"] . "' alt='". $rowStudent["name"] ."' width='100px' /></td>";
                echo "<td>" . $rowStudent["name"] . "</td>";
                echo "<td>" . $rowStudent["age"] . "</td>";
                echo "<td>" . $rowStudent["grade"] . "</td>";
                echo "<td>";
                echo "<button type='button' onclick='toEditStudent(" . $rowStudent["id"] . ")'>Edit</button>";
                echo "<form action='delete_student.php' method='POST'>
                    <input type='hidden' name='id' value='" . $rowStudent["id"] . "'>
                    <button type='submit' name='delete' value='delete' onclick='return confirm(\"Are you sure to delete this student?\")'>Delete</button>
                 </form>";
                echo "</td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo "<td colspan='6'>No Data</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script src="script.js"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>