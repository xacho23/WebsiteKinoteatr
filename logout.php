<?php
session_start(); // Начинаем или продолжаем сессию

// Удаляем все переменные сессии
$_SESSION = array();

// Уничтожаем сессию
session_destroy();

// Перенаправляем на главную страницу или на страницу входа
header("Location: login.php");
exit();
