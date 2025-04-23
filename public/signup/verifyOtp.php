<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully']);
    } 
    else {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect OTP']);
    }
}
?>
