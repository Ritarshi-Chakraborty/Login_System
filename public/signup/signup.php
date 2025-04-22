<?php
    require_once '../../dbconnect.php';
    require_once '../ValidateEmail.php';

    session_start();

    if (isset($_SESSION['signup_status'])) {
        echo "<div id='signup-status'>".$_SESSION['signup_status']."</div>";
        unset($_SESSION['signup_status']); // Show once, then remove
    }

    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the submitted username and password
        $submitted_username = $_POST['username'];
        $submitted_password = $_POST['password'];
        $submitted_email = $_POST['email'];

        $email_response = new ValidateEmail($submitted_email);
        $emailStatus = $email_response->emailStatus();

        // If email is not valid, set session and redirect without inserting
        if ($emailStatus !== true && $emailStatus !== "") {
            $_SESSION['signup_status'] = 'invalid';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Stop script here
        }

        // Check if the username already exists
        $check_duplicate = "SELECT * FROM `user_credentials` WHERE `email` = '$submitted_email'";
        $result = mysqli_query($conn, $check_duplicate);

        if(mysqli_num_rows($result)>0) {
            // $_SESSION['duplicate_user'] = true;
            $_SESSION['signup_status'] = 'duplicate';
        } 
        else {
            $insert_query = "INSERT INTO `user_credentials` (`user_name`, `email`, `password`) VALUES ('$submitted_username', '$submitted_email', '$submitted_password')";
            mysqli_query($conn, $insert_query);
            $_SESSION['signup_status'] = 'success';
        }

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="signup-form">
            <h1>Signup!</h1>
            <label>Enter your Username<span>*</span></label>
            <input type="text" name="username">
            <div class="message-box">
                <p class="username-message"></p>
            </div>
            <label>Enter your Email Id<span>*</span></label>
            <input type="text" name="email">
            <div class="message-box">
                <p class="email-message"></p>
            </div>

            <button type="button" onclick="sendOTP()" id="send-otp-btn">Send OTP</button>
            <div class="err-msg">
                <p id="user_email_err"></p>
            </div>
            <label for="otp">Enter OTP</label>
            <div class="btn-wrap">
                <input type="text" id="otp" name="otp" class="wrap-inp" placeholder="Enter the OTP you received">
                <button type="button" id="verify-otp-btn" onclick="verifyOtp()">Verify OTP</button>
            </div>
            <div class="err-msg">
                <p id="otp_err"></p>
            </div>

            <label>Enter your Password<span>*</span></label>
            <input type="text" name="password">
            <div class="message-box">
                <p class="password-message"></p>
            </div>
            <label>Confirm your Password<span>*</span></label>
            <input type="text" name="confirm-password">
            <div class="message-box">
                <p class="confirm-password-message"></p>
            </div>
            <button type="submit">Submit</button>
        </form>
        <div class="btn-wrapper">
            <a href="../login/login.php" title="Login">Login</a>
        </div>
    </div>
</body>
</html>
