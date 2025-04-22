<?php
    require_once '../../dbconnect.php';

    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../../public/login/login.php");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_SESSION['userCred'];
        $full_name = $_POST['first_name']." ".$_POST['last_name'];

        // Check if the user already has a full name
        $check_query = "SELECT `fullname` FROM `user_credentials` WHERE `email` = '$user'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // User exists: update fullname
            $update_query = "UPDATE `user_credentials` SET `fullname` = '$full_name' WHERE `email` = '$user'";
            $result = mysqli_query($conn, $update_query);
        } 
        else {
            // User not found: insert fullname
            $insert_query = "INSERT INTO `user_credentials` (`fullname`) VALUES ('$full_name') WHERE `email` = '$user'";
            $result = mysqli_query($conn, $insert_query);
        }

        if ($result) {
            header("location: ../../navigation.php?q=2");
            exit();
        }
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fullname</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-throttle-debounce@1.1/jquery.ba-throttle-debounce.min.js"></script>
    <script src="./name.js"></script>
</head>
<body>
    <div class="container">
        <form action="" id="name-form" method="post" enctype="multipart/form-data"> 
            <h1>Full Name</h1>
            <label>Enter your First name<span>*</span></label>
            <input type="text" name="first_name">
            <div class="message-box">
                <p class="firstname-message">This field is required.</p>
            </div>
            <label>Enter your Last name<span>*</span></label>
            <input type="text" name="last_name">
            <div class="message-box">
                <p class="lastname-message">This field is required.</p>
            </div>
            <label>Your Fullname</label>
            <input type="text" name="full_name" disabled required>
            <button type="submit">Next</button>
        </form>
        <div class="btn-wrapper">
            <a href="../../public/logout.php" title="Logout">Logout</a>
        </div>
    </div>
</body>
</html>
