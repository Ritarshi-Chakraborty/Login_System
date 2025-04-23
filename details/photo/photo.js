$(document).ready(function() {
    /**
     * Validate the selected image file during form submission and image selection.
     * This function checks whether an image file is selected, and if so, it validates the file size to ensure it's less than 1MB. It     also displays appropriate messages in case of validation failure.
     */
    function validateImage() {
        let fileInput = $('input[name="image"]')[0];
        let messageBox = $('.message-box p');

        /**
         * Event listener for the form submission.
         * 
         * This listener ensures that the form cannot be submitted if no image is selected or if the selected image exceeds the size limit (1MB).
         * 
         * @param {Event} e The form submit event
         */
        $('#image-form').on('submit', function (e) {
            let file = fileInput.files[0];

            /**
             * If no file is selected, prevent form submission and show error message
             */
            if (!file) {
                e.preventDefault();
                messageBox.text("Please select an image.").css('display', 'block');
            } 
            /**
             * If the file size exceeds 1MB (1048576 bytes), prevent form submission and show error message
             */
            else if (file.size > 1048576) { 
                e.preventDefault();
                messageBox.text("File size is too large. Please select images which are less than 1mb.").css('display', 'block');
            } 
            else {
                messageBox.css('display', 'none');
            }
        });

        /**
         * Event listener for the image file input change event.
         * 
         * This listener hides the error message when the user selects a file, as long as the file is valid. It ensures that no error message is displayed when a file is selected.
         * 
         * @param {Event} e The change event triggered when a file is selected
         */
        $('input[name="image"]').on('change', function () {
            /**
             * If a file is selected, hide the message
             */
            if (fileInput.files && fileInput.files.length > 0) {
                messageBox.css('display', 'none');
            }
        });
    }

    validateImage();
});
