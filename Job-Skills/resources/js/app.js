import "./bootstrap";
import Alpine from 'alpinejs';

// Import Alpine components
import emploiManager from './components/emploiManager.js';
import emploiFilter from './components/emploiFilter.js';

// Register components globally
window.emploiManager = emploiManager;
window.emploiFilter = emploiFilter;

window.Alpine = Alpine;

Alpine.start();

import "preline";

import { createIcons, icons } from 'lucide';

window.lucide = { createIcons, icons };
