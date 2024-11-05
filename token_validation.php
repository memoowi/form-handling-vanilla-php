<?php
include 'conn.php';

function validateToken($token) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT user_id FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if($row["expiration"] > time()) {
                return true;
            } else {
                $stmt = $conn->prepare("DELETE FROM tokens WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                return false;
            }
        }
        return false;
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>