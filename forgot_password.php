<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование билетов в кинотеатр</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #2c2c2c;">
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
        <a href="login.php" id="vhod" class="nav-link special-link">Вход</a>
        <a href="signup.php" id="regis" class="nav-link special-link">Регистрация</a>
    </nav>
</header>
<div style="background-color: #2c2c2c; display: flex; justify-content: center; margin: 40px;">
    <div style="display: flex; flex-direction: column; align-items: center; background-color: #242424; width: 50%; box-shadow: 1px 1px 16px rgba(0, 0, 0, 0.7); border: 1px solid rgba(255, 0, 0, 0.7); border-radius: 10px; padding: 20px;">
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'vendor/autoload.php';

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $conn = new mysqli('localhost', 'root', '', 'kinoteatr');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM users WHERE mail = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(50));
                $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
                $sql = "UPDATE users SET reset_token=?, reset_expires=? WHERE mail=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $token, $expires, $email);
                $stmt->execute();

                $reset_link = "http://localhost/reset_password.php?token=$token";

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'ssl://smtp.mail.ru';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kino.khaus@mail.ru';
                    $mail->Password = 'cczaxY9RLiXSVcn5tFeq';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 465;

                    $mail->setFrom('kino.khaus@mail.ru', 'Кинохаус');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Восстановление пароля';
                    $mail->Body = "Для восстановления пароля, пожалуйста, перейдите по следующей ссылке: <a href='$reset_link'>$reset_link</a>";

                    $mail->send();
                    echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Письмо с инструкциями по восстановлению пароля отправлено на ваш email.</p>';
                } catch (Exception $e) {
                    echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Письмо не может быть отправлено. Ошибка: ' . $mail->ErrorInfo . '</p>';
                }
            } else {
                echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Email не найден.</p>';
            }

            $conn->close();
        }
        ?>
    </div>
</div>
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
        overlay.classList.toggle('active');
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
