<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['use_bonuses'])) {
        $_SESSION['use_bonuses'] = filter_var($_POST['use_bonuses'], FILTER_VALIDATE_BOOLEAN);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
