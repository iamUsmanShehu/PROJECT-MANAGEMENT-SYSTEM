<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if the email already exists
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists
        echo "Error: The email address is already registered. Please use a different email.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, email, password, role) 
                VALUES ('$username', '$email', '$password', '$role')";

        if (mysqli_query($conn, $sql)) {
            echo "Registration successful!";
            // Redirect to login or dashboard, as required
            header('Location: login.php');
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>


<!-- Registration Form -->
<form method="POST" action="register.php">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role" required>
        <option value="SuperAdmin">SuperAdmin</option>
        <option value="Admin">Admin</option>
        <option value="User">User</option>
    </select><br>
    <button type="submit">Register</button>
</form>
