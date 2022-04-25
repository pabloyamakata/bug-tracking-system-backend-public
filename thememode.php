<?php
session_start();
include('headers.php');
include('connection.php');

// Updates the theme mode value in the database every time user clicks on the switch button.
$update_mode = "UPDATE theme_mode SET mode = ? WHERE user_id = ? ";

// Gets (remembers) the theme mode value every time user logs in.
$get_mode = "SELECT mode FROM theme_mode WHERE user_id = ? ";

if($method == 'POST' && isset($_SESSION['user_id'])) {
    $thememode = json_decode($_POST['thememode']);
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $update_mode);
    mysqli_stmt_bind_param($stmt, 'ii', $thememode->thememode, $user_id);
    mysqli_stmt_execute($stmt);

    if(mysqli_affected_rows($connection) > 0) {
        echo $update_mode_status = json_encode(array('status' => true));
    }

    mysqli_close($connection);

} elseif($method == 'GET' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $get_mode);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $mode_data = mysqli_fetch_assoc($result);
    echo $mode_data_json = json_encode(array('mode_value' => $mode_data['mode']));

    mysqli_close($connection);
}