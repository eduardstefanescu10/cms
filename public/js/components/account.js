// Change labels
$('title').text(lang['title_account'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_account']);
$('#label_account').html('<i class="fas fa-user"></i>&nbsp;' + lang['title_account']);
$('#first_name_label').html(lang['label_first_name']);
$('#last_name_label').html(lang['label_last_name']);
$('#username_label').html(lang['label_username']);
$('#email_label').html(lang['label_email']);
$('#button_update').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;' + lang['label_please_wait']);
$('#title_change_password').text(lang['title_change_password']);


// Disable fields and button
$('#firstName').attr('disabled', true);
$('#lastName').attr('disabled', true);
$('#username').attr('disabled', true);
$('#email').attr('disabled', true);
$('#button_update').attr('disabled', true);


// Get admin's details
$(document).ready(function() {
    $.ajax({
        type: 'GET',
        url: ADMIN_DETAILS_GET_URL,
        timeout: TIMEOUT,
        statusCode: {
            200: function(json) {
                // OK
                // Enable fields and button
                $('#firstName').attr('disabled', false);
                $('#lastName').attr('disabled', false);
                $('#username').attr('disabled', false);
                $('#email').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);

                // Add values to fields
                $('#firstName').val(json.firstName);
                $('#lastName').val(json.lastName);
                $('#username').val(json.username);
                $('#email').val(json.email);
            },
            401: function() {
                // Unauthorized
                redirect('logout');
            },
            500: function() {
                // Internal Server Error
                // Show message
                showMessage('error', lang['error_account_details_get_failed'])

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            }
        }
    });
});


// Update details request
$('#account_settings').submit(function(event) {
    // Prevent default action
    event.preventDefault();

    // Check if first name is empty
    if ($('#firstName').val().trim() == '') {
        // Display error message
        showMessage('warning', lang['error_first_name_empty'])

        return;
    }

    // Check if last name is empty
    if ($('#lastName').val().trim() == '') {
        // Display error message
        showMessage('warning', lang['error_last_name_empty'])

        return;
    }

    // Check if email is empty
    if ($('#email').val() == '') {
        // Display error message
        showMessage('warning', lang['error_email_empty'])

        return;
    }

    // Check if username is empty
    if ($('#username').val() == '') {
        // Display error message
        showMessage('warning', lang['error_username_empty'])

        return;
    }

    $.ajax({
        type: 'POST',
        url: ADMIN_UPDATE_DETAILS_URL,
        data: JSON.stringify(
            {
                'firstName': $('#firstName').val(),
                'lastName': $('#lastName').val(),
                'email': $('#email').val(),
                'username': $('#username').val()
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        beforeSend: function () {
            // Hide error message
            hideMessage();

            // Disable fields and button
            $('#firstName').attr('disabled', true);
            $('#lastName').attr('disabled', true);
            $('#username').attr('disabled', true);
            $('#email').attr('disabled', true);
            $('#button_update').attr('disabled', true);

            // Change button label
            $('#button_update').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;' + lang['label_please_wait']);
        },
        statusCode: {
            200: function (json) {
                // OK
                // Check if success
                if (json.status == 'success') {
                    // Show message
                    showMessage('success', lang['success_admin_update_details'])
                } else {
                    // Show message
                    showMessage('warning', lang['error_' + json.status]);
                }

                // Enable fields and button
                $('#firstName').attr('disabled', false);
                $('#lastName').attr('disabled', false);
                $('#username').attr('disabled', false);
                $('#email').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            },
            400: function () {
                // Show message
                showMessage('error', lang['error_internal_server'])

                // Enable fields and button
                $('#firstName').attr('disabled', false);
                $('#lastName').attr('disabled', false);
                $('#username').attr('disabled', false);
                $('#email').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            },
            401: function () {
                // Unauthorized
                redirect('logout');
            },
            500: function () {
                // Internal Server Error
                // Show message
                showMessage('error', lang['error_account_details_update_failed']);

                // Enable fields and button
                $('#firstName').attr('disabled', false);
                $('#lastName').attr('disabled', false);
                $('#username').attr('disabled', false);
                $('#email').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            }
        }
    })
});
