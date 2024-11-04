<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    try {
        if(isset($_POST["id"])) {
            $id = $_POST["id"];
            $query = "DELETE FROM class WHERE id = '$id'";
            $result = $conn->query($query);
            if ($result) {
                header("Location: index.php");
            }
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
}
?>