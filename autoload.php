<?php
    global $con;
    require __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Lade die Umgebungsvariablen
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    require("connection.php");