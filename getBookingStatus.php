<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kinoteatr";

if (!isset($_GET['code'])) {
    echo json_encode(["error" => "Код бронирования не указан."]);
    exit();
}

$bookingCode = $_GET['code'];

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT 
            bookings.*, 
            sessions.day, 
            sessions.time, 
            sessions.hall_id, 
            seats.roww, 
            seats.number AS seat_number, 
            bookings.random_code, 
            movies.title 
        FROM bookings 
        JOIN sessions ON bookings.session_id = sessions.id 
        JOIN seats ON bookings.seat_id = seats.id 
        JOIN movies ON sessions.movie_id = movies.id 
        WHERE bookings.random_code = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Ошибка подготовки запроса: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $bookingCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();

    $response = array(
        'status' => $booking['status'],
        'day' => $booking['day'],
        'time' => $booking['time'],
        'hall_id' => $booking['hall_id'],
        'roww' => $booking['roww'],
        'seat_number' => $booking['seat_number'],
        'random_code' => $booking['random_code'],
        'user_email' => $booking['email'],
        'movie_title' => $booking['title']
    );

    echo json_encode($response);
} else {
    echo json_encode(["error" => "Бронирование не найдено."]);
}

$stmt->close();
$conn->close();
?>
