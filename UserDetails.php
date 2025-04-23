<?php
    require_once 'dbconnect.php';

    /**
     * Class UserDetails
     *
     * This class retrieves details of a user from the database.
     * It fetches the user's name, email, phone number, photo, and their exam results.
     */
    class UserDetails {
        /**
         * @var string $email The email of the user.
         */
        protected $email;

        /**
         * @var mysqli $conn The database connection object.
         */
        protected $conn;

        /**
         * UserDetails constructor.
         *
         * @param string $user The user's email.
         * @param mysqli $conn The database connection object.
         */
        function __construct($user, $conn) {
            $this->email = $user;
            $this->conn = $conn;
        }

        /**
         * Fetch the full name of the user.
         *
         * @return string The user's full name.
         */
        function getName() {
            $sql = "SELECT `fullname` FROM `user_credentials` WHERE `email` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $this->email); // 's' indicates that the parameter is a string
            $stmt->execute();
            $stmt->bind_result($fullname);
            $stmt->fetch();
            $stmt->close();
            return $fullname;
        }

        /**
         * Get the user's email.
         *
         * @return string The user's email.
         */
        function getEmail() {
            return $this->email;
        }

        /**
         * Fetch the user's phone number.
         *
         * @return string The user's phone number.
         */
        function getNumber() {
            $sql = "SELECT `phone_number` FROM `user_credentials` WHERE `email` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $this->email); // 's' indicates that the parameter is a string
            $stmt->execute();
            $stmt->bind_result($phone_number);
            $stmt->fetch();
            $stmt->close();
            return $phone_number;
        }

        /**
         * Fetch the user's photo.
         *
         * @return string The user's photo URL or path.
         */
        function getImage() {
            $sql = "SELECT `photo` FROM `user_credentials` WHERE `email` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $this->email); // 's' indicates that the parameter is a string
            $stmt->execute();
            $stmt->bind_result($photo);
            $stmt->fetch();
            $stmt->close();
            return $photo;
        }

        /**
         * Fetch the user's exam results.
         *
         * @return string The HTML table containing the user's results.
         */
        function getResult() {
            $sql = "SELECT * FROM `user_result` WHERE `email` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $this->email); // 's' indicates that the parameter is a string
            $stmt->execute();
            $result = $stmt->get_result();
            
            $table = '<table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>';

            // Loop through each result and create a table row
            while ($row = $result->fetch_assoc()) {
                $subject = htmlspecialchars($row['subject']);
                $marks = htmlspecialchars($row['marks']);
                // Append each row to the table
                $table .= "<tr>
                            <td>$subject</td>
                            <td>$marks</td>
                        </tr>";
            }
            // Close the table
            $table .= '</table>';
            $stmt->close();
            return $table;
        }
    }
?>
