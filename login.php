<?php
session_start();
include('headers.php');
include('connection.php');

if($method == 'POST') {
    $userdata = json_decode($_POST['values']);

    $get_user = "SELECT id, username, password 
                 FROM users 
                 WHERE username = ? 
                 AND password = ? ";
                
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $get_user);
    mysqli_stmt_bind_param($stmt, 'ss', $userdata->username, $userdata->password);
    mysqli_stmt_execute($stmt);   
    $result = mysqli_stmt_get_result($stmt); 
        
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result); 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo $login_status = json_encode(array('status' => true));
    } else echo $login_status = json_encode(array('status' => false));

    mysqli_close($connection);
}