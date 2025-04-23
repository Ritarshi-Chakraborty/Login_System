<?php
require_once '../../dbconnect.php';
require_once '../ValidateEmail.php';

session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email first
    $email_response = new ValidateEmail($email);
    $emailStatus = $email_response->emailStatus();

    // If email is not valid, send the error response
    if ($emailStatus !== true && $emailStatus !== "") {
        echo json_encode(['status' => 'error', 'message' => $emailStatus]);
        exit();
    }

    // Check if email exists in the database
    $sql = "SELECT * FROM user_credentials WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email exists in the database, return an error response
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This email Id has already been registered.']);
        exit();
    }

    // Generate a random OTP (6 digits)
    $otp = rand(100000, 999999);

    // Save the OTP in the session to verify it later
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;

    // Send OTP to the user's email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL'];
        $mail->Password = $_ENV['PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom($_ENV['EMAIL'], $_ENV['NAME']);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'OTP for email verification';
        $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>";

        $mail->send();

        echo json_encode(['status' => 'success', 'message' => 'OTP has been sent to your email address']);
    } 
    catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    }
}
?>
