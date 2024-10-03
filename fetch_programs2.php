<?php
// Database connection
$db = mysqli_connect("localhost", "root", "", "project_management_system");

// Get department_id from request
if (isset($_GET['department_id']) && is_numeric($_GET['department_id'])) {
    $departmentId = $_GET['department_id'];

    // Fetch programs related to the department
    $programsQuery = mysqli_query($db, "SELECT program_id, name FROM programs WHERE department_id = $departmentId");
    $programs = [];

    while ($row = mysqli_fetch_assoc($programsQuery)) {
        $programs[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($programs);
}

// Close the database connection
mysqli_close($db);
?>
