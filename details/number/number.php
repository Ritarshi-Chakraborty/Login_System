<?php
    require_once '../../dbconnect.php';
    
    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../../login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_SESSION['userCred'];
        $mobile_number = "+91" . $_POST['number'];
    
        // Use prepared statement to check if the user exists
        $check_query = "SELECT `fullname` FROM `user_credentials` WHERE `email` = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $user);  // 's' denotes a string (email)
        mysqli_stmt_execute($stmt_check);
        $check_result = mysqli_stmt_get_result($stmt_check);
    
        if (mysqli_num_rows($check_result) > 0) {
            // User exists: update phone number
            $update_query = "UPDATE `user_credentials` SET `phone_number` = ? WHERE `email` = ?";
            $stmt_update = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt_update, "ss", $mobile_number, $user);  // 'ss' for two strings
            $result = mysqli_stmt_execute($stmt_update);
        } 
        else {
            // User not found: insert phone number
            $insert_query = "UPDATE `user_credentials` SET `phone_number` = ? WHERE `email` = ?";
            $stmt_insert = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt_insert, "ss", $mobile_number, $user);  // 'ss' for two strings
            $result = mysqli_stmt_execute($stmt_insert);
        }
    
        if ($result) {
            header("location: ../../response/response.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone number</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./number.js"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="number-form" enctype="multipart/form-data">
            <h1>Phone number</h1>
            <label>Enter your mobile number<span>*</span></label>
            <input type="text" name="number">
            <div class="message-box">
                <p>Please enter a 10-digit Indian number starting with either 6, 7, 8 or 9.</p>
            </div>
            <button type="submit">Submit</button>
        </form>
        <div class="btn-wrapper">
            <a href="../../public/logout.php" title="Logout">Logout</a>
        </div>
    </div>
</body>
</html>
