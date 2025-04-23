<?php
    require_once '../dbconnect.php';
    require_once '../UserDetails.php';

    session_start();
    if (!$_SESSION['userCred']) {
        header("location: ../public/login/login.php");
    }

    $user = $_SESSION['userCred'];
    $userDetails = new UserDetails($user, $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Hello, <?php echo $userDetails->getName(); ?>!</h1>
        <h3>Phone Number >>>> <?php echo $userDetails->getNumber(); ?></h3>
        <h3 class="email">Email >>>> <?php echo $userDetails->getEmail(); ?></h3>
        <div class="image-wrapper" data-width="" data-height="">
            <img src="<?php echo $userDetails->getImage(); ?>" alt="Uploaded Image">
        </div>
        <?php echo $userDetails->getResult(); ?>
        <div class="btn-wrapper">
            <a href="../public/logout.php" title="Logout">Logout</a>
        </div>
    </div>
</body>
</html>
