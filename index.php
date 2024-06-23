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


<div class="slider">
  <div class="slides">
    <div class="slide" style="background-image: url('slide1.jpg');">
	<div class="slide-container">
      <div class="slide-text">Погрузитесь в мир кино с непревзойденным комфортом и качеством звука и изображения.</div>
    </div>
	 </div>
    <div class="slide" style="background-image: url('slide2.jpg');">
	<div class="slide-container">
      <div class="slide-text">Наслаждайтесь эксклюзивными закусками и напитками в нашем баре.</div>
    </div>
	 </div>
    <div class="slide" style="background-image: url('slide3.jpg');">
	<div class="slide-container">
      <div class="slide-text">Переживайте каждый момент фильма как никогда раньше благодаря передовым технологиям и инновационным решениям в каждом зале.</div>
    </div>
	 </div>
  </div>
</div>
	
<div class="promo-block">
<div class="promo-blockbl">
  <div class="promo-text">
    <h2>Самый вкусный попкорн в кинобарах Кинохаус!</h2>
    <p>Попробуйте наши Комбо-наборы!</p>
    <p>Покупая комбо-наборы вы получаете попкорн + напиток на ваш выбор на 15% дешевле, чем вы бы приобрели их по отдельности! Скорее в кино!</p>
    <button id="openModal">Подробнее</button>
  </div>
  <div class="promo-image">
    <img src="popcorn.png" alt="Комбо МИX">
  </div>
 
  
  
</div>
</div>

  <div id="modal" class="modal">
  <div class="modal-content" style="font-family:'slider'">
    <span id="closeModal" class="close">&times;</span>
	<h2 style="margin-top: 25px">Кинобар</h2>
	<h3>Наслаждайтесь каждым моментом вашего кинопутешествия с нашим широким выбором закусок и напитков.</h3>
	<ul>
	<li>Попкорн — выбирайте между сладким, солёным или карамельным;<br><br></li>
	<li>Напитки — прохладные газированные, соки, а также ассортимент крафтовых напитков;<br><br></li>
	<li>Не пропустите наши комбо-предложения — выбирайте пакеты сочетаний напитков и закусок по выгодным ценам!<br></li>
	</ul>
	<img src="popcorn_modul.png" alt="Комбо МИX">
  </div>
 </div>
 
 
 
  <div id="modal2" class="modal">
  <div class="modal-content">
    <span id="closeModal2" class="close">&times;</span>
	<h2 style="margin-top: 25px">Откройте для себя новый уровень кинопросмотра!</h2>
	<h3>Мы гордимся тем, что предлагаем вам самые современные технологии, которые сделают ваш опыт просмотра фильмов незабываемым.</h3>
   	<ul>
	<li>Кристально чёткое изображение благодаря последним моделям проекторов с поддержкой 4K и 3D. Каждый кадр оживает перед вашими глазами с невероятной детализацией.<br><br></li>
	<li>Объёмный звук Dolby Atmos® — погрузитесь в атмосферу фильма с невероятно реалистичным звуком, который исходит со всех сторон, даже сверху!<br><br></li>
	</ul>
	<img style="margin-bottom:20px" src="4k_modul.png" alt="Комбо МИX">
  </div>
 </div>




 <div id="modal3" class="modal">
  <div class="modal-content">
    <span id="closeModal3" class="close">&times;</span>
	<h2 style="margin-top: 25px">Кинобар</h2>
	<h3>Наслаждайтесь каждым моментом вашего кинопутешествия с нашим широким выбором закусок и напитков.</h3>
    <p>Перед началом сеанса или в его перерыве почувствуйте полное погружение в мир кинематографа, побалуя себя изысканными вкусами нашего кинобара. От классического попкорна, приготовленного прямо перед вами, до эксклюзивных гурме-комбинаций — мы предлагаем идеальные спутники для любого фильма:</p>
	<ul>
	<li>Попкорн — выбирайте между сладким, солёным или карамельным;<br><br></li>
	<li>Напитки — прохладные газированные, соки, а также ассортимент крафтовых напитков;<br><br></li>
	<li>Закуски — разнообразные чипсы, орешки и сушёные фрукты для лёгкого перекуса;<br><br></li>
	<li>Не пропустите наши комбо-предложения — выбирайте пакеты сочетаний напитков и закусок по выгодным ценам!<br></li>
	</ul>
	<img src="popcorn_modul.png" alt="Комбо МИX">
  </div>
 </div> 
 
  <div class="fon2">
 <div class="promo-block2">
<div class="promo-blockbl">
  <div class="promo-image2">
    <img src="4k.png" alt="Комбо МИX">
  </div>

  <div class="promo-text2">
    <h2>Самое высококачественное изображение и звук!</h2>
	<p>Вы не пожелеете, что выбрали нас!</p>
    <p>Наши залы оснащены самым современным оборудованием для проекции, такие как 4K и 3D проекторы, а также системы звука Dolby Atmos для объемного звучания.</p>
    <button id="openModal2">Подробнее</button>
  </div>
</div>
 </div>
 </div>
 
 <div class="promo-block3">
<div class="promo-blockbl">
  <div class="promo-text">
    <h2>Наслаждайтесь просмотром в уникальных зрительских залах!</h2>
    <p>VIP-залы с повышенным комфортом: удобные кресла-качалки или диваны, индивидуальное обслуживание прямо в зале.</p>
    <p>А так же залы 4DX, где можно ощутить движение кресел, эффекты ветра, дождя и запахи, сопровождающие фильм!</p>
    <a href="halls.php"><button>Подробнее</button></a>
  </div>
  <div class="promo-image">
    <img src="vip.png" alt="Комбо МИX">
  </div>
</div>
 </div>
 
  <div class="fon3">
<div class="price-header">Цена билетов</div>
<div class="pricing-container">
<div class="price-block">
<div class="price">Стандарт</div>
<div class="price2">
<ul>
 <li>Удобные кресла с подстаканниками<br><br></li>
 <li>Большой экран высокого разрешения<br><br></li>
 <li>Система объемного звука, которая создает погружающий эффект<br></li>
</ul>
</div>
<div class="price3">от 290 ₽</div>
  </div>
  
  
  
  <div class="price-block">
  <div class="price" style="color: gold;"> VIP </div>
<div class="price2">
<ul>
 <li>Роскошные диваны с возможностью регулировки положения<br><br></li>
 <li>Меньшее количество мест для более уединенной атмосферы<br><br></li>
 <li>Персональное обслуживание на месте<br></li>
</ul>
</div>
<div class="price3">от 490 ₽</div>
  </div>
    
  <div class="price-block">
  <div class="price" style="margin-bottom:1px; margin-top:-5px">4DX</div>
<div class="price2" style="margin-top:1px; font-size:13px; margin-bottom:1px">
<ul>
 <li>Движущиеся кресла, синхронизированные с событиями на экране<br><br></li>
 <li>Передовые технологии для создания качественного изображения и звука<br><br></li>
 <li>Различные эффекты, такие как ветер, туман, пузыри, свет и запахи<br></li>
</ul>
</div>
<div class="price3">от 390 ₽</div>
  </div>
</div> 
 <div class="price_footer">
 <p>Вы можете забронировать желаемый вами билет на сайте, а позже оплатить его на кассе кинотеатра.</p>
<a href="rasp.php"><button class="glow-on-hover">К расписанию</button></a>
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
