<?php
    require ('../../vendor/autoload.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require_once __DIR__ . '/../../vendor/autoload.php';

    // Load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
    $dotenv->load();

    class SendLink {
        /**
         * Undocumented variable
         *
         * @var string 
         */
        protected $userEmail;

        /**
         * Constructor that sets the user email
         *
         * @param string
         */
        function __construct($mail) {
            $this->userEmail = $mail;
        }

        /**
         * Function to send the reset link to the email
         *
         * @return void
         */
        function resetLink() {
            $mail = new PHPMailer();
            try {
                /**
                 * Server settings
                 */
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['EMAIL'];
                $mail->Password   = $_ENV['PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                /**
                 * Recipients
                 */
                $mail->setFrom($_ENV['EMAIL'], $_ENV['NAME']);
                $mail->addAddress($this->userEmail);
                /**
                 * Content
                 */
                $mail->isHTML(true);
                $resetLink = "http://www.loginsystem.com/public/reset_password/reset.php";
                $mail->Subject = 'Password reset link';
                $mail->Body = "<h2>Here is the link to reset your password!</h2>
                <a href='$resetLink'>Reset Link</a>";
                
                /**
                 * Send the mail
                 */
                $mail->send();
            }
            catch (Exception $e) {

            }
        }
    }
?>
