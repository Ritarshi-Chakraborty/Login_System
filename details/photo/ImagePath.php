<?php
    /**
     * Class to handle the image upload and return the file path.
     * This class moves the uploaded file to a target directory and stores the path.
     */
    class ImagePath {       
        /**
         * @var string $targetFile Path where the uploaded file will be stored
         */
        protected $targetFile;

        /**
         * Constructor that handles the file upload process
         *
         * @param string $targetDir The directory where the uploaded image will be saved
         * @param array $userImg The $_FILES array containing the uploaded image data
         */
        function __construct($targetDir, $userImg) {
            $this->targetFile = $targetDir . basename($userImg["name"]);
            move_uploaded_file($userImg["tmp_name"], $this->targetFile);
        }

        /**
         * Method to return the path of the uploaded image
         *
         * @return string The file path of the uploaded image
         */
        function returnPath() {
            return $this->targetFile;
        }
    }
?>
