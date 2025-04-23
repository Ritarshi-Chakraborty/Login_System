<?php
    require_once '../../dbconnect.php';

    session_start();

    if (isset($_SESSION['login_status'])) {
        echo "<div id='login-status'>".$_SESSION['login_status']."</div>";
        unset($_SESSION['login_status']); // Show once, then remove
    }

    if (isset($_SESSION['reset_status'])) {
        echo "<div id='reset-status'>".$_SESSION['reset_status']."</div>";
        unset($_SESSION['reset_status']); // Show once, then remove
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $submitted_email = $_POST['email'];
        $submitted_password = $_POST['password'];

        // Use prepared statement to check if the email exists
        $check_email_query = "SELECT * FROM `user_credentials` WHERE `email` = ?";
        $stmt = mysqli_prepare($conn, $check_email_query);
        mysqli_stmt_bind_param($stmt, "s", $submitted_email);  // 's' denotes string
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            $row  = mysqli_fetch_assoc($result);

            if(password_verify($submitted_password, $row["password"])) {
                $_SESSION['userCred'] = $submitted_email;
                header("location: ../../navigation.php?q=1");
                exit();
            } 
            else {
                $_SESSION['login_status'] = 'incorrect';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        else {
            $_SESSION['login_status'] = 'not found';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="login-form">
            <h1>Login!</h1>
            <label>Enter your Email Id<span>*</span></label>
            <input type="text" name="email">
            <div class="message-box">
                <p class="email-message"></p>
            </div>
            <label>Enter your Password<span>*</span></label>
            <input type="password" name="password">
            <div class="message-box">
                <p class="password-message"></p>
            </div>
            <button type="submit">Submit</button>
        </form>
        <div class="btn-wrapper">
            <a href="../signup/signup.php" title="Signup">Signup</a>
            <a href="../reset_password/email.php" title="Forgot pasword">Forgot password</a>
        </div>
    </div>
</body>
</html>
