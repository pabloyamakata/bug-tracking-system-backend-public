<?php
session_start();
include('headers.php');
include('connection.php');

// Checks whether user exists in database.
$check_user = "SELECT username, password FROM users WHERE username = ? AND password = ? ";

// Makes an insertion if user doesn't exist in database.
$insert_user = "INSERT INTO users (username, password) VALUES(?, ?)";

// Returns data once user is added to the database.
$get_new_user = "SELECT id, username FROM users WHERE username = ? AND password = ? ";

// Inserts default theme mode value into the database after user signs up for the first time.
$insert_mode = "INSERT INTO theme_mode VALUES(?, ?)";

if($method == 'POST') {
    $userdata = json_decode($_POST['values']);
    $stmt = mysqli_stmt_init($connection);
    
    mysqli_stmt_prepare($stmt, $check_user);
    mysqli_stmt_bind_param($stmt, 'ss', $userdata->username, $userdata->password);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($check_result) < 1) {
        mysqli_stmt_prepare($stmt, $insert_user); 
        mysqli_stmt_bind_param($stmt, 'ss', $userdata->username, $userdata->password);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_prepare($stmt, $get_new_user);
        mysqli_stmt_bind_param($stmt, 'ss', $userdata->username, $userdata->password);
        mysqli_stmt_execute($stmt);
        $select_result = mysqli_stmt_get_result($stmt);

        $user = mysqli_fetch_assoc($select_result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $mode_value = 0;
        mysqli_stmt_prepare($stmt, $insert_mode);
        mysqli_stmt_bind_param($stmt, 'ii', $_SESSION['user_id'], $mode_value);
        mysqli_stmt_execute($stmt);

        echo $registration_status = json_encode(array('status' => true));
    } else {
        echo $registration_status = json_encode(array('status' => false));
    }
    
    mysqli_close($connection);
}