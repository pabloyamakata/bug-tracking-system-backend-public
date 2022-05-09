<?php
$http_origin = $_SERVER['HTTP_ORIGIN'];
if($http_origin == "http://example1.com") {
    header("Access-Control-Allow-Origin: http://example1.com");
} elseif($http_origin == "http://example2.com") {
    header("Access-Control-Allow-Origin: http://example2.com");
}
header("Access-Control-Allow-Credentials: true");
header('Set-Cookie: PHPSESSID= ' . session_id() . '; SameSite=None; Secure');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];

if($method == "OPTIONS") {
    die();
}