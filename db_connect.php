<?php
$host = 'localhost';
$dbname = 'project_management_system';
$user = 'root';  // Your database username
$pass = '';      // Your database password

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
