<?php
    require_once '../../dbconnect.php';
    session_start();

    if(!isset($_SESSION['reset_email'])) {
        header('location: ../login/login.php');
        exit();
    }

    if (isset($_SESSION['reset_status'])) {
        echo "<div id='reset-status'>".$_SESSION['reset_status']."</div>";
        unset($_SESSION['reset_status']); // Show once, then remove
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the new password
        $new_password = $_POST['password'];
        $email = $_SESSION['reset_email'];
    
        // Hash the new password before storing it
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
        // Use prepared statement to safely update the password
        $change_password_query = "UPDATE `user_credentials` SET `password` = ? WHERE `email` = ?";
        $stmt = mysqli_prepare($conn, $change_password_query);
        
        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email); // 'ss' denotes two strings
    
        // Execute the prepared statement
        $result = mysqli_stmt_execute($stmt);
    
        // Check the result of the query
        if ($result) {
            // Successfully changed the password
            $_SESSION['reset_status'] = 'success';
            unset($_SESSION['reset_email']);
            header('location: ../login/login.php');
            exit();
        } 
        else {
            // There was an error in updating the password
            $_SESSION['reset_status'] = 'error';
            unset($_SESSION['reset_status']);
            header('location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./resetPassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="reset-form">
            <h1>Reset Password!</h1>
            <label>Enter your new password<span>*</span></label>
            <input type="password" name="password">
            <div class="message-box">
                <p class="password-message"></p>
            </div>
            <label>Confirm your new password<span>*</span></label>
            <input type="password" name="confirm-password">
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
