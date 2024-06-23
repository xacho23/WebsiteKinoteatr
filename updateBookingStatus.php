<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kinoteatr";

if (isset($_GET['code']) && isset($_GET['status'])) {
    $bookingCode = $_GET['code'];
    $newStatus = $_GET['status'];

    // Создание подключения
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка подключения
    if ($conn->connect_error) {
        echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    $sql = "UPDATE bookings SET status = ? WHERE random_code = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => "Ошибка подготовки запроса: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $newStatus, $bookingCode);
    if ($stmt->execute()) {
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        echo json_encode(["error" => "Ошибка выполнения запроса: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array('error' => 'Код бронирования или статус не указан.'));
}
?>
