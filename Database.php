<?php
    // database connection
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "chat";

    // conn object
    $conn = mysqli_connect($servername, $username, $pass);

    $res = $conn->execute_query("SELECT SCHEMA_NAME
                                 FROM INFORMATION_SCHEMA.SCHEMATA
                                 WHERE SCHEMA_NAME = 'chat'");

    if($res->num_rows == 0) {
        $sql = "CREATE DATABASE $dbname;";
        $usersTab = "CREATE TABLE users(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        );";

        $messagesTab = "CREATE TABLE messages(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            sender VARCHAR(255) NOT NULL,
            receiver VARCHAR(255) NOT NULL,
            message TEXT NOT NULL
        );";

        $res = $conn->query($sql);
        if($res === false) {
            echo "Failed to create database !";
            die;
        }
        
        $conn->select_db($dbname);

        $res |= $conn->query($usersTab);
        $res |= $conn->query($messagesTab);

        if($res === false) {
            echo "Failed to create database !";
            die;
        }
    } else {
        $conn->select_db($dbname);
    }
?>