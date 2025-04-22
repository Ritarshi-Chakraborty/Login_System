$(document).ready(function() {
    /**
     * Function to validate the textarea input
     * It handles both real-time input cleaning and form submission validation.
     */
    function validateTextarea() {
        let message = $('.message-box p');

        /**
         * Event listener for handling user input in the textarea.
         * It cleans the input by removing unwanted characters.
         */
        $('textarea').on('input', function () {
            let cleanedLines = [];
            let lines = $(this).val().split('\n');

            /**
             * Loop through each line to clean it:
             * 1. Cleans the subject part (only letters and spaces)
             * 2. Cleans the marks part (only numbers)
             */
            for (let line of lines) {
                let parts = line.split('|');
                let subject = parts[0].replace(/[^A-Za-z]/g, '').substring(0,18);
                let marks = parts[1] ? parts[1].replace(/[^0-9]/g, '') : '';

                /**
                 * Reconstruct the line
                 */
                let cleanedLine = subject;
                if (line.includes('|')) {
                    cleanedLine += '|' + marks;
                }

                cleanedLines.push(cleanedLine);
            }

            /**
             * Update the textarea with cleaned input & hide the message when the user is typing
             */
            $(this).val(cleanedLines.join('\n'));
            message.hide();
        });

        /**
         * Event listener for form submission to validate the input.
         * It checks for empty input, correct format, and marks within valid range.
         */
        $('#result-form').on('submit', function(e) {
            let isValid = true;
            let value = $('textarea').val();

            /**
             * Check if the textarea is empty
             */
            if (value === '') {
                isValid = false;
                message.text("This field is required.");
            } 
            else {
                let lines = value.split('\n');
                let regex = /^[A-Za-z]+\|[0-9]+$/;

                /**
                 * Loop through each line to:
                 * 1. Ensure it's not empty.
                 * 2. Check if it matches the required format (Subject|Marks).
                 * 3. Ensure marks do not exceed 100.
                 */
                for (let i = 0; i < lines.length; i++) {
                    let line = lines[i];

                    /**
                     * Check if the line is completely empty
                     */
                    if (line === '') {
                        isValid = false;
                        message.text(`Line ${i + 1} is empty.`);
                        break;
                    }

                    /**
                     * Check format
                     */
                    if (!regex.test(line)) {
                        isValid = false;
                        message.text(`Invalid format in line ${i+1}. Please use the following format: Subject|Marks`);
                        break;
                    }

                    /**
                     * Check marks > 100
                     */
                    let parts = line.split('|');
                    let marks = parseInt(parts[1]);
                    if (marks > 100) {
                        isValid = false;
                        message.text(`Error on line ${i + 1}. Maximum marks can be 100.`);
                        break;
                    }
                }
            }

            /**
             * If not valid, prevent form submission and show the error message
             */
            if (!isValid) {
                e.preventDefault();
                $('.message-box p').show();
            }
        });
    }

    validateTextarea();
})
