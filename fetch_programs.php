<?php
include 'db_connect.php';

if (isset($_POST['department_id'])) {
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    
    $sql = "SELECT * FROM programs WHERE department_id = '$department_id'";
    $result = mysqli_query($conn, $sql);
    
    echo '<option value="">Select Program</option>';
    while ($program = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $program['program_id'] . '">' . $program['name'] . '</option>';
    }
}
?>
