import "./bootstrap";
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import "preline";

import { createIcons, icons } from 'lucide';

window.lucide = { createIcons, icons };
