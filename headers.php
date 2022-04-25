<?php
header('Access-Control-Allow-Origin: https://example.com'); 
header("Access-Control-Allow-Credentials: true");
header('Set-Cookie: PHPSESSID= ' . session_id() . '; SameSite=None; Secure');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];

if($method == "OPTIONS") {
    die();
}