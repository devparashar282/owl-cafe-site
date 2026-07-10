
// Preloader
window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    setTimeout(() => {
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
    }, 1500); // Wait for owl animation to finish before fading out
});

// Navbar Scroll Effect
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
    
    if (window.scrollY > 300) {
        scrollTopBtn.classList.add('active');
    } else {
        scrollTopBtn.classList.remove('active');
    }
});

// Scroll to Top
document.getElementById('scrollTopBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Initialize AOS Animation
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});

// Initialize Swiper for Reviews (if element exists)
if (document.querySelector('.reviews-slider')) {
    new Swiper('.reviews-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });
}

// Animated Counters Observer
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter-number');
    if (counters.length > 0) {
        const observerOptions = {
            threshold: 0.5
        };
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalValue = parseInt(target.getAttribute('data-target'));
                    const duration = 2000; // ms
                    const startValue = 0;
                    const increment = finalValue / (duration / 16);
                    let currentValue = startValue;

                    const updateCounter = () => {
                        currentValue += increment;
                        if (currentValue < finalValue) {
                            target.innerText = Math.ceil(currentValue);
                            requestAnimationFrame(updateCounter);
                        } else {
                            target.innerText = finalValue + (target.getAttribute('data-suffix') || '');
                        }
                    };
                    updateCounter();
                    observer.unobserve(target);
                }
            });
        }, observerOptions);

        counters.forEach(counter => {
            observer.observe(counter);
        });
    }

    // Particle/Bean Generator for Hero Section
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        for (let i = 0; i < 15; i++) {
            let bean = document.createElement('div');
            bean.classList.add('floating-bean');
            bean.style.left = Math.floor(Math.random() * 100) + '%';
            bean.style.animationDuration = (Math.random() * 10 + 10) + 's';
            bean.style.animationDelay = (Math.random() * 5) + 's';
            heroSection.appendChild(bean);
        }
    }
});
