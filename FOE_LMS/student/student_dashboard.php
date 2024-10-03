<?php
session_start();
require 'functions.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Student Login</title>
      <link rel="stylesheet" href="../assets/css/components.css">
      <link rel="stylesheet" href="../assets/css/icons.css">
      <link rel="stylesheet" href="../assets/css/responsee.css">
      <link rel="stylesheet" href="../assets/owl-carousel/owl.carousel.css">
      <link rel="stylesheet" href="../assets/owl-carousel/owl.theme.css">     
      <link rel="stylesheet" href="../assets/css/template-style.css">
      <link href="https://fonts.googleapis.com/css?family=Barlow:100,300,400,700,800&amp;subset=latin-ext" rel="stylesheet">  
      <script type="text/javascript" src="../assets/js/jquery-1.8.3.min.js"></script>
      <script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>    
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
      <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> 

   </head>
   <!--
    You can change the color scheme of the page. Just change the class of the <body> tag. 
    You can use this class: "primary-color-white", "primary-color-red", "primary-color-orange", "primary-color-blue", "primary-color-aqua", "primary-color-dark" 
    -->
    
    <!--
    Each element is able to have its own background or text color. Just change the class of the element.  
    You can use this class: 
    "background-white", "background-red", "background-orange", "background-blue", "background-aqua", "background-primary" 
    "text-black", "text-red", "text-orange", "text-blue", "text-aqua", "text-primary"
    -->

   <body class="size-1520 primary-color-red background-dark">
      <!-- HEADER -->
      <header class="grid">
        <!-- Top Navigation -->
        <nav class="s-12 grid background-none background-primary-hightlight">
           <!-- logo -->
           <a href="student_dashboard.php" class="m-12 l-3 padding-2x logo">
           <img src="../assets/img/logo.jpg">
           </a>
           <div class="top-nav s-12 l-9"> 
              <ul class="top-ul right chevron">
              <li><a href="student_dashboard.php">My Profile</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="view_history.php">View History</a></li>
                <li><a href="request_item.php">Request Items</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="#" onclick="confirmLogout()">Logout</a></li>
              </ul>
           </div>
        </nav>
      </header>
              <!-- SECTION 2 -->
   
    <!-- MAIN -->
    <main role="main">
        <!-- TOP SECTION -->
        <section class="grid">
            <!-- Main Carousel -->
            <div class="s-12 margin-bottom carousel-fade-transition owl-carousel carousel-main carousel-nav-white carousel-hide-arrows background-dark">
            <div class="item background-image" style="background-image:url(../assets/img/w1.jpg)">
                    <p class="text-padding text-strong text-white text-s-size-30 text-size-60 text-uppercase background-primary">Welcome <?php echo $_SESSION['name']; ?></p>
                    <p class="text-padding text-size-20 text-dark text-uppercase background-white">where innovation meets education, and experimentation leads to discovery.</p>
                </div>

                <div class="item background-image" style="background-image:url(../assets/img/w2.jpg)">
                    <p class="text-padding text-strong text-white text-s-size-30 text-size-60 text-uppercase background-primary">Welcome to the LMS of the University of Jaffna</p>
                    <p class="text-padding text-size-20 text-dark text-uppercase background-white">Request Lab Components.</p>
                </div>
                <div class="item background-image" style="background-image:url(../assets/img/w3.jpg)">
                    <p class="text-padding text-strong text-white text-s-size-30 text-size-60 text-uppercase background-primary"> Request Lab Components and keep track </p>
                    <p class="text-padding text-size-20 text-dark text-uppercase background-white">Retrun Lab Components.</p>
                </div>
            </div>
        </section>
        <section class="grid margin text-center">
            <a href="edit_profile.php" class="s-12 m-6 l-3 padding-2x vertical-center margin-bottom background-red">
                <i class="icon-sli-user text-size-60 text-white center margin-bottom-15"></i>
                <h3 class="text-strong text-size-20 text-line-height-1 margin-bottom-30 text-uppercase">Edit Profile</h3>
            </a>
            <a href="change_password.php" class="s-12 m-6 l-3 padding-2x vertical-center margin-bottom background-blue">
                <i class="icon-sli-settings text-size-60 text-white center margin-bottom-15"></i>
                <h3 class="text-strong text-size-20 text-line-height-1 margin-bottom-30 text-uppercase">Change Password</h3>
            </a>


            <a href="view_history.php" class="s-12 m-6 l-3 padding-2x vertical-center margin-bottom background-orange">
                <i class="icon-sli-list text-size-60 text-white center margin-bottom-15"></i>
                <h3 class="text-strong text-size-20 text-line-height-1 margin-bottom-30 text-uppercase">View History</h3>
            </a>
            <a href="request_item.php" class="s-12 m-6 l-3 padding-2x vertical-center margin-bottom background-aqua">
                <i class="icon-sli-refresh text-size-60 text-white center margin-bottom-15"></i>
                <h3 class="text-strong text-size-20 text-line-height-1 margin-bottom-30 text-uppercase">Request Items</h3>
            </a>
        </section>
        <section class="grid section margin-bottom background-dark">
          <h2 class="s-12 l-6 center text-thin text-size-40 text-white text-uppercase margin-bottom-30 ">Welcome <?php echo $_SESSION['name']; ?>!</h2>
          <h2 class="s-12 l-6 center text-thin text-size-30 text-white text-uppercase margin-bottom-30 ">Email: <?php echo $_SESSION['email']; ?></h2>
          <p class="s-12 l-6 center ">Welcome to the Laboratory Management System of the University of Jaffna, where innovation meets education, and experimentation leads to discovery. Step into a world where knowledge thrives, curiosity ignites, and breakthroughs await. Join us on a journey of scientific exploration and academic excellence, shaping the future one discovery at a time.</p>
        </section>
        
        <div class="s-12 text-center margin-bottom">
             <p class="text-size-12">© 2024, Laboratory Management System, Faculty of Engineering</p>
             <p class="text-size-12">University of Jaffna</p>
             <p class="text-size-12">Website Developed by Group 11.</p>
           </div>
       </footer>   
                                                                        
      <script type="text/javascript" src="../assets/js/responsee.js"></script>
      <script type="text/javascript" src="../assets/owl-carousel/owl.carousel.js"></script>
      <script type="text/javascript" src="../assets/js/template-scripts.js"></script>

<script>
function confirmLogout() {
    // Display confirmation dialog
    var logout = confirm("Are you sure you want to logout?");
    // If user confirms, redirect to logout page
    if (logout) {
        window.location.href = "logout.php";
    }
    // If user cancels, do nothing
}
</script>

   </body>
</html>