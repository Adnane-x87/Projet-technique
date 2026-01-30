import "./bootstrap";
import "preline";
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Lucide icons
window.lucide = { createIcons, icons };

// Initialize Lucide icons on page load
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide && window.lucide.createIcons) {
        window.lucide.createIcons({ icons: window.lucide.icons });
    }
});
