// Constants
const BASE_URL = 'http://localhost/cms';
const API_URL  = BASE_URL + '/api';
const LOGIN_URL = API_URL + '/account/login';
const TIMEOUT   = 30000; // 30 seconds


// Show/hide sidebar
$('#menu-toggle').click(function(e) {
    e.preventDefault();

    $('.main-wrapper').toggleClass('toggled');
});