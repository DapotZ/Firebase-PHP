document.addEventListener('DOMContentLoaded', function() {
    const prevSlide = document.getElementById('prevSlide');
    const nextSlide = document.getElementById('nextSlide');
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    // Menampilkan slide pertama saat halaman dimuat
    slides[currentSlide].classList.remove('hidden');
    
    // Fungsi untuk mengatur latar belakang ke gradient biru
    function setBlueGradientBackground() {
        document.body.classList.remove('bg-black-gradient');
        document.body.classList.add('bg-blue-gradient');
    }

    // Fungsi untuk mengatur latar belakang ke gradient hitam
    function setBlackGradientBackground() {
        document.body.classList.remove('bg-blue-gradient');
        document.body.classList.add('bg-black-gradient');
    }

    // Event listener untuk tombol panah kiri
    prevSlide.addEventListener('click', () => {
        slides[currentSlide].classList.add('hidden');
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        slides[currentSlide].classList.remove('hidden');

        if (currentSlide === 0) {
            setBlueGradientBackground();
        } else {
            setBlackGradientBackground();
        }
    });

    // Event listener untuk tombol panah kanan
    nextSlide.addEventListener('click', () => {
        slides[currentSlide].classList.add('hidden');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.remove('hidden');

        if (currentSlide === 0) {
            setBlueGradientBackground();
        } else {
            setBlackGradientBackground();
        }
    });
});


