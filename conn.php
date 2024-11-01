<?php

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "idn_backpacker_school";
    // $dbname = "hmmm";

    try {
        $conn = mysqli_connect($hostname, $username, $password, $dbname);

        // if ($conn) {
        //     echo "Connection Success";
        // }

    } catch (mysqli_sql_exception $e) {
        echo "An error has occured : " . $e->getMessage();
    }


?>