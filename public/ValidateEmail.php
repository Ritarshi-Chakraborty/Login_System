<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    // Load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../');
    $dotenv->load();

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
            // Sets the email and constructs the URL for the API request.
            $this->email = $email;
            $this->api_key = $_ENV['APIKEY'];
            $this->url = "http://apilayer.net/api/check?access_key=$this->api_key&email=$email";
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
            $response = file_get_contents($this->url);
            $data = json_decode($response, true);

            // If the email format is valid, proceed to check the existence of the email.
            if ($data['format_valid']) {
                if ($data['smtp_check']) {
                    return true;
                } 
                else {
                    return "This email id does not exist.";
                }
            } 
            else {
                return "Invalid syntax for Email Id.";
            }
        }
    }
?>
