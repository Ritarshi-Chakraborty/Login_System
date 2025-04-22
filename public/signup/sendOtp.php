<?php
    require_once '../../dbconnect.php';
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
        $email = $_POST["email"];
        //check whether the usr name exists.
        $exists_sql = "select * from `user_credentials` where `email` = '$email'";
        $result = mysqli_query($conn, $exists_sql);
        if (mysqli_num_rows($result) > 0) {
            echo json_encode(["status" => "exist"]);
            exit;
        }
        $otp = rand(100000, 999999);
        $_SESSION["signup_otp"] = $otp;
        $_SESSION["signup_email"] = $email;
        include "./ValidateEmail.php";
        $validator = new ValidateEmail($email);
        if ($validator->returnMail() === $email) {
            $validator->sendOTP($otp);
            echo json_encode(["status" => "sent"]);
            exit;
        }
    }
    echo json_encode(["status" => "error"]);
?>
