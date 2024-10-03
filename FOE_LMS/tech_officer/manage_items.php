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

// Fetch all item records from the database
$query = "SELECT * FROM lab_item";
$result = mysqli_query($connection, $query);
$officer_id = $_SESSION['officer_id'];
// Handle form submissions for adding, editing, and deleting items
if (isset($_POST['add'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $count = (int)$_POST['count'];

    $no = 0;

    while ($count > 0) {
        $no += 1;
    
        // Insert the new item record into the database
        $insert_query = "INSERT INTO lab_item (name, price) VALUES ('$name', '$price')";
        if (mysqli_query($connection, $insert_query)) {
            if ($no == $_POST['count']) {
                // If insertion is successful, set success message and redirect to manage_items.php
                $_SESSION['success_message'] = "New {$_POST['name']} of {$_POST['count']}  added successfully.";
                $message = "New item added successfully by Officer ID: $officer_id ,Item Name: $name ,Count:{$_POST['count']}";
                log_technical_officer_notification_item($officer_id ,$message, $connection);
    
                header("Location: manage_items.php");
                exit();
            }
        } else {
            // If insertion fails, set error message
            $_SESSION['error_message'] = "Error adding item" . mysqli_error($connection);
            break;
        }
        $count -= 1;
    }
    
}

if (isset($_POST['edit'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Update the item record in the database
    $update_query = "UPDATE lab_item SET name='$name', price='$price' WHERE item_id=$id";
    if (mysqli_query($connection, $update_query)) {
        // If update is successful, set success message and redirect to manage_items.php
        $_SESSION['success_message'] = "Item edited successfully.";

        $message = "Item edited successfully by Officer ID: $officer_id ,Item ID: $id.";
        log_technical_officer_notification_item($officer_id ,$message, $connection);

        header("Location: manage_items.php");
        exit();
    } else {
        // If update fails, set error message
        $_SESSION['error_message'] = "Error updating item" . mysqli_error($connection);
    }
}

if (isset($_GET['delete'])) {
    // Retrieve the item ID to be deleted
    $id = $_GET['delete'];

    // Check if there are any associated records in the issued table where return_date is null
    $check_query = "SELECT * FROM issued WHERE item_id = $id AND return_date IS NULL";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Associated records with null return_date found, set error message
        $_SESSION['error_message'] = "Cannot delete item. There are associated records with null return dates in the Issued table.";
    } else {
        // No associated records found with null return_date, proceed with deletion

        // Delete the item record from the issued table
        $delete_issued_query = "DELETE FROM issued WHERE item_id = $id";
        if (!mysqli_query($connection, $delete_issued_query)) {
            // If deletion from issued table fails, set error message
            $_SESSION['error_message'] = "Error deleting item from issued table: " . mysqli_error($connection);
            exit(); // Exit the script to prevent further execution
        }

        // Delete the item record from the lab_item table
        $delete_lab_item_query = "DELETE FROM lab_item WHERE item_id = $id";
        if (mysqli_query($connection, $delete_lab_item_query)) {
            // If deletion from lab_item table is successful, set success message
            $_SESSION['success_message'] = "{$_POST['name']} deleted successfully.";
            $message = "Item deleted successfully by Officer ID: $officer_id ,Item ID: $id.";
            log_technical_officer_notification_item($officer_id ,$message, $connection);
            header("Location: manage_items.php");
            exit();
        } else {
            // If deletion from lab_item table fails, set error message
            $_SESSION['error_message'] = "Error deleting item from lab_item table: " . mysqli_error($connection);
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
      <script>
        function confirmDelete(event) {
            if (!confirm("Are you sure you want to delete this item?")) {
                event.preventDefault(); // Prevent the default action if the user cancels
            }
        }
    </script>
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
        <div class="container">
            <main role="main">
                <!-- TOP SECTION -->
                <header class="grid">
                    <div class="s-12 padding-2x">
                        <h1 class="text-strong text-white text-center center text-size-60 text-uppercase margin-bottom-20">
                            MANAGE ITEMS</h1>
                    </div>
                </header>
                <?php
                // Display success message if set
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success text-center" role="alert" style="color: green; font-size: 30px;">' . $_SESSION['success_message'] . '</div>';
                    unset($_SESSION['success_message']); // Clear the success message
                }

                // Display error message if set
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger text-center" role="alert" style="color: red; font-size: 30px;">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']); // Clear the error message
                }
                ?>
                <hr>
                <!-- Add Item Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel" style="color: black;">Add New Item</h5>
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
                                        <label for="addPrice">Price</label>
                                        <input type="number" class="form-control" id="addPrice" name="price"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="addPrice">Qunatity</label>
                                        <input type="number" class="form-control" id="addCount" name="count"
                                            required>
                                    </div>
                                    <button type="submit" name="add" class="btn btn-primary">Add Item</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                            <!-- Edit Item Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel" style="color: black;">Edit Item</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editForm" action="" method="post">
                                                <input type="hidden" id="editId" name="id">
                                                <div class="form-group">
                                                    <label for="editName" style="">Name</label>
                                                    <input type="text" class="form-control text-black" id="editName"
                                                        name="name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="editPrice">Price</label>
                                                    <input type="number" class="form-control text-black"
                                                        id="editPrice" name="price">
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save
                                                    Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

<section>
<div class="container">
                    <!-- Dropdown Box -->
                    <div class="row ">
                        <div class="col">
                            <select id="tableSelector" class="form-control">
                                <option value="table1">Item Table With ID </option>
                                <option value="table2">Item Table With Count</option>
                                <option value="table3">Available Item with Count</option>
                                <option value="table4">Issued Item with Count</option>
                                <option value="table5">Lost Items</option>
                            </select>
                        </div>
                    </div>
                    <!-- Table Section -->
                    <div id="table1">
                        <div class="row ">
                            <div class="col-md-12">
                                <h1 style="color: white; text-align: center;">Item Table With ID</h1>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Display item records
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['item_id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['price'] . "" . "</td>";
                                            echo "<td>
                                                <a href='#' class='btn btn-primary btn-sm edit-btn text-white'
                                                    data-id='" . $row['item_id'] . "' data-name='" . $row['name'] . "'
                                                    data-price='" . $row['price'] . "' data-toggle='modal'
                                                    data-target='#editModal'>Edit</a>
                                                <a href='manage_items.php?delete=" . $row['item_id'] . "'
                                                    class='btn btn-danger btn-sm text-white' onclick = 'confirmDelete(event)'>Delete</a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="table2" style="display: none;">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h1 style="color: white; text-align: center;">Item Table With Count</h1>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch item data along with the count of each item
                                        $count_query = "SELECT name, price, COUNT(*) AS count FROM lab_item GROUP BY name, price";

                                        $count_result = mysqli_query($connection, $count_query);

                                        // Loop through the results and display each item with its count
                                        while ($row = mysqli_fetch_assoc($count_result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['price'] . "</td>";
                                            echo "<td>" . $row['count'] . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        </div>
<div id="table3" style="display: none;">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Available Item with Count</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Execute the query
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

                    // Check if query is successful
                    if ($query_run) {
                        // Loop through the results and display each item with its count
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['count'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Display an error message if the query fails
                        echo "<tr><td colspan='2'>Error fetching data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



                            <div id="table4" style="display: none;">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h1 style="color: white; text-align: center;">Issued Item with Count</h1>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                        <th>Name</th>
                                                    <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                                // Fetch item data along with the count of each item
                                                $issued_count_query = "SELECT lab_item.name, COUNT(issued.item_id) AS count
                                                    FROM lab_item
                                                    LEFT JOIN issued ON lab_item.item_id = issued.item_id
                                                    WHERE issued.return_date IS NULL
                                                    GROUP BY lab_item.name";

                                                $issued_count_result = mysqli_query($connection, $issued_count_query);

                                                // Loop through the results and display each item with its count
                                                while ($row = mysqli_fetch_assoc($issued_count_result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['name'] . "</td>";
                                                    echo "<td>" . $row['count'] . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        </div>

                        <div id="table5" style="display: none;">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1 style="color: white; text-align: center;">Lost Items</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Fine Amount</th>
                        <th>Lost By (Student ID)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch lost items from the database
                    $lost_query = "
                        SELECT fine_table.student_id, fine_table.item_id, fine_table.days, fine_table.fine_amount, lab_item.name, lab_item.price
                        FROM fine_table
                        JOIN lab_item ON fine_table.item_id = lab_item.item_id
                        WHERE fine_table.days = -1";
                    $lost_result = mysqli_query($connection, $lost_query);
                    while ($row = mysqli_fetch_assoc($lost_result)) {
                        ?>
                        <tr>
                            <td ><?php echo $row['item_id']; ?></td>
                            <td ><?php echo $row['name']; ?></td>
                            <td ><?php echo $row['price']; ?></td>
                            <td ><?php echo $row['fine_amount']; ?></td>
                            <td ><?php echo $row['student_id']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




</section>
<section class="grid margin-bottom">
                    <div class="s-12 padding-2x background-dark">
                        <button type="button"
                            class="s-12 m-9 l-3 center btn btn-primary text-size-20 text-white" data-toggle="modal"
                            data-target="#addModal" class="btn btn-primary background-aqua">Add Item</button>
                    </div>
                </section>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        // Populate edit modal with selected item data
        $('.edit-btn').click(function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var price = $(this).data('price');

            $('#editId').val(id);
            $('#editName').val(name);
            $('#editPrice').val(price);
        });

        // Handle table selection change
        $('#tableSelector').change(function () {
            var selectedOption = $(this).val();
            if (selectedOption === 'table1') {
                $('#table1').show();
                $('#table2').hide();
                $('#table3').hide();
                $('#table4').hide();
                $('#table5').hide();
            } else if (selectedOption === 'table2') {
                $('#table1').hide();
                $('#table2').show();
                $('#table3').hide();
                $('#table4').hide();
                $('#table5').hide();
            } else if (selectedOption === 'table3') {
                $('#table1').hide();
                $('#table2').hide();
                $('#table3').show();
                $('#table4').hide();
                $('#table5').hide();
            } else if (selectedOption === 'table4') {
                $('#table1').hide();
                $('#table2').hide();
                $('#table3').hide();
                $('#table4').show();
                $('#table5').hide();
            } else if (selectedOption === 'table5') {
                $('#table1').hide();
                $('#table2').hide();
                $('#table3').hide();
                $('#table4').hide();
                $('#table5').show();
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

