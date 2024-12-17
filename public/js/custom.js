// Fix for slideshow
let slideIndex = 0;
const showSlides = () => {
    const slides = document.getElementsByClassName("mySlides");
    if (!slides || slides.length === 0) return;
    
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    slides[slideIndex - 1].style.display = "block";
    setTimeout(showSlides, 2000);
};

// Initialize WOW.js if it exists
document.addEventListener('DOMContentLoaded', function() {
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }
}); 