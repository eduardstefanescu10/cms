// Constants
const BASE_URL = 'http://localhost/cms';
const API_URL  = BASE_URL + '/api';


// Show/hide sidebar
$('#menu-toggle').click(function(e) {
    e.preventDefault();

    $('.main-wrapper').toggleClass('toggled');
});