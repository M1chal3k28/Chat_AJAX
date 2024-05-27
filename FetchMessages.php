<?php
    session_start();
    include("Database.php");

    if(!isset($_SESSION["username"]))
        exit("You are not logged");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $sender = $_POST["sender"];
        $receiver = $_POST["receiver"];

        $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY creationDate");
        $stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0) 
            while($row = $result->fetch_assoc()) 
                echo '<div class="message"><strong>'. ucfirst($row["sender"]) .':</strong> '. $row["message"] .'</div>';
    }

    $conn->close();
?>