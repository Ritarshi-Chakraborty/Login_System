<?php
    require_once __DIR__ . '/vendor/autoload.php';

    // Load the .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/');
    $dotenv->load();

    $servername = $_ENV['SERVERNAME'];
    $username = $_ENV['USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DATABASE'];

    // MySQL connection
    $conn = new mysqli($servername, $username, $password, $database);
?>
