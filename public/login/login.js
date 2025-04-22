$(document).ready(function() {
    // Handle login form
    function handleLogin() {
        $('input[name="email"], input[name="password"]').on('input', function() {
            let fieldName = $(this).attr('name');
            let value = $(this).val();

            // Remove spaces from the value in real-time
            $(this).val(value.replace(/\s/g, ''));

            // Hide the message once the user starts typing
            if (fieldName === 'email') {
                if ($(this).val().trim()) {
                    $('.email-message').text('').removeClass('show-message');
                }
            } 
            else if (fieldName === 'password') {
                if ($(this).val().trim()) {
                    $('.password-message').text('').removeClass('show-message');
                }
            }
        })

        $('#login-form').on('submit', function(event) {
            let email = $("input[name='email']").val().trim();
            let password = $('input[name="password"]').val().trim();
            let valid = true;

            // Validate email
            if(!email) {
                $('.email-message').text('This field is required.').addClass('show-message');
                valid = false;
            }

            // Validate password
            if(!password) {
                $('.password-message').text('This field is required.').addClass('show-message');
                valid = false;
            }

            if(!valid) {
                event.preventDefault();
            }
        })
    }

    function handleSweetAlert() {
        let loginStatus = $('#login-status').text().trim();
        if (loginStatus === 'incorrect') {
            Swal.fire({
                title: "Incorrect password!",
                // text: "Please enter a valid email address.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
        else if (loginStatus === 'not found') {
            Swal.fire({
                title: "Email Id not found!",
                // text: "Please try again with a different email address.",
                icon: "question",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }

        let resetStatus = $('#reset-status').text().trim();
        if(resetStatus === 'success') {
            Swal.fire({
                title: "Password reset successful!",
                text: "Please login with your new password to continue.",
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
    }

    handleLogin();
    handleSweetAlert();
})
