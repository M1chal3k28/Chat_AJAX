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

        if($result->num_rows > 0) {
            $error = "Username already exists";
        } else {
            $stmt = $conn->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $username, $hashedPassword);

            if($stmt->execute()) {
                $_SESSION["username"] = $username;
                header("Location: chat.php");
                exit();
            } else {
                $error = "Registration failed";
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
        <h1>Register</h1>
        <?php if(isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action=<?php echo $_SERVER["PHP_SELF"]; ?> method="post">
            <label for="username">Username: </label>
                <input type="text" name="username" required> <br>
            <label for="password">Password: </label>
                <input type="password" name="password" required> <br>
            
            <button>Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </main>
</body>
</html>