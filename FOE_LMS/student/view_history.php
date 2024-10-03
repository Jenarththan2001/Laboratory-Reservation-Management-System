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

// Fetch issued items history
$queryIssued = "SELECT * FROM Issued WHERE student_id = (SELECT student_id FROM Student WHERE email = '$_SESSION[email]')";
$queryIssued_run = mysqli_query($connection, $queryIssued);

// Fetch requested items history
$queryRequested = "SELECT * FROM request WHERE student_id = (SELECT student_id FROM Student WHERE email = '$_SESSION[email]')";
$queryRequested_run = mysqli_query($connection, $queryRequested);
// Fetch cancelled requests history
$queryCancelled = "SELECT * FROM cancelled_request WHERE student_id = (SELECT student_id FROM Student WHERE email = '$_SESSION[email]')";
$queryCancelled_run = mysqli_query($connection, $queryCancelled);
$fine_query = "SELECT fine_table.*, lab_item.name AS item_name
                FROM fine_table
                JOIN lab_item ON fine_table.item_id = lab_item.item_id
                WHERE fine_table.student_id = (SELECT student_id FROM Student WHERE email = '$_SESSION[email]') ";

$fine_result = mysqli_query($connection, $fine_query);
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
   <!--
    You can change the color scheme of the page. Just change the class of the <body> tag. 
    You can use this class: "primary-color-white", "primary-color-red", "primary-color-orange", "primary-color-blue", "primary-color-aqua", "primary-color-dark" 
    -->
    
    <!--
    Each element is able to have its own background or text color. Just change the class of the element.  
    You can use this class: 
    "background-white", "background-red", "background-orange", "background-blue", "background-aqua", "background-primary" 
    "text-white", "text-red", "text-orange", "text-blue", "text-aqua", "text-primary"
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
                    <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">VIEW MY HISTORY</h1>
                    <hr style="border: 1px solid white; width: 100%;">
                </div>
            </header>
            <div class="container">

        <!-- Dropdown Box -->
    <div class="row mt-4">
        <div class="col-md-12">
            <select id="tableSelector" class="form-control mb-4">
                <option value="issued">Issued</option>
                <option value="requested">Requested</option>
                <option value="cancelled">Cancelled</option>
                <option value="Fined">Fined</option>
            </select>
        </div>
    </div>

<!-- Fined Items Table -->
<div id="FinedTable" style="display: none;">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Fined Table</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Over Due Days/Lost</th>
                        <th>Fine Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display fined items
                    while ($row = mysqli_fetch_assoc($fine_result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['item_id']; ?></td>
                        <td><?php echo $row['item_name']; ?></td>
                        <td><?php echo ($row['days'] == -1 ? 'Lost' : $row['days']); ?></td>
                        <td><?php echo $row['fine_amount']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Cancelled Requests Table -->
<div id="cancelledTable" style="display: none;">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Cancelled Requests</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Cancelled Timestamp</th>
                        <th>Item Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display cancelled requests
                    while ($row = mysqli_fetch_assoc($queryCancelled_run)) {
                        echo "<tr>";
                        echo "<td>" . $row['request_id'] . "</td>";
                        echo "<td>" . $row['cancelled_timestamp'] . "</td>";
                        echo "<td>" . $row['item_name'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Requested Items Table -->
    <div id="requestedTable" style="display: none;">
        <div class="row mt-4">
            <div class="col-md-12">
                <h1 style="color: white; text-align: center;">Requested Items</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Request Timestamp</th>
                            <th>Item Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display requested items
                        while ($row = mysqli_fetch_assoc($queryRequested_run)) {
                            echo "<tr>";
                            echo "<td>" . $row['request_id'] . "</td>";
                            echo "<td>" . $row['timestamp'] . "</td>";
                            echo "<td>" . $row['item_name'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Requested Items Table -->
    <div id="issuedItemsTable">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Issued Items </h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Issue ID</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display issued items
                    while ($row = mysqli_fetch_assoc($queryIssued_run)) {
                        echo "<tr>";
                        echo "<td>" . $row['issue_id'] . "</td>";
                        echo "<td>" . $row['issue_date'] . "</td>";
                        echo "<td>" . $row['due_date'] . "</td>";
                        if (isset($row['return_date'])) {
                            echo "<td style='color: green;'>" . $row['return_date'] . "</td>";
                        } else {
                            $due_date = new DateTime($row['due_date']);
                            $current_date = new DateTime();
                            $interval = $current_date->diff($due_date);
                            $days_diff = $interval->format('%r%a'); // Use '%r%a' to get the signed difference
                    
                            if ($days_diff < 0) {
                                $days_diff = abs($days_diff);
                                echo "<td style='color: red; text-shadow: 1px 1px 2px rgba(255, 0, 0, 0.7), 0 0 10px rgba(255, 0, 0, 0.5);'>Not returned yet! (" . $days_diff . " days overdue !!)</td>";
                            } else {
                                echo "<td style='color: red;'>Not returned yet! (Due in " . $days_diff . " days)</td>";
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#tableSelector').change(function () {
            var selectedOption = $(this).val();
            if (selectedOption === 'issued') {
                $('#issuedItemsTable').show();
                $('#requestedTable').hide();
                $('#cancelledTable').hide();
                $('#FinedTable').hide();
            } else if (selectedOption === 'requested') {
                $('#issuedItemsTable').hide();
                $('#requestedTable').show();
                $('#cancelledTable').hide();
                $('#FinedTable').hide();
            } else if (selectedOption === 'cancelled') {
                $('#issuedItemsTable').hide();
                $('#requestedTable').hide();
                $('#cancelledTable').show();
                $('#FinedTable').hide();
            }else if (selectedOption === 'Fined') {
                $('#issuedItemsTable').hide();
                $('#requestedTable').hide();
                $('#cancelledTable').hide();
                $('#FinedTable').show();
            }
        });
    });
</script>


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
