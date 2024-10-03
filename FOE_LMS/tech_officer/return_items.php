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

// Fetch issued items that are yet to be returned and not marked as lost
$query = "
    SELECT issued.* 
    FROM issued 
    LEFT JOIN fine_table ON issued.item_id = fine_table.item_id AND fine_table.days = -1
    WHERE issued.return_date IS NULL 
    AND fine_table.item_id IS NULL";





$result = mysqli_query($connection, $query);

// Check if the return button is clicked
if (isset($_POST['return'])) {
    // Retrieve the issue id
    $issue_id = $_POST['issue_id'];

    $item_query = "SELECT issued.student_id, lab_item.name as item_name, issued.item_id FROM issued 
                   JOIN lab_item ON issued.item_id = lab_item.item_id 
                   WHERE issued.issue_id = $issue_id";
    $item_result = mysqli_query($connection, $item_query);
    if ($item_result) {
        $item_row = mysqli_fetch_assoc($item_result);
        $student_id = $item_row['student_id'];
        $item_name = $item_row['item_name'];
        $item_id = $item_row['item_id'];
    }

    // Update the return date to current timestamp
    $update_query = "UPDATE issued SET return_date = CURRENT_TIMESTAMP WHERE issue_id = $issue_id";
    if (mysqli_query($connection, $update_query)) {
        // Set success message for return
        $_SESSION['success_message'] = "Item returned successfully";
        $officer_id = $_SESSION['officer_id']; // Assuming officer_id is stored in session
        $message = "Issued item $item_name returned successfully, Issue ID: $issue_id";
        $message1 = "Issued item $item_name returned successfully , Issue ID: $issue_id ,Student ID: $student_id ,Officer ID: $officer_id";
        log_notification($connection, $student_id, $message);
        log_technical_officer_notification($officer_id, $student_id, $message1, $connection);
        
    } else {
        // Display error message if update fails
        $_SESSION['error_message'] = "Error returning item: " . mysqli_error($connection);
    }

    // Redirect to refresh the page
    header("Location: return_items.php");
    exit();
}


