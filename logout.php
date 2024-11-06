<?php
include 'conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_SESSION["token"])) {
            $token = $_SESSION["token"];
            $stmt = $conn->prepare("DELETE FROM tokens WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
        }
    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    } finally {
        $conn->close();
        session_destroy();
        header("Location: index.php");
    }
}

?>