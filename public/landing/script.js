// ===== LOADER =====
window.addEventListener('load', function() {
  setTimeout(function() {
    document.getElementById('loader').classList.add('hide');
  }, 1200);
});

// ===== NAVBAR SCROLL =====
var navbar = document.getElementById('navbar');
var backToTop = document.getElementById('backToTop');
window.addEventListener('scroll', function() {
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
  if (window.scrollY > 400) {
    backToTop.classList.add('visible');
  } else {
    backToTop.classList.remove('visible');
  }
  // Active nav link
  var sections = document.querySelectorAll('.section, .hero');
  var links = document.querySelectorAll('.nav-menu a');
  var current = '';
  sections.forEach(function(s) {
    if (window.scrollY >= s.offsetTop - 200) {
      current = s.getAttribute('id');
    }
  });
  links.forEach(function(l) {
    l.classList.remove('active');
    if (l.getAttribute('href') === '#' + current) {
      l.classList.add('active');
    }
  });
});

// ===== MOBILE MENU =====
var hamburger = document.getElementById('hamburger');
var mobileMenu = document.getElementById('mobileMenu');
hamburger.addEventListener('click', function() {
  hamburger.classList.toggle('active');
  mobileMenu.classList.toggle('active');
});
mobileMenu.querySelectorAll('a').forEach(function(a) {
  a.addEventListener('click', function() {
    hamburger.classList.remove('active');
    mobileMenu.classList.remove('active');
  });
});

// ===== BACK TO TOP =====
backToTop.addEventListener('click', function() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ===== SCROLL ANIMATIONS =====
function handleAnimations() {
  var elements = document.querySelectorAll('.fade-up, .zoom-in');
  elements.forEach(function(el) {
    var rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 80) {
      el.classList.add('visible');
    }
  });
}
window.addEventListener('scroll', handleAnimations);
window.addEventListener('load', handleAnimations);

// ===== COUNTER ANIMATION =====
function animateCounters() {
  var counters = document.querySelectorAll('.stat-number');
  counters.forEach(function(counter) {
    var rect = counter.getBoundingClientRect();
    if (rect.top < window.innerHeight && !counter.dataset.animated) {
      counter.dataset.animated = 'true';
      var target = parseInt(counter.dataset.target);
      var current = 0;
      var increment = Math.ceil(target / 60);
      var timer = setInterval(function() {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        counter.textContent = current + '+';
      }, 30);
    }
  });
}
window.addEventListener('scroll', animateCounters);

// ===== GALLERY FILTER =====
var filterBtns = document.querySelectorAll('.filter-btn');
var galleryItems = document.querySelectorAll('.gallery-item');
filterBtns.forEach(function(btn) {
  btn.addEventListener('click', function() {
    filterBtns.forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    var filter = btn.dataset.filter;
    galleryItems.forEach(function(item) {
      if (filter === 'all' || item.dataset.cat === filter) {
        item.style.display = 'block';
        setTimeout(function() { item.style.opacity = '1'; item.style.transform = 'scale(1)'; }, 50);
      } else {
        item.style.opacity = '0';
        item.style.transform = 'scale(0.8)';
        setTimeout(function() { item.style.display = 'none'; }, 400);
      }
    });
  });
});

// ===== TESTIMONIAL AUTO-SCROLL =====
var testimonialTrack = document.getElementById('testimonialTrack');
var testimonialIndex = 0;
function scrollTestimonials() {
  if (!testimonialTrack) return;
  var cards = testimonialTrack.querySelectorAll('.testimonial-card');
  if (cards.length === 0) return;
  testimonialIndex++;
  if (testimonialIndex >= cards.length) {
    testimonialIndex = 0;
    testimonialTrack.scrollTo({ left: 0, behavior: 'smooth' });
  } else {
    var cardWidth = cards[0].offsetWidth + 24;
    testimonialTrack.scrollTo({ left: cardWidth * testimonialIndex, behavior: 'smooth' });
  }
}
setInterval(scrollTestimonials, 3500);

// ===== HERO PARTICLES =====
var particlesContainer = document.getElementById('particles');
if (particlesContainer) {
  for (var i = 0; i < 20; i++) {
    var p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.top = Math.random() * 100 + '%';
    p.style.animationDelay = Math.random() * 8 + 's';
    p.style.animationDuration = (6 + Math.random() * 6) + 's';
    p.style.width = (2 + Math.random() * 3) + 'px';
    p.style.height = p.style.width;
    particlesContainer.appendChild(p);
  }
}

// ===== SMOOTH SCROLL FOR ALL ANCHOR LINKS =====
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    var target = document.querySelector(this.getAttribute('href'));
    if (target) {
      var offset = 70;
      var targetPos = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: targetPos, behavior: 'smooth' });
    }
  });
});
