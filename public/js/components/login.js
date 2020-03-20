$('title').text(lang['title_login'] + ' | ' + lang['site_name']);
$('h1').text(lang['label_welcome']);
$('#label_username').text(lang['label_username']);
$('#label_password').text(lang['label_password']);
$('#label_password_forgot').text(lang['label_password_forgot']);
$('#label_remember_me').text(lang['label_remember_me']);
$('#button_login').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_login']);


// Check/uncheck remember me
$('#login_remember').click(function() {
    // Check if is checked
    if ($('#login_remember').value == 'yes') {
        // Uncheck
        $('#login_remember').val('');
    } else {
        // Check
        $('#login_remember').val('yes');
    }
});


// Log in request
$('#login_form').submit(function(event) {
    // Prevent default action
    event.preventDefault();

    $.ajax({
        type: 'POST',
        url: LOGIN_URL,
        data: JSON.stringify(
            {
                "username": $('#login_username').val(),
                "password": $('#login_password').val(),
                "remember": $('#login_remember').val()
            }
        ),
        contentType: 'application/json',
        timeout: TIMEOUT,
        beforeSend: function() {
            // Hide error
            $('#error_message').removeClass('error-show');

            // Disable fields and button
            $('#login_username').attr('disabled', true);
            $('#login_password').attr('disabled', true);
            $('#login_remember').attr('disabled', true);
            $('#button_login').attr('disabled', true);

            // Change button label
            $('#button_login').html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;' + lang['label_please_wait']);
        },
        statusCode: {
            200: function () {
                // OK
                window.location = BASE_URL;
            },
            400: function() {
                // Bad request
                // Display error
                $('#error_message').text(lang['error_login']);
                $('#error_message').addClass('error-show');

                // Enable fields and button
                $('#login_username').attr('disabled', false);
                $('#login_password').attr('disabled', false);
                $('#login_remember').attr('disabled', false);
                $('#button_login').attr('disabled', false);

                // Clear fields
                $('#login_username').val('');
                $('#login_password').val('');

                // Change button label
                $('#button_login').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_login']);
            },
            401: function() {
                // Unauthorized
                // Display error
                $('#error_message').text(lang['error_login']);
                $('#error_message').addClass('error-show');

                // Enable fields and button
                $('#login_username').attr('disabled', false);
                $('#login_password').attr('disabled', false);
                $('#login_remember').attr('disabled', false);
                $('#button_login').attr('disabled', false);

                // Clear fields
                $('#login_username').val('');
                $('#login_password').val('');

                // Change button label
                $('#button_login').html('<i class="fas fa-check"></i>&nbsp;&nbsp;' + lang['button_login']);
            }
        }
    });
});