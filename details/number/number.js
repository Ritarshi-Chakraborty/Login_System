$(document).ready(function() {
    /**
     * Function to validate the phone number input field.
     * Ensures the number is in the valid format for Indian phone numbers (starting with 6-9 and followed by 9 digits).
     */
    function validateNumber() {
        let input = $('input[name="number"]');
        let message = $('.message-box p');

        /**
         * Function to check if the given number matches the required format.
         * The format should be a 10-digit number starting with a digit between 6 and 9.
         * 
         * @param {string} number - The phone number to check.
         * @returns {boolean} - Returns true if the number matches the pattern, false otherwise.
         */
        function checkNumber(number) {
            return /^[6-9]\d{9}$/.test(number);
        }

        /**
         * Event listener for the 'input' event to handle the user typing in the input field.
         * 
         * It removes non-numeric characters and ensures the number length doesn't exceed 10 digits.
         * Also hides the error message while typing.
         */
        input.on('input', function () {
            let number = $(this).val().replace(/[^0-9]/g, '').slice(0, 10);
            $(this).val(number);
            message.hide();
        });

        /**
         * Event listener for the 'paste' event to handle when the user pastes something into the input field.
         * It removes non-numeric characters and ensures the number length doesn't exceed 10 digits.
         * Also hides the error message when pasting.
         */
        input.on('paste', function () {
            let inputEl = $(this);
            let number = $(this).val().replace(/[^0-9]/g, '').slice(0, 10);
            inputEl.val(number);
            message.hide()
        });

        /**
         * Event listener for form submission to validate the phone number.
         * It checks if the phone number matches the required format.
         * 
         * @param {Event} e - The form submission event.
         */
        $('#number-form').on('submit', function(e) {
            let number = input.val();
            if (!checkNumber(number)) {
                e.preventDefault();
                message.show();
            }
        });
    }

    validateNumber();
})
