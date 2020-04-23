// Change labels
$('title').text(lang['title_categories_new'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_categories_new']);
$('#label_categories').html('<i class="fas fa-plus"></i>&nbsp;' + lang['title_categories_new']);
$('#button_create').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_create']);
$('#label_category_name').text(lang['label_category_name']);
$('#label_status').text(lang['label_status']);
$('#label_option_available').text(lang['label_option_available']);
$('#label_option_draft').text(lang['label_option_draft']);
$('#label_option_trash').text(lang['label_option_trash']);


// New category
$('#category_new').submit(function(event) {
    // Prevent default action
    event.preventDefault();

    // Hide error message
    hideMessage();

    // Get values from the fields
    let name   = $('#name').val().trim();
    let status = $('#status').val().trim();

    // Check if name is not empty
    if (name != '') {
        // Check if has less than 51 chars
        if (name.length < 51) {
            // Check if has less one char
            if (name.length > 0) {
                if (!name.match(/^[0-9a-zA-Z ]+$/)) {
                    // Not correct
                    // Display error message
                    showMessage('warning', lang['error_name_chars'])

                    return;
                }
            } else {
                // Not correct
                // Display error message
                showMessage('warning', lang['error_name_length_min'])

                return;
            }
        } else {
            // Not correct
            // Display error message
            showMessage('warning', lang['error_name_length_max'])

            return;
        }
    } else {
        // Empty name
        // Display error message
        showMessage('warning', lang['error_name_missing'])

        return;
    }

    // Check if status is valid
    if (status != '0' && status != '1' && status != '2') {
        // Display error message
        showMessage('warning', lang['error_status_invalid'])

        return;
    }

    $.ajax({
        type: 'POST',
        url: CATEGORIES_CREATE_URL,
        data: JSON.stringify(
            {
                'name': name,
                'status': status
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        beforeSend: function () {
            // Disable fields and button
            $('#name').attr('disabled', true);
            $('#status').attr('disabled', true);

            // Change button label
            $('#button_create').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;' + lang['label_please_wait']);
        },
        statusCode: {
            200: function (json) {
                // OK
                // Check if success
                if (json.status == 'success') {
                    // Redirect to categories
                    redirect('categories');
                } else {
                    // Show message
                    showMessage('warning', lang['error_' + json.status]);
                }
            },
            400: function () {
                // Bad request
                showMessage('error', lang['error_category_create']);

                // Enable fields and button
                $('#name').attr('disabled', false);
                $('#status').attr('disabled', false);

                // Empty field
                $('#name').val('');

                // Change button label
                $('#button_create').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_create']);
            },
            401: function () {
                // Unauthorized
                redirect('logout');
            },
            500: function () {
                // Internal Server Error
                showMessage('error', lang['error_category_create']);

                // Enable fields and button
                $('#name').attr('disabled', false);
                $('#status').attr('disabled', false);

                // Empty field
                $('#name').val('');

                // Change button label
                $('#button_create').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_create']);
            }
        }
    })
});