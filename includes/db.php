<?php
//Define the database connection details
$servername = "localhost"; //XAMPP MySQL rubs lovally
$username = "root"; //default username in XAMPP
$password = ""; //default password is empty
$dbname = "login_demo"; //The name of the database we created


//Our connection
$conn = new mysqli ($servername, $username, $password, $dbname );

//Check if the connection work

if($conn->connect_error){

    die("connection failed: " . $conn->connection_error);
}

