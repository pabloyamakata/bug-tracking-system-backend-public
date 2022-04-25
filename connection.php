<?php
$servername = 'xxxxxx';
$username = 'xxxxxx';
$password = 'xxxxxx';
$database = 'xxxxxx';

$connection = mysqli_connect($servername, $username, $password, $database);

if(!$connection) {
    exit('Database connection error: '.mysqli_connect_errno());
}