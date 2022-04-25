<?php
session_start();
include('headers.php');
include('connection.php');

if($method == 'POST') {
    $bug_info = json_decode($_POST['bug_id']);
    
    $delete_bug = "DELETE FROM bugs WHERE id = ? ";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $delete_bug);
    mysqli_stmt_bind_param($stmt, 'i', $bug_info->id);
    mysqli_stmt_execute($stmt);

    if(mysqli_affected_rows($connection) > 0) {
        echo $bug_deletion_status = json_encode(array('status' => true));
    }
    
    mysqli_close($connection);
}