<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lab");
$query = "SELECT * FROM Technical_Officer WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($query_run);

// Update profile information if form is submitted
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    // Add more fields as needed

    $update_query = "UPDATE Technical_Officer SET name='$name', email='$email' WHERE email='$_SESSION[email]'";
    $update_query_run = mysqli_query($connection, $update_query);

    if($update_query_run){
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['success_message'] = "Profile updated successfully."; // Set the success message
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to the same page to refresh
        exit();
    } else {
        // Add error message
    }
}

// Check if success message is set
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); // Clear the success message
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

   </head>

  <style>
    .password-input-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
  }
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
      <body class="size-1520 primary-color-red background-dark">
      <!-- MAIN -->
      <main role="main">
        <!-- TOP SECTION -->
        <header class="grid">
        	<div class="s-12 padding-2x">
            <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">EDIT PROFILE</h1>
          </div>
        </header>
        <div class="container">
        <hr style="border: 1px solid white; width: 100%;">

          
        <?php
// Display success message if set
if (!empty($success_message)) {
    echo '<div class="alert alert-success text-center" role="alert" style="color: green; font-size: 30px;">' .$success_message. '</div>';
}
?>
<hr>

<div class="container">
        <div class="row justify-content-center"> 
            <div class="col-md-6"> 
                <div class="bg-light p-4 mt-4"> 

                    <form action="" method="post" class="mt-4"> 
                        <div class="form-group"> 
                            <label for="name">Name:</label> 
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required >
                        </div> 
                        <div class="form-group"> 
                            <label for="email">Email:</label> 
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required >
                        </div> 
                        <button type="submit" name="update" class="btn btn-primary btn-block">Update Profile</button> 
                    </form> 
                </div> 
            </div> 
        </div> 
    </div>       
              
            </form>
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

