<?php
session_start();
if ($_SESSION['role'] !== 'User') {
    header('Location: login.php');
    exit;
}

echo "Welcome User: " . $_SESSION['username'];
// Add User functionalities here
?>
