<?php
    session_start();

    require("connection.php");

    if (!isset($_SESSION['user_id'])) {
        die("Fehler: Nicht eingeloggt.");
    }

    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
        $file = $_FILES['profile_image'];
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            $userId = $_SESSION['user_id'];

            // Profilbild-Location in der Datenbank speichern
            $stmt = $con->prepare("UPDATE users SET profilePictureLocation = :profilePicture WHERE id = :userId");
            $stmt->execute([
                ':profilePicture' => $uploadFile,
                ':userId' => $userId
            ]);

            // Neuer Bildpfad in der Session speichern
            $_SESSION['profile_image'] = $uploadFile;

            // Weiterleitung zur Hauptseite
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Fehler beim Hochladen.";
        }
    }
?>
