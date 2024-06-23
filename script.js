document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('mouseover', () => {
            link.style.backgroundColor = 'rgba(255, 0, 0, 0.0)';

			
        });

        link.addEventListener('mouseout', () => {
            link.style.backgroundColor = 'transparent';
        });
    });
});

let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("slide");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
    slides[i].classList.remove("slide-fade");
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  slides[slideIndex-1].style.display = "flex";
  slides[slideIndex-1].classList.add("slide-fade");
  setTimeout(showSlides, 7000); // Изменяйте изображения каждые 5 секунд
}




const modal = document.getElementById("modal");
const btn = document.getElementById("openModal");
const span = document.getElementById("closeModal");

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}



const modal2 = document.getElementById("modal2");
const btn2 = document.getElementById("openModal2");
const span2 = document.getElementById("closeModal2");

btn2.onclick = function() {
  modal2.style.display = "block";
}

span2.onclick = function() {
  modal2.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}


const modal3 = document.getElementById("modal3");
const btn3 = document.getElementById("openModal3");
const span3 = document.getElementById("closeModal3");

btn3.onclick = function() {
  modal3.style.display = "block";
}

span3.onclick = function() {
  modal3.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal3) {
    modal3.style.display = "none";
  }
}



// script.js
