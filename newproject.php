<?php
session_start();
include('headers.php');
include('connection.php');

// Checks whether a project with provided name exists in database.
$check_project = "SELECT name FROM projects WHERE name = ? AND user_id = ? ";

$insert_project = "INSERT INTO projects (
    user_id,
    reporting_date,
    name,
    description,
    project_leader,
    start_date,
    deadline,
    current_status,
    frontend,
    backend,
    ddbb
) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if($method == 'POST' && isset($_SESSION['user_id'])) {
    $projectdata = json_decode($_POST['values']);
    $project_reporting_date = json_decode($_POST['project_reporting_date']);
    $user_id = $_SESSION['user_id'];
    $frontend = $projectdata->frontend != '' ? $projectdata->frontend : NULL;
    $backend = $projectdata->backend != '' ? $projectdata->backend : NULL;
    $database = $projectdata->database != '' ? $projectdata->database : NULL;
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, $check_project); 
    mysqli_stmt_bind_param($stmt, 'si', $projectdata->name, $user_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($check_result) < 1) {
        mysqli_stmt_prepare($stmt, $insert_project);
        mysqli_stmt_bind_param(
            $stmt,
            'issssssssss',
            $user_id,
            $project_reporting_date->date,
            $projectdata->name,
            $projectdata->description,
            $projectdata->projectLeader,
            $projectdata->startDate,
            $projectdata->deadline,
            $projectdata->currentStatus,
            $frontend,
            $backend,
            $database
        );
        mysqli_stmt_execute($stmt);

        if(mysqli_affected_rows($connection) < 1) {
            echo $newproject_status = json_encode(array('status' => false));
            die(mysqli_error($connection));
        }

        echo $newproject_status = json_encode(array('status' => true));
    } else {
        echo $newproject_status = json_encode(array('status' => 'project already exists'));
    }

    mysqli_close($connection);
}