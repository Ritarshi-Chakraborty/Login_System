$(document).ready(function() {
  // Flag to track OTP verification status
  let otpVerified = false;

  /**
   * Function to handle the signup form
   */
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

      $('input[name="otp"]').on('input', function() {
          let value = $(this).val().replace(/[^0-9]/g, '');
          $(this).val(value);           
      })

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

          // Check if OTP is verified before submitting
          if (!otpVerified && valid) {
              Swal.fire({
                  title: "Please verify your email to complete registration!",
                  icon: "warning",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: true,
                  backdrop: true
              });
              valid = false;
          }

          // If anything is wrong, don't submit
          if (!valid) {
              event.preventDefault();
          }

      })
  }

  /**
   * Function to handle the sweet alerts
   */
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

  /**
   * Function to send otp
   */
  function sendOTP() {
      $('#sendOtp-btn').on('click', function() {
          let userEmail = $('input[name="email"]').val();

          // Validate email field first
          if (userEmail === "") {
              Swal.fire({
                  title: "Please enter your Email Id!",
                  icon: "warning",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: true,
                  backdrop: true
              });
              return;
          }
          /**
           * Send AJAX request to sendOtp.php
           */
          $.ajax({
              url: 'sendOtp.php',
              type: 'POST',
              data: { email: userEmail },
              success: function(response) {
                  const responseObj = JSON.parse(response);
                  // Handle response here
                  if(responseObj.status === 'success') {
                      Swal.fire({
                          title: "OTP has been sent to your email address!",
                          icon: "success",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: true,
                          backdrop: true
                      });
                  } 
                  else  {
                      Swal.fire({
                          title: "Error!",
                          text: responseObj.message,
                          icon: "error",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: true,
                          backdrop: true
                      });
                  }
              },
              error: function() {
                  Swal.fire({
                      title: "Error!",
                      text: "An error occurred while sending the OTP. Please try again later.",
                      icon: "error",
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      allowEnterKey: true,
                      backdrop: true
                  });
              }
          });
      })
  }

  /**
   * Function to verify otp
   */
  function verifyOTP() {
      $('#verifyOtp-btn').on('click', function() {
          let otpEntered = $('input[name="otp"]').val();
          // Validate otp field first
          if (otpEntered === "") {
            Swal.fire({
                title: "Please enter the otp!",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                backdrop: true
            });
            return;
        }
          /**
           * Check if OTP matches the one saved in the session
           */
          $.ajax({
              url: 'verifyOtp.php',
              type: 'POST',
              data: { otp: otpEntered },
              success: function(response) {
                    console.log(response)
                  const responseObj = JSON.parse(response);
                  if (responseObj.status === 'success') {
                        // Set OTP as verified
                        otpVerified = true;
                        Swal.fire({
                          title: "OTP verified successfully!",
                          icon: "success",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: true,
                          backdrop: true
                      });
                  } 
                  else {
                      Swal.fire({
                          title: "Error!",
                          text: responseObj.message,
                          icon: "error",
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          allowEnterKey: true,
                          backdrop: true
                      });
                  }
              }
          });
      });
  }


  handleSignup();
  handleSweetAlert();
  sendOTP();
  verifyOTP();
})
