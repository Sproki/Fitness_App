<?php
    session_start();

    global $con;
    require("autoload.php");

    header('Content-Type: application/json');

    // Prüfen, ob der Request POST ist
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
        exit;
    }

    // JSON-Daten empfangen
    $data = json_decode(file_get_contents('php://input'), true);
    $recipe_id = $data['recipe_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null; // Nutzer-ID aus Session holen

    if (!$recipe_id || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Fehlende Parameter']);
        exit;
    }

    // Verbindung zur Datenbank herstellen
    try {
        $stmt = $con->prepare("INSERT INTO meals (user_id, recipes_id, meal_time) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $recipe_id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }