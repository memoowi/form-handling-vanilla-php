<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            $sql = "DELETE FROM students WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result) {
                header("Location: index.php");
            }
        }
    } catch (mysqli_sql_exception $e) {
        // echo "An error occurred: " . $e->getMessage();
        echo "An error occurred: " . $e->getMessage();
    }
}
