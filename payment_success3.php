<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;

require 'vendor/autoload.php';

session_start();
$price = $_SESSION['price'];
$sessionId = $_GET['session_id'];
$seatIds = explode(',', $_GET['seat_ids']);
$email = $_SESSION['email'];
$bonus = $price / 10;
// Подключаемся к базе данных
$host = 'localhost';
$db = 'kinoteatr';
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

// Сохраняем информацию о покупке в базе данных
foreach ($seatIds as $seatId) {
    $stmt = $pdo->prepare('UPDATE bookings SET session_id = ?, user_id = ?, status = ?, email = ? WHERE seat_id = ?');
    $stmt->execute([$sessionId, $_SESSION['user_id'], 'Куплено', $email, $seatId]);
}

$useBonuses = $_SESSION['use_bonuses'] ?? false;

if ($useBonuses) {
    $stmt = $pdo->prepare('UPDATE users SET bonus = 0 WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}

function getBookingsForUser($email) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT bookings.*, sessions.day, sessions.time, sessions.hall_id, seats.roww, seats.number, bookings.random_code, movies.title 
    FROM bookings 
    JOIN sessions ON bookings.session_id = sessions.id 
    JOIN seats ON bookings.seat_id = seats.id 
    JOIN movies ON sessions.movie_id = movies.id 
    WHERE bookings.email = ? 
      AND bookings.booking_time >= NOW() - INTERVAL 2 MINUTE');

    $stmt->execute([$email]);
    return $stmt->fetchAll();
}

if (!isset($_SESSION['loggedin'])) {
        $bookings = getBookingsForUser($email);

        // Создание директории для QR-кодов, если она не существует
        if (!is_dir('qrcodes')) {
            mkdir('qrcodes', 0777, true);
        }

        $mail = new PHPMailer(true);

        try {
            // Настройки сервера
            $mail->isSMTP();
            $mail->Host = 'ssl://smtp.mail.ru'; // Укажите SMTP сервер
            $mail->SMTPAuth = true;
            $mail->Username = 'kino.khaus@mail.ru'; // Ваш email
            $mail->Password = 'cczaxY9RLiXSVcn5tFeq'; // Ваш пароль от email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 465;

            // Получатель
            $mail->setFrom('kino.khaus@mail.ru', 'Кинохаус');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            // Содержание письма
            $mail->isHTML(true);
         // Вывод отладки, можно использовать 3 для более детализированной информации

            $mail->Subject = 'Ваши билеты на сеанс';

            // Начало HTML-контента
            $bodyContent = "<div style='font-family: Arial, sans-serif;'>";
            
            foreach ($bookings as $booking) {
                $hallId = $booking['hall_id'];
                $type = '';
                $price = 0;

                if ($hallId >= 1 && $hallId <= 4) {
                    $type = 'Стандарт';
                    $price = 290;
                } elseif ($hallId == 5 || $hallId == 6) {
                    $type = 'VIP';
                    $price = 590;
                } elseif ($hallId == 7 || $hallId == 8) {
                    $type = '4DX';
                    $price = 490;
                }

                // Преобразование даты и времени
                $dayFormatted = DateTime::createFromFormat('Y-m-d', $booking['day'])->format('d.m.Y');
                $timeFormatted = DateTime::createFromFormat('H:i:s', $booking['time'])->format('H:i');

                $bodyContent .= "<h1 style='color: #4CAF50;'>{$booking['title']}</h1>";
                $bodyContent .= "<p style='font-size: 18px;'>{$dayFormatted} {$timeFormatted}</p>";
                $bodyContent .= "<p style='color: green'>Спасибо за покупку!.</p>";
                $bodyContent .= "<p>Кинотеатр «Кинохаус»</p>";
                $bodyContent .= "<p>г. Ростов-на-Дону, ул. Текучева, 105</p>";
                $bodyContent .= "<p>Код бронирования: <strong>{$booking['random_code']}</strong></p>";
                $bodyContent .= "<p>Зал: <strong>{$booking['hall_id']} ({$type})</strong></p>";
                $bodyContent .= "<p>Ряд: <strong>{$booking['roww']}</strong></p>";
                $bodyContent .= "<p>Место: <strong>{$booking['number']}</strong></p>";


                // Генерация QR-кода для кода бронирования
                $qrCode = new QrCode($booking['random_code']);
                $qrCode->setSize(200);
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
                
                $qrCodePath = 'qrcodes/' . $booking['random_code'] . '.png';
                $qrCode->writeFile($qrCodePath);

                // Встраивание изображения QR-кода
                $mail->addEmbeddedImage($qrCodePath, $booking['random_code']);
                $bodyContent .= "<img src='cid:{$booking['random_code']}' alt='QR Code'>";

                $bodyContent .= "<hr>";
            }

            $bodyContent .= "</div>";
            $mail->Body = $bodyContent;

            if ($mail->send()) {
                header("Location: succes_buy.php");
                exit();
            } else {
                echo 'Ошибка при отправке билетов: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Ошибка при отправке билетов: {$mail->ErrorInfo}";
        }

        // Удаление временно созданных файлов QR-кодов
        foreach ($bookings as $booking) {
            $qrCodePath = 'qrcodes/' . $booking['random_code'] . '.png';
            if (file_exists($qrCodePath)) {
                unlink($qrCodePath);
            }
        }

        exit();
    }
else {
        $bookings = getBookingsForUser($email);

        // Создание директории для QR-кодов, если она не существует
        if (!is_dir('qrcodes')) {
            mkdir('qrcodes', 0777, true);
        }

        $mail = new PHPMailer(true);
        if (!($useBonuses)) {
        $stmt = $pdo->prepare("UPDATE users SET bonus = bonus + :bonus WHERE id = :user_id");
        $stmt->execute([':bonus' => $bonus, ':user_id' => $_SESSION['user_id']]);
        }
        try {
            // Настройки сервера
            $mail->isSMTP();
            $mail->Host = 'ssl://smtp.mail.ru'; // Укажите SMTP сервер
            $mail->SMTPAuth = true;
            $mail->Username = 'kino.khaus@mail.ru'; // Ваш email
            $mail->Password = 'cczaxY9RLiXSVcn5tFeq'; // Ваш пароль от email
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 465;
         // Вывод отладки, можно использовать 3 для более детализированной информации

            // Получатель
            $mail->setFrom('kino.khaus@mail.ru', 'Кинохаус');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            // Содержание письма
            $mail->isHTML(true);
            $mail->Subject = 'Ваши билеты на сеанс';

            // Начало HTML-контента
            $bodyContent = "<div style='font-family: Arial, sans-serif;'>";
            
            foreach ($bookings as $booking) {
                $hallId = $booking['hall_id'];
                $type = '';
                $price = 0;

                if ($hallId >= 1 && $hallId <= 4) {
                    $type = 'Стандарт';
                    $price = 290;
                } elseif ($hallId == 5 || $hallId == 6) {
                    $type = 'VIP';
                    $price = 590;
                } elseif ($hallId == 7 || $hallId == 8) {
                    $type = '4DX';
                    $price = 490;
                }

                // Преобразование даты и времени
                $dayFormatted = DateTime::createFromFormat('Y-m-d', $booking['day'])->format('d.m.Y');
                $timeFormatted = DateTime::createFromFormat('H:i:s', $booking['time'])->format('H:i');

                $bodyContent .= "<h1 style='color: #4CAF50;'>{$booking['title']}</h1>";
                $bodyContent .= "<p style='font-size: 18px;'>{$dayFormatted} {$timeFormatted}</p>";
                $bodyContent .= "<p style='color: green'>Спасибо за покупку!</p>";
                $bodyContent .= "<p>Кинотеатр «Кинохаус»</p>";
                $bodyContent .= "<p>г. Ростов-на-Дону, ул. Текучева, 105</p>";
                $bodyContent .= "<p>Код бронирования: <strong>{$booking['random_code']}</strong></p>";
                $bodyContent .= "<p>Зал: <strong>{$booking['hall_id']} ({$type})</strong></p>";
                $bodyContent .= "<p>Ряд: <strong>{$booking['roww']}</strong></p>";
                $bodyContent .= "<p>Место: <strong>{$booking['number']}</strong></p>";


                // Генерация QR-кода для кода бронирования
                $qrCode = new QrCode($booking['random_code']);
                $qrCode->setSize(200);
                $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
                
                $qrCodePath = 'qrcodes/' . $booking['random_code'] . '.png';
                $qrCode->writeFile($qrCodePath);

                // Встраивание изображения QR-кода
                $mail->addEmbeddedImage($qrCodePath, $booking['random_code']);
                $bodyContent .= "<img src='cid:{$booking['random_code']}' alt='QR Code'>";

                $bodyContent .= "<hr>";
            }

            $bodyContent .= "</div>";
            $mail->Body = $bodyContent;

            if ($mail->send()) {
                header("Location: profile.php");
                exit();
            } else {
                echo 'Ошибка при отправке билетов: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Ошибка при отправке билетов: {$mail->ErrorInfo}";
        }

        // Удаление временно созданных файлов QR-кодов
        foreach ($bookings as $booking) {
            $qrCodePath = 'qrcodes/' . $booking['random_code'] . '.png';
            if (file_exists($qrCodePath)) {
                unlink($qrCodePath);
            }
        }

        exit();
    }
    header("Location: succes_buy.php");
?>
