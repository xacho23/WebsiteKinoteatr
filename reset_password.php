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
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка формы
            $token = $_POST['token'];
            $new_password = $_POST['new_password'];
            $new_password = password_hash($new_password, PASSWORD_ARGON2I);

            $conn = new mysqli('localhost', 'root', '', 'kinoteatr');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Сохраняем новый пароль без шифрования
                $sql = "UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $new_password, $token);
                $stmt->execute();

                echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Ваш пароль был успешно обновлен.</p>';
            } else {
                echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Ссылка на восстановление пароля недействительна или истекла.</p>';
            }

            $conn->close();
        } else {
            // Отображение формы
            if (isset($_GET['token'])) {
                $token = htmlspecialchars($_GET['token']);
                ?>
                <p style="color: white; font-size: 24px; font-family:'slider'; text-align: center;">Придумайте новый пароль.</p>
                <form action="reset_password.php" method="POST" style="display: flex; flex-direction: column; align-items: center;">
                    <input type="hidden" name="token" value="<?php echo $token; ?>" required>
                    <input type="password" name="new_password" placeholder="Введите новый пароль" style="max-width: 200px; border: 2px solid red; background-color: #333; border-radius: 20px; padding: 10px; margin: 20px" required>
                    <button type="submit" style="max-width: 200px; border: 2px solid red; background-color: #333; border-radius: 20px; padding: 10px; margin: 20px; color: white; height: 45px;">Сменить пароль</button>
                </form>
                <?php
            } else {
                echo '<p style="color: white; font-size: 24px; font-family: \'slider\'; text-align: center;">Некорректная ссылка.</p>';
            }
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
