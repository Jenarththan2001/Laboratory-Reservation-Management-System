<?php
function log_notification($student_id, $message, $connection) {
    $log_query = "INSERT INTO notifications (student_id, message) VALUES ('$student_id', '$message')";
    mysqli_query($connection, $log_query);
}

function log_technical_officer_notification($student_id, $message, $connection) {
    $log_query = "INSERT INTO technical_officer_notifications (student_id, message) VALUES ('$student_id', '$message')";
    mysqli_query($connection, $log_query);
}


?>