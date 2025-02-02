<?php 
    session_start();
    
    $sessionTimeout = 600; // 10 Minuten

    if (!isset($_SESSION["firstname"])) {
        header("Location: index.php");
        exit;
    }

    // Session Timeout überprüfen
    if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"]) > $sessionTimeout) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $_SESSION["last_activity"] = time();

    $firstname = htmlspecialchars($_SESSION["firstname"]);

    global $con;
    require("autoload.php");

    $statistics = [];
    $weeklyStatistics = [];
    $maxValue = 0;

    try {
        $sql = "SELECT title, description FROM exercises ORDER BY RAND() LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Überprüfen, ob ein Eintrag gefunden wurde
        if ($row) {
            $title = htmlspecialchars($row['title']);
            $description = htmlspecialchars($row['description']);
        } else {
            $title = "Kein Training gefunden";
            $description = "Bitte füge neue Übungen hinzu.";
        }

        $currentDate = new DateTimeImmutable();
        $userId = $_SESSION['user_id'];
        $stmt = $con->prepare("SELECT * from statistic where user_id = :userId and `date` = :date");
        $stmt->execute([':userId' => $userId, ':date' => $currentDate->format('Y-m-d')]);
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $statistics[$row['key']] = $row['value'];
        }

        $weeklyStartDate = new DateTimeImmutable('-7 days');
        $stmt = $con->prepare("SELECT * from statistic where user_id = :userId and `key` = 'calories_burned' and `date` > :date order by `date`");
        $stmt->execute([':userId' => $userId, ':date' => $weeklyStartDate->format('Y-m-d')]);
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $weeklyStatistics[$row['date']] = $row['value'];
        }

        if (!empty($weeklyStatistics)) {
            $maxValue = max($weeklyStatistics);
        }
    } catch (PDOException $e) {
        die("Fehler bei der Abfrage: " . $e->getMessage());
    } 
?>

<!DOCTYPE html>
<html lang="en" class="">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="global.css">
    </head>
    <header>
        <?php include("components/profile.php"); ?>
        <?php include("components/navbar.html"); ?>

        <?php include("components/darkModeButton.html"); ?>
    </header>
    <body class="z-10 bg-white dark:bg-black">
        <div class="mx-[40px] mb-20">
            <div class="my-5">
                <h2 class="text-2xl text-black dark:text-white">Willkommen zurück💪</h2>
                <h1 class="text-3xl text-black dark:text-white font-bold"><?php echo $firstname?></h1>
            </div>
        <div class="grid grid-flow-col grid-rows-2 gap-[10px]">
            <div class="bg-gray-100 w-[150px] h-[150px] row-span-2 rounded-[15px] flex flex-col items-center justify-center gap-2">
                <p class="text-5xl">🦶</p>
                <div class="text-center">
                    <p class="text-[14px] font-semibold">Heute zurückgelegt</p>
                    <p class="text-[14px] font-bold"><?php echo $statistics['steps'] ?? 0?> Schritte</p>
                </div>
            </div>

                
            <div class="bg-gray-100 w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                <div class="flex items-center gap-2">
                    <p class="text-3xl">🔥</p>
                    <div>
                        <p class="text-[11px] font-semibold">Heute verbrannt</p>
                        <p class="text-[11px] font-bold"><?php echo $statistics['calories_burned'] ?? 0?> kcal</p>
                    </div>
                </div>
            </div>
                
            <div class="bg-gray-100 w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                <div class="flex items-center gap-2">
                    <p class="text-3xl">🏃</p>
                    <div>
                        <p class="text-[11px] font-semibold">Heute gelaufen</p>
                        <p class="text-[11px] font-bold"><?php echo $statistics['kilometers'] ?? 0?> Kilometer</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div>
            <h2 class="text-xl text-black mb-3">Wöchentliche Statistiken</h2>
            <div class="bg-gray-100 w-full h-[150px] rounded-[15px]">
                <div class="flex items-end p-4 h-full justify-between" style="display: <?= empty($weeklyStatistics) ? 'none' : 'flex' ?>">
                    <?php foreach($weeklyStatistics as $date => $value): ?>
                        <?php
                            $barHeight = ($value / $maxValue) * 100;
                        ?>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="bg-blue-500 w-4 rounded-md" style="height: <?= $barHeight ?>%;">
                        </div>
                        <span class="mt-2 text-xs text-center">
                          <?= (new DateTimeImmutable($date))->format('D') ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex p-4 h-full justify-center items-center content-center justify-items-center" style="display: <?= empty($weeklyStatistics) ? 'block' : 'none' ?>">
                    <p>Keine Statistiken vorhanden, du fauler Hund</p>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div>
            <h2 class="text-xl text-black mb-3">Deine verdienten Medallien</h2>
            <div class="bg-gray-100 w-full h-[100px] rounded-[15px] flex items-center justify-center overflow-x-auto">
                <div class="flex gap-[10px]">
                    <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                    <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                    <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                </div>
            </div>
        </div>

            <hr class="my-5">

        <div>
            <h2 class="text-xl mb-5">Beispielvideos:</h2>
            <div class="grid grid-cols-2 gap-[20px]">              
                <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/2qOOGrcxuTE?si=ecegmp6snrdyksBd" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/vSl23jffAAg?si=341glG97D0mDidLL" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-2 row-2">
                    <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/uXFjLXgIcYc?si=y3_1UbWkfTkFn29M" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>             
        </div>

            <hr class="my-5">

            <div>
                <h2 class="text-xl mb-5">Training of the Day 🏋️‍♂️</h2>
                <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex">
                    <img src="https://placehold.co/100x100" class="left-0 w-auto h-full rounded-[15px]">
                    <article class="overflow-scroll my-5 mx-3">
                        <h3 class="mb-2 font-semibold"><?php echo $title; ?></h3>
                        <p><?php echo $description; ?></p>
                    </article>
                </div>
            </div>

             <hr class="my-5">
        </div>
    </body>
        <script src="https://cdn.tailwindcss.com"></script>

        <script src="scripts/darkModeHandler.js"></script>
        <script src="scripts/tailwind.config.js"></script>
</html>