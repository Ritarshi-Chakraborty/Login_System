<?php
    // 

    /**
     * Class to validate the email format and check if the email exists using an external API.
     * This class communicates with the email validation service and performs necessary checks.
     */
    class ValidateEmail {
        
        /**
         * @var string $email The email address to be validated.
         */
        protected $email;

        /**
         * @var string $url The URL for the email validation API request.
         */
        protected $url;

        /**
         * @var string $api_key The API key to access the validation service.
         */
        protected $api_key;

        /**
         * Constructor to initialize the email and prepare the API request URL.
         *
         * @param string $email The email address to be validated.
         */
        function __construct($email) {
            /**
             * Sets the email and constructs the URL for the API request.
             */
            $this->email = $email;
            $this->api_key = $_ENV['APIKEY'];
            // $this->url = "http://apilayer.net/api/check?access_key=$this->api_key&email=$email";
        }

        /**
         * Method to check the validity of the email address.
         * The method sends a request to the API to check the format and existence of the email.
         * If valid, the email is stored in the session; otherwise, an error message is set.
         *
         * @return void
         */
        function emailStatus() {
            // Sends a request to the API and checks the response for email validity.
            // $response = file_get_contents($this->url);
            // $data = json_decode($response, true);

            // // If the email format is valid, proceed to check the existence of the email.
            // if ($data['format_valid']) {
            //     // If SMTP check is successful, store the email in the session and redirect to the response page.
            //     if ($data['smtp_check']) {
            //         return true;
            //     } 
            //     else {
            //         // $_SESSION['email_error'] = "This email id does not exist.";
            //         return "This email id does not exist.";
            //     }
            // } 
            // else {
            //     // $_SESSION['email_error'] = "Incorrect syntax for Email Id.";
            //     return "Invalid syntax for Email Id.";
            // }
            return true;
        }

        function sendOTP($otp) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->SMTPAuth   = true;
                $mail->Host       = 'smtp.gmail.com';
                $mail->Username   = 'priyansubhattacharya962003@gmail.com';
                $mail->Password   = $_ENV['app_pass'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->setFrom('priyansubhattacharya962003@gmail.com', 'Admin');
                $mail->addAddress($this->email);
                $mail->isHTML(true);
                $mail->Subject = 'Your Signup OTP';
                $mail->Body    = "Your OTP is: <strong>$otp</strong>";
                $mail->send();
            } 
            catch (Exception $e) {
                // Log error if needed
            }
        }
    }
?>
