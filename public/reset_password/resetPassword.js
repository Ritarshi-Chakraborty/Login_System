$(document).ready(function() {
    function handleSendLink() {
        $('input[name="email"]').on('input', function() {
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
        })

        $('#sendlink-form').on('submit', function(event) {
            let email = $("input[name='email']").val().trim();
            let valid = true;

            // Validate email
            if(!email) {
                $('.email-message').text('This field is required.').addClass('show-message');
                valid = false;
            }

            if(!valid) {
                event.preventDefault();
            }
        })
    }

    function handleResetPassword() {
        $('input[name="username"], input[name="password"], input[name="confirm-password"], input[name="email"]').on('input', function () {
            let fieldName = $(this).attr('name');
            let value = $(this).val();

            // Remove spaces from the value in real-time
            $(this).val(value.replace(/\s/g, ''));

            // Limit characters based on field
            if (fieldName === 'password') {
                if (value.length > 15) {
                    $(this).val(value.substring(0, 15));
                }
            }
            else if (fieldName === 'confirm-password') {
                if (value.length > 15) {
                    $(this).val(value.substring(0, 15));
                }
            }

            // Hide the message once the user starts typing
            if (fieldName === 'password') {
                if ($(this).val().trim()) {
                    $('.password-message').text('').removeClass('show-message');
                }
            }
            else if (fieldName === 'confirm-password') {
                if ($(this).val().trim()) {
                    $('.confirm-password-message').text('').removeClass('show-message');
                }
            }
        })

        $('#reset-form').on('submit', function(event) {
            let password = $('input[name="password"]').val().trim();
            let confirmPassword = $('input[name="confirm-password"]').val().trim();

            // Regular expression to allow numbers, "@", underscores and atleast one alphabet
            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[_@])[a-zA-Z0-9_@]+$/;

            // Validate password
            if (!password) {
                $('.password-message').text('This field is required.').addClass('show-message');
                valid = false;
            } 
            else if (password.length < 6) {
                $('.password-message').text('Minimum length must be 6.').addClass('show-message');
                valid = false;
            }
            else if (!passwordRegex.test(password)) {
                $('.password-message').text('Password should contain atleast one special character (underscore or "@"), atleast one alphabet and atleast one numeric value.').addClass('show-message');
                valid = false;
            } else {
                $('.password-message').removeClass('show-message');
            }

            // Validate confirm password
            if (!confirmPassword) {
                $('.confirm-password-message').text('This field is required.').addClass('show-message');
                valid = false;
            }
            else if(confirmPassword != password) {
                $('.confirm-password-message').text("Password doesn't match.").addClass('show-message');
                valid = false;
            }


            // If anything is wrong, don't submit
            if (!valid) {
                event.preventDefault();
            }
        })
    }


    function handleSweetAlert() {
        let emailStatus = $('#email-status').text().trim();
        if (emailStatus === 'not found') {
            Swal.fire({
                title: "User not found!",
                // text: "Please enter a valid email address.",
                icon: "question",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
        else if (emailStatus === 'invalid') {
            Swal.fire({
                title: "Invalid email id!",
                // text: "Please try again with a different email address.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
        else if (emailStatus === 'sent') {
            Swal.fire({
                title: "Password reset link sent to your email!",
                // text: "Please try again with a different email address.",
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        } 

        let resetStatus = $('reset-status').text().trim();
        if(resetStatus === 'error') {
            Swal.fire({
                title: "Server error!",
                text: "Please try again.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
    }

    
    handleSendLink();
    handleResetPassword();
    handleSweetAlert();
})
