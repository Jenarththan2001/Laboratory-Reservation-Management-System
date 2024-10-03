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

// Fetch the current user's password from the database
$current_password = "";
$query = "SELECT password FROM Technical_Officer WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);
if ($row = mysqli_fetch_assoc($query_run)) {
    $current_password = $row['password'];
}

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Retrieve form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate the old password
    if ($current_password !== $old_password) {
        $_SESSION['error_message'] = "Incorrect old password.";
        header("Location: change_password.php");
        exit();
    }

    // Validate the new password and confirm password
    if ($new_password !== $confirm_new_password) {
        $_SESSION['error_message'] = "New password and confirm password do not match.";
        header("Location: change_password.php");
        exit();
    }

    // Update the password in the database
    $update_query = "UPDATE Technical_Officer SET password = '$new_password' WHERE email = '$_SESSION[email]'";
    if (mysqli_query($connection, $update_query)) {
        // Password updated successfully
        $_SESSION['success_message'] = "Password updated successfully.";
    } else {
        // Failed to update password
        $_SESSION['error_message'] = "Failed to update password.";
        header("Location: change_password.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en-US">
   <head>
   <meta charset="UTF-8">
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
    <style>
    .password-input-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 70%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
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
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="#" onclick="confirmLogout()">Logout</a></li>
                
              </ul>
           </div>
        </nav>
      </header>
      
      <head>
        <!-- Your head content here -->
    </head>
    <body class="size-1520 primary-color-red background-dark">
        <!-- HEADER -->
        <!-- Your header content here -->
    
      <!-- MAIN -->
      <main role="main">



      
         <!-- TOP SECTION -->
         <header class="grid">
            <div class="s-12 padding-2x">
             <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">CHANGE OLD PASSWORD</h1>
            
           </div>
         </header>
   
         
         <div class="container">
         <hr style="border: 1px solid white; width: 100%;">
         </body>
</html>

<?php
// Display success or error message
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success text-center" role="alert" style="color: green; font-size: 30px;">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Clear the success message
} elseif (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger text-center" role="alert" style="color: red; font-size: 30px;">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Clear the error message
}
?>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="bg-light p-4 mt-4">
                        <form action="" method="post" class="mt-4">
                            <div class="form-group password-input-container">
                                <label for="old_password">Old Password:</label>
                                <input type="password" class="form-control" name="old_password" required>
                                <i class="toggle-password fas fa-eye" data-target="old_password"></i>
                            </div>
                            <div class="form-group password-input-container">
                                <label for="new_password">New Password:</label>
                                <input type="password" class="form-control" name="new_password" required>
                                <i class="toggle-password fas fa-eye" data-target="new_password"></i>
                            </div>
                            <div class="form-group password-input-container">
                                <label for="confirm_new_password">Confirm New Password:</label>
                                <input type="password" class="form-control" name="confirm_new_password" required>
                                <i class="toggle-password fas fa-eye" data-target="confirm_new_password"></i>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary btn-block">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <section>
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

 

    </body>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            const toggleButton = document.createElement('span');
            toggleButton.className = 'toggle-password fas fa-eye';
            toggleButton.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
            input.parentNode.appendChild(toggleButton);
        });
    });
</script>
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
</html>
