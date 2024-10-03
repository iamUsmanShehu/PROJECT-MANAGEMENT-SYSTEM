<?php
include 'db_connect.php';

// Fetch all uploaded projects
$sql = "SELECT p.project_id, p.title, p.abstract, p.file_path, d.name AS department_name,  pr.name AS program_name, u.username, p.created_at
        FROM projects p
        JOIN departments d ON p.department_id = d.department_id
        JOIN programs pr ON p.program_id = pr.program_id
        JOIN users u ON p.uploaded_by = u.user_id";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ADUSTECH-PROJECT MANAGEMENT SYSTEM</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="" rel="icon">
  <link href="" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <h1 class="sitename"><span>A-</span>PMS</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Projects</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h2>A-PMS</h2>
            <p>This Project Management System is designed to streamline the process of managing and accessing student academic projects in an educational institution.</p>
            <div class="d-flex">
              <a href="login.php" class="btn-get-started">login</a>
              
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="assets/img/IMG-20240914-WA0025.jpg" class="img-fluid" alt="" style="width: 100%;border-radius: 10px;">
          </div>
        </div>
      </div>

      <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
        <div class="container position-relative">
          <div class="row gy-4 mt-5">

            <div class="col-xl-6 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-person"></i></div>
                <h4 class="title"><a href="login.php" class="stretched-link">Admin</a></h4>
                <a href="login.php" class="btn-get-started">Login </a>
              </div>
            </div>

            <div class="col-xl-6 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-person"></i></div>
                <h4 class="title"><a href="login.php" class="stretched-link">Super Admin</a></h4>
                <a href="login.php" class="btn-get-started">Login </a>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p class="who-we-are">About Software</p>
            <h3>Brief Description of the Software</h3>
            <p class="fst-italic">
              This Project Management System is designed to streamline the process of managing and accessing student academic projects in an educational institution. It features three distinct user panels: SuperAdmin, Admin, and User. Each user role has specific functionalities:


            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i><b>SuperAdmin Panel:</b><br> <span>The SuperAdmin has complete control over the system. They can create and manage admins, departments, and programs. This role ensures that the right administrative team is in place and that academic programs and departments are accurately reflected in the system.</span></li>
              <li><i class="bi bi-check-circle"></i><b>Admin Panel:</b><br> <span>Admins are responsible for managing project-related tasks. They can upload project files (PDF, DOC, PPT) to the system, alongside metadata such as the project title and abstract.</span></li>
              <li><i class="bi bi-check-circle"></i> <b>User:</b><br>The User Can Only View Project, Read the Abstract and or Download the Project...<span></span></li>
            </ul>
            <!-- <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a> -->
          </div>

          <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">
              <div class="col-lg-6">
                <img src="assets/img/WhatsApp Image 2024-09-26 at 04.23.45_0487cba7.jpg" class="img-fluid" alt="">
              </div>
              <div class="col-lg-6">
                <div class="row gy-4">
                  <div class="col-lg-12">
                    <img src="assets/img/WhatsApp Image 2024-09-26 at 04.23.45_56ddd17a.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-12">
                    <img src="assets/img/IMG-20240914-WA0005.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Projects</h2>
        <div><span>View and Download</span> <span class="description-title">Projects</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

                <?php foreach ($projects as $project): ?>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-files"></i>
              </div>
              <a href="view_project_details.php?id=<?php echo $project['project_id']; ?>" class="stretched-link">
                <h3><?php echo $project['title']; ?></h3>
              </a>
              <p>
                Department: <?php echo $project['department_name']; ?><br>
                Program: <?php echo $project['program_name']; ?>
              </p>
              <span class="form-control bg-default"><?php echo $project['created_at']; ?></span>
            </div>
          </div><!-- End Service Item -->
          <?php endforeach; ?>

        </div>

      </div>

    </section><!-- /Services Section -->



    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact Library</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Only Whatapp</h3>
                <p>+234999999999</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email MIS</h3>
                <p>info@adustech.edu.ng</p>
              </div>
            </div><!-- End Info Item -->

          </div>

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer light-background">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">ADUSTECH-PROJECT MANAGEMENT SYSTEM</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="credits">
        Designed by <a href="#">Dalha Tsakuwa</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>