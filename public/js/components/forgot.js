$('title').text(lang['title_forgot_password'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_forgot_password']);
$('#label_email').text(lang['label_email']);
$('#button_recover').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_recover']);
$('#label_message_back_to_login').text(lang['label_message_back_to_login']);


// Forgot request
$('#forgot_form').submit(function(event) {
    // Prevent default action
    event.preventDefault();

    // Check if email is empty
    if ($('#forgot_email').val() == '') {
        // Display error
        $('#error_message').text(lang['error_forgot_empty']);
        $('#error_message').addClass('error-show');

        return;
    }

    $.ajax({
        type: 'POST',
        url: FORGOT_URL,
        data: JSON.stringify(
            {
                'email': $('#forgot_email').val()
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        beforeSend: function() {
            // Hide error
            $('#error_message').removeClass('error-show');

            // Disable field and button
            $('#forgot_email').attr('disabled', true);
            $('#button_recover').attr('button_recover', true);

            // Change button label
            $('#button_recover').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;' + lang['label_please_wait']);
        },
        statusCode: {
            200: function (json) {
                // Check status
                if (json.status == 'success') {
                    // Hide field and button
                    $('#label_email').hide();
                    $('#forgot_email').hide();
                    $('#button_recover').hide();

                    // Display success message
                    $('#success_message').text(lang['success_forgot']);
                    $('#success_message').show();
                } else {
                    // Display error
                    $('#error_message').text(lang['error_forgot_wrong']);
                    $('#error_message').addClass('error-show');

                    // Enable field and button
                    $('#forgot_email').attr('disabled', false);
                    $('#button_recover').attr('button_recover', false);

                    // Empty field
                    $('#forgot_email').val('');

                    // Change button label
                    $('#button_recover').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_recover']);
                }
            },
            400: function () {
                // Display error
                $('#error_message').text(lang['error_forgot_wrong']);
                $('#error_message').addClass('error-show');

                // Enable field and button
                $('#forgot_email').attr('disabled', false);
                $('#button_recover').attr('button_recover', false);

                // Empty field
                $('#forgot_email').val('');

                // Change button label
                $('#button_recover').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_recover']);
            },
            500: function () {
                // Display error
                $('#error_message').text(lang['error_internal_server']);
                $('#error_message').addClass('error-show');

                // Enable field and button
                $('#forgot_email').attr('disabled', false);
                $('#button_recover').attr('button_recover', false);

                // Empty field
                $('#forgot_email').val('');

                // Change button label
                $('#button_recover').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_recover']);
            }
        }
    });
});