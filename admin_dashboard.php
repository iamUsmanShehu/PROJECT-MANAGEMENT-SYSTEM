<?php
session_start();
include 'db_connect.php';

// Check if the user is an Admin
if ($_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

$total_projects = "SELECT COUNT(project_id) AS 'Total' FROM `projects`";
  $projects_stmt = $conn->prepare($total_projects);
  $projects_stmt->execute();
  $projects_result = $projects_stmt->get_result();
  
  if ($projects_result->num_rows > 0) {
      $total = $projects_result->fetch_assoc();
      $projects_total = $total['Total'];
  }


// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $abstract = mysqli_real_escape_string($conn, $_POST['abstract']);
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    $program_id = mysqli_real_escape_string($conn, $_POST['program_id']);
    $uploaded_by = $_SESSION['user_id']; // Assume user_id is stored in session when Admin logs in

    $target_dir = "uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file type
    $allowed_types = array("pdf", "doc", "docx", "ppt", "pptx");
    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Insert file information into the database
            $sql = "INSERT INTO projects (title, abstract, file_path, department_id, program_id, uploaded_by) 
                    VALUES ('$title', '$abstract', '$target_file', '$department_id', '$program_id', '$uploaded_by')";

            if (mysqli_query($conn, $sql)) {
                echo "Project uploaded successfully!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Only PDF, DOC, DOCX, PPT, and PPTX files are allowed.";
    }
}

// Fetch all uploaded projects
$sql = "SELECT p.project_id, p.title, p.abstract, p.file_path, d.name AS department_name,  pr.name AS program_name, u.username, p.created_at
        FROM projects p
        JOIN departments d ON p.department_id = d.department_id
        JOIN programs pr ON p.program_id = pr.program_id
        JOIN users u ON p.uploaded_by = u.user_id";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch departments for the dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");

// // Fetch all
// $sql_progs = "SELECT * FROM `programs`";
// $result = mysqli_query($conn, $sql);
// $programs = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch departments for the dropdown
$programs = mysqli_query($conn, "SELECT * FROM programs");

?>
 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content" style="padding: 15px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Upload New Project</h3>
                </div>
                <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Project Title" class="form-control" required><br>
                    <textarea name="abstract" placeholder="Project Abstract" class="form-control" required></textarea><br>

               
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">Select Department</option>
                        <?php while ($dept = mysqli_fetch_assoc($departments)) { ?>
                            <option value="<?php echo $dept['department_id']; ?>"><?php echo $dept['name']; ?></option>
                        <?php } ?>
                    </select><br>

                    <select name="program_id" id="program_id" class="form-control" required>
                        <option value="">Select Program</option>
                        <?php while ($program = mysqli_fetch_assoc($programs)) { ?>
                            <option value="<?php echo $program['department_id']; ?>"><?php echo $program['name']; ?></option>
                        <?php } ?>
                    </select><br>

                    <input type="file" name="file" class="form-control" required><br>
                
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-success">Upload Project</button>
                 
                </div>
            </form>
            </div>
        </div>
    </div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="css/bootstrap-cybrog.min.css" rel="stylesheet">

    <link href="css/charisma-app.css" rel="stylesheet">
    <link href='bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='css/jquery.noty.css' rel='stylesheet'>
    <link href='css/noty_theme_default.css' rel='stylesheet'>
    <link href='css/elfinder.min.css' rel='stylesheet'>
    <link href='css/elfinder.theme.css' rel='stylesheet'>
    <link href='css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='css/uploadify.css' rel='stylesheet'>
    <link href='css/animate.min.css' rel='stylesheet'>

    <!-- jQuery -->
    <script src="bower_components/jquery/jquery.min.js"></script>

    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">

