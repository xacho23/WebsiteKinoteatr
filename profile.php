<!DOCTYPE html>
<html lang="ru">
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>   
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Бронирование билетов в кинотеатр</title>
<link rel="stylesheet" href="style.css">
<style>
    /* Стили для модального окна */
.modal333 {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.7); 
    animation: fadeIn 0.5s; 
}

.modal333.show {
    display: block;
    animation: fadeIn 0.5s; 
}

.modal-content333 {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
    max-width: 256px;
    text-align: center;
    border-radius: 10px;
    animation: slideIn 0.5s; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.close333 {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close333:hover,
.close333:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.qrcode-container333 {
    margin-top: 15px;
}

/* Анимации */
@keyframes fadeIn3 {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn3 {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.container {
    margin:50px;
    border: 2px solid rgba(255,0,0,0.5);
    background-color: rgba(0,0,0,0.4);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

#reader {
    margin-top: 20px;
}

input {
    margin-top: 20px;
    padding: 10px;
    width: 80%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}


    </style>
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
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
if (isset($_SESSION['message'])) {
  echo '<div class="alert">' . $_SESSION['message'] . '</div>';
  unset($_SESSION['message']); // Удаляем сообщение после его отображения
}

$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

// Инициализация подключения к БД
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

$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$userRole = $stmt->fetchColumn();

if ($userRole === 'meneger') {
    echo ' <div class="container">';
    echo '     <h1 style="color: white">Панель Билетера</h1>';
        echo '<button style="qr-button1" id="start-camera">Включить/Выключить камеру</button>';
        echo '    <div id="reader" style="max-width: 600px;"></div>';
        echo '    <input type="text" id="booking-code" placeholder="Код бронирования" readonly>';
        echo '    <p style="color: white; font-size: 16px" id="status"></p>';
echo'
        <div style="color: white" id="hall-number"></div>
        <div style="color: white" id="hall-type"></div>
        <div style="color: white" id="seat-number"></div>
        <div style="color: white" id="user-email"></div>
        <div style="color: white" id="showtime"></div>
        <div style="color: white" id="movie-title"></div>
        <div style="color: white" id="status-buttons"></div>';
        
        echo ' </div>';



}
else{


// Если пользователь является администратором
if ($userRole === 'admin') {
    echo '<div style="display:flex; flex-direction: column; flex-wrap: wrap; margin-bottom: 300px; align-items: center; line-height: 3em; color:white;" class="admin-panel">';
    echo '<h2 style="color: white; text-align: center; font-family:slider; margin-top: 60px;">Административная панель</h2>';
    echo '<div style="display:flex; flex-wrap: wrap; justify-content: center; flex-direction: row; width:90%">';

    // Форма для добавления фильма
    echo '<div style="margin: 20px; padding: 20px; background-color: rgba(30,30,30,0.5); border-radius: 20px;" class="adminblocks">';
    echo '<h3 style="color: white; text-align: center; font-family:slider;">Добавить новый фильм</h3>';
    echo '<form action="add_movie.php" method="post" enctype="multipart/form-data">';
    echo '<label style="color:white;" for="poster">Постер:</label><br>';
    echo '<input type="file" id="poster" name="poster" required><br>';
    echo '<label style="color:white;" for="title">Название:</label><br>';
    echo '<input type="text" id="title" name="title" required><br>';
    echo '<label style="color:white;" for="description">Описание:</label><br>';
    echo '<textarea id="description" name="description" required></textarea><br>';
    echo '<label style="color:white;" for="genre">Жанр:</label><br>';
    echo '<input type="text" id="genre" name="genre" required><br>';
    echo '<label style="color:white;" for="full_desc">Полное описание:</label><br>';
    echo '<textarea id="full_desc" name="full_desc" required></textarea><br>';

    echo '<label style="color:white;" for="avtor">Режиссер:</label><br>';
    echo '<textarea id="avtor" name="avtor" required></textarea><br>';
    echo '<label style="color:white;" for="inroles">В ролях:</label><br>';
    echo '<textarea id="inroles" name="inroles" required></textarea><br>';
    echo '<label style="color:white;" for="strana">Страна:</label><br>';
    echo '<textarea id="strana" name="strana" required></textarea><br>';
    echo '<label style="color:white;" for="dlitel">Длительность:</label><br>';
    echo '<textarea id="dlitel" name="dlitel" required></textarea><br>';
    echo '<label style="color:white;" for="video_url">Ссылка на трейлер:</label><br>';
    echo '<textarea id="video_url" name="video_url" required></textarea><br>';
    echo '<label style="color:white;" for="screenshot1">Скриншот 1:</label><br>';
    echo '<input type="file" id="screenshot1" name="screenshot1" required><br>';
    echo '<label style="color:white;" for="screenshot2">Скриншот 2:</label><br>';
    echo '<input type="file" id="screenshot2" name="screenshot2" required><br>';
    echo '<label style="color:white;" for="screenshot3">Скриншот 3:</label><br>';
    echo '<input type="file" id="screenshot3" name="screenshot3" required><br>';
    echo '<input type="submit" value="Добавить фильм">';
    echo '</form>';
    echo '</div>';

    // Форма для добавления сеанса
    echo '<div style="margin: 20px; padding: 20px; background-color: rgba(30,30,30,0.5); border-radius: 20px;" class="adminblocks">';
    echo '<h3 style="color: white; text-align: center; font-family:slider;">Добавить новый сеанс</h3>';
    echo '<form action="add_session.php" method="post">';
    echo '<label style="color:white;" for="movie_id">Фильм:</label><br>';
    echo '<select id="movie_id" name="movie_id" required>';
    $stmt = $pdo->query('SELECT id, title FROM movies');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$row['id']}'>{$row['title']}</option>";
    }
    echo '</select><br>';
    echo '<label style="color:white;" for="day">Дата:</label><br>';
    echo '<input type="date" id="day" name="day" required><br>';
    echo '<label style="color:white;" for="time">Время:</label><br>';
    echo '<input type="time" id="time" name="time" required><br>';
    echo '<label style="color:white;" for="hall_id">Зал:</label><br>';

    $sql = "SELECT hall_id FROM halls";
    $stmt = $pdo->query($sql);
    
    
    // Начало вывода HTML
    echo '<select id="hall_id" name="hall_id" required>';
    
    if ($stmt->rowCount() > 0) {
        // Вывод данных каждой строки
        while ($row = $stmt->fetch()) {
            echo '<option value="' . $row["hall_id"] . '">' . $row["hall_id"] . '</option>';
        }
    } else {
        echo '<option value="">Нет доступных данных</option>';
    }
    echo '</select><br>';
    echo '<input type="submit" value="Добавить сеанс">';
    echo '</form>';
    echo '</div>';

    // Форма для удаления фильма
    echo '<div style=" margin: 20px; padding: 20px; background-color: rgba(30,30,30,0.5); border-radius: 20px;" class="adminblocks">';
    echo '<h3 style="color: white; text-align: center; font-family:slider;">Удалить фильм</h3>';
    echo '<form action="delete_movie.php" method="post">';
    echo '<select id="delete_id" name="delete_id" required>';
    $stmt = $pdo->query('SELECT id, title FROM movies');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$row['id']}'>{$row['title']}</option>";
    }
    echo '</select><br>';
    echo '<input type="submit" value="Удалить фильм">';
    echo '</form>';
    echo '</div>';

    // Форма для удаления сеанса
    echo '<div style="margin: 20px; padding: 20px; background-color: rgba(30,30,30,0.5); border-radius: 20px;" class="adminblocks">';
    echo '<h3 style="color: white; text-align: center; font-family:slider;">Удалить сеанс</h3>';
    echo '<form action="delete_session.php" method="post">';
    echo '<select id="delete_session_id" name="delete_session_id" required>';
    $sessions = $pdo->query('SELECT sessions.id, movies.title, sessions.day, sessions.time FROM sessions JOIN movies ON sessions.movie_id = movies.id')->fetchAll();
    foreach ($sessions as $session) {
        echo "<option value='{$session['id']}'>{$session['title']} - {$session['day']} {$session['time']}</option>";
    }
    echo '</select><br>';
    echo '<input type="submit" value="Удалить сеанс">';
    echo '</form>';
    echo '</div>';

    echo '</div>';
} else {
    $stmt = $pdo->prepare('SELECT bonus FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $userBonus = $stmt->fetchColumn();
    echo '<div style="display:flex; justify-content: center; margin-top: 50px">';
    echo "<div class='profilebox'>
    <div class='emaill'><strong>{$email}</strong></div>
    <div style='color: white; font-family: slider; margin-top: 50px; text-align: center; font-size:30px;'>У вас <strong>{$userBonus}</strong> бонусов. </div>
    </div>";    
    echo '</div>'; 
    // Получаем бронирования пользователя
    $stmt = $pdo->prepare('SELECT bookings.*, sessions.day, sessions.id AS s_id, sessions.time, sessions.hall_id, seats.roww, seats.number, bookings.random_code, movies.title FROM bookings JOIN sessions ON bookings.session_id = sessions.id JOIN movies ON sessions.movie_id = movies.id JOIN seats ON bookings.seat_id = seats.id WHERE bookings.user_id = ?');
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();

    function getHallDetails($hallId) {
        if ($hallId >= 1 && $hallId <= 4) {
            return ['type' => 'Стандарт', 'price' => 290];
        } elseif ($hallId >= 5 && $hallId <= 6) {
            return ['type' => 'VIP', 'price' => 590];
        } elseif ($hallId >= 7 && $hallId <= 8) {
            return ['type' => '4DX', 'price' => 490];
        } else {
            return ['type' => 'Неизвестно', 'price' => 0]; // На случай, если hall_id не в диапазоне 1-8
        }
    }

    if (empty($bookings)) {
        echo '<div style="display:flex; justify-content: center; margin-top: 20px; margin-bottom: 20px">';
        echo '<div class="profilebox">';
        echo '<h2 style="font-family:slider; color: white; margin-top: 50px; text-align: center;">Ваши билеты</h2>';
        echo '<div style="color: white; font-family: slider; margin-top: 20px; text-align: center; font-size:20px; ">У вас пока нет забронированных билетов.</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<style>
            .booking-card {
                border: 2px solid rgba(255,0,0,0.6);
                margin: 10px 0;
                padding: 20px;
                border-radius: 20px;
                background-color: rgba(30,30,30,0.5);
                box-shadow: 0 4px 4px rgba(0,0,0,0.9);
                max-width:600px;
                color:white;
                margin-bottom:20px;
                line-height:1.5em;
                margin-left: 20px;
                margin-right: 20px;
                width: 80%
            }
            .cancel-button {
                display: flex;
                justify-content: center;
                background-color: #333;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 10px;
                cursor: pointer;
                margin-top: 10px;
                width:100%;
                font-size:16px;
            }
            .qr-button {
                background-color: #333;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 10px;
                cursor: pointer;
                margin-top: 10px;
                width:100%;
                font-size:16px;
            }
        </style>';
        echo '<h2 style="font-family:slider; color: white; margin-top: 50px; text-align:center;">Ваши билеты</h2>';
        echo '<div style="display:flex; justify-content: center">';
        echo '<div class="profilebox" style="margin-bottom: 30px">';
        echo "<label style='color:white; font-family:'slider';'><input type='checkbox' id='useBonusesCheckbox'> Использовать бонусы</label>";
        $hasReservations = false;
        foreach ($bookings as $booking) {
            if ($booking['status'] == 'Бронь') {
                $hasReservations = true;
                break;
            }
        }
        if ($hasReservations) {
            echo "<div style='text-align: center; margin-top: 20px; width:86%'>
                    <form action='yookassa_payment3.php' method='post'>
                    <input type='hidden' name='email' value='{$email}'>
                    <input type='hidden' id='useBonusesHiddenInput' name='useBonuses'>
                        <button type='submit' class='qr-button1' style='margin-bottom: 20px; border: 2px solid green;'>Выкупить все забронированные билеты</button>
                    </form>
                  </div>";
        }
        foreach ($bookings as $booking) {
            $hallDetails = getHallDetails($booking['hall_id']);
            
            // Преобразование даты и времени
            $dayFormatted = DateTime::createFromFormat('Y-m-d', $booking['day'])->format('d.m.Y');
            $timeFormatted = DateTime::createFromFormat('H:i:s', $booking['time'])->format('H:i');
            
            // Получаем время бронирования и добавляем один час для таймера
            $bookingTime = new DateTime($booking['booking_time']);
            $expiryTime = $bookingTime->modify('+1 hour')->format('Y-m-d H:i:s');
        
            echo "<div class='booking-card'>
                <strong>Фильм:</strong>  {$booking['title']} <br> 
                <strong>Сеанс:</strong> {$dayFormatted} {$timeFormatted}<br> 
                <strong>Зал:</strong> {$booking['hall_id']} ({$hallDetails['type']})<br> 
                <strong>Ряд:</strong> {$booking['roww']}<br>
                <strong>Место:</strong> {$booking['number']}<br>
                <strong>Статус:</strong> {$booking['status']}<br>";
                
            
                if ($booking['status'] == 'Бронь') {
                    $sessionId = $booking['s_id']; // Предполагаем, что session_id извлекается из $booking
                    $seatIds = $booking['seat_id']; // Предполагаем, что seat_ids тоже извлекаются и являются массивом
                    $totalPrice = $hallDetails['price']; // Используем total_price, предполагая, что это общая стоимость, возможно, нужно рассчитать
                
                    echo "<strong>Цена:</strong> $totalPrice руб.<br>";
                    echo "<div id='timer-{$booking['id']}' class='timer' data-expiry='{$expiryTime}'></div>
                        <div style='display:flex; flex-direction: row; flex-wrap: wrap; justify-content: space-around;'>
                            <form action='cancel_booking.php' method='post' style='width:45%;'>
                                <input type='hidden' name='booking_id' value='{$booking['id']}'>
                                <button type='submit' class='cancel-button'>Отменить бронь</button>
                            </form>
                            <form action='yookassa_payment2.php' method='post' style='width:45%;'>
                                <input type='hidden' name='session_id' value='{$sessionId}'>
                                <input type='hidden' name='seat_ids' value='{$seatIds}'>
                                <input type='hidden' name='price' value='{$totalPrice}'>
                                <input type='hidden' id='useBonusesHiddenInput' name='useBonuses'>
                                <button type='submit' class='qr-button11'>Купить</button>
                            </form>
                        </div>";
                
                } elseif ($booking['status'] == 'Куплено') {
                    echo "<br>
                        <div style='display:flex; flex-direction: row; flex-wrap: wrap; justify-content: space-around; width: 100%'>
                            <button class='qr-button' onclick='showQrCode(\"{$booking['random_code']}\")'>Показать QR</button>
                        </div>";
                }
            echo "</div>";
        }
        
        echo '<h2 style="line-height: 40px;font-family: slider; color: white; margin-top: 50px; text-align:center; margin-left: 40px; margin-right:40px;">Покажите QR-код билетёру у входа в зал.</h2>';
        echo '</div>';
        echo '</div>';
    }
}

}

    $_SESSION['hall_id'] = $_POST['hall_id'];

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
    const useBonusesCheckbox = document.getElementById('useBonusesCheckbox');
    const useBonusesHiddenInput = document.getElementById('useBonusesHiddenInput');

    useBonusesCheckbox.addEventListener('change', function() {
        useBonusesHiddenInput.value = this.checked ? '1' : '0';
        console.log('useBonusesCheckbox checked:', this.checked);
        console.log('useBonusesHiddenInput value:', useBonusesHiddenInput.value);
    });
});
</script>   

