// Ensure Font Awesome is loaded
if (!document.querySelector('script[src*="fontawesome"]')) {
  const fontAwesomeScript = document.createElement('script');
  fontAwesomeScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js';
  document.head.appendChild(fontAwesomeScript);
}

// Dropdown for "Call Mechanic"
const callDropdownBtn = document.getElementById('call-dropdown-btn');
if (callDropdownBtn) {
  callDropdownBtn.addEventListener('click', function (e) {
    e.preventDefault();
    const dropdownContent = this.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function (e) {
    if (!e.target.matches('#call-dropdown-btn') && !e.target.closest('.call-dropdown-content')) {
      const dropdowns = document.getElementsByClassName('call-dropdown-content');
      for (let i = 0; i < dropdowns.length; i++) {
        if (dropdowns[i].style.display === 'block') {
          dropdowns[i].style.display = 'none';
        }
      }
    }
  });
}

// Emergency SOS Button
const sosButtons = document.querySelectorAll('.emergency-trigger');
sosButtons.forEach(button => {
  button.addEventListener('click', function () {
    alert('Emergency services have been notified!');
  });
});

// Header Scroll Behavior
let lastScrollY = 0;
window.addEventListener('scroll', () => {
  const header = document.getElementById('main-header');
  const currentScrollY = window.scrollY;

  if (currentScrollY > lastScrollY) {
    // Scrolling down
    header.classList.add('hide');
  } else {
    // Scrolling up
    header.classList.remove('hide');
  }

  lastScrollY = currentScrollY;
});

// Mobile Menu Toggle
const mobileMenuButton = document.createElement('button');
mobileMenuButton.innerHTML = '<i class="fas fa-bars"></i>';
mobileMenuButton.classList.add('mobile-menu-btn');

const nav = document.querySelector('nav ul');
if (nav) {
  document.querySelector('header .container').appendChild(mobileMenuButton);

  mobileMenuButton.addEventListener('click', () => {
    nav.classList.toggle('show');
    mobileMenuButton.innerHTML = nav.classList.contains('show')
      ? '<i class="fas fa-times"></i>'
      : '<i class="fas fa-bars"></i>';
  });

  // Close mobile menu when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('nav') && !e.target.closest('.mobile-menu-btn')) {
      nav.classList.remove('show');
      mobileMenuButton.innerHTML = '<i class="fas fa-bars"></i>';
    }
  });
}

// Smooth Scrolling for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    const targetId = this.getAttribute('href').substring(1);
    const targetElement = document.getElementById(targetId);

    if (targetElement) {
      const offset = document.getElementById('main-header').offsetHeight;
      const targetPosition = targetElement.offsetTop - offset;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    }
  });
});

// Highlight Active Navigation Link
window.addEventListener('scroll', () => {
  const sections = document.querySelectorAll('section');
  const navLinks = document.querySelectorAll('nav ul li a');

  let currentSection = '';
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 100;
    const sectionHeight = section.clientHeight;

    if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
      currentSection = section.getAttribute('id');
    }
  });

  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href').includes(currentSection)) {
      link.classList.add('active');
    }
  });
});

// Contact Form Handling
const contactForm = document.getElementById('contact-form');
if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Message sent successfully!');
    contactForm.reset();
  });
}

// Newsletter Form Handling
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) {
  newsletterForm.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Thank you for subscribing to our newsletter!');
    newsletterForm.reset();
  });
}

// Accordion Functionality for Safety Tips
const accordionHeaders = document.querySelectorAll('.tip-header');
accordionHeaders.forEach(header => {
  header.addEventListener('click', () => {
    const activeAccordion = document.querySelector('.active-accordion');
    if (activeAccordion && activeAccordion !== header.nextElementSibling) {
      activeAccordion.classList.remove('active-accordion');
    }

    const content = header.nextElementSibling;
    content.classList.toggle('active-accordion');
  });
});

// Vehicle Selector Logic
const makeSelect = document.getElementById('make');
const modelSelect = document.getElementById('model');
const yearSelect = document.getElementById('year');

if (makeSelect && modelSelect && yearSelect) {
  const models = {
    toyota: ['Corolla', 'Camry', 'RAV4', 'Hilux'],
    honda: ['Civic', 'Accord', 'CR-V', 'City'],
    ford: ['Mustang', 'Fiesta', 'Explorer', 'Ranger'],
    hyundai: ['i10', 'i20', 'Creta', 'Venue']
  };

  makeSelect.addEventListener('change', () => {
    const selectedMake = makeSelect.value.toLowerCase();
    modelSelect.innerHTML = '<option value="">Select Model</option>';

    if (models[selectedMake]) {
      models[selectedMake].forEach(model => {
        const option = document.createElement('option');
        option.value = model.toLowerCase();
        option.textContent = model;
        modelSelect.appendChild(option);
      });
    }
  });

  makeSelect.dispatchEvent(new Event('change'));

  yearSelect.innerHTML = '<option value="">Select Year</option>';
  const currentYear = new Date().getFullYear();
  for (let year = currentYear; year >= currentYear - 15; year--) {
    const option = document.createElement('option');
    option.value = year;
    option.textContent = year;
    yearSelect.appendChild(option);
  }
}

