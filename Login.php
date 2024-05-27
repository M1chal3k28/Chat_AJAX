<?php
    session_start();
    if(isset($_SESSION["username"])) {
        header("Location: chat.php");
        exit();
    }
    
    include('Database.php');

    // Handle registration
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) {
            $error = "Username doesn't exist";
        } else {
            $row = $result->fetch_assoc();
            $hashedPass = $row["password"];

            if(password_verify($password, $hashedPass)) {
                $_SESSION["username"] = $username;
                header("Location: chat.php");
            } else {
                $error = "Wrong Password!";
            }
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <main id="register">
        <h1>Login</h1>
        <?php if(isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form action=<?php echo $_SERVER["PHP_SELF"]; ?> method="post">
            <label for="username">Username: </label>
                <input type="text" name="username" required> <br>
            <label for="password">Password: </label>
                <input type="password" name="password" required> <br>
            
            <button>Log in</button>
        </form>

        <p>New account? <a href="register.php">Register here</a>.</p>
    </main>
</body>
</html>