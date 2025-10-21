<?php
include 'includes/db.php'; //connect to the database
include 'includes/functions.php';
$message = ""; //to show errors or success

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username =trim($_POST['username']);
    $password = trim($_POST['password']);

    //check if the user name exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $message ="There is somebody else with this name !!";
    }else{
        //Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        //Insert new user
        $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?) ");
        $insert->bind_param("ss", $username, $hashedPassword);

        if ($insert->execute()){
            $message = "Signup successful! You can now login.";
        }else{
            $message ="Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
    <h2>Create Account</h2>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Sign Up</button>
    </form>
    <p style="color:blue;"><?php echo $message; ?></p>
    <a href="login.php">Go to Login</a>
</body>
</html>
