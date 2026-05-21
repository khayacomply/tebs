// ===== MAIN.JS - TEBS Website =====
// All code runs after DOM is fully loaded

document.addEventListener('DOMContentLoaded', () => {
  
  // ===== 1. DYNAMIC FOOTER YEAR =====
  const yearEl = document.getElementById('year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // ===== 2. WOW.JS INITIALIZATION (Scroll Animations) =====
  if (typeof WOW !== 'undefined') {
    new WOW({
      boxClass: 'wow',
      animateClass: 'animate__animated',
      offset: 50,
      mobile: true,
      live: true
    }).init();
  }

  // ===== 3. SWIPER INITIALIZATION (Testimonials) =====
  if (typeof Swiper !== 'undefined') {
    new Swiper('.testimonial-swiper', {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: { 
        delay: 5000, 
        disableOnInteraction: false 
      },
      pagination: { 
        el: '.swiper-pagination', 
        clickable: true 
      },
      breakpoints: {
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });
  }

  // ===== 4. LIGHTBOX CONFIGURATION (Image Gallery) =====
  if (typeof lightbox !== 'undefined') {
    lightbox.option({
      resizeDuration: 200,
      wrapAround: true,
      albumLabel: "Image %1 of %2",
      fadeDuration: 300,
      fitImagesInViewport: true
    });
  }

  // ===== 5. GLASSY NAVBAR SCROLL EFFECT =====
  const navbar = document.getElementById('mainNavbar');
  if (navbar) {
    // Check initial scroll position on load
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    }
    
    // Listen for scroll events
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }, { passive: true }); // passive: true improves scroll performance
  }

  // ===== 6. AUTO-HIGHLIGHT ACTIVE PAGE IN NAVBAR =====
  function setActiveNav() {
    // Get current page filename (e.g., "about.html")
    const path = window.location.pathname;
    let currentPage = path.split('/').pop() || 'index.html';
    
    // Handle root directory case (e.g., tebs.co.za/ loads index.html)
    if (currentPage === '' || currentPage === '/') {
      currentPage = 'index.html';
    }
    
    // Loop through all nav links and find the match
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
      const href = link.getAttribute('href');
      if (!href) return;
      
      // Clean href: remove hashes and query params for comparison
      const linkPage = href.split('#')[0].split('?')[0];
      
      // Check for exact match and toggle active class
      if (linkPage === currentPage) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
  }
  
  // Run active nav check immediately
  setActiveNav();
  
}); // End of DOMContentLoaded