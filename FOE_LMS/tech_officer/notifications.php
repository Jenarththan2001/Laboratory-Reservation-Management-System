<?php
session_start();


// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "lab");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch notifications for the logged-in technical officer
$officer_id = $_SESSION['officer_id']; // Assuming officer_id is stored in session
//$queryNotifications = "SELECT * FROM technical_officer_notifications WHERE recipient_id = $officer_id ORDER BY created_at DESC";
$queryNotifications = "SELECT * FROM technical_officer_notifications ORDER BY created_at DESC";
$resultNotifications = mysqli_query($connection, $queryNotifications);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>TO Login</title>
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
<body class="size-1520 primary-color-red background-dark">
    <!-- HEADER -->
    <header class="grid">
        <!-- Top Navigation -->
        <nav class="s-12 grid background-none background-primary-hightlight">
            <!-- logo -->
            <a href="officer_dashboard.php" class="m-12 l-3 padding-2x logo">
                <img src="../assets/img/logo.jpg">
            </a>
            <div class="top-nav s-12 l-9"> 
                <ul class="top-ul right chevron">
                    <li><a href="officer_dashboard.php">My Profile</a></li>
                    <li><a href="edit_profile.php">Edit</a></li>
                    <li><a href="change_password.php">Password</a></li>
                    <li><a href="manage_items.php">Manage Items</a></li>
                    <li><a href="manage_requests.php">Manage Requests</a></li>
                    <li><a href="manage_students.php">Manage Students</a></li>
                    <li><a href="return_items.php">Return</a></li>
                    <li><a href="notifications.php">Notifications</a></li> <!-- Add notification link -->
                    <li><a href="#" onclick="confirmLogout()">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- MAIN -->
    <main role="main">
        <!-- TOP SECTION -->
        <header class="grid">
            <div class="s-12 padding-2x">
                <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">Notifications</h1>
                <hr style="border: 1px solid white; width: 100%;">
            </div>
        </header>
        <div class="container">
            <div class="row mt-4">
                <div class="col-md-12">
                    <ul class="list-group">
                    <?php
                        if (mysqli_num_rows($resultNotifications) > 0) {
                            while ($row = mysqli_fetch_assoc($resultNotifications)) {
                                echo "<li class='list-group-item'>";
                                echo '<span><i class="icon-message text-size-20 text-red center "></i> ' . $row['created_at'] . ' - ' . $row['message'] . '</span>';
                                echo "</li>";
                            }
                        } else {
                            echo '<li class="list-group-item">No notifications</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    <footer>
    <br>
    <br>
    <br>
    <br>
    <br>
    
<div class="s-12 text-center margin-bottom">
             <p class="text-size-12">© 2024, Laboratory Management System, Faculty of Engineering</p>
             <p class="text-size-12">University of Jaffna</p>
             <p class="text-size-12">Website Developed by Group 11.</p>
           </div>
       </footer>   
</section>
    
    <!-- Scripts -->
    <script type="text/javascript" src="../assets/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
