// Change labels
$('title').text(lang['title_categories'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_categories']);
$('#label_categories').html('<i class="fas fa-table"></i>&nbsp;' + lang['title_categories']);
$('#searchText').attr('placeholder', lang['label_categories_placeholder']);
$('#label_category_new').html('<i class="fas fa-plus"></i>&nbsp;' + lang['title_categories_new']);


// Get categories
function getCategories() {
    // Insert spinner
    $('#categories_table').html('<div class="spinner-box"><i class="fas fa-spinner fa-spin"></i></div>');

    // Get search text
    let searchText = $('#searchText').val().trim();

    $.ajax({
        type: 'POST',
        url: CATEGORIES_LIST_URL,
        data: JSON.stringify(
            {
                "searchText": searchText,
                "number": 10,
                "page": 1,
                "status":
                    [
                        "DRAFT",
                        "AVAILABLE",
                        "TRASH"
                    ],
                "startDate": null,
                "endDate": null
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        statusCode: {
            200: function(json) {
                // OK
                if (json.categories.length > 0) {
                    // Create table
                    let tableContent = [];
                    let tableHeader =
                        '<div class="table-responsive">' +
                        '<table class="table table-striped">' +
                        '<thead>' +
                        '<th scope="col">' + lang['label_ID'] + '</th>' +
                        '<th scope="col">' + lang['label_name'] + '</th>' +
                        '<th scope="col">' + lang['label_status'] + '</th>' +
                        '<th scope="col">' + lang['label_date'] +'</th>' +
                        '</thead>' +
                        '<tbody>';
                    let tableFooter =
                        '</tbody>' +
                        '</table>' +
                        '</div>'

                    // Loop categories
                    $.each(json.categories, function(key, value) {
                        // Add order to final table
                        tableContent.push(
                            '<tr>' +
                            '<th scope="row"><a href="' + BASE_URL + '/categories/view/' + value['ID'] + '">' + value['ID'] + '</a></th>' +
                            '<td>' + value['title'] + '</td>' +
                            '<td>' + convertStatusBE(value['status']) + '</td>' +
                            '<td>' + convertDateBE(value['added'], 0) + '</td>' +
                            '</tr>'
                        )
                    });

                    // Build the entire table
                    let ordersTable = tableHeader + tableContent + tableFooter;

                    // Return table
                    $('#categories_table').html(ordersTable);
                } else {
                    // No categories
                    $('#categories_table').text(lang['success_categories_list_none']);
                }
            },
            400: function() {
                // Bad request
                $('#categories_table').hide();
            },
            401: function() {
                // Unauthorized
                redirect('logout');
            },
            500: function() {
                // Internal Server Error
                $('#categories_table').hide();
            }
        }
    });
}


// Get categories after page is loaded
getCategories();


// Define values
let typingTimer;
let doneTyping = 500; // half of a second


// On keyup
$('#searchText').on('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(getCategories, doneTyping);
});


// On keydown
$('#searchText').on('kedown', function() {
    clearTimeout(typingTimer);
});
