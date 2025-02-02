<?php
$dsn = $_ENV['DB_DSN'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    // Verbindung zur Datenbank aufbauen
    $con = new PDO(
        dsn: $dsn,
        username: $user,
        password: $password,
        options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fehler als Exception werfen
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Standardmäßiges Fetch-Format
        ]
    );
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}
