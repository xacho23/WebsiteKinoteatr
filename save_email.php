<?php
session_start();
if (!isset($_SESSION['loggedin'])) {

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email'] = $email;
        http_response_code(200);
    } else {
        http_response_code(400);
        echo 'Invalid email format';
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}}
?>
