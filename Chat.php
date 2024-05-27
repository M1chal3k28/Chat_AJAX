<?php
    session_start();
    if(!isset($_SESSION["username"])) {
        header("Location: login.php");
    }

    include("Database.php");

    $selectedUser = null;
    $showChatBox = false;
    if(isset($_GET["user"])) {
        $selectedUser = mysqli_real_escape_string($conn, $_GET["user"]);
        $showChatBox = true;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username != ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    
    $result = $stmt->get_result();

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <section id="chatContainer">
        <header>
            <section id="welcome">
                <p>Welcome <?php echo $_SESSION["username"]; ?>! </p>
                <button onclick="location.href='Logout.php'">Logout</button>
            </section>
        </header>

        <main>
            <h1>Select user to chat with: </h1>
            <?php while($row = $result->fetch_assoc()) : ?>
                <div class="user" onclick="location.href='?user=<?php echo htmlspecialchars($row['username']); ?>'"><p><?php echo htmlspecialchars($row["username"]); ?></p></div>
            <?php endwhile; ?>
        </main>
    </section>

    <?php if($showChatBox) : ?>
        <section id="chatBox">
            <div id="chatBoxHeader">
                <h2><?php echo ucfirst($selectedUser); ?></h2>
                <button id="closeBtn" onclick="closeChat();">X</button>
            </div>

            <div id="chatBoxBody">

            </div>

            <form id="chatForm" >
                <input type="hidden" id="sender" value=<?php echo $_SESSION["username"]; ?> >
                <input type="hidden" id="receiver" value=<?php echo $selectedUser; ?> >
                <input type="text" id="message" placeholder="Type your message..." required>
                <button>Send</button>
            </form>
        </section>  
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const closeChat = () => {
            document.querySelector("#chatBox").style.display = "none";
        };  

        const scrollToChatBottom = () => {
            const chatBox = $('#chatBoxBody');
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        };

        const fetchMessages = () => {
            const sender = $("#sender").val();
            const receiver = $("#receiver").val();

            $.ajax({
                url: "FetchMessages.php",
                type: "POST",
                data: {sender: sender, receiver: receiver},
                success: function(data) {
                    $('#chatBoxBody').html(data);
                    scrollToChatBottom();
                }
            });
        };

        $(document).ready(function() {
            fetchMessages();
            setInterval(fetchMessages, 3000);
        });

        $("#chatForm").submit(function(e) {
            e.preventDefault();
            const sender = $("#sender").val();
            const receiver = $("#receiver").val();
            const message = $("#message").val();

            $.ajax({
                url: "SubmitMessage.php",
                type: "POST",
                data: {sender: sender, receiver: receiver, message: message},
                success: function() {
                    $("#message").val('');
                    fetchMessages();
                }
            });
        });
    </script>
</body>
</html>