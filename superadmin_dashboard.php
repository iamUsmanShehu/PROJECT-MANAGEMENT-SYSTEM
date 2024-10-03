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

$total_admins = "SELECT COUNT(user_id) AS 'Total' FROM `users` WHERE role = 'Admin'";
  $admins_stmt = $conn->prepare($total_admins);
  $admins_stmt->execute();
  $admins_result = $admins_stmt->get_result();
  
  if ($admins_result->num_rows > 0) {
      $total = $admins_result->fetch_assoc();
      $admins_total = $total['Total'];
  }

  $total_dept = "SELECT COUNT(department_id) AS 'Total' FROM `departments`";
  $dept_stmt = $conn->prepare($total_dept);
  $dept_stmt->execute();
  $dept_result = $dept_stmt->get_result();
  
  if ($dept_result->num_rows > 0) {
      $total = $dept_result->fetch_assoc();
      $dept_total = $total['Total'];
  }

  $total_programs = "SELECT COUNT(program_id) AS 'Total' FROM `programs`";
  $programs_stmt = $conn->prepare($total_programs);
  $programs_stmt->execute();
  $programs_result = $programs_stmt->get_result();
  
  if ($programs_result->num_rows > 0) {
      $total = $programs_result->fetch_assoc();
      $programs_total = $total['Total'];
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard</title>
</head>
<body>
    <h1>Welcome SuperAdmin: <?php echo $_SESSION['username']; ?></h1>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ADUSTECH-PROJECT MANAGEMENT SYSTEM</title>
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
            <a class="navbar-brand" href="index.html">
                <span>ADUSTECH-PMS</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> <?php echo $_SESSION['username']; ?></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="login.php">Logout</a></li>
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
    <div class="col-md-4 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="<?=$admins_total?> Admins." class="well top-block" href="#">
            <i class="glyphicon glyphicon-user blue"></i>

            <div>Total Admins</div>
            <div><?=$admins_total?></div>
            <span class="notification"><?=$admins_total?></span>
        </a>
    </div>

    <div class="col-md-4 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="<?=$dept_total?> Deparments." class="well top-block" href="#">
            <i class="glyphicon glyphicon-file green"></i>

            <div>Total Deparments</div>
            <div><?=$dept_total?></div>
            <span class="notification green"><?=$dept_total?></span>
        </a>
    </div>

    <div class="col-md-4 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="<?=$programs_total?> Programs." class="well top-block" href="#">
            <i class="glyphicon glyphicon-file yellow"></i>

            <div>Total Programs</div>
            <div><?=$programs_total?></div>
            <span class="notification yellow"><?=$programs_total?></span>
        </a>
    </div>

    <!-- <div class="col-md-3 col-sm-3 col-xs-6">
        <a data-toggle="tooltip" title="12 new messages." class="well top-block" href="#">
            <i class="glyphicon glyphicon-envelope red"></i>

            <div>Messages</div>
            <div>25</div>
            <span class="notification red">12</span>
        </a>
    </div> -->
</div>

<div class="row">
    <div class="box col-md-4">
        <div class="box-inner homepage-box">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-th"></i> Admin</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i
                            class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round btn-default"><i
                            class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <ul class="nav nav-tabs" id="myTab">
                    <!-- <li class="active"><a href="#info">Info</a></li> -->
                </ul>

                <div id="myTabContent" class="tab-content">
                    <!-- <div class="tab-pane active" id="info"> -->
				    <table border="1">
				        <tr>
				            <th>ID</th>
				            <th>Username</th>
				            <th>Email</th>
				            <th>Action</th>
				        </tr>
				        <?php while ($admin = mysqli_fetch_assoc($admins)) { ?>
				            <tr>
				                <td><?php echo $admin['user_id']; ?></td>
				                <td><?php echo $admin['username']; ?></td>
				                <td><?php echo $admin['email']; ?></td>
				                <td>
				                    <a href="edit_admin.php?id=<?php echo $admin['user_id']; ?>"><i
                            class="glyphicon glyphicon-edit"></i></a> &nbsp
				                    <a href="superadmin_dashboard.php?delete_id=<?php echo $admin['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this admin?')"><i
                            class="glyphicon glyphicon-trash"></i></a>
				                </td>
				            </tr>
				        <?php } ?>
				    </table>

                    
                </div>
            </div>
        </div>
    </div>
    <!--/span-->

    <div class="box col-md-4">
        <div class="box-inner homepage-box">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-th"></i> Existing Departments</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i
                            class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round btn-default"><i
                            class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <ul class="nav nav-tabs" id="myTab">
                    <!-- <li class="active"><a href="#info">Info</a></li> -->
                </ul>

                <div id="myTabContent" class="tab-content">
                    <!-- <div class="tab-pane active" id="info"> -->
				    <table border="1">
				        <tr>
				            <th>Department ID</th>
				            <th>Department Name</th>
				        </tr>
				        <?php while ($dept = mysqli_fetch_assoc($departments)) { ?>
				            <tr>
				                <td><?php echo $dept['department_id']; ?></td>
				                <td><?php echo $dept['name']; ?></td>
				            </tr>
				        <?php } ?>
				    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <!--/span-->
    <div class="box col-md-4">
        <div class="box-inner homepage-box">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-th"></i>Existing Programs</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i
                            class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round btn-default"><i
                            class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <ul class="nav nav-tabs" id="myTab">
                    <!-- <li class="active"><a href="#info">Info</a></li> -->
                </ul>

                <div id="myTabContent" class="tab-content">
                    <!-- <div class="tab-pane active" id="info"> -->
                        <table border="1">
					        <tr>
					            <th>Program ID</th>
					            <th>Program Name</th>
					            <th>Department</th>
					        </tr>
					        <?php while ($prog = mysqli_fetch_assoc($programs)) { ?>
					            <tr>
					                <td><?php echo $prog['program_id']; ?></td>
					                <td><?php echo $prog['program_name']; ?></td>
					                <td><?php echo $prog['name']; ?></td>
					            </tr>
					        <?php } ?>
					    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->
    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->


    <hr>
  <!-- Add New Admin -->
  <?php include 'model.php'; ?>

    <!-- Add New Department -->
	<!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3></h3>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Save changes</a>
                </div>
            </div>
        </div>
    </div> -->

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

<?php
mysqli_close($conn);
?>
