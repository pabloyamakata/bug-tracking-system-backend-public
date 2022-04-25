<?php
session_start();
include('headers.php');
include('connection.php');

if($method == 'GET' && isset($_SESSION['user_id'])) {

    $get_bugs = "SELECT id, reporting_date, name, description, project, 
                project_leader, current_status, priority_level,
                severity_level, initial_date, final_date
                FROM bugs 
                WHERE user_id = ? ";
    
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $get_bugs); 
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $bugs = [];

    while($row = mysqli_fetch_assoc($result)) {
        $bugs[] = $row;
    }
    
    echo $bugs_json = json_encode($bugs);
    
    mysqli_close($connection);
}