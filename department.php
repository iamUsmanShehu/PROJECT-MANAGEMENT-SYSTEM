<?php
session_start();
include 'db_connect.php';

// Check if the user is a SuperAdmin
if ($_SESSION['role'] !== 'SuperAdmin') {
    header('Location: login.php');
    exit;
}

// Handle department creation
if (isset($_POST['add_department'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    // Check if department already exists
    $check_dept_query = "SELECT * FROM departments WHERE name = '$name'";
    $result = mysqli_query($conn, $check_dept_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Department already exists.";
    } else {
        $sql = "INSERT INTO departments (name) VALUES ('$name')";
        if (mysqli_query($conn, $sql)) {
            echo "Department added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle program creation
if (isset($_POST['add_program'])) {
    $program_name = mysqli_real_escape_string($conn, $_POST['program_name']);
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    
    // Check if program already exists in the same department
    $check_program_query = "SELECT * FROM programs WHERE name = '$program_name' AND department_id = '$department_id'";
    $result = mysqli_query($conn, $check_program_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Program already exists for this department.";
    } else {
        $sql = "INSERT INTO programs (name, department_id) VALUES ('$program_name', '$department_id')";
        if (mysqli_query($conn, $sql)) {
            echo "Program added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle adding a new Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Admin with this email already exists.";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'Admin')";
        if (mysqli_query($conn, $sql)) {
            echo "Admin added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle deleting an Admin
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM users WHERE user_id='$delete_id' AND role='Admin'";
    if (mysqli_query($conn, $sql)) {
        echo "Admin deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all departments
$departments = mysqli_query($conn, "SELECT * FROM departments");
$department = mysqli_query($conn, "SELECT * FROM departments");

// Fetch all programs
$programs = mysqli_query($conn, "SELECT p.program_id, p.name AS program_name, d.name 
                                FROM programs p 
                                JOIN departments d ON p.department_id = d.department_id");

// Fetch all Admins
$admins = mysqli_query($conn, "SELECT * FROM users WHERE role='Admin'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Department</title>
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

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="img/favicon.ico">
<style type="text/css">
    input, select, button{
        padding: 16px 32px;
        margin-bottom: 5px;
        border-radius: 15px;
        color: black;
        width: 100%;
    }
    button{
        background: #71c55d;
        color: white;
    }
    .container{
        width: 50%;
        margin:none;
    }
</style>
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
                <span>ADUSTECH-PMS</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
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
            
        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
           <?php include 'sidebar.php'; ?>
        </div>
        <!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
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
    <div class="container">
         <!-- Add Department Section -->
        <h2>Add New Department</h2>
        <form method="POST" action="superadmin_dashboard.php">
            <input type="text" name="name" placeholder="Department Name" required>
            <button type="submit" name="add_department" style="color: #ffffff; background-color: #00bc8c;">Add Department</button>
        </form>
        
    </div>
</div>
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->


    <hr>

  <?php include 'model.php'; ?>
   

    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; 20124</p>

        <p class="col-md-3 col-sm-3 col-xs-12 powered-by">Developed by: <a
                href="">Dalha Tsakuwa </a></p>
    </footer>

</div><!--/.fluid-container-->

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