</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="admin_dashboard.php">
                <span>ADUSTECH-PMS</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> <?=$_SESSION['username']?></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="login.html">Logout</a></li>
                </ul>
            </div>
            <!-- user dropdown ends -->

            <!-- theme selector starts -->
            <div class="btn-group pull-right theme-container animated tada">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-tint"></i><span
                        class="hidden-sm hidden-xs"> Change Theme / Skin</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" id="themes">
                    <li><a data-value="classic" href="#"><i class="whitespace"></i> Classic</a></li>
                    <li><a data-value="cerulean" href="#"><i class="whitespace"></i> Cerulean</a></li>
                    <li><a data-value="cyborg" href="#"><i class="whitespace"></i> Cyborg</a></li>
                    <li><a data-value="simplex" href="#"><i class="whitespace"></i> Simplex</a></li>
                    <li><a data-value="darkly" href="#"><i class="whitespace"></i> Darkly</a></li>
                    <li><a data-value="lumen" href="#"><i class="whitespace"></i> Lumen</a></li>
                    <li><a data-value="slate" href="#"><i class="whitespace"></i> Slate</a></li>
                    <li><a data-value="spacelab" href="#"><i class="whitespace"></i> Spacelab</a></li>
                    <li><a data-value="united" href="#"><i class="whitespace"></i> United</a></li>
                </ul>
            </div>
            <!-- theme selector ends -->
<!-- 
            <ul class="collapse navbar-collapse nav navbar-nav top-menu">
                <li><a href="#"><i class="glyphicon glyphicon-globe"></i> Visit Site</a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"><i class="glyphicon glyphicon-star"></i> Dropdown <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
                <li>
                    <form class="navbar-search pull-left">
                        <input placeholder="Search" class="search-query form-control col-md-10" name="query"
                               type="text">
                    </form>
                </li>
            </ul> -->

        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Main</li>
                        <li><a class="ajax-link" href="admin_dashboard.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                        </li>
                        <li><a class="ajax-link" href="#"><i
                                    class="glyphicon glyphicon-user"></i><span> Profile</span></a></li>
                        <li><a class="ajax-link btn-setting" href="#"><i class="glyphicon glyphicon-file"></i><span> Add Project</span></a>
                        </li>

                        
                        <li><a href="logout.php"><i class="glyphicon glyphicon-lock"></i><span> Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->

        <div id="content" class="col-lg-10 col-sm-10">
           
            <div>
    <ul class="breadcrumb">
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
    </ul>
</div>
<div class=" row">

        <div class=" row">
    <div class="col-md-4 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="<?=$projects_total?> " class="well top-block" href="#">
            <i class="glyphicon glyphicon-file"></i>

            <div>Total Projects</div>
            <div><?=$projects_total?></div>
            <!-- <span class="notification"></span> -->
        </a>
    </div>

</div>
<div class="col-md-12">
    <h2>Uploaded Projects</h2>
    <?php 
        if (isset($_GET["success"])) {
             echo "<div class='alert alert-success'>" . $_GET["success"] . "</div>";
        }
    ?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Abstract</th>
        <th>Department</th>
        <th>Program</th>
        <th>Uploaded By</th>
        <th>File</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($projects as $project): ?>
        <tr>
            <td><?php echo $project['project_id']; ?></td>
            <td><?php echo $project['title']; ?></td>
            <td><?php echo $project['abstract']; ?></td>
            <td><?php echo $project['department_name']; ?></td>
            <td><?php echo $project['program_name']; ?></td>
            <td><?php echo $project['username']; ?></td>
            <td><a href="<?php echo $project['file_path']; ?>" target="_blank">Download</a></td>
            <td><?php echo $project['created_at']; ?></td>
            <td>
                <a href="delete_project.php?id=<?php echo $project['project_id']; ?>" onclick="return confirm('Are you sure you want to delete this project?');">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>&nbsp;
                <a href="edit_project.php?id=<?php echo $project['project_id']; ?>">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


</div>
</div>
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->


    <hr>

   

    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; 20124</p>

        <p class="col-md-3 col-sm-3 col-xs-12 powered-by">Developed by: <a
                href="">Dalha Tsakuwa </a></p>
    </footer>

</div><!--/.fluid-container-->
   
    <script>
        // Load programs based on selected department
        $('#department_id').change(function() {
            var department_id = $(this).val();
            $.ajax({
                url: 'fetch_programs.php',
                type: 'POST',
                data: { department_id: department_id },
                success: function(data) {
                    $('#program_id').html(data);
                }
            });
        });
    </script>
<!-- external javascript -->

<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='bower_components/moment/min/moment.min.js'></script>
<script src='bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="js/charisma.js"></script>


</body>
</html>

<?php
mysqli_close($conn);
?>
