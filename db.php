<?php
$host = "localhost";
$user = "root";  // Change this if you have a different DB user
$pass = "";      // Set password if applicable
$db = "forum_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
