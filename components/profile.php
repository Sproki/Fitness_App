<?php 
    global $con;
    require('autoload.php');
    
    $defaultImage = htmlspecialchars("https://placehold.co/100x100", ENT_QUOTES, 'UTF-8');
    
    if (!isset($_SESSION['user_id'])) {
        echo "<img src='$defaultImage' class='rounded-full m-3 h-12 w-12'>";
        exit();
    }
    
    $userId = $_SESSION['user_id'];
    
    try {
        $stmt = $con->prepare("SELECT profilePictureLocation FROM users WHERE id = :userId");
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $profileImage = $row && !empty($row['profilePictureLocation']) ? $row['profilePictureLocation'] : $defaultImage;
    } catch (PDOException $e) {
        $profileImage = $defaultImage;
    }

    echo "<a href='einstellungen.php'><img src='$profileImage' class='rounded-full m-3 h-12 w-12'></a>";