<?php
session_start();

//checks if username is logged in
if(!isset($_SESSION['username'])){
       // Not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username']; //get username
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>This is a protected page only accessible after login.</p>
    <a href="logout.php">Logout</a>
</body>
</html>