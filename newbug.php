<?php
session_start();
include('headers.php');
include('connection.php');

$insert_bug = "INSERT INTO bugs (
    user_id,
    project_id, 
    reporting_date,
    name, 
    description, 
    project, 
    project_leader, 
    current_status, 
    priority_level, 
    severity_level, 
    initial_date, 
    final_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if($method == 'POST' && isset($_SESSION['user_id'])) {
    $bugdata = json_decode($_POST['values']);
    $projectinfo = json_decode($_POST['project_id']);
    $bug_reporting_date = json_decode($_POST['bug_reporting_date']);
    $user_id = $_SESSION['user_id'];
    $finalDate = $bugdata->finalDate != '' ? $bugdata->finalDate : NULL;
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, $insert_bug); 
    mysqli_stmt_bind_param(
        $stmt,
        'iissssssssss',
        $user_id,
        $projectinfo->id,
        $bug_reporting_date->date,
        $bugdata->name,
        $bugdata->description,
        $bugdata->project,
        $bugdata->projectLeader,
        $bugdata->currentStatus,
        $bugdata->priorityLevel,
        $bugdata->severityLevel,
        $bugdata->initialDate,
        $finalDate
    );
    mysqli_stmt_execute($stmt);
            
    if(mysqli_affected_rows($connection) < 1) {
        echo $newbug_status = json_encode(array('status' => false));
        die(mysqli_error($connection));
    }
        
    echo $newbug_status = json_encode(array('status' => true));
        
    mysqli_close($connection);
}