<?php
session_start();
include('headers.php');
include('connection.php');

// All projects must have a unique name so, it's necessary to make some validations:
// Verifies whether the user changes the name of the project.
$check_project_name = "SELECT name 
                       FROM projects 
                       WHERE id = ? 
                       AND name = ? ";

// Edits project if user doesn't change the name of the project.
$edit_project_same_name = "UPDATE projects 
                           SET description = ?, project_leader = ?, start_date = ?, deadline = ?, 
                           current_status = ?, frontend = ?, backend = ?, ddbb = ? 
                           WHERE id = ? ";

// Updates also 'bugs' table with the new data if user doesn't change the name of the project. 
$update_bugs_table_same_project_name = "UPDATE bugs
                                        SET project_leader = ?
                                        WHERE project_id = ? ";

// Verifies whether the new name exists in database if user changes the name of the project.
$check_new_project_name = "SELECT name 
                           FROM projects 
                           WHERE name = ? 
                           AND user_id = ? ";

// Edits project if user does change its name and this new provided name doesn't exist in database.
$edit_project_different_name = "UPDATE projects 
                                SET name = ?, description = ?, project_leader = ?, 
                                start_date = ?, deadline = ?, current_status = ?, 
                                frontend = ?, backend = ?, ddbb = ? 
                                WHERE id = ? ";

// Updates also 'bugs' table with the new data if user does change the name of the project.
$update_bugs_table_different_project_name = "UPDATE bugs
                                             SET project = ?, project_leader = ?
                                             WHERE project_id = ? ";

if($method == 'POST' && isset($_SESSION['user_id'])) {
    $projectdata = json_decode($_POST['values']);
    $project_id = json_decode($_POST['project_id']);
    $user_id = $_SESSION['user_id'];
    $frontend = $projectdata->frontend != '' ? $projectdata->frontend : NULL;
    $backend = $projectdata->backend != '' ? $projectdata->backend : NULL;
    $database = $projectdata->database != '' ? $projectdata->database : NULL;
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, $check_project_name);
    mysqli_stmt_bind_param($stmt, 'is', $project_id->id, $projectdata->name);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);
        
    if(mysqli_num_rows($check_result) == 1) {
        mysqli_stmt_prepare($stmt, $edit_project_same_name);
        mysqli_stmt_bind_param(
            $stmt,
            'ssssssssi',
            $projectdata->description,
            $projectdata->projectLeader,
            $projectdata->startDate,
            $projectdata->deadline,
            $projectdata->currentStatus,
            $frontend,
            $backend,
            $database,
            $project_id->id
        );
        mysqli_stmt_execute($stmt);

        if(mysqli_affected_rows($connection) < 1) {
            echo $editproject_status = json_encode(array('status' => false));
            die(mysqli_error($connection));
        }
                
        mysqli_stmt_prepare($stmt, $update_bugs_table_same_project_name);
        mysqli_stmt_bind_param(
            $stmt, 
            'si', 
            $projectdata->projectLeader,
            $project_id->id
        );
        mysqli_stmt_execute($stmt);
                
        echo $editproject_status = json_encode(array('status' => true));
                
    } elseif(mysqli_num_rows($check_result) < 1) {
            mysqli_stmt_prepare($stmt, $check_new_project_name);
            mysqli_stmt_bind_param($stmt, 'si', $projectdata->name, $user_id);
            mysqli_stmt_execute($stmt);
            $new_name_result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($new_name_result) < 1) {
                mysqli_stmt_prepare($stmt, $edit_project_different_name);
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssssssssi',
                    $projectdata->name,
                    $projectdata->description,
                    $projectdata->projectLeader,
                    $projectdata->startDate,
                    $projectdata->deadline,
                    $projectdata->currentStatus,
                    $frontend,
                    $backend,
                    $database,
                    $project_id->id
                );
                mysqli_stmt_execute($stmt);
                        
                mysqli_stmt_prepare($stmt, $update_bugs_table_different_project_name);
                mysqli_stmt_bind_param(
                    $stmt,
                    'ssi',
                    $projectdata->name,
                    $projectdata->projectLeader,
                    $project_id->id
                );
                mysqli_stmt_execute($stmt);
                        
                echo $editproject_status = json_encode(array('status' => true));
                    
            } else {
                echo $editproject_status = json_encode(array('status' => 'project already exists'));
            }
        }
    
    mysqli_close($connection);
}