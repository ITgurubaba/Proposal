import '../../css/front/app.css';

import 'preline';
import {HSPinInput} from "preline";

import './bootstrap';

import '../plugins/sweetalert2.js';
import '../plugins/intl-tel.js';

/* Initializing JS */
document.addEventListener('livewire:navigate', (event) => {
   HSPinInput.autoInit();
});
