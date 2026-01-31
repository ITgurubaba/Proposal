import '../../css/admin/app.css'

import $ from 'jquery';
window.jQuery = window.$ = $

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import '../plugins/sweetalert2.js';
import '../plugins/intl-tel.js';
