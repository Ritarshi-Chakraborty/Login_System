$(document).ready(function() {
    // Function to handle the signup form
    function handleSignup() {
        $('input[name="username"], input[name="password"], input[name="confirm-password"], input[name="email"]').on('input', function () {
            let fieldName = $(this).attr('name');
            let value = $(this).val();

            // Remove spaces from the value in real-time
            $(this).val(value.replace(/\s/g, ''));

            // Limit characters based on field
            if (fieldName === 'username') {
                if (value.length > 20) {
                    $(this).val(value.slice(0,20));
                }
            } 
            else if (fieldName === 'password') {
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
            if (fieldName === 'username') {
                if ($(this).val().trim()) {
                    $('.username-message').text('').removeClass('show-message');
                }
            } 
            else if (fieldName === 'email') {
                if ($(this).val().trim()) {
                    $('.email-message').text('').removeClass('show-message');
                }
            }
            else if (fieldName === 'password') {
                if ($(this).val().trim()) {
                    $('.password-message').text('').removeClass('show-message');
                }
            }
            else if (fieldName === 'confirm-password') {
                if ($(this).val().trim()) {
                    $('.confirm-password-message').text('').removeClass('show-message');
                }
            }
        });

        $('#signup-form').on('submit', function(event) {
            let username = $('input[name="username"]').val().trim();
            let email = $("input[name='email']").val().trim();
            let password = $('input[name="password"]').val().trim();
            let confirmPassword = $('input[name="confirm-password"]').val().trim();
            let valid = true;

            // Regular expression to allow numbers, "@", underscores and atleast one alphabet
            const userRegex = /^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z0-9_@]+$/;
            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[_@])[a-zA-Z0-9_@]+$/;

            // Validate username
            if (!username) {
                $('.username-message').text('This field is required.').addClass('show-message');
                valid = false;
            }
            else if (username.length < 5) {
                $('.username-message').text('Minimum length must be 5.').addClass('show-message');
                valid = false;
            }
            else if (!userRegex.test(username)) {
                $('.username-message').text('Username should only contain underscores, "@", numeric values and atleast two alphabets.').addClass('show-message');
                valid = false;
            } else {
                $('.username-message').removeClass('show-message');
            }

            // Validate email
            if(!email) {
                $('.email-message').text('This field is required.').addClass('show-message');
                valid = false;
            }
            
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

    // Function to handle the sweet alerts
    function handleSweetAlert() {
        let signupStatus = $('#signup-status').text().trim();
        if (signupStatus === 'invalid') {
            Swal.fire({
                title: "Invalid Email Id!",
                text: "Please enter a valid email address.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
        else if (signupStatus === 'duplicate') {
            Swal.fire({
                title: "Email Id already exists!",
                text: "Please try again with a different email address.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        } else if (signupStatus === 'success') {
            Swal.fire({
                title: "Registration successful!",
                text: "Please login to continue.",
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
        }
    }

    handleSignup();
    handleSweetAlert();
})

//function to send the otp
function sendOTP() {
    const email = document.getElementById("user_email").value.trim();
    const otpInput = document.getElementById("otp");
    const verifyBtn = document.getElementById("verify-otp-btn");
    if (!email) {
    document.getElementById("user_email_err").textContent = "Enter your email first.";
    document.getElementById("user_email_err").style.display = "block";
    return;
    }
    fetch("sendOtp.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `email=${encodeURIComponent(email)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "sent") {
        Swal.fire("Success!", "OTP sent to your email!", "success");
        otpInput.disabled = false;
        verifyBtn.disabled = false;
        } else if(data.status === "exist") {
        Swal.fire("Oops!", "Failed to send OTP. This email already exists.", "error");
        }
        else {
        Swal.fire("Oops!", "Failed to send OTP. Please insert correct email.", "error");
        }
    });
}

// function to verify the otp
function verifyOtp() {
    const otp = document.getElementById("otp").value.trim();
    if (!otp) {
      document.getElementById("otp_err").textContent = "Enter OTP first.";
      document.getElementById("otp_err").style.display = "block";
      return;
    }
    fetch("verifyOtp.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `otp=${encodeURIComponent(otp)}`
    })
      .then(res => res.json())
      .then(data => {
        alert(data.status)
        if (data.status === "verified") {
          Swal.fire("Verified!", "OTP verified successfully.", "success");
          document.getElementById("submit-btn").disabled = false;
          document.getElementById("verify-otp-btn").disabled = true;
          document.getElementById("otp").disabled = true;
        } else {
          document.getElementById("otp_err").textContent = "Invalid OTP. Try again.";
          document.getElementById("otp_err").style.display = "block";
        }
      });
}
