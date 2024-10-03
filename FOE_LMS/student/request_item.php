<?php
session_start();
require 'functions.php';

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

// Function to insert a request
function insertRequest($connection, $student_id, $item_name, $needed_count) {
    // Validate needed_count with available count
    $query = "SELECT COUNT(*) AS count FROM lab_item WHERE name = '$item_name'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $available_count = $row['count'];
    
    if ($needed_count > $available_count) {
        return false;
    }

    // Insert each item individually
    for ($i = 0; $i < $needed_count; $i++) {
        $insert_query = "INSERT INTO Request (student_id, item_name) VALUES ($student_id, '$item_name')";
        if (!mysqli_query($connection, $insert_query)) {
            return false;
        }
    }

    // Log notification
    $message = "Requested item: $item_name, Count: $needed_count";
    $message1 = "Requested item: $item_name, Count: $needed_count, Student ID: $student_id";
    log_notification($student_id, $message, $connection);
    log_technical_officer_notification($student_id, $message1, $connection); // Log to technical officer
    
    return true;
}

// Function to delete a request
function deleteRequest($connection, $student_id, $item_name) {
    $delete_query = "DELETE FROM Request WHERE student_id = $student_id AND item_name = '$item_name' ORDER BY request_id DESC LIMIT 1";
    if (mysqli_query($connection, $delete_query)) {
        // Log notification
        $message = "Cancelled request for item: $item_name";
        $message1 = "Cancelled request for item: $item_name, Student ID: $student_id";
        log_notification($student_id, $message, $connection);
        log_technical_officer_notification($student_id, $message1, $connection); // Log to technical officer
        return true;
    } else {
        return false;
    }
}

// Handle request action
if (isset($_POST['action']) && $_POST['action'] == 'request') {
    $student_id = $_SESSION['student_id'];
    $item_name = $_POST['item_name'];
    $needed_count = $_POST['needed_count']; // Get needed count from the form
    if (insertRequest($connection, $student_id, $item_name, $needed_count)) {
        $_SESSION['success_message'] = "Request made successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to make request. Please try again.";
    }
    header("Location: request_item.php");
    exit();
}

// Handle cancel request action
if (isset($_POST['action']) && $_POST['action'] == 'cancel_request') {
    $student_id = $_SESSION['student_id'];
    $item_name = $_POST['item_name'];
    if (deleteRequest($connection, $student_id, $item_name)) {
        $_SESSION['success_message'] = "Request canceled successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to cancel request. Please try again.";
    }
    header("Location: request_item.php");
    exit();
}

// Fetch available items for request
$query = "SELECT name, COUNT(*) AS count
FROM (
    SELECT DISTINCT item_id
    FROM issued
    WHERE return_date IS NOT NULL
    AND item_id NOT IN (
        SELECT DISTINCT item_id
        FROM issued
        WHERE return_date IS NULL
    )
    UNION
    SELECT item_id
    FROM lab_item
    WHERE item_id NOT IN (
        SELECT DISTINCT item_id
        FROM issued
    )
) AS available_items
JOIN lab_item ON available_items.item_id = lab_item.item_id
GROUP BY name";

$query_run = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
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
      
      <head>
        <!-- Your head content here -->
    </head>
    <body class="size-1520 primary-color-red background-dark">
        <!-- HEADER -->
        <!-- Your header content here -->
    
    <!-- MAIN -->
    <main role="main">
      <!-- TOP SECTION -->
      <div class="container">
      <header class="grid">
          <div class="s-12 padding-2x">
              <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">Request Item </h1>
              <hr style="border: 1px solid white; width: 100%;">
          </div>
      </header>
 
            <?php
            // Display success or error message
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success text-center" role="alert">' .$_SESSION['success_message']. '</div>';
                unset($_SESSION['success_message']); // Clear the success message
            } elseif (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger text-center" role="alert">' .$_SESSION['error_message']. '</div>';
                unset($_SESSION['error_message']); // Clear the error message
            }
            ?>
            <hr>   
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Available Count</th>
                    <th>Needed Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
            <?php
            if ($query_run && mysqli_num_rows($query_run) > 0) {
                while ($row = mysqli_fetch_assoc($query_run)) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['count'] . "</td>";

                    // Count the requested items for the current student
                    $count_needed_query = "SELECT COUNT(*) AS request_count FROM Request WHERE student_id = (SELECT student_id FROM Student WHERE email = '$_SESSION[email]') AND item_name = '" . $row['name'] . "'";
                    $count_needed_run = mysqli_query($connection, $count_needed_query);
                    $count_needed_row = mysqli_fetch_assoc($count_needed_run);
                    $requested_count = $count_needed_row['request_count'];

                    echo "<td>";
                    // Display the current needed count or input for requesting
                    if ($requested_count > 0) {
                        echo $requested_count;
                        echo "<td>";
                        echo "<form action='request_item.php' method='POST'>";
                        echo "<input type='hidden' name='action' value='cancel_request'>";
                        echo "<input type='hidden' name='item_name' value='" . $row['name'] . "'>";
                        echo "<button class='btn btn-danger' type='submit'>Cancel Request</button>";
                        echo "</form>";
                    } else {
                        echo "<form action='request_item.php' method='POST'>";
                        echo "<input type='hidden' name='action' value='request'>";
                        echo "<input type='hidden' name='item_name' value='" . $row['name'] . "'>";
                        echo "<input type='number' name='needed_count' min='1' max='" . $row['count'] . "' required>";
                        echo "</td>";  // Close the Needed Count column
                        echo "<td>";   // Open the Action column
                        echo "<button class='btn btn-primary' type='submit'>Request</button>";
                        echo "</form>";
                    }
                    echo "</td>";  // Close the Action column
                    echo "</tr>";  // Close the row
                }
            } else {
                echo "<tr><td colspan='4'>No items available for request.</td></tr>";
            }
            ?>
            
            </tbody>
        </table>
    </div>
</div>

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
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 
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