// Leaflet.js Map Initialization
document.addEventListener('DOMContentLoaded', () => {
  const mapContainer = document.getElementById('map');
  if (mapContainer) {
    const map = L.map('map').setView([27.7172, 85.3240], 13); // Default center: Kathmandu

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Custom mechanic shop icon
    const mechanicIcon = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/2750/2750765.png',
      iconSize: [30, 30],
      iconAnchor: [15, 30],
      popupAnchor: [0, -30]
    });

    // Custom user location icon
    const userIcon = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
      iconSize: [25, 25],
      iconAnchor: [12, 25],
      popupAnchor: [0, -25]
    });

    // Array of mechanic shops with details
    const mechanicShops = [
      {
        lat: 27.7150,
        lng: 85.3200,
        name: 'Everest Auto Service',
        address: 'Ring Road, Kathmandu',
        contact: '+977-9800000001',
        rating: '⭐️⭐️⭐️⭐️ 4.5/5',
        services: 'Car repair, tire replacement, battery service'
      },
      {
        lat: 27.7200,
        lng: 85.3300,
        name: 'Himalayan Vehicle Repair',
        address: 'Lazimpat, Kathmandu',
        contact: '+977-9800000002',
        rating: '⭐️⭐️⭐️⭐️⭐️ 5.0/5',
        services: 'Emergency roadside assistance, general auto repair'
      },
      {
        lat: 27.7250,
        lng: 85.3100,
        name: 'Nepal Auto Garage',
        address: 'Maitighar, Kathmandu',
        contact: '+977-9800000003',
        rating: '⭐️⭐️⭐️⭐️ 4.2/5',
        services: 'Oil change, brake service, electrical diagnostics'
      }
    ];

    // Add markers for mechanic shops
    mechanicShops.forEach(shop => {
      L.marker([shop.lat, shop.lng], { icon: mechanicIcon })
        .addTo(map)
        .bindPopup(`
          <h3>${shop.name}</h3>
          <p><b>Address:</b> ${shop.address}</p>
          <p><b>Contact:</b> ${shop.contact}</p>
          <p><b>Rating:</b> ${shop.rating}</p>
          <p><b>Services:</b> ${shop.services}</p>
        `);
    });

    // Get user location
    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(
        position => {
          const userLat = position.coords.latitude;
          const userLng = position.coords.longitude;

          // Place user marker
          L.marker([userLat, userLng], { icon: userIcon })
            .addTo(map)
            .bindPopup('<b>You are here</b>')
            .openPopup();

          // Center map on user location
          map.setView([userLat, userLng], 14);
        },
        error => {
          console.error('Geolocation error:', error.message);
          alert('Location access denied. Please enable location services.');
        }
      );
    } else {
      alert('Geolocation is not supported by your browser.');
    }
  }
});
document.addEventListener('DOMContentLoaded', () => {
  const authLinks = document.getElementById('auth-links');
  const isLoggedIn = localStorage.getItem('isLoggedIn');

  if (isLoggedIn) {
    const userName = localStorage.getItem('userName') || 'User';
    authLinks.innerHTML = `
      <span>Welcome, ${userName}!</span>
      <a href="profile.html"><i class="fas fa-user"></i> Profile</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    `;
  }
});
document.addEventListener('DOMContentLoaded', async () => {
  const isLoggedIn = localStorage.getItem('isLoggedIn');

  if (isLoggedIn) {
    try {
      const response = await fetch('/api/vehicles'); // Replace with your backend endpoint
      const vehicles = await response.json();

      const vehicleContainer = document.querySelector('.vehicle-cards');
      vehicleContainer.innerHTML = vehicles.map(vehicle => `
        <div class="vehicle-card">
          <h3>${vehicle.make} ${vehicle.model}</h3>
          <ul>
            <li><strong>Year:</strong> ${vehicle.year}</li>
            <li><strong>Color:</strong> ${vehicle.color}</li>
            <li><strong>Mileage:</strong> ${vehicle.mileage} km</li>
          </ul>
        </div>
      `).join('');
    } catch (error) {
      console.error('Error fetching vehicles:', error);
    }
  }
});