<?php
session_start(); // Начинаем сессию для работы с авторизацией

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
$loggedin = (isset($_SESSION['loggedin']) && $_SESSION['loggedin']);
// Проверяем, авторизован ли пользователь

$sessionId = $_GET['session_id']; // Получаем ID сеанса
$user_id = $_SESSION['user_id'];
// Получаем все места в зале и информацию о бронировании
$stmt = $pdo->prepare('SELECT seats.*, bookings.id AS booking_id FROM seats LEFT JOIN bookings ON seats.id = bookings.seat_id AND bookings.session_id = ? WHERE seats.hall_id = (SELECT hall_id FROM sessions WHERE id = ?)');
$stmt->execute([$sessionId, $sessionId]);
$seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Инициализируем переменную $seatsByRow
$seatsByRow = [];

if ($seats) {
    // Группируем места по рядам
    foreach ($seats as $seat) {
        $seatsByRow[$seat['roww']][] = $seat;
    }
    ksort($seatsByRow); // Сортируем по рядам
}

$hallIdQuery = $pdo->prepare('SELECT hall_id FROM sessions WHERE id = ? LIMIT 1');
$hallIdQuery->execute([$sessionId]);
$hallIdResult = $hallIdQuery->fetch();
$hallId = $hallIdResult['hall_id'];
$hallStmt = $pdo->prepare('SELECT * FROM halls WHERE hall_id = ?');
$hallStmt->execute([$hallId]);
$hall = $hallStmt->fetch();

if ($hall) {
    $type = $hall['type'];
    $price = $hall['price'];
} else {
    $type = 'Неизвестно';
    $price = 0;
}

