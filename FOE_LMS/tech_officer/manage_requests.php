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

// Fetch all request records from the database sorted by timestamp
$query = "SELECT * FROM request ORDER BY timestamp ASC";
$result = mysqli_query($connection, $query);

// Check if the request buttons are clicked (issue or reject)
if (isset($_POST['issue']) || isset($_POST['reject'])) {
    // Retrieve the request id
    $request_id = $_POST['request_id'];

    // Check which button is clicked
    if (isset($_POST['issue'])) {
        // Retrieve request details
        $student_id = $_POST['student_id'];
        $item_name = $_POST['item_name'];
        $officer_id = $_SESSION['officer_id'];
        $due_days = isset($_POST['due_days']) ? (int)$_POST['due_days'] : 14;

        // Ensure due days are between 1 and 30
        if ($due_days < 1 || $due_days > 30) {
            $_SESSION['error_message'] = "Due days must be between 1 and 30.";
            header("Location: manage_requests.php");
            exit();
        }

        // Check if there are any returned items with the same name available for issuing
        $available_item_query = "SELECT MIN(available_items.item_id) AS min_item_id FROM 
        (
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
        WHERE lab_item.name = '$item_name'";

        $available_item_result = mysqli_query($connection, $available_item_query);

        if ($available_item_result) {
            $query_ava_count = "SELECT name, COUNT(*) AS count
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
                                WHERE name = '$item_name'
                                GROUP BY name";

            $query_ava_count_result = mysqli_query($connection, $query_ava_count);
            $available_count_row = mysqli_fetch_assoc($query_ava_count_result);
            $available_count = $available_count_row['count'];

            if ($available_count > 0) {
                // Fetch the result row
                $item_row = mysqli_fetch_assoc($available_item_result);
                $item_id = $item_row['min_item_id'];

                // Calculate due date based on due days
                $due_date = date('Y-m-d H:i:s', strtotime("+$due_days days"));

                // Insert into issued table
                $insert_issued_query = "INSERT INTO issued (issue_date, due_date, item_id, officer_id, student_id) VALUES
                                        (CURRENT_TIMESTAMP, '$due_date', $item_id, $officer_id, $student_id)";
                if (mysqli_query($connection, $insert_issued_query)) {
                    // Delete the record from the request table
                    $delete_request_query = "DELETE FROM request WHERE request_id = $request_id";
                    if (mysqli_query($connection, $delete_request_query)) {
                        // Set success message for issue and deletion
                        $_SESSION['success_message'] = "Request issued successfully.";
                    } else {
                        $_SESSION['error_message'] = "Error deleting request record: " . mysqli_error($connection);
                    }
                } else {
                    // Display error message if insertion fails
                    $_SESSION['error_message'] = "Error issuing item: " . mysqli_error($connection);
                }
            } else {
                // No items available for issuing
                $_SESSION['error_message'] = "No $item_name available for issuing.";
            }
        } else {
            // Display error message if query fails
            $_SESSION['error_message'] = "Error querying item availability: " . mysqli_error($connection);
        }

        $message = "Issued item: $item_name";
        $message1 = "Issued item: $item_name ,Student ID: $student_id , Officer ID: $officer_id";
        log_notification($connection, $student_id, $message);
        log_technical_officer_notification($officer_id, $student_id, $message1, $connection);
    } elseif (isset($_POST['reject'])) {
        // Request is rejected
        // Retrieve the request record from the request table
        $get_request_query = "SELECT * FROM request WHERE request_id = $request_id";
        $request_result = mysqli_query($connection, $get_request_query);
        $request_row = mysqli_fetch_assoc($request_result);

        // Retrieve item_name and student_id from the request row
        $item_name = $request_row['item_name'];
        $student_id = $request_row['student_id'];
        $officer_id = $_SESSION['officer_id'];

        // Insert the rejected request record into the cancelled_request table
        $insert_cancelled_query = "INSERT INTO cancelled_request (request_id, timestamp, student_id, item_name, cancelled_timestamp) 
                                   VALUES ('{$request_row['request_id']}', '{$request_row['timestamp']}', '{$request_row['student_id']}', 
                                   '{$request_row['item_name']}', CURRENT_TIMESTAMP)";

        if (mysqli_query($connection, $insert_cancelled_query)) {
            // Delete the record from the request table
            $delete_request_query = "DELETE FROM request WHERE request_id = $request_id";
            if (mysqli_query($connection, $delete_request_query)) {
                // Set success message for rejection
                $_SESSION['success_message'] = "Request rejected successfully";
            } else {
                // Display error message if deletion fails
                $_SESSION['error_message'] = "Error rejecting request: " . mysqli_error($connection);
            }
        } else {
            // Display error message if insertion into cancelled_request fails
            $_SESSION['error_message'] = "Error inserting into cancelled_request table: " . mysqli_error($connection);
        }

        $message = "Rejected request for item: $item_name";
        $message1 = "Rejected request for item: $item_name ,Student ID: $student_id , Officer ID: $officer_id";
        log_notification($connection, $student_id, $message);
        log_technical_officer_notification($officer_id, $student_id, $message1, $connection);
    }

    // Redirect to refresh the page
    header("Location: manage_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body>
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

      <div class="container">
<main role="main">
        <!-- TOP SECTION -->
        <header class="grid">
        	<div class="s-12 padding-2x">
            <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">MANAGE REQUESTS</h1>
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
        <hr>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
        <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Student ID</th>
                        <th>Item Name</th>
                        <th>Due Days</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                                    // Display request records if available
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['request_id'] . "</td>";
                                            echo "<td>" . $row['timestamp'] . "</td>";
                                            echo "<td>" . $row['student_id'] . "</td>";
                                            echo "<td>" . $row['item_name'] . "</td>";
                                            echo "<td>
                                                    <input type='number' name='due_days' value='14' min='1' max='30' form='form_" . $row['request_id'] . "' class='form-control'>
                                                </td>";
                                            echo "<td>
                                                    <form id='form_" . $row['request_id'] . "' action='' method='post'>
                                                        <input type='hidden' name='request_id' value='" . $row['request_id'] . "'>
                                                        <input type='hidden' name='student_id' value='" . $row['student_id'] . "'>
                                                        <input type='hidden' name='item_name' value='" . $row['item_name'] . "'>
                                                        <button type='submit' name='issue' class='btn btn-success btn-sm'>Issue</button>
                                                        <button type='submit' name='reject' class='btn btn-danger btn-sm'>Reject</button>
                                                    </form>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        // Display message if no requests available
                                        echo "<tr><td colspan='6'>No requests available in the list</td></tr>";
                                    }
                                ?>
                </tbody>
            </table>
        </div>
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
