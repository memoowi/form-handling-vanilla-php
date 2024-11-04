<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            $query = "SELECT photo FROM students WHERE id = '$id'";
            $photo = mysqli_fetch_assoc($conn->query($query))["photo"];
            if (file_exists($photo)) {
                unlink($photo);
            }
            $sql = "DELETE FROM students WHERE id = '$id'";
            $result = $conn->query($sql);
            if ($result) {
                header("Location: index.php");
            }
        }
    } catch (mysqli_sql_exception $e) {
        // echo "An error occurred: " . $e->getMessage();
        echo "An error occurred: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}
