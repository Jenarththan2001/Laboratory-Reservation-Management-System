<!DOCTYPE html> 
<html> 
<head> 
    <title>Laboratory Management System - Student signup</title> 
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> 
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="assets/css/components.css">
      <link rel="stylesheet" href="assets/css/icons.css">
      <link rel="stylesheet" href="assets/css/responsee.css">
      <link rel="stylesheet" href="assets/owl-carousel/owl.carousel.css">
      <link rel="stylesheet" href="assets/owl-carousel/owl.theme.css">     
      <link rel="stylesheet" href="assets/css/template-style.css">
      <link href="https://fonts.googleapis.com/css?family=Barlow:100,300,400,700,800&amp;subset=latin-ext" rel="stylesheet">  
      <script type="text/javascript" src="assets/js/jquery-1.8.3.min.js"></script>
      <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>  
</head> 

<body class="size-1520 primary-color-red background-dark">
      <!-- HEADER -->
      <header class="grid">
        <!-- Top Navigation -->
        <nav class="s-12 grid background-none background-primary-hightlight">
           <!-- logo -->
           <a href="index.php" class="m-12 l-3 padding-2x logo">
           <img src="assets/img/logo.jpg">
           </a>
           <div class="top-nav s-12 l-9"> 
              <ul class="top-ul right chevron">
              <li><a href="index.php">Student Login</a></li>
                <li><a href="to_login.php">Technical Officer Login</a></li>
                <li><a href="signup.php">Sign up</a></li>
                
              </ul>
           </div>
        </nav>
      </header>
<body> 
<main role="main">
         <!-- TOP SECTION -->
         <header class="grid">
            <div class="s-12 padding-2x">
             <h1 class="text-strong text-black text-center center text-size-60 text-uppercase margin-bottom-20">Student sign up</h1>      
           </div>
         </header>
   
         <div class="container">
        <div class="row justify-content-center"> 
            <div class="col-md-6"> 
                <div class="bg-light p-4 mt-4"> 
                    <form action="" method="post" class="mt-4"> 
                        <div class="form-group"> 
                            <label for="name">Full Name:</label> 
                            <input type="text" name="name" class="form-control" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="email">Email ID:</label> 
                            <input type="email" name="email" class="form-control" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="password1">Password:</label> 
                            <input type="password" name="password1" class="form-control" required> 
                        </div>

                        <div class="form-group"> 
                            <label for="password2">Password:</label> 
                            <input type="password" name="password2" class="form-control" required placeholder="confirm-password"> 
                        </div> 
                        <button type="submit" name="signup" class="btn btn-primary btn-block">Signup</button>   
                    </form> 
                    <?php 
                        session_start(); 
                        if(isset($_POST['signup'])){ 
                            $connection = mysqli_connect("localhost","root",""); 
                            $db = mysqli_select_db($connection,"lab"); 
                            $name = $_POST['name'];
                            $email = $_POST['email'];
                            $password1 = $_POST['password1'];
                            $password2 = $_POST['password2'];
                            if ($password1 != $password2) {
                                echo '<div class="alert alert-danger" role="alert">Passwords do not match! Registration failed.</div>';
                            } else {
                                $password = $password1; // Use password1 for the password
                                $query = "INSERT INTO Student (email, password, name) VALUES ('$email', '$password', '$name')"; 
                                $query_run = mysqli_query($connection,$query); 
                                if($query_run){ 
                                    echo '<div class="alert alert-success" role="alert">Registration successful! You can now login.</div>'; 
                                } else { 
                                    echo '<div class="alert alert-danger" role="alert">Registration failed. Please try again later.</div>'; 
                                } 
                            } 
                        } 
                        ?>
                </div> 
            </div> 
        </div> 
    </div> 
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="assets/js/responsee.js"></script>
      <script type="text/javascript" src="assets/owl-carousel/owl.carousel.js"></script>
      <script type="text/javascript" src="assets/js/template-scripts.js"></script>
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
</html>
