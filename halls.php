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
  <h2 style="text-align: center; font-family: 'slider'; color: white; font-size:48px;">Наши залы</h2>
<div class="halls_ramka">
  <div class="halls_ramkabl">
<h2>Залы Стандарт</h2>
<h3 style="font-size: 24px;">Ваш уютный кинозал для каждого дня</h3>	
  <ul>
	<li>Удобные сидения — комфортные кресла с оптимальным расстоянием между рядами для легкости перемещения и лучшего обзора.<br><br></li>
	<li>Отличное звучание и изображение — чистый и ясный звук, а также чёткое изображение на больших экранах для максимального погружения в фильм.<br><br></li>
	<li>Продуманное размещение — кинозалы спроектированы таким образом, чтобы каждое место было лучшим местом для просмотра.<br><br></li>
	<li>Идеальная чистота — наши залы поддерживаются в идеальном состоянии благодаря регулярной уборке и обслуживанию.<br></li>
	</ul>

  <div class="image-row">
    <img src="standart1.jpg" alt="Image 1" class="modal-image">
    <img src="standart2.jpg" alt="Image 2" class="modal-image">
    <img src="standart3.jpg" alt="Image 3" class="modal-image">
  </div>
  
  <p>
  Независимо от того, проводите ли вы вечер с друзьями, на свидании или наслаждаетесь семейным выходом, наши стандартные залы созданы для того, чтобы каждый сеанс оставлял приятные впечатления.
</p><p style="font-style: italic;">
Присоединяйтесь к нам для просмотра новинок и любимых классиков. В нашем кинотеатре кино — это больше, чем просто фильмы, это опыт, который мы с удовольствием делимся с вами!
</p>
  </div>
</div>
</div>


<div class="fon">
<div class="halls_ramka">
  <div class="halls_ramkabl">
<h2>Залы VIP</h2>
<h3 style="font-size: 24px;">Представляем вашему вниманию наши VIP-залы, где каждая деталь создана для обеспечения непревзойдённого комфорта и уединения!</h3>	
  <ul>
	<li>Эксклюзивные кожаные кресла-реклайнеры с возможностью регулировки положения для вашего идеального удобства.<br><br></li>
	<li>Персональное обслуживание — наша вежливая команда всегда рядом, чтобы удовлетворить любые ваши запросы.<br><br></li>
	<li>Премиум-меню с изысканными блюдами и напитками, сервированными прямо к вашему месту.<br><br></li>
	<li>Изолированная акустическая среда для непревзойдённого звукового погружения.<br></li>
	</ul>

  <div class="image-row">
    <img src="vip2.jpg" alt="Image 1" class="modal-image">
    <img src="vip1.jpg" alt="Image 2" class="modal-image">
    <img src="vip3.jpg" alt="Image 3" class="modal-image">
  </div>
  
<p>
VIP-залы предлагают непревзойдённый уровень комфорта и сервиса. В уединении наших VIP-залов каждый момент превращается в незабываемое событие.</p>
</p>
<p style="font-style: italic;">
  В наших VIP-залах вы не просто смотрите фильм — вы живёте им!
</p>
  </div>
</div>
</div>


<div class="fon4">
<div class="halls_ramka">
  <div class="halls_ramkabl">
<h2>Залы 4DX</h2>
<h3 style="font-size: 24px;">Искусство кинематографа достигло нового уровня благодаря нашим залам 4DX, где вы становитесь частью фильма</h3>	
  <ul>
	<li>Движение кресел синхронизировано с событиями на экране, дополненное эффектами ветра, тумана и света.<br><br></li>
	<li>Спецэффекты такие как пузыри, дождь и ароматы, делают каждый момент ещё более реалистичным.<br><br></li>
	<li>Окружающие звук и изображение обеспечивают максимальное погружение в сюжет и эмоции фильма.<br></li>
	</ul>

  <div class="image-row">
    <img src="4dx1.jpg" alt="Image 1" class="modal-image">
    <img src="4dx2.jpg" alt="Image 2" class="modal-image">
    <img src="4dx3.jpg" alt="Image 3" class="modal-image">
  </div>
  
  <p>
    Проводите ли вы динамичный вечер с друзьями, желаете ли вы впечатлить кого-то особенного, или ищете новый способ кинопросмотра для всей семьи, залы 4DX предлагают непревзойдённую интенсивность ощущений. Почувствуйте каждый эффект специального действия, будучи окруженными ветром, дождём и даже ароматами, происходящими на экране.
</p><p style="font-style: italic;">
  Мы приглашаем вас стать частью революции в мире кино с 4DX — где каждый сеанс — это приключение, которым мы с радостью делимся с вами!
</p>
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
