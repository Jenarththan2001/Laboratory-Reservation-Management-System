<!DOCTYPE html> 
<html> 
<head> 
    <title>Laboratory Management System - Student Login</title> 
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
           <a href="index.php" class="m-12 l-3 padding-2x logo larger-logo">
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
             <h1 class="text-strong text-black text-center center text-size-60 text-uppercase margin-bottom-20">Student login</h1>      
           </div>
         </header>
   
    <div class="container">
        <div class="row justify-content-center"> 
            <div class="col-md-6"> 
                <div class="bg-light p-4 mt-4"> 
                    <form action="" method="post" class="mt-4"> 
                        <div class="form-group"> 
                            <label for="email">Email ID:</label> 
                            <input type="text" name="email" class="form-control" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="password">Password:</label> 
                            <input type="password" name="password" class="form-control" required> 
                        </div> 
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button> 
                        <p class="mt-2 text-center text">Don't have an account? <a href="signup.php" style="color: blue;">Signup now!</a></p>

                    </form> 
                    <?php 
                        session_start(); 
                        if(isset($_POST['login'])){ 
                            $connection = mysqli_connect("localhost","root",""); 
                            $db = mysqli_select_db($connection,"lab"); 
                            $query = "SELECT * FROM Student WHERE email = '$_POST[email]'"; 
                            $query_run = mysqli_query($connection,$query); 
                            while ($row = mysqli_fetch_assoc($query_run)) { 
                                if($row['email'] == $_POST['email']){ 
                                    if($row['password'] == $_POST['password']){ 
                                        $_SESSION['name'] = $row['name']; 
                                        $_SESSION['email'] = $row['email']; 
                                        $_SESSION['student_id'] = $row['student_id']; 
                                        header("Location: student/student_dashboard.php"); 
                                    } 
                                    else{ 
                                        echo '<div class="alert alert-danger" role="alert">Wrong Password !!</div>'; 
                                    } 
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


      
