<?php
    require_once '../../dbconnect.php';
    require_once '../ValidateEmail.php';
    require_once './sendLink.php';

    session_start();
    if (isset($_SESSION['email_status'])) {
        echo "<div id='email-status'>".$_SESSION['email_status']."</div>";
        unset($_SESSION['email_status']); // Show once, then remove
    }

    $emailStatus = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $submitted_email = $_POST['email'];

        $email_response = new ValidateEmail($submitted_email);
        $emailStatus = $email_response->emailStatus();

        // If email is not valid, set session and redirect without inserting
        if ($emailStatus !== true && $emailStatus !== "") {
            $_SESSION['email_status'] = 'invalid';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Stop script here
        }
        else {
            $check_email = "SELECT * FROM `user_credentials` WHERE `email` = '$submitted_email'";
            $result = mysqli_query($conn, $check_email);

            if (mysqli_num_rows($result)) {
                $mail = new SendLink($submitted_email);
                $mail->resetLink();
                $_SESSION['reset_email'] = $submitted_email;
                $_SESSION['email_status'] = 'sent';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); // Stop script here
            }
            else {
                $_SESSION['email_status'] = 'not found';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit(); // Stop script here
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send reset link</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./resetPassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="sendlink-form">
            <h1>Confirm your Email!</h1>
            <label>Enter your registered Email Id<span>*</span></label>
            <input type="text" name="email">
            <div class="message-box">
                <p class="email-message"></p>
            </div>
            <button type="submit">Submit</button>
        </form>
        <div class="btn-wrapper">
            <a href="../login/login.php" title="Login">Login</a>
        </div>
    </div>
</body>
</html>
