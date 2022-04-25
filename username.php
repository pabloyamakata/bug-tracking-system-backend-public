<?php
session_start();
include('headers.php');

if($method == 'GET' && isset($_SESSION['user_id'])) {
    echo $username_json = json_encode(array(
        'username' => $_SESSION['username']
    ));
}