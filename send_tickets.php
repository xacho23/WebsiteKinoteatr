<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $sessionId = $_POST['session_id'];
    $seatIds = $_POST['seat_ids'];

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

        // Содержание письма
        $mail->isHTML(true);
        $mail->Subject = 'Ваши билеты на сеанс';
        $mail->Body = "<p>Спасибо за ваш выбор! Вот ваши билеты на сеанс:</p><p>Сеанс ID: $sessionId</p><p>Места: $seatIds</p>";

        $mail->send();
        echo 'Билеты успешно отправлены на ваш email.';
    } catch (Exception $e) {
        echo "Ошибка при отправке билетов: {$mail->ErrorInfo}";
    }
}
?>
