<?php
session_start(); // Начинаем сессию

$host = 'localhost';
$dbname = 'kinoteatr';
$username = 'root';
$password = '';

// Создаем соединение
$conn = new mysqli($host, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['mail'];

// Проверка на валидность электронной почты
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Ошибка: Неверный формат электронной почты.";
    $_SESSION['message_type'] = 'error'; // Можно использовать для стилизации сообщения
    header("Location: signup.php");
    exit();
}

// Проверяем, существует ли уже такой email
$sql = "SELECT id FROM users WHERE mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Если найден, устанавливаем сообщение об ошибке и перенаправляем
    $_SESSION['message'] = "Ошибка: Пользователь с таким email уже существует.";
    $_SESSION['message_type'] = 'error'; // Можно использовать для стилизации сообщения
    header("Location: signup.php");
    exit();
} else {
    // Если не найден, продолжаем регистрацию
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_ARGON2I); // Хешируем пароль
    $role = 'client';

    $sql = "INSERT INTO users (mail, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Регистрация успешна.";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "Ошибка регистрации: " . $stmt->error;
        $_SESSION['message_type'] = 'error';
    }

    header("Location: signup.php");
    exit();
}

$stmt->close();
$conn->close();
?>
