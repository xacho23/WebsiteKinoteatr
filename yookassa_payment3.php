<?php
session_start();

$host = 'localhost'; // Замените на ваш хост
$db = 'kinoteatr'; // Замените на имя вашей базы данных
$user = 'root'; // Замените на вашего пользователя базы данных
$pass = ''; // Замените на ваш пароль базы данных

$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}
if (!isset($_SESSION['loggedin'])) {
    $email = $_GET['email'];
}else{
    $email = $_SESSION['email'];
}
$token = bin2hex(random_bytes(32));
// Находим все билеты пользователя со статусом "бронь"
$stmt = $pdo->prepare('
    SELECT b.session_id, b.seat_id, s.hall_id, h.price 
    FROM bookings b 
    JOIN sessions s ON b.session_id = s.id
    JOIN halls h ON s.hall_id = h.hall_id
    WHERE b.email = ? AND b.status = "Бронь"
');
$stmt->execute([$email]);
$tickets = $stmt->fetchAll();

if (empty($tickets)) {
    die('У вас нет забронированных билетов.');
}
$totalPrice = 0;
$seatIds = [];
$sessionId = null;

foreach ($tickets as $ticket) {
    $totalPrice += $ticket['price'];
    $seatIds[] = $ticket['seat_id'];
    $sessionId = $ticket['session_id'];
}
$bonus1 = isset($_POST['useBonuses']) ? (int)$_POST['useBonuses'] : 0;

if ($bonus1 === 0) {
    $bonus = 0;
    $_SESSION['use_bonuses'] = false;
}
else{
$bonus = $_SESSION['userBonus'];
$_SESSION['use_bonuses'] = true;
}
$totalPrice -= $bonus;
$_SESSION['price'] = $totalPrice;
$price = $_SESSION['price'];
foreach ($seatIds as $seatId) {
    $stmt = $pdo->prepare('UPDATE bookings SET token = ? WHERE seat_id = ?');
    $stmt->execute([$token, $seatId]);
}
$paymentData = [
    'amount' => [
        'value' => $price,
        'currency' => 'RUB',
    ],
    'confirmation' => [
        'type' => 'redirect',
        'return_url' => 'http://localhost/payment_success2.php?session_id=' . $sessionId . '&seat_ids=' . implode(',', $seatIds) .   '&email=' . $email . '&token=' . $token,
    ],
    'capture' => true,
    'description' => 'Покупка билетов в кинотеатр',
];

// Ваши настройки для API ЮКассы
$shopId = '389385';
$secretKey = 'test_ULXoHMfmSWIgrHO5zlb9djkI7tdTT9rNL3uGIy21pGE';

$ch = curl_init('https://api.yookassa.ru/v3/payments');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Idempotence-Key: ' . uniqid(),
    'Authorization: Basic ' . base64_encode("$shopId:$secretKey"),
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);
if (isset($responseData['confirmation']['confirmation_url'])) {
    header('Location: ' . $responseData['confirmation']['confirmation_url']);
    exit();
} else {
    echo 'Ошибка при создании платежа. Пожалуйста, попробуйте позже.';
}
?>
