document.addEventListener('DOMContentLoaded', () => {

  /* ================= MOBILE MENU ================= */
  const toggle = document.getElementById('menuToggle');
  const nav = document.getElementById('navMenu');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      nav.classList.toggle('show');
    });
  }

  /* ================= FOOTER YEAR ================= */
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();

 /* ================= OPENSTREETMAP ================= */
  const mapEl = document.getElementById('osm-map');

  // Check if element exists AND if Leaflet (L) is loaded
  if (mapEl && typeof L !== 'undefined') {
    const lat = 51.752796;
    const lng = 0.455109;
    const zoom = 15;

    // REMOVED the line that forces height = '200px'
    // We let CSS handle the height now.

    const map = L.map(mapEl, {
      center: [lat, lng],
      zoom: zoom,
      scrollWheelZoom: false, // Keeps page scrolling smooth
      dragging: !L.Browser.mobile // Optional: Disables map dragging on mobile so you don't get stuck in the footer
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng])
      .addTo(map)
      .bindPopup('<strong>Rayland Builders</strong><br>3 Pipchin Road<br>Chelmsford');

    // Force Leaflet to recalc size after layout/render
    setTimeout(() => map.invalidateSize(), 300);

    // Update "Open in OSM" link
    const osmLink = document.getElementById('osm-link');
    if (osmLink) {
      osmLink.href = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=${zoom}/${lat}/${lng}`;
    }
  }
});