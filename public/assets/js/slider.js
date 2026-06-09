document.addEventListener("DOMContentLoaded", function() {
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide-item');
  const dots = document.querySelectorAll('.dot-indicator');
  const totalSlides = slides.length;
  let slideInterval;

  // Jika tidak ada elemen slider di halaman, hentikan eksekusi JS
  if (totalSlides === 0) return;

  function showSlide(index) {
    if (index >= totalSlides) currentSlide = 0;
    else if (index < 0) currentSlide = totalSlides - 1;
    else currentSlide = index;

    slides.forEach((slide, i) => {
      if (i === currentSlide) {
        slide.classList.remove('opacity-0', 'pointer-events-none');
        slide.classList.add('opacity-100');
      } else {
        slide.classList.remove('opacity-100');
        slide.classList.add('opacity-0', 'pointer-events-none');
      }
    });

    dots.forEach((dot, i) => {
      if (i === currentSlide) {
        dot.classList.remove('w-2', 'bg-white/50');
        dot.classList.add('w-8', 'bg-white');
      } else {
        dot.classList.remove('w-8', 'bg-white');
        dot.classList.add('w-2', 'bg-white/50');
      }
    });
  }

  // Ekspos fungsi ke global window agar bisa dipanggil oleh atribut onclick di HTML
  window.changeSlide = function(direction) {
    clearInterval(slideInterval);
    showSlide(currentSlide + direction);
    startAutoplay();
  };

  window.goToSlide = function(index) {
    clearInterval(slideInterval);
    showSlide(index);
    startAutoplay();
  };

  function startAutoplay() {
    slideInterval = setInterval(() => {
      showSlide(currentSlide + 1);
    }, 5000);
  }

  // Mulai slider pertama kali
  startAutoplay();
});