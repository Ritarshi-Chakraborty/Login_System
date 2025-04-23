<?php
    require_once '../../dbconnect.php';

    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../../public/login/login.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = $_SESSION['userCred'];
        $result_details = explode("\n", $_POST['result']);
    
        // Loop through each result entry
        foreach ($result_details as $value) {
            // Split subject and marks using the pipe character, and trim any spaces
            $result_parts = explode('|', $value);
            if (count($result_parts) == 2) {
                $subject = trim($result_parts[0]);
                $marks = trim($result_parts[1]);
    
                $check_query = "SELECT * FROM `user_result` WHERE `email` = ? AND `subject` = ?";
                $stmt_check = mysqli_prepare($conn, $check_query);
                mysqli_stmt_bind_param($stmt_check, "ss", $user, $subject); // 'ss' for two strings
                mysqli_stmt_execute($stmt_check);
                $check_result = mysqli_stmt_get_result($stmt_check);

                if (mysqli_num_rows($check_result) > 0) {
                    // Subject and marks exist for the user: update marks
                    $update_query = "UPDATE `user_result` SET `marks` = ? WHERE `email` = ? AND `subject` = ?";
                    $stmt_update = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($stmt_update, "sss", $marks, $user, $subject); // 'sss' for three strings
                    $result = mysqli_stmt_execute($stmt_update);
                } 
                else {
                    // Subject doesn't exist for this user: insert new result
                    $insert_query = "INSERT INTO `user_result` (`email`, `subject`, `marks`) VALUES (?, ?, ?)";
                    $stmt_insert = mysqli_prepare($conn, $insert_query);
                    mysqli_stmt_bind_param($stmt_insert, "sss", $user, $subject, $marks); // 'sss' for three strings
                    $result = mysqli_stmt_execute($stmt_insert);
                }
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
            <a href="../../public/logout.php" title="Logout">Logout</a>
        </div>
    </div>
</body>
</html>
