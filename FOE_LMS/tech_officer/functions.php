<?php
function log_notification($connection, $student_id, $message) {
    $insert_query = "INSERT INTO notifications (student_id, message) VALUES ($student_id, '$message')";
    mysqli_query($connection, $insert_query);
}
function log_technical_officer_notification($officer_id, $student_id ,$message, $connection) {
    $log_query = "INSERT INTO technical_officer_notifications (officer_id,student_id, message) VALUES ('$officer_id','$student_id', '$message')";
    mysqli_query($connection, $log_query);
}
function log_technical_officer_notification_student($officer_id ,$message, $connection) {
    $log_query = "INSERT INTO technical_officer_notifications (officer_id, message) VALUES ('$officer_id','$message')";
    mysqli_query($connection, $log_query);
}
function log_technical_officer_notification_item($officer_id ,$message, $connection) {
    $log_query = "INSERT INTO technical_officer_notifications (officer_id, message) VALUES ('$officer_id','$message')";
    mysqli_query($connection, $log_query);
}
?>