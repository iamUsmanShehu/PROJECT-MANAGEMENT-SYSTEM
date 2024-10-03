<?php
session_start();
include 'db_connect.php';

// Check if the user is a SuperAdmin
if ($_SESSION['role'] !== 'SuperAdmin') {
    header('Location: login.php');
    exit;
}

$admin_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM users WHERE user_id='$admin_id' AND role='Admin'";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "UPDATE users SET username='$username', email='$email' WHERE user_id='$admin_id' AND role='Admin'";
    if (mysqli_query($conn, $sql)) {
        echo "Admin updated successfully!";
        header('Location: superadmin_dashboard.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
</head>
<body>
    <h1>Edit Admin</h1>
    <form method="POST" action="edit_admin.php?id=<?php echo $admin_id; ?>">
        <input type="text" name="username" value="<?php echo $admin['username']; ?>" required><br>
        <input type="email" name="email" value="<?php echo $admin['email']; ?>" required><br>
        <button type="submit">Update Admin</button>
    </form>
</body>
</html>

<?php
mysqli_close($conn);
?>
