<?php
session_start();
include('headers.php');
include('connection.php');

if($method == 'POST') {
    $project_info = json_decode($_POST['project_id']);

    $delete_project = "DELETE FROM projects WHERE id = ? ";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $delete_project);
    mysqli_stmt_bind_param($stmt, 'i', $project_info->id);
    mysqli_stmt_execute($stmt);

    if(mysqli_affected_rows($connection) > 0) {
        echo $project_deletion_status = json_encode(array('status' => true));
    }

    mysqli_close($connection);
}