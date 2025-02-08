<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    global $con;
    require("autoload.php");

    $maxConsumedCalories = 1000;

    $userId = $_SESSION['user_id'];

    if (isset($_POST["submit"])) {
        $consumedCalories = $_SESSION['calories_consumed'];
        $consumedCaloriesToday = $_SESSION['calories_today'];
    }

    // Aktuelles Datum ermitteln (im Format YYYY-MM-DD)
    $today = date("Y-m-d");

    // SQL-Abfrage vorbereiten
    $sql = "SELECT value FROM statistic WHERE `key` = :key AND `date` = :date AND `user_id` = :userID";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        'key' => 'calories_consumed',
        'date' => $today,
        'userID' => $userId
    ]);

    // Wert abrufen
    $consumedCaloriesToday = $stmt->fetchColumn();

    // Falls kein Wert gefunden wurde, Standardwert setzen
    if ($consumedCaloriesToday === false) {
        $consumedCaloriesToday = 0;
    }

    $progress = ( $consumedCaloriesToday / $maxConsumedCalories ) * 100; // Prozentwert von 0 bis 100
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <header>
        <?php include("components/profile.php"); ?>
        <?php include("components/navbar.html"); ?>

        <?php include("components/darkModeButton.html"); ?>
    </header>
    <body class="z-10 bg-white">
        <div class="mb-20 mx-[40px]">
            <div class="w-[200px] h-[200px] relative flex justify-center content-center">
                <div class="w-full h-full rounded-[50%] flex justify-center content-center"
                     style="background: conic-gradient(#3B82F6 <?php echo $progress; ?>%, transparent <?php echo $progress; ?>%);">
                </div>
                <div class="absolute bg-white w-[95%] h-[95%] rounded-full place-self-center"></div>
            </div>

            <form action="updateconsumedcalories.php" method="POST">
                <input type="number" placeholder="Kalorien" name="caloriesConsumed" required>
                <button type="submit">Speichern</button>
            </form>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>
</html>
