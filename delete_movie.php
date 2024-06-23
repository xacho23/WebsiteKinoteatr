<?php
session_start();

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



if (isset($_POST['delete_id'])) {
    $movie_id = $_POST['delete_id'];

    // Подготовленное выражение для безопасного удаления фильма
    $stmt = $pdo->prepare('DELETE FROM movies WHERE id = :id');
    $stmt->execute(['id' => $movie_id]);

    if ($stmt->rowCount()) {
        echo "Фильм успешно удален.";
    } else {
        echo "Ошибка при удалении фильма.";
    }
} else {
    echo "Не указан идентификатор фильма.";
}

// Перенаправление обратно на административную страницу
header('Location: profile.php');
exit();
?>
