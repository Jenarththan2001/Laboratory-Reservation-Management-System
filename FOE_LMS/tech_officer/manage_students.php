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

// Fetch all student records from the database
$query = "SELECT * FROM Student";
$result = mysqli_query($connection, $query);
$officer_id = $_SESSION['officer_id'];
// Check if the form is submitted for adding a new student
if (isset($_POST['add'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insert the new student record into the database
    $insert_query = "INSERT INTO Student (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($connection, $insert_query)) {
        // If insertion is successful, redirect to manage_students.php
        $_SESSION['success_message'] = "New student added successfully.";
       
        $message = "New student added successfully by Officer ID: $officer_id ,Student Email: $email .";
        log_technical_officer_notification_student($officer_id ,$message, $connection);

        header("Location: manage_students.php");
        exit(); // Ensure that no more code is executed after redirection
    } else {
        // If insertion fails, you can handle the error as needed
        $_SESSION['error_message'] = "Error adding studen" . mysqli_error($connection);
    }
}


// Check if the form is submitted for editing a student
if (isset($_POST['edit'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update the student record in the database
    $update_query = "UPDATE Student SET name='$name', email='$email' WHERE student_id=$id";
    if (mysqli_query($connection, $update_query)) {
        // If update is successful, redirect to manage_students.php
        $_SESSION['success_message'] = "Student details edited successfully.";
        header("Location: manage_students.php");
        $message = "Student details edited successfully by Officer ID: $officer_id ,Student ID : $id.";
        log_technical_officer_notification_student($officer_id ,$message, $connection);

        exit(); // Ensure that no more code is executed after redirection
    } else {
        // If update fails, you can handle the error as needed
        $_SESSION['error_message'] = "Error updating student " . mysqli_error($connection);
    }
}


// Check if the delete button is clicked for a student record
if (isset($_GET['delete'])) {
    // Retrieve the student id to be deleted
    $id = $_GET['delete'];

    // Check if there are any associated records in the Issued table
    $check_query = "SELECT * FROM Issued WHERE student_id = $id";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Associated records found, handle them accordingly
        // For example, you can update their status or display a warning message
        $_SESSION['error_message'] = "Cannot delete student. There are associated records in the Issued table. " . mysqli_error($connection);
        
    } else {
        // No associated records found, proceed with deletion
        $delete_query = "DELETE FROM Student WHERE student_id = $id";
        if (mysqli_query($connection, $delete_query)) {
            $_SESSION['error_message'] = "Student deleted successfully.";
            $message = "Student deleted successfully by Officer ID: $officer_id ,Student ID : $id.";
            log_technical_officer_notification_student($officer_id ,$message, $connection);
            header("Location: manage_students.php"); // Redirect to refresh the page
            exit();
        } else {
            // Deletion failed
            $_SESSION['error_message'] = "Error deleting record " . mysqli_error($connection);
        }
    }
    
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

<header class="grid">
        	<div class="s-12 padding-2x">
            <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">MANAGE STUDENTS</h1>
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
        <!-- Add Student Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel" style= "color: black">Add New Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" action="" method="post">
                            <div class="form-group">
                                <label for="addName">Name</label>
                                <input type="text" class="form-control" id="addName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="addEmail">Email</label>
                                <input type="email" class="form-control" id="addEmail" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="addPassword">Password</label>
                                <input type="password" class="form-control" id="addPassword" name="password"
                                    required>
                            </div>
                            <button type="submit" name="add" class="btn btn-primary">Add Student</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">

            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display student records
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['student_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>
                                    <a href='#' class='btn btn-primary btn-sm edit-btn text-white' data-id='" . $row['student_id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "' data-toggle='modal' data-target='#editModal'>Edit</a>
                                    <a href='manage_students.php?delete=" . $row['student_id'] . "' class='btn btn-danger btn-sm text-white' onclick='confirmDelete(event)'>Delete</a>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel" style= "color: black">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="post">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button"  class="s-12 m-9 l-3 center btn btn-primary text-size-20 text-white" data-toggle="modal" data-target="#addModal">
            Add Student
        </button>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Populate edit modal with selected student data
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');

            $('#editId').val(id);
            $('#editName').val(name);
            $('#editEmail').val(email);
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
</body>
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


        function confirmDelete(event) {
            if (!confirm("Are you sure you want to delete this item?")) {
                event.preventDefault(); // Prevent the default action if the user cancels
            }
        }
    
</script>

</html>
