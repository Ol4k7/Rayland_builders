document.addEventListener('DOMContentLoaded', () => {

  /* ================= 1. MOBILE MENU ================= */
  const toggle = document.getElementById('menuToggle');
  const nav = document.getElementById('navMenu');
  
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      nav.classList.toggle('show');
      
      // Update the icon (☰ to ✖) for a nice touch
      if (nav.classList.contains('show')) {
        toggle.textContent = '✕';
      } else {
        toggle.textContent = '☰';
      }
    });
  }

  /* ================= 2. AUTO ACTIVE LINK ================= */
  // This highlights the correct link in the nav bar automatically
  const currentPage = window.location.pathname.split("/").pop() || 'index.php';
  const navLinks = document.querySelectorAll('.nav a');

  navLinks.forEach(link => {
    const linkPage = link.getAttribute('href');
    if (linkPage === currentPage) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

  /* ================= 3. FOOTER YEAR ================= */
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();

  /* ================= 4. SCROLL REVEAL ANIMATION (The "Breathing" Effect) ================= */
  // This automatically adds the animation class to cards and sections
  const observerOptions = {
    threshold: 0.15, // Trigger when 15% of the element is visible
    rootMargin: "0px 0px -50px 0px"
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
        observer.unobserve(entry.target); // Only animate once
      }
    });
  }, observerOptions);

  // Select elements to animate
  const elementsToAnimate = document.querySelectorAll('.service-card, .hero-content, .section-intro, .contact-card, .contact-form, .about-text');
  
  elementsToAnimate.forEach(el => {
    el.classList.add('reveal'); // Add base CSS class
    observer.observe(el);       // Start watching
  });

  /* ================= 5. OPENSTREETMAP (Leaflet) ================= */
  const mapEl = document.getElementById('osm-map');

  if (mapEl && typeof L !== 'undefined') {
    const lat = 51.752796;
    const lng = 0.455109;
    const zoom = 15;

    const map = L.map(mapEl, {
      center: [lat, lng],
      zoom: zoom,
      scrollWheelZoom: false,
      dragging: !L.Browser.mobile 
    });

    // Use a cleaner, high-contrast map style
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
    }).addTo(map);

    // Custom Icon (Optional: makes it look premium)
    const customIcon = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // Yellow location pin
      iconSize: [38, 38],
      iconAnchor: [19, 38],
      popupAnchor: [0, -30]
    });

    // Fallback to default if custom icon fails, but here we try to use a nice one
    L.marker([lat, lng]) // Add {icon: customIcon} here if you download an icon image
      .addTo(map)
      .bindPopup('<strong>Rayland Builders</strong><br>3 Pipchin Road<br>Chelmsford');

    setTimeout(() => map.invalidateSize(), 300);

    const osmLink = document.getElementById('osm-link');
    if (osmLink) {
      osmLink.href = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=${zoom}/${lat}/${lng}`;
    }
  }

});