$stmt = $pdo->prepare('SELECT bonus FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$userBonus = $stmt->fetchColumn();
$_SESSION['userBonus'] = $userBonus; 



$sessionTimeQuery = $pdo->prepare('SELECT time FROM sessions WHERE id = ? LIMIT 1');
$sessionTimeQuery1 = $pdo->prepare('SELECT day FROM sessions WHERE id = ? LIMIT 1');
$sessionTimeQuery1->execute([$sessionId]);
$sessionTimeQuery->execute([$sessionId]);
$sessionTimeResult = $sessionTimeQuery->fetch();
$sessiondayResult = $sessionTimeQuery1->fetch();
$sessionStartTime = strtotime($sessionTimeResult['time']);
$sessionDay = strtotime($sessiondayResult['day']);
$currentTimestamp = time();
$timeDifference = $sessionStartTime - $currentTimestamp;
$sessionDate = date('Y-m-d', $sessionDay);
$currentDate = date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Бронирование билетов в кинотеатр</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="background-color: rgba(20,20,20,0.95)">
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

<h2 style="color:white; font-family:'slider'; text-align:center; margin-top: 50px;">Выберите места для бронирования</h2>
<div class='zakaz'>
<div class='zakazbl'> 
<div id="hallScheme">
<h3 style="color:white; font-family:'slider';">Экран</h3>  
<div class="curved-underline"></div>
    <?php if ($seatsByRow): ?>
        <?php foreach ($seatsByRow as $row => $seatsInRow): ?>
            <div class="row">
                <div class="row-number">Ряд <?php echo htmlspecialchars($row); ?></div>
                <div class="seats">
                    <?php foreach ($seatsInRow as $seat): ?>
                        <div class="seat<?php echo $seat['booking_id'] ? ' booked' : ''; ?>" data-seat-id="<?php echo htmlspecialchars($seat['id']); ?>" <?php echo $seat['booking_id'] ? 'disabled' : ''; ?>>
                            <?php echo htmlspecialchars($seat['number']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color:white;">Нет доступных мест для этого сеанса.</p>
    <?php endif; ?>
    <form id="seatReservationForm" action="reserve_seat.php" method="POST">
    <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($sessionId); ?>">
    <input type="hidden" id="seat_ids" name="seat_ids" value="">
    <input type="hidden" id="email_hidden" name="email_hidden" value="">
    <input type="hidden" id="use_bonuses_hidden" name="use_bonuses_hidden" value="">
    <div id="proverka" style="<?php echo !($loggedin) ? 'display: block;' : 'display: none;'; ?>">
        <p style="color: white">Введите email на который будут высланы билеты или <a style="color: white" href="signup.php">зарегистрируйтесь</a></p>
        <input style="border-radius: 20px; height: 25px; width:98%; background-color: #333; border: 2px solid rgba(255,0,0,0.5); color: white;" id="email" name="email" value="">
    </div>
    <button type="submit" id="reserveButton" disabled>Бронировать выбранные места</button>
    <button type="button" id="buyButton" class="buyButton" disabled>Купить выбранные места</button>
    <p style="color:white; font-family:'slider';">Тип зала: <?php echo $type; ?>, Цена билета: <span id="ticketPrice"><?php echo $price; ?></span> руб.</p>
    <p style="color:white; font-family:'slider';">Общая стоимость: <span id="totalPrice">0</span> руб.</p>
    <div style="<?php echo ($loggedin) ? 'display: block;' : 'display: none;'; ?>">
    <p style="color:white; font-family:'slider';">Ваши бонусы: <span id="userBonuses"><?php echo $userBonus; ?></span></p>
    <label style="color:white; font-family:'slider';"><input type="checkbox" id="useBonuses"> Использовать бонусы</label>
    </div>
</form>
</div>
</div>
</div>
<script>
    const timeDifference = <?php echo $timeDifference; ?>;
    const sessionDate = '<?php echo $sessionDate; ?>';
    const currentDate = '<?php echo $currentDate; ?>';
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const seats = document.querySelectorAll('.seat:not(.booked)');
    const reserveButton = document.getElementById('reserveButton');
    const buyButton = document.getElementById('buyButton');
    const seatIdsInput = document.getElementById('seat_ids');
    const emailInput = document.getElementById('email');
    const emailHiddenInput = document.getElementById('email_hidden');
    const useBonusesHiddenInput = document.getElementById('use_bonuses_hidden');
    const ticketPrice = parseInt(document.getElementById('ticketPrice').textContent);
    const totalPriceElement = document.getElementById('totalPrice');
    const userBonuses = parseInt(document.getElementById('userBonuses').textContent);
    const useBonusesCheckbox = document.getElementById('useBonuses');
    const selectedSeats = [];

    if (timeDifference < 3600 && new Date(sessionDate) <= new Date(currentDate)) {
        reserveButton.style.display = 'none';
    }

    function updateTotalPrice() {
        let totalPrice = ticketPrice * selectedSeats.length;
        if (useBonusesCheckbox.checked) {
            const discount = Math.min(totalPrice, userBonuses);
            totalPrice -= discount;
        }
        totalPriceElement.textContent = totalPrice;
    }

    useBonusesCheckbox.addEventListener('change', function () {
        updateTotalPrice();
        updateSessionBonuses(this.checked);
        useBonusesHiddenInput.value = this.checked ? '1' : '0';
    });

    function updateSessionBonuses(useBonuses) {
        fetch('update_bonuses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `use_bonuses=${useBonuses}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Ошибка при обновлении бонусов');
            }
        })
        .catch(error => console.error('Ошибка:', error));
    }

    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return re.test(String(email).toLowerCase());
    }

    const loggedin = <?php echo json_encode($loggedin); ?>;

    function updateButtonState() {
        const isEmailValid = loggedin || validateEmail(emailInput.value);
        const areSeatsSelected = selectedSeats.length > 0;

        reserveButton.disabled = !(isEmailValid && areSeatsSelected);
        buyButton.disabled = !(isEmailValid && areSeatsSelected);
    }

    emailInput.addEventListener('input', updateButtonState);

    seats.forEach(seat => {
        seat.addEventListener('click', function () {
            const seatId = this.getAttribute('data-seat-id');
            const index = selectedSeats.indexOf(seatId);

            if (index === -1) {
                if (selectedSeats.length < 6) {
                    selectedSeats.push(seatId);
                    this.classList.add('selected');
                } else {
                    alert('Вы можете выбрать до 6 мест.');
                }
            } else {
                selectedSeats.splice(index, 1);
                this.classList.remove('selected');
            }

            seatIdsInput.value = selectedSeats.join(',');

            updateButtonState();
            updateTotalPrice();
        });
    });

    buyButton.addEventListener('click', function () {
        const sessionId = document.querySelector('input[name="session_id"]').value;
        const seatIds = seatIdsInput.value;
        const email = emailInput.value;
        let totalPrice = parseInt(totalPriceElement.textContent);

        // Сохраняем email в скрытое поле перед отправкой формы
        emailHiddenInput.value = email;

        // Отправляем данные на сервер для сохранения email в сессии
        fetch('save_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `email=${encodeURIComponent(email)}`,
        })
        .then(response => {
            if (response.ok) {
                // Переходим на форму оплаты ЮКассы после успешного сохранения email
                window.location.href = `yookassa_payment.php?session_id=${sessionId}&seat_ids=${seatIds}&price=${totalPrice}&use_bonuses=${useBonusesHiddenInput.value}`;
            } else {
                alert('Ошибка при сохранении email');
            }
        });
    });
});

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
});
</script>
<script src="script.js"></script>
</body>
</html>
