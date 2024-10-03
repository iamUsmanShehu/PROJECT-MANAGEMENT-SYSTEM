<?php
// Database connection
$db = mysqli_connect("localhost", "root", "", "project_management_system");

// Check if the project ID is set in the URL and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $projectId = $_GET['id'];

    // Fetch project details with JOIN on departments and programs
    $query = mysqli_prepare($db, "
        SELECT p.project_id, p.title, p.abstract, p.file_path, d.name as department_name, pr.name as program_name, u.username, p.created_at
        FROM projects p 
        JOIN departments d ON p.department_id = d.department_id 
        JOIN programs pr ON p.program_id = pr.program_id 
        JOIN users u ON p.uploaded_by = u.user_id 
        WHERE p.project_id = ?
    ");
    mysqli_stmt_bind_param($query, 'i', $projectId);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $project = mysqli_fetch_assoc($result);

    if (!$project) {
        echo "Project not found.";
        exit();
    }
} else {
    echo "Invalid project ID.";
    exit();
}

// Close the database connection
mysqli_close($db);
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo htmlspecialchars($project['title']); ?> - Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">

<style type="text/css">

body {
  font-family: 'open sans';
  overflow-x: hidden; }

img {
  max-width: 100%; }

.preview {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }
  @media screen and (max-width: 996px) {
    .preview {
      margin-bottom: 20px; } }

.preview-pic {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.preview-thumbnail.nav-tabs {
  border: none;
  margin-top: 15px; }
  .preview-thumbnail.nav-tabs li {
    width: 18%;
    margin-right: 2.5%; }
    .preview-thumbnail.nav-tabs li img {
      max-width: 100%;
      display: block; }
    .preview-thumbnail.nav-tabs li a {
      padding: 0;
      margin: 0; }
    .preview-thumbnail.nav-tabs li:last-of-type {
      margin-right: 0; }

.tab-content {
  overflow: hidden; }
  .tab-content img {
    width: 100%;
    -webkit-animation-name: opacity;
            animation-name: opacity;
    -webkit-animation-duration: .3s;
            animation-duration: .3s; }

.card {
  margin-top: 50px;
  background: #eee;
  padding: 3em;
  line-height: 1.5em; }

@media screen and (min-width: 997px) {
  .wrapper {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex; } }

.details {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }

.colors {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.product-title, .price, .sizes, .colors {
  text-transform: UPPERCASE;
  font-weight: bold; }

.checked, .price span {
  color: #0bbe30; }

.product-title, .rating, .product-description, .price, .vote, .sizes {
  margin-bottom: 15px; }

.product-title {
  margin-top: 0; }

.size {
  margin-right: 10px; }
  .size:first-of-type {
    margin-left: 40px; }

.color {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
  height: 2em;
  width: 2em;
  border-radius: 2px; }
  .color:first-of-type {
    margin-left: 20px; }

.add-to-cart, .like {
  background: #0bbe30;
  padding: 1.2em 1.5em;
  border: none;
  text-transform: UPPERCASE;
  font-weight: bold;
  color: #fff;
  -webkit-transition: background .3s ease;
          transition: background .3s ease; }
.like{
    background: #b5bab6;
}
  .add-to-cart:hover, .like:hover {
    background: #b36800;
    color: #fff; }

.not-available {
  text-align: center;
  line-height: 2em; }
  .not-available:before {
    font-family: fontawesome;
    content: "\f00d";
    color: #fff; }

.orange {
  background: #0bbe30; }

.green {
  background: #85ad00; }

.blue {
  background: #0076ad; }

.tooltip-inner {
  padding: 1.3em; }

@-webkit-keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

@keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

/*# sourceMappingURL=style.css.map */
</style>
  </head>

  <body>
    
    <div class="container">
        <div class="card">
            <div class="container-fliud">
                <div class="wrapper row">
                    <div class="preview col-md-6">
                        
                        <div class="preview-pic tab-content">
                          <div class="tab-pane active" id="pic-1"><img src="document-icon-36540.png" /></div>
                        </div>
                        
                        
                    </div>
                    <div class="details col-md-6">

                        <h3 class="product-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($project['department_name']); ?></p>
                        <p><strong>Program:</strong> <?php echo htmlspecialchars($project['program_name']); ?></p>
                        <p><strong>Uploaded By:</strong> <?php echo htmlspecialchars($project['username']); ?></p>
                        <p><strong>Uploaded 0n:</strong> <?php echo htmlspecialchars($project['created_at']); ?></p>
                        <p class="product-description"><b>Abstract:</b> <?php echo htmlspecialchars($project['abstract']); ?></p>
                     
                        
                        <div class="action">
                            <a class="add-to-cart btn btn-default" href="<?php echo htmlspecialchars($project['file_path']); ?>" target="_blank">Download Project</a>
                            <a class="like btn btn-info" href="index.php">Cancle</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
