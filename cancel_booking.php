<?php
// cancel_booking.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];

    // Ваша логика для удаления бронирования
    $result = deleteBookingById($booking_id);

    if ($result) {
        // Сообщение об успешной отмене
        // echo "Бронирование успешно отменено.";
        header('Location: profile.php'); // Перенаправление на profile.php
        exit();
    } else {
        echo "Ошибка при отмене бронирования.";
    }
}

function deleteBookingById($booking_id) {
    // Ваша логика для удаления бронирования из базы данных
    // Пример:
    $query = "DELETE FROM bookings WHERE id=?";
    
    // Подключение к базе данных и выполнение запроса с использованием подготовленных выражений
    // Предположим, что у вас есть функция для получения подключения к базе данных
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();

    return $result;
}

function getDatabaseConnection() {
    // Ваша логика для подключения к базе данных
    // Например, с использованием MySQLi:
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'kinoteatr';

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