<script>
document.addEventListener('DOMContentLoaded', function() {
    const timers = document.querySelectorAll('.timer');

    timers.forEach(function(timer) {
        const expiryTime = new Date(timer.getAttribute('data-expiry')).getTime();

        function updateTimer() {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                timer.innerHTML = "Время бронирования истекло";
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timer.innerHTML = `<strong>Осталось времени:</strong> ${hours}ч ${minutes}м ${seconds}с`;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
});

</script>

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
});
</script>
<div id="qrModal" class="modal333">
    <div class="modal-content333">
        <span class="close333" onclick="closeQrModal()">&times;</span>
        <div id="qrcode" class="qrcode-container333"></div>
    </div>
</div>
<script src="script1.js"></script> 
<script>
function showQrCode(code) {
    var modal333 = document.getElementById("qrModal");
    var qrContainer333 = document.getElementById("qrcode");
    qrContainer333.innerHTML = "";
    var qrcode = new QRCode(qrContainer333, {
        text: code,
        width: 256,
        height: 256
    });
    modal333.style.display = "block";
    modal333.classList.add("show"); // добавляем класс для анимации
}

function closeQrModal() {
    var modal333 = document.getElementById("qrModal");
    modal333.classList.remove("show"); // убираем класс для анимации
    setTimeout(function() {
        modal333.style.display = "none";
    }, 500); // задержка для завершения анимации
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    var modal333 = document.getElementById("qrModal");
    if (event.target == modal333) {
        modal333.classList.remove("show");
        setTimeout(function() {
            modal333.style.display = "none";
        }, 500);
    }
}
</script>
</body>
</html>
