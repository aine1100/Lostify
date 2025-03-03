window.addEventListener("scroll", isScrolled);
const navbar=document.querySelector(".navbar")
navbar.style.color="white"


function isScrolled() {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 0) {
        navbar.style.backgroundColor = "white";
        navbar.style.color = "#102b48"; 
        navbar.style.transition = "0.5s";
       
    } else {
        navbar.style.backgroundColor = "#102b48";
        navbar.style.color = "white";
        navbar.style.transition = "0.5s";
    }
}

function toggleMenu(){
    const menu=document.querySelector(".menu")
    menu.classList.toggle("hidden")
}