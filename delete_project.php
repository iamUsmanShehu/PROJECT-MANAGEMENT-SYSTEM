<?php
// Database connection
$db = mysqli_connect("localhost", "root", "", "project_management_system");

// Check if project ID is set
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $projectId = $_GET['id'];

    // Prepare the delete statement
    $deleteQuery = mysqli_prepare($db, "DELETE FROM projects WHERE project_id = ?");
    mysqli_stmt_bind_param($deleteQuery, 'i', $projectId);

    // Execute the delete query
    if (mysqli_stmt_execute($deleteQuery)) {
        // Redirect after successful deletion
        header("Location: view_projects.php?success=Project deleted successfully");
        exit();
    } else {
        echo "Error deleting project: " . mysqli_error($db);
    }

    // Close the statement
    mysqli_stmt_close($deleteQuery);
} else {
    echo "Invalid project ID.";
}

// Close the database connection
mysqli_close($db);
?>
