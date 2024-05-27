<?php
    session_start();
    include("Database.php");

    if(!isset($_SESSION["username"]))
        exit("You didn't log in");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $sender = $_POST["sender"];
        $receiver = $_POST["receiver"];
        $message = $_POST["message"];
        $stmt = $conn->prepare("INSERT INTO messages(sender, receiver, message) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $sender, $receiver, $message);
        $stmt->execute();
    }

    $conn->close();
?>