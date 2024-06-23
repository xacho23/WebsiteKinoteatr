<?php
session_start();

// Проверяем, авторизован ли пользователь и имеет ли он права администратора
$host = 'localhost';
$db   = 'kinoteatr';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


// Проверка наличия необходимых данных
if (!isset($_POST['movie_id'], $_POST['day'], $_POST['time'], $_POST['hall_id'])) {
    // В случае ошибки можно установить сообщение об ошибке через сессию и перенаправить пользователя обратно
    $_SESSION['message'] = 'Необходимо заполнить все поля!';
    header('Location: profile.php'); // Предположим, что это страница с формой добавления сеанса
    exit;
}

$movie_id = $_POST['movie_id'];
$day = $_POST['day'];
$time = $_POST['time'];
$hall_id = $_POST['hall_id'];

// Подготовка SQL запроса для вставки данных
$sql = "INSERT INTO sessions (movie_id, day, time, hall_id) VALUES (?, ?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movie_id, $day, $time, $hall_id]);

    // После успешного добавления сеанса, перенаправляем на страницу с информацией об этом
    $_SESSION['message'] = 'Сеанс успешно добавлен!';
    header('Location: profile.php'); // Предположим, что это страница административной панели
} catch (PDOException $e) {
    // Обработка ошибки при работе с базой данных
    $_SESSION['message'] = "Ошибка при добавлении сеанса: " . $e->getMessage();
    header('Location: profile.php'); // Перенаправление обратно на страницу добавления сеанса в случае ошибки
}
?>
