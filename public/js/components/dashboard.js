// Change labels
$('title').text(lang['title_dashboard'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_dashboard']);
$('#label_traffic').html('<i class="fas fa-chart-line"></i>&nbsp;' + lang['label_traffic']);
$('#label_devices').html('<i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;' + lang['label_devices']);
$('#label_new_orders').html('<i class="fas fa-dollar-sign"></i>&nbsp;' + lang['label_new_orders']);
$('#traffic_statistics').html('<div class="spinner-box"><i class="fas fa-spinner fa-spin"></i></div>');
$('#orders_list').html('<div class="spinner-box"><i class="fas fa-spinner fa-spin"></i></div>');
$('#devices_statistics').html('<div class="spinner-box"><i class="fas fa-spinner fa-spin"></i></div>');



// After the page is loaded
$(document).ready(function() {
    // Get traffic days views
    $.ajax({
        type: 'POST',
        url: TRAFFIC_DAYS_URL,
        data: JSON.stringify(
            {
                "days": 7
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        statusCode: {
            200: function (json) {
                // OK
                // Remove spinner
                $('#traffic_statistics').html('');

                // Create traffic chart
                createTrafficChart(json);
            },
            400: function () {
                // Bad request
                $('#traffic_statistics').html(lang['error_traffic_bad']);
            },
            401: function () {
                // Unauthorized
                redirect('logout');
            }
        }
    });

    // Get devices views
    $.ajax({
        type: 'POST',
        url: TRAFFIC_DEVICES_URL,
        data: JSON.stringify(
            {
                "startDate": getDate(6),
                "endDate": getDate()
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        statusCode: {
            200: function (json) {
                // OK
                // Remove spinner
                $('#devices_statistics').html('');

                // Create devices chart
                getDevicesChart(json.mobile, json.web);
            },
            400: function () {
                // Bad request
                $('#devices_statistics').html(lang['error_traffic_bad']);
            },
            401: function () {
                // Unauthorized
                redirect('logout');
            }
        }
    });

    // Get orders
    $.ajax({
        type: 'POST',
        url: ORDERS_URL,
        data: JSON.stringify(
            {
                "searchText": null,
                "number": 10,
                "page": 1,
                "status":
                [
                    "PENDING"
                ],
                "startDate": getDate(365),
                "endDate": getDate()
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        statusCode: {
            200: function(json) {
                // OK
                if (json.orders.length > 0) {
                    // Create table
                    let tableContent = [];
                    let tableHeader =
                        '<div class="table-responsive">' +
                        '<table class="table table-striped">' +
                        '<thead>' +
                        '<th scope="col">' + lang['label_ID'] + '</th>' +
                        '<th scope="col">' + lang['label_name'] + '</th>' +
                        '<th scope="col"></th>' +
                        '<th scope="col">' + lang['label_country'] +'</th>' +
                        '<th scope="col">' + lang['label_phone'] +'</th>' +
                        '<th scope="col">' + lang['label_total'] + '</th>' +
                        '<th scope="col">' + lang['label_date'] +'</th>' +
                        '</thead>' +
                        '<tbody>';
                    let tableFooter =
                        '</tbody>' +
                        '</table>' +
                        '</div>'

                    // Loop orders
                    $.each(json.orders, function(key, value) {
                        // Add order to final table
                        tableContent.push(
                            '<tr>' +
                            '<th scope="row"><a href="' + BASE_URL + '/orders/view/' + value['ID'] + '">' + value['ID'] + '</a></th>' +
                            '<td>' + value['firstName'] + ' ' + value['lastName'] + '</td>' +
                            '<td><img src="' + BASE_URL + '/public/images/flags/' + value['country'] + '.png" "width="30" height="20" alt="' + value['country'] + '"></td>' +
                            '<td>' + value['country'] + '</td>' +
                            '<td>' + value['countryCode'] + ' ' + value['phone'] + '</td>' +
                            '<td>' + convertAmountBE(value['total']) + '</td>' +
                            '<td>' + convertDateBE(value['added'], 1) + '</td>' +
                            '</tr>'
                        )
                    });

                    // Build the entire table
                    let ordersTable = tableHeader + tableContent + tableFooter;

                    // Return table
                    $('#orders_list').html(ordersTable);
                } else {
                    // No orders
                    $('#orders_box').hide();
                }
            },
            400: function() {
                // Bad request
                $('#orders_box').hide();
            },
            401: function() {
                // Unauthorized
                redirect('logout');
            },
            500: function() {
                // Internal Server Error
                $('#orders_box').hide();
            }
        }
    });
});


// Traffic chart
function createTrafficChart(values) {
    // Traffic chart
    new Morris.Line({
        // Where we draw
        element: 'traffic_statistics',
        // The data chart
        data: [
            {
                day: convertDateBE(getDate(6), 1),
                value: values[getDate(6)]
            },
            {
                day: convertDateBE(getDate(5), 1),
                value: values[getDate(5)]
            },
            {
                day: convertDateBE(getDate(4), 1),
                value: values[getDate(4)]
            },
            {
                day: convertDateBE(getDate(3), 1),
                value: values[getDate(3)]
            },
            {
                day: convertDateBE(getDate(2), 1),
                value: values[getDate(2)]
            },
            {
                day: convertDateBE(getDate(1), 1),
                value: values[getDate(1)]
            },
            {
                day: convertDateBE(getDate(), 1),
                value: values[getDate()]
            }
        ],
        // Define xkey
        xkey: 'day',
        // Define ykeys
        ykeys: ['value'],
        // Define labels
        labels: [lang['label_views']],
        // Define line colors
        lineColors: ['#ce00ce'],
        // Resize
        resize: true,
        // Disable time display
        parseTime: false
    });
}


// Devices chart
function getDevicesChart(mobileViews, webViews) {
    // Donut
    Morris.Donut({
        // Where we draw
        element: 'devices_statistics',
        // The data chart
        data: [
            {
                label: lang['label_web'],
                value: webViews
            },
            {
                label: lang['label_mobile'],
                value: mobileViews
            }
        ],
        // The colors
        colors: ['#4699df', '#b900b9'],
        // Resize
        resize: true,
    });
}