<?php
    // database connection
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "chat";

    // conn object
    $conn = mysqli_connect($servername, $username, $pass, $dbname);

    if($conn === false) {
        echo "<p>Connection failed !</p>";
        die;
    }
?>