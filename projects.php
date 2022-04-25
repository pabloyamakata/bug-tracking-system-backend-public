<?php
session_start();
include('headers.php');
include('connection.php');

if($method == 'GET' && isset($_SESSION['user_id'])) {
    
    $get_projects = "SELECT id, reporting_date, name, description, project_leader,
                    start_date, deadline, current_status, frontend, backend, ddbb
                    FROM projects 
                    WHERE user_id = ? ";
    
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $get_projects);
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $projects = [];

    while($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
    
    echo $projects_json = json_encode($projects);
    
    mysqli_close($connection);
}