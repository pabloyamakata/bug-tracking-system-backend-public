<?php
session_start();
include('headers.php');

session_unset();
session_destroy();

if(isset($_SESSION['user_id'])) {
    echo $logout_status = json_encode(array('status' => false));
} else {
    echo $logout_status = json_encode(array('status' => true));
}