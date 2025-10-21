<?php
session_start();
include 'includes/db.php'; // connect to database
include 'includes/functions.php';
$message = "";

// Initialize login attempts for rate limiting
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if (!isset($_SESSION['last_attempt'])) {
    $_SESSION['last_attempt'] = time();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Rate limiting: max 3 attempts per 60 seconds
    if ($_SESSION['attempts'] >= 3 && (time() - $_SESSION['last_attempt']) < 60) {
        $message = "Too many login attempts. Try again later.";
    } else {
        // Reset attempts if more than 60 seconds passed
        if ((time() - $_SESSION['last_attempt']) >= 60) {
            $_SESSION['attempts'] = 0;
        }

        // Check if the username exists
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['attempts'] = 0; // Reset attempts on success
                $message = "Login successful! Welcome back, $username.";

                // Redirect to protected page
                header("Location: dashboard.php");
                exit(); // Always exit after header redirect
            } else {
                $message = "Incorrect password!";
                $_SESSION['attempts'] += 1;
                $_SESSION['last_attempt'] = time();
            }
        } else {
            $message = "Username not found!";
            $_SESSION['attempts'] += 1;
            $_SESSION['last_attempt'] = time();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p style="color:red;"><?php echo $message; ?></p>
    <a href="signup.php">Go to Signup</a>
</body>
</html>
