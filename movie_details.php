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

$movieId = $_GET['movie_id'];

// Получаем информацию о фильме
$stmt = $pdo->prepare('SELECT * FROM movies WHERE id = ?');
$stmt->execute([$movieId]);
$movie = $stmt->fetch();

// Проверяем, существует ли фильм
if ($movie) {
    echo '<div class="moviebig1">';
    echo '<div class="moviebig">';
    echo '<div class="moviebigbl">';
    echo '<div class="movieposterandtext">';
    echo '<div class="posterbig"><img src="' . $movie['poster'] . '" alt="' . $movie['title'] . ' Постер"></div>';
    echo '<div class="movietitle">';
    echo '<h2>' . $movie['title'] . '</h2>';
    echo '<div id="kp-rating" style="justify-content: center; line-height: 10px; display:flex;">Загрузка рейтинга...</div>';
    
    echo '<div class="movieinf">';
    echo '<div class="movieinf1">';
    echo '<p class="details">' . 'Жанр: ' .  '</p>';
    echo '<p class="details1">' . $movie['genre'] . '</p>';
    echo '</div>';
    echo '<div class="movieinf1">';
    echo '<p class="details">' . 'Режиссер: ' . '</p>';
    echo '<p class="details1">' .  $movie['avtor'] . '</p>';
    echo '</div>';
    echo '<div class="movieinf1">';
    echo '<p class="details">' . 'В ролях: ' .'</p>';
    echo '<p class="details1">' . $movie['inroles'] . '</p>';
    echo '</div>';
    echo '<div class="movieinf1">';
    echo '<p class="details">' . 'Страна: ' .  '</p>';
    echo '<p class="details1">' .  $movie['strana'] . '</p>';
    echo '</div>';
    echo '<div class="movieinf1">';
    echo '<p class="details">' . 'Продолжительность: ' . '</p>';
    echo '<p class="details1">' .  $movie['dlitel'] . '</p>';
    echo '</div>';
    echo '</div>';   
    echo '</div>';
    echo '</div>';
    echo '<p class="desc">' . $movie['full_desc'] . '</p>';
    if (!empty($movie['screenshot1'])) {
        echo '<div class="screen_cont">';
        echo '<img src="' . $movie['screenshot1'] . '" alt="' . $movie['title'] . ' Скриншот">';
        echo '<img src="' . $movie['screenshot2'] . '" alt="' . $movie['title'] . ' Скриншот">';
        echo '<img src="' . $movie['screenshot3'] . '" alt="' . $movie['title'] . ' Скриншот">';
        echo '</div>';
    }

    // Вставляем трейлер ниже описания, если URL видео существует
    if (!empty($movie['video_url'])) {
        echo '<div class="trailer">';
        echo '<p class="trailer_p">' . "Трейлер" . '</p>';
        echo '<iframe class="trailer_frame" src="' . $movie['video_url'] . '" width="90%" height="100%" allow="autoplay; encrypted-media; fullscreen; picture-in-picture;" frameborder="0" allowfullscreen></iframe>';
        echo '</div>';
    }

    echo '<div class="sessions">';
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $dayAfterTomorrow = date('Y-m-d', strtotime('+2 days'));
    
    // Получаем все сеансы фильма на сегодня, завтра и послезавтра
    $stmt = $pdo->prepare('SELECT * FROM sessions WHERE movie_id = ? ORDER BY day, time');
    $stmt->execute([$movieId]);

    $sessionCounter = 0; // Счетчик для ограничения количества выводимых сеансов до 5

    while ($session = $stmt->fetch()) {
        if ($sessionCounter >= 10) {
            break; // Прерываем цикл, если было выведено 10 сеансов
        }

        $sessionTime = strtotime($session['day'] . ' ' . $session['time']);
        $currentTime = time();
        
        if ($sessionTime > $currentTime) {
            $remainingTime = $sessionTime - $currentTime; // Время до сеанса в секундах
            $isDisabled = $remainingTime <= 20 * 60 ? 'disabled' : ''; // Отключить кнопку, если до сеанса осталось 20 минут или меньше

            echo '<a href="booking.php?session_id=' . $session['id'] . '" class="session1' . $isDisabled . '">';
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
    

            $sessionDate = date('Y-m-d', strtotime($session['day']));
            if ($sessionDate == $today) {
                $formattedDate = 'Сегодня';
            } elseif ($sessionDate == $tomorrow) {
                $formattedDate = 'Завтра';
            } else {
                $formattedDate = date('d.m', strtotime($session['day']));
            }
            
            $formattedTime = date('H:i', $sessionTime);
            
            echo '<p>' . $formattedDate . ', ' . $formattedTime . '</p>';
            echo '<p>'.'Зал'.' ' . $session['hall_id'] .' '. $type .'</p>';
            echo '<p>' . $price . ' руб.' . '</p>';
            echo '</a>';

            $sessionCounter++; // Увеличиваем счетчик после вывода каждого сеанса
        }
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<p>Фильм не найден.</p>';
}



?>
    <script>
        const apiKey = '4ddcc233-4f0d-49cd-8e64-8a58469c55b5';
        const filmTitle = '<?php echo $movie["title"]; ?>';
        const url = `https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=${encodeURIComponent(filmTitle)}`;

        fetch(url, {
          headers: {
            'X-API-KEY': apiKey
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.films && data.films.length > 0) {
              const rating = data.films[0].rating;
              document.getElementById('kp-rating').textContent = `Рейтинг на Кинопоиске: ${rating} ★`;
            } else {
              document.getElementById('kp-rating').textContent = 'Рейтинг не найден';
            }
          })
          .catch(error => console.error('Error:', error));
    </script>

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
    const sessions = document.querySelectorAll('.session1disabled');
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
