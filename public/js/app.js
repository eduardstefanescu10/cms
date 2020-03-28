// Constants
const BASE_URL      = 'http://localhost/cms';
const API_URL       = BASE_URL + '/api';
const LOGIN_URL     = API_URL + '/account/login';
const FORGOT_URL    = API_URL + '/account/forgot'
const ORDERS_URL    = API_URL + '/orders/list'
const TIMEOUT       = 30000; // 30 seconds
const CURRENCY_ICON = '<i class="fas fa-dollar-sign"></i>';

// Show/hide sidebar
$('#menu-toggle').click(function(e) {
    e.preventDefault();

    $('.main-wrapper').toggleClass('toggled');
});


// Get date
function getDate(pastDays = 0) {
    // Create new Date object
    let today = new Date();

    // Calculate date based on past days
    today.setDate(today.getDate() - pastDays);

    // Format date
    let dd   = String(today.getDate()).padStart(2, '0');
    let mm   = String(today.getMonth() + 1).padStart(2, '0');
    let yyyy = today.getFullYear();

    // Return final date
    return yyyy + '-' + mm + '-' + dd;
}


// Redirect
function redirect(url) {
    window.location.replace(url);
}


// Convert backend amount
function convertAmountBE(amount) {
    return CURRENCY_ICON + ' ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


// Convert backend date
function convertDateBE(dateTime, type = 0) {
    // Check if dateTime is empty
    if (dateTime == "") {
        // Return empty string
        return "";
    }

    // Variables
    let datePart = "";
    let timePart = "";

    if (/\s/.test(dateTime)) {
        // Split by " "
        let split1 = dateTime.split(" ", 2);

        // Get date and time from split
        datePart = split1[0];
        timePart = split1[1];
    } else {
        datePart = dateTime;
    }

    // Split by "-"
    let split2 = datePart.split("-", 3);

    // Months
    let months = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];

    // Check type
    if (type == 0) {
        // Type 0
        // Example: February 16, 2020
        return months[parseInt(split2[1]) - 1] + ' ' + split2[2] + ', ' + split2[0];
    } else if (type == 1) {
        // Type 1
        // Example: February 16
        return months[parseInt(split2[1]) - 1] + ' ' + split2[2];
    }
}