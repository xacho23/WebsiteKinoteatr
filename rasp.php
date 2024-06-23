<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Бронирование билетов в кинотеатр</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="background-color: rgba(20,20,20,0.95)">

<?php session_start(); ?>
<header style="margin-bottom: -20px;">
<div class="logo"><a href="index.php"><img src="aaa.png" alt="logo"></a></div>
    <button class="hamburger" aria-label="Открыть меню">
    <span></span>
    <span></span>
    <span></span>
    </button>
    <div class="overlay"></div>
    <nav class="navigation">
        <a href="index.php" class="nav-link">Главная</a>
        <a href="rasp.php" class="nav-link">Расписание</a>
        <a href="about.php" class="nav-link">О нас</a>
        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
            <a href="profile.php" class="nav-link special-link">Профиль</a>
            <a href="logout.php" id="regis" class="nav-link special-link">Выйти</a>
        <?php else: ?>
            <a href="login.php" id="vhod" class="nav-link special-link">Вход</a>
            <a href="signup.php" id="regis" class="nav-link special-link">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>

<h2 style="margin-top:50px; color:white; font-family:'slider'; font-size:30px; text-align: center">Расписание сеансов</h2>
<?php
// Ваши настройки подключения к базе данных
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

$stmt = $pdo->query('SELECT * FROM movies');
while ($movie = $stmt->fetch())
{
echo '<div class="moviemain">';
echo '<div class="movie">';
echo '<div class="moviebl">';
echo '<div class="moviemain2">';
echo '<div class="movieposter"><a><img src="' . $movie['poster'] . '" alt="' . $movie['title'] . ' Poster"></a></div>';
echo '<div class="movietext">';
echo '<h2>' . $movie['title'] . '</h2>';
echo '<p>' . $movie['genre'] . '</p>';
echo '<p>' . $movie['description'] . '</p>';
echo '<a href="movie_details.php?movie_id=' . $movie['id'] . '" style="border: 2px solid red; border-radius:20px; width: 90px; padding:10px; text-decoration: none; color: white; text-align: center">Подробнее</a>'; // Добавлена кнопка "Подробнее"
echo '</div>';
echo '</div>';
echo '<div class="sessions">';

$sessions = $pdo->query('SELECT * FROM sessions WHERE movie_id = ' . $movie['id'] . ' ORDER BY day ASC, time ASC');

$sessionCount = 0; // Счетчик для ограничения вывода блоков сессии
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

// Далее, в цикле, где выводится информация о сеансе
while ($session = $sessions->fetch() and $sessionCount < 6) // Ограничение до 6 сессий
{
    // Преобразование времени сессии для сравнения
    $sessionTime = strtotime($session['day'] . ' ' . $session['time']);
    $currentTime = time();

        // Определение типа и цены билета
        $hallStmt = $pdo->prepare('SELECT * FROM halls WHERE hall_id = ?');
        $hallStmt->execute([$session['hall_id']]);
        $hall = $hallStmt->fetch();

        if ($hall) {
            $type = $hall['type'];
            $price = $hall['price'];
        } else {
            $type = 'Неизвестно';
            $price = 0;
        }

        echo '<a href="booking.php?session_id=' . $session['id'] . '" class="session ' . $isDisabled . '">';
        
        // Здесь изменяем способ вывода даты
        $sessionDate = date('Y-m-d', strtotime($session['day']));
        if ($sessionDate == $today) {
            $formattedDate = 'Сегодня';
        } elseif ($sessionDate == $tomorrow) {
            $formattedDate = 'Завтра';
        } else {
            $formattedDate = date('d.m', strtotime($session['day']));
        }
        
        // Форматирование времени сессии в чч.мм
        $formattedTime = date('H:i', $sessionTime);
        
        echo '<p>' . $formattedDate . ', ' . $formattedTime . '</p>'; // Обновлено для вывода отформатированного времени
        echo '<p>' . $price . ' руб.' . '</p>'; // Добавлено отображение цены билета
        echo '</a>'; 
        $sessionCount++; // Увеличение счетчика после успешного вывода сессии
    }


echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
}
?>

<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-about">
      <h3>Кинохаус</h3>
      <p>Кинохаус — там, где каждый фильм становится частью вашей жизни, где встречаются мечты и реальность, где начинается ваша собственная история великолепия кинематографа.</p>
    </div>
    <div class="footer-social">
      <h3 style="text-align:center; margin-left:-5px; color:white">Соцсети</h3>
      <a href="#"><img style="width:60px; height:40px; text-align:center" src="vk.png"></a>
      <a href="#"><img style="width:70px; height:40px; text-align:center" src="yt.png"></a>
      <a href="#"><img style="width:65px; height:40px; text-align:center" src="whats.png"></a>
    </div>
    <div class="footer-contact">
      <h3 class="footertext">Контактная информация</h3>
      <p class="footertext">Телефон: +7 (989) 521-30-69</p>
      <p class="footertext">Email: kinohouse@mail.ru</p>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('.navigation');
    const overlay = document.querySelector('.overlay');

    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        nav.classList.toggle('active');
        overlay.classList.toggle('active'); // Тоггл для затемнения
    });

    overlay.addEventListener('click', function() {
        this.classList.remove('active');
        nav.classList.remove('active');
        hamburger.classList.remove('active');
    });
    
    // Обработчик для неактивных сеансов
    const sessions = document.querySelectorAll('.session.disabled');
    sessions.forEach(session => {
        session.addEventListener('click', function(event) {
            event.preventDefault(); // Отменяет переход по ссылке
            alert('Сеанс недоступен для бронирования, так как до его начала осталось менее 20 минут.');
        });
    });
});
</script>
<script src="script.js"></script>
</body>
</html>
