
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






<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Введите вашу электронную почту</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(String(email).toLowerCase());
        }

        function validateForm() {
            var email = document.forms["emailForm"]["email"].value;
            if (!validateEmail(email)) {
                alert("Пожалуйста, введите корректный адрес электронной почты.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body style="background-color: rgba(20,20,20,0.95)">
    <h2 style="color:white; font-family:'slider'; text-align:center; margin-top: 50px;">Введите вашу электронную почту для получения билетов</h2>
    <form name="emailForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()" style="text-align:center;">
        <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($_SESSION['session_Id']); ?>">
        <input type="hidden" name="seat_ids" value="<?php echo htmlspecialchars($_SESSION['seat_Ids']); ?>">
        <input type="email" name="email" placeholder="Введите вашу почту" required style="padding:10px; width:300px; margin-bottom:10px;">
        <button type="submit" style="padding:10px 20px;">Получить билеты</button>
    </form>
    
    <?php
    session_start();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email) {
            $_SESSION['emailconf'] = true;
            // Перенаправление или дальнейшая обработка, например, сохранение данных
            header("Location: reserve_seat.php");
            exit();
        } else {
            $_SESSION['emailconf'] = false;
            echo "<p style='color:red; text-align:center;'>Некорректный адрес электронной почты. Попробуйте еще раз.</p>";
        }
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
});


</script>
<script src="script.js"></script>
</body>
</html>

