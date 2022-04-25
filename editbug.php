<?php
session_start();
include('headers.php');
include('connection.php');

// Edits bug if user doesn't choose another project to assign the error to.
$edit_bug_same_project = "UPDATE bugs 
                          SET name = ?, description = ?, project = ?, project_leader = ?, 
                          current_status = ?, priority_level = ?, severity_level = ?,
                          initial_date = ?, final_date = ? 
                          WHERE id = ? ";

// Edits bug if user does choose another project to assign the error to.
$edit_bug_new_project = "UPDATE bugs 
                         SET project_id = ?, name = ?, description = ?, project = ?, 
                         project_leader = ?, current_status = ?, priority_level = ?, 
                         severity_level = ?, initial_date = ?, final_date = ? 
                         WHERE id = ? ";

if($method == 'POST') {
    $bugdata = json_decode($_POST['values']);
    $project_id = json_decode($_POST['project_id']);
    $bug_id = json_decode($_POST['bug_id']);
    $finalDate = $bugdata->finalDate != '' ? $bugdata->finalDate : NULL;
    $stmt = mysqli_stmt_init($connection);

    if($project_id->id == 0) {
        mysqli_stmt_prepare($stmt, $edit_bug_same_project);
        mysqli_stmt_bind_param(
            $stmt,
            'sssssssssi',
            $bugdata->name,
            $bugdata->description,
            $bugdata->project,
            $bugdata->projectLeader,
            $bugdata->currentStatus,
            $bugdata->priorityLevel,
            $bugdata->severityLevel,
            $bugdata->initialDate,
            $finalDate,
            $bug_id->id
        );
        mysqli_stmt_execute($stmt);

        if(mysqli_affected_rows($connection) < 1) {
            echo $editbug_status = json_encode(array('status' => false));
            die(mysqli_error($connection));
        }

        echo $editbug_status = json_encode(array('status' => true));
    
    } elseif($project_id->id > 0) {
        mysqli_stmt_prepare($stmt, $edit_bug_new_project);
        mysqli_stmt_bind_param(
            $stmt,
            'isssssssssi',
            $project_id->id,
            $bugdata->name,
            $bugdata->description,
            $bugdata->project,
            $bugdata->projectLeader,
            $bugdata->currentStatus,
            $bugdata->priorityLevel,
            $bugdata->severityLevel,
            $bugdata->initialDate,
            $finalDate,
            $bug_id->id
        );
        mysqli_stmt_execute($stmt);

        if(mysqli_affected_rows($connection) < 1) {
            echo $editbug_status = json_encode(array('status' => false));
            die(mysqli_error($connection));
        }

        echo $editbug_status = json_encode(array('status' => true));
    }
    
    mysqli_close($connection);
}