// Check if the fine for late return button is clicked
if (isset($_POST['fine_late'])) {
    $issue_id = $_POST['issue_id'];
    $days_left = $_POST['days_left'];
    $fine_amount = 500;
    $over_due=$days_left;
    // Fetch the student_id and item_id
    $item_query = "SELECT student_id, item_id FROM issued WHERE issue_id = $issue_id";
    $item_result = mysqli_query($connection, $item_query);
    if ($item_result) {
        $item_row = mysqli_fetch_assoc($item_result);
        $student_id = $item_row['student_id'];
        $item_id = $item_row['item_id'];

        // Begin a transaction
        mysqli_begin_transaction($connection);

        try {
            // Insert fine record into the fine_table
            $insert_fine_query = "INSERT INTO fine_table (student_id, item_id, days, fine_amount) VALUES ('$student_id', '$item_id', '$over_due', '$fine_amount')";
            mysqli_query($connection, $insert_fine_query);

            // Update the return date to current timestamp
            $update_query = "UPDATE issued SET return_date = CURRENT_TIMESTAMP WHERE issue_id = $issue_id";
            mysqli_query($connection, $update_query);

            // Commit the transaction
            mysqli_commit($connection);

            $_SESSION['success_message'] = "Late return fine imposed and returned successfully.";
                // Log student notification for late return fine
                   $message = "Late return fine imposed and returned successfully for item $item_name, Issue ID: $issue_id";
                   log_notification($connection, $student_id, $message);
    
                 // Log officer notification for late return fine
                  $officer_id = $_SESSION['officer_id']; // Assuming officer_id is stored in session
                  $message1 = "Late return fine imposed for item $item_name, Issue ID: $issue_id, Student ID: $student_id, Officer ID: $officer_id";

    log_technical_officer_notification($officer_id, $student_id, $message1, $connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
            $_SESSION['error_message'] = "Error imposing fine or updating return date: " . $exception->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Error fetching student and item details: " . mysqli_error($connection);
    }

    // Redirect to refresh the page
    header("Location: return_items.php");
    exit();
}

// Check if the fine for lost item button is clicked
if (isset($_POST['fine_lost'])) {
    $issue_id = $_POST['issue_id'];
    $fine_amount = 1000;

    // Fetch the student_id and item_id
    $item_query = "SELECT student_id, item_id FROM issued WHERE issue_id = $issue_id";
    $item_result = mysqli_query($connection, $item_query);
    if ($item_result) {
        $item_row = mysqli_fetch_assoc($item_result);
        $student_id = $item_row['student_id'];
        $item_id = $item_row['item_id'];

        // Insert fine record into the fine_table
        $insert_fine_query = "INSERT INTO fine_table (student_id, item_id, days, fine_amount) VALUES ('$student_id', '$item_id', -1, '$fine_amount')";
        if (mysqli_query($connection, $insert_fine_query)) {
            $_SESSION['success_message'] = "Lost item fine imposed successfully.";

                // Log student notification for lost item fine
    $message = "Lost item fine imposed successfully for item $item_name, Issue ID: $issue_id";
    log_notification($connection, $student_id, $message);
    
    // Log officer notification for lost item fine
    $officer_id = $_SESSION['officer_id']; // Assuming officer_id is stored in session
    $message1 = "Lost item fine imposed for item $item_name, Issue ID: $issue_id, Student ID: $student_id, Officer ID: $officer_id";
    log_technical_officer_notification($officer_id, $student_id, $message1, $connection);
        } else {
            $_SESSION['error_message'] = "Error imposing fine: " . mysqli_error($connection);
        }
    } else {
        $_SESSION['error_message'] = "Error fetching student and item details: " . mysqli_error($connection);
    }

    // Redirect to refresh the page
    header("Location: return_items.php");
    exit();
}
// Fetch fine details along with the item name
$fine_query = "
    SELECT fine_table.*, lab_item.name AS item_name
    FROM fine_table
    JOIN lab_item ON fine_table.item_id = lab_item.item_id
";
$fine_result = mysqli_query($connection, $fine_query);
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="#" onclick="confirmLogout()">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <head>
        <!-- TOP SECTION -->
        <header class="grid">
            <div class="s-12 padding-2x">
                <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">RETURN
                    ITEMS</h1>
            </div>
        </header>
        
        <?php
        
    // Display success message if set
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success text-center" role="alert" style="color: green; font-size: 30px;">' .$_SESSION['success_message']. '</div>';
        unset($_SESSION['success_message']); // Clear the success message
    }

    // Display error message if set
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger text-center" role="alert" style="color: red; font-size: 30px;">' .$_SESSION['error_message']. '</div>';
        unset($_SESSION['error_message']); // Clear the error message
    }
    ?>
        <div class="container">
        <hr style="border: 1px solid white; width: 100%;">
    <!-- Dropdown Box -->
    <div class="row mt-4">
        <div class="col-md-12">
            <select id="tableSelector" class="form-control mb-4">
                <option value="issued">Issued Items Yet to be Returned</option>
                <option value="all_details">All Details</option>
                <option value="fine_table">Fine Table</option>
            </select>
        </div>
    </div>
    <!-- Table Section -->
    <div id="issuedItemsTable">
        <div class="row mt-4">
            <div class="col-md-12">
                <h1 style="color: white; text-align: center;">Issued Items Yet to be Returned</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Issue ID</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Days Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                        // Display issued items
                        while ($row = mysqli_fetch_assoc($result)) {
                            $issue_id = $row['issue_id'];
                            $issue_date = new DateTime($row['issue_date']);
                            $due_date = new DateTime($row['due_date']);
                            $current_date = new DateTime();
                            $days_left = $current_date->diff($due_date)->format("%r%a");

                            echo "<tr>";
                            echo "<td>" . $issue_id . "</td>";
                            echo "<td>" . $issue_date->format('Y-m-d') . "</td>";
                            echo "<td>" . $due_date->format('Y-m-d') . "</td>";
                            $interval = $issue_date->diff($current_date);  // Assuming you meant to calculate this interval
                            $daysDifference = $interval->days;

                            $color1 = $daysDifference > 0 ? 'red' : 'green';
                            $shadow1 = $daysDifference > 0 ? 'red' : 'green';
                            
                            echo "<td style='color: {$color1}; text-shadow: 1px 1px 2px {$shadow1};'>" . ($row['return_date'] ?? 'Not returned') . "</td>";
                            if ($days_left >= 0) {
                                echo "<td style='color: {$color1}; text-shadow: 1px 1px 2px {$shadow1};'>" . $days_left . " days</td>";
                            } else {
                                echo "<td style='color: {$color1}; text-shadow: 1px 1px 2px {$shadow1};'>" . abs($days_left) . " days, Due Over!</td>";
                            }
                            echo "<td>";
                            if ($days_left >= 0) {
                                echo "<form action='' method='post' style='display:inline-block;'>
                                        <input type='hidden' name='issue_id' value='" . $issue_id . "'>
                                        <button type='submit' name='return' class='btn btn-primary btn-sm'>Return</button>
                                      </form>";
                            } else {
                                echo "<form action='' method='post' style='display:inline-block; margin-right: 10px;'>
                                <input type='hidden' name='issue_id' value='" . $issue_id . "'>
                                <input type='hidden' name='days_left' value='" . abs($days_left) . "'>
                                <button type='submit' name='fine_late' class='btn btn-warning btn-sm'>Fine for Late Return</button>
                              </form>";
                        echo "<form action='' method='post' style='display:inline-block;'>
                                <input type='hidden' name='issue_id' value='" . $issue_id . "'>
                                <button type='submit' name='fine_lost' class='btn btn-danger btn-sm'>Fine for Lost Item</button>
                              </form>";
                        
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="fineTable" style="display: none;">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Fine Table</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Item Name</th>
                        <th>Over Due Days/Lost</th>
                        <th>Fine Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display fine details
                    while ($row = mysqli_fetch_assoc($fine_result)) {
                        echo "<tr>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['item_name'] . "</td>";
                        echo "<td>" . ($row['days'] == -1 ? 'Lost' : $row['days']) . "</td>"; // Check for -1 and display 'Lost'
                        echo "<td>" . $row['fine_amount'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div id="allDetailsTable" style="display: none;">
        <div class="row mt-4">
            <div class="col-md-12">
                <h1 style="color: white; text-align: center;">All Details</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Issue ID</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Item ID</th>
                            <th>Officer ID</th>
                            <th>Student ID</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
// Fetch all details from the issued table
// Fetch all details from the issued table
$all_details_query = "
    SELECT issued.*, 
           CASE WHEN fine_table.days = -1 THEN 'Lost' ELSE issued.return_date END AS return_status 
    FROM issued 
    LEFT JOIN fine_table ON issued.item_id = fine_table.item_id
    ORDER BY issue_id ASC"
    ;
$all_details_result = mysqli_query($connection, $all_details_query);

// Display all details
while ($row = mysqli_fetch_assoc($all_details_result)) {
    echo "<tr>";
    echo "<td>" . $row['issue_id'] . "</td>";
    echo "<td>" . $row['issue_date'] . "</td>";
    echo "<td>" . $row['due_date'] . "</td>";
    echo "<td>" . ($row['return_status'] ?? 'Not returned') . "</td>"; // Use return_status to show return date or indicate loss
    echo "<td>" . $row['item_id'] . "</td>";
    echo "<td>" . $row['officer_id'] . "</td>";
    echo "<td>" . $row['student_id'] . "</td>";
    echo "</tr>";
}
?>

                    </tbody>
                </table>
            </div>
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
            $('#allDetailsTable').hide();
            $('#fineTable').hide(); // Hide Fine Table
        } else if (selectedOption === 'all_details') {
            $('#issuedItemsTable').hide();
            $('#allDetailsTable').show();
            $('#fineTable').hide(); // Hide Fine Table
        } else if (selectedOption === 'fine_table') {
            $('#issuedItemsTable').hide();
            $('#allDetailsTable').hide();
            $('#fineTable').show(); // Show Fine Table
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
