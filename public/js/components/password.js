// Change labels
$('title').text(lang['title_change_password'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_change_password']);
$('#label_change_password').html('<i class="fas fa-lock"></i>&nbsp;' + lang['title_change_password']);
$('#label_current_password').text(lang['label_current_password']);
$('#label_new_password').text(lang['label_new_password']);
$('#label_confirm_password').text(lang['label_confirm_password']);
$('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);


// Change password
$('#change_password').submit(function(event) {
    // Prevent default action
    event.preventDefault();

    // Hide error message
    hideMessage();

    // Get values from the fields
    let currentPass = $('#currentPass').val().trim();
    let newPass     = $('#newPass').val().trim();
    let confirmPass = $('#confirmPass').val().trim();

    // Check if current password is not empty
    if (currentPass != '') {
        // Check if has less than 8 chars
        if (currentPass.length < 8 || currentPass.length > 50) {
            // Not correct
            // Display error message
            showMessage('warning', lang['error_pass_current_invalid'])

            return;
        }
    } else {
        // Empty password
        // Display error message
        showMessage('warning', lang['error_pass_current_empty'])

        return;
    }

    // Check if new password is not empty
    if (newPass != '') {
        // Check if has at least 8 chars
        if (newPass.length > 7) {
            // Check if has less than 51 chars
            if (newPass.length < 51) {
                // Check password chars
                if (!newPass.match(/^[0-9a-zA-Z]+$/)) {
                    // Check if new and current password match
                    if (currentPass == newPass) {
                        // Match
                        // Display error message
                        showMessage('warning', lang['error_pass_new_match'])

                        return;
                    }
                } else {
                    // Weak password
                    // Display error message
                    showMessage('warning', lang['error_pass_new_invalid'])

                    return;
                }
            } else {
                // Max length
                // Display error message
                showMessage('warning', lang['error_pass_new_length_max'])

                return;
            }
        } else {
            // Minimum length
            // Display error message
            showMessage('warning', lang['error_pass_new_length_min'])

            return;
        }
    } else {
        // Empty password
        // Display error message
        showMessage('warning', lang['error_pass_new_empty'])

        return;
    }

    // Check if confirm password is not empty
    if (confirmPass != '') {
        // Check if new and confirm password don't match
        if (newPass != confirmPass) {
            // Display error message
            showMessage('warning', lang['error_pass_confirm_match'])

            return;
        }
    } else {
        // Empty
        // Display error message
        showMessage('warning', lang['error_pass_confirm_empty'])

        return;
    }

    $.ajax({
        type: 'POST',
        url: ADMIN_CHANGE_PASS_URL,
        data: JSON.stringify(
            {
                'currentPass': currentPass,
                'newPass': newPass,
                'confirmPass': confirmPass
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        beforeSend: function () {
            // Disable fields and button
            $('#currentPass').attr('disabled', true);
            $('#newPass').attr('disabled', true);
            $('#confirmPass').attr('disabled', true);
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
                    showMessage('success', lang['success_pass_changed'])
                } else {
                    // Show message
                    showMessage('warning', lang['error_' + json.status]);
                }

                // Enable fields and button
                $('#currentPass').attr('disabled', false);
                $('#newPass').attr('disabled', false);
                $('#confirmPass').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            },
            400: function () {
                // Bad request
                showMessage('error', lang['error_pass_changed']);

                // Enable fields and button
                $('#currentPass').attr('disabled', false);
                $('#newPass').attr('disabled', false);
                $('#confirmPass').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Empty fields
                $('#currentPass').val('');
                $('#newPass').val('');
                $('#confirmPass').val('');

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            },
            401: function () {
                // Unauthorized
                redirect('logout');
            },
            500: function () {
                // Internal Server Error
                showMessage('error', lang['error_pass_changed']);

                // Enable fields and button
                $('#currentPass').attr('disabled', false);
                $('#newPass').attr('disabled', false);
                $('#confirmPass').attr('disabled', false);
                $('#button_update').attr('disabled', false);

                // Empty fields
                $('#currentPass').val('');
                $('#newPass').val('');
                $('#confirmPass').val('');

                // Remove spinner
                $('#button_update').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_update']);
            }
        }
    })
});