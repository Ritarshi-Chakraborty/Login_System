<?php
    require_once '../../dbconnect.php';
    require_once 'ImagePath.php';

    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../../public/login/login.php");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_SESSION['userCred'];
        $uploaded_image = new ImagePath("/var/www/Login_System/images/", $_FILES['image']);
        $image = '../images/'. basename($uploaded_image->returnPath());

        // Check if the user already has a full name
        $check_query = "SELECT `photo` FROM `user_credentials` WHERE `email` = '$user'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // User exists: update fullname
            $update_query = "UPDATE `user_credentials` SET `photo` = '$image' WHERE `email` = '$user'";
            $result = mysqli_query($conn, $update_query);
        } 
        else {
            // User not found: insert fullname
            $insert_query = "INSERT INTO `user_credentials` (`photo`) VALUES ('$image') WHERE `email` = '$user'";
            $result = mysqli_query($conn, $insert_query);
        }

        if ($result) {
            header("location: ../../navigation.php?q=3");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./photo.js"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="image-form" enctype="multipart/form-data">
            <h1>Photo</h1>
            <label>Upload an Image<span>*</span></label>
            <input class="picture-input" type="file" name="image" accept="image/*">
            <div class="message-box">
                <p></p>
            </div>
            <button type="submit">Next</button>
        </form>
        <div class="btn-wrapper">
            <a href=".../../public/logout.php" title="Logout">Logout</a>
        </div>
    </div>
</body>
</html>
