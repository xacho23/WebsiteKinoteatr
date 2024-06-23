<!DOCTYPE html>
<html lang="ru">
<head>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Бронирование билетов в кинотеатр</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
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


    <div class="fon4">
    <div class="halls_ramka2">
      <div class="halls_ramkablabout">
    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A0760bfd9d742cffb0e70eaac418238ffe5ba6e8ad9ea914027ca7167b7c88388&amp;source=constructor" frameborder="0" class="aboutframe"></iframe>
    <p class="abouttext">Добро пожаловать в "Кинохаус" — ваш идеальный выбор для незабываемого кинопросмотра! Расположенный в сердце Ростова-на-Дону, наш кинотеатр предлагает уникальный сочетание комфорта, качества и атмосферы, который делает каждый ваш визит особенным.</p>
    <h3>Местоположение</h3>
    <p class="abouttext2">"Кинохаус" находится по адресу: Ростова-на-Дону, ул.Текучева, 126. Наше удобное местоположение делает нас доступными из любой точки города, а рядом расположенные парковки облегчают посещение для тех, кто приезжает на личном транспорте.</p>
    <h3>Контакты</h3>
    <p class="abouttext2">Телефон: +79895213069<br>Электронная почта: kinohouse@mail.ru</p>
    </div>
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
