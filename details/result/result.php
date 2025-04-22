<?php
    require_once '../../dbconnect.php';

    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../../public/login/login.php");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_SESSION['userCred'];
        $result_details = explode("\n", $_POST['result']);

        foreach($result_details as $value) {
            $subject = trim(explode('|', $value)[0]);
            $marks = trim(explode('|', $value)[1]);

            // Check if the user already has a full name
            $check_email = "SELECT * FROM `user_result` WHERE `email` = '$user'";
            $check_subject = "SELECT * FROM `user_result` WHERE `email` = '$user' AND `subject` = '$subject'";
            $check_result = mysqli_query($conn, $check_subject);

            if(mysqli_num_rows($check_result) > 0) {
                // Subject and marks exist for that user
                $update_query = "UPDATE `user_result` SET `marks` = '$marks' WHERE `email` = '$user' AND `subject` = '$subject'";
                $result = mysqli_query($conn, $update_query);
            }
            else {
                // User not found: insert fullname
                $insert_query = "INSERT INTO `user_result` (`email`, `subject`, `marks`) VALUES ('$user', '$subject', '$marks')";
                $result = mysqli_query($conn, $insert_query);
            }
        }

        header("location: ../../navigation.php?q=4");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./result.js"></script>
</head>
<body>
    <div class="container">
        <form action="" method="post" id="result-form" enctype="multipart/form-data">
            <h1>Result</h1>
            <label>Enter your Subjects and Marks<span>*</span></label>
            <textarea placeholder="Please use the following format: English|80" name="result"></textarea>
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
