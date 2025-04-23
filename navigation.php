<?php
    session_start();
    // Check if the user is logged in. If not, redirect to the login page.
    if (!$_SESSION['loggedIn']) {
        header("location: ./public/login/login.php");
    }

    // Check if the 'q' parameter is present in the URL and redirect the user accordingly.
    if (isset($_GET['q'])) {
        $q = $_GET['q'];
        // Redirects to different question pages based on the value of the 'q' parameter.
        if ($q == 1) {
            header("Location: ./details/name/name.php");
            exit();
        }
        elseif ($q == 2) {
            header("Location: ./details/photo/photo.php");
            exit();
        } 
        elseif ($q == 3) {
            header("Location: ./details/result/result.php");
            exit();
        } 
        elseif ($q == 4) {
            header("Location: ./details/number/number.php");
            exit();
        }
        else {
            header("Location: ./details/name/name.php");
            exit();
        }
    }
    // If 'q' is not set or has an invalid value, redirects to the default question page (q4). 
    else {
        header("Location: ./details/name/name.php");
        exit();
    }
?>
