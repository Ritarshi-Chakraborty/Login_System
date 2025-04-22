<?php
    require_once '../dbconnect.php';

    class UserDetails {
        protected $email;
        protected $conn;

        function __construct($user, $conn) {
            $this->email = $user;
            $this->conn = $conn;
        }

        function getName() {
            $sql = "SELECT `fullname` FROM `user_credentials` WHERE `email` = '$this->email'";
            $result = mysqli_query($this->conn, $sql);

            // Fetch the result as an associative array
            $row = mysqli_fetch_assoc($result);
            return $row['fullname'];
        }

        function getEmail() {
            return $this->email;
        }

        function getNumber() {
            $sql = "SELECT `phone_number` FROM `user_credentials` WHERE `email` = '$this->email'";
            $result = mysqli_query($this->conn, $sql);

            // Fetch the result as an associative array
            $row = mysqli_fetch_assoc($result);
            return $row['phone_number'];
        }

        function getImage() {
            $sql = "SELECT `photo` FROM `user_credentials` WHERE `email` = '$this->email'";
            $result = mysqli_query($this->conn, $sql);

            // Fetch the result as an associative array
            $row = mysqli_fetch_assoc($result);
            return $row['photo'];
        }

        function getResult() {
            $sql =  "SELECT * FROM `user_result` WHERE `email` = '$this->email'";
            $result = mysqli_query($this->conn, $sql);
            $table = '<table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>';

            while ($row = mysqli_fetch_assoc($result)) {

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
            return $table;
        }
    }
?>
