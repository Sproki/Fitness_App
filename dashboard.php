<?php

    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    $sessionTimeout = 600; // 10 Minuten

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
    $medals = [];
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

        $stmt = $con->prepare("SELECT mu.date, ms.`key`, ms.image_path from medals_user mu join sport_app.medals_settings ms on mu.medals_setting_id = ms.id where user_id = :userId order by date desc limit 3");
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $medals[] = [
                'key' => $row['key'],
                'date' => (new DateTimeImmutable($row['date']))->format('F Y'),
                'image' => $row['image_path'],
            ];
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
    <body class="z-10 bg-white dark:bg-[#121212]">
        <div class="mx-[40px] mb-20">
            <div class="my-5">
                <h2 class="text-2xl text-black dark:text-white">Willkommen zurück💪</h2>
                <h1 class="text-3xl text-black font-bold dark:text-white"><?php echo $firstname?></h1>
            </div>
        <div class="grid grid-flow-col grid-rows-2 gap-[10px]">
            <div class="bg-gray-100 dark:bg-[#2e2e2e] w-[150px] h-[150px] row-span-2 rounded-[15px] flex flex-col items-center justify-center gap-2">
                <p class="text-5xl">🦶</p>
                <div class="text-center">
                    <p class="text-[14px] font-semibold text-black dark:text-white">Heute zurückgelegt</p>
                    <p class="text-[14px] font-bold text-black dark:text-white"><?php echo $statistics['steps'] ?? 0?> Schritte</p>
                </div>
            </div>


            <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                <div class="flex items-center gap-2">
                    <p class="text-3xl">🔥</p>
                    <div>
                        <p class="text-[11px] font-semibold text-black dark:text-white">Heute verbrannt</p>
                        <p class="text-[11px] font-bold text-black dark:text-white"><?php echo $statistics['calories_burned'] ?? 0?> kcal</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                <div class="flex items-center gap-2">
                    <p class="text-3xl">🏃</p>
                    <div>
                        <p class="text-[11px] font-semibold text-black dark:text-white">Heute gelaufen</p>
                        <p class="text-[11px] font-bold text-black  dark:text-white"><?php echo $statistics['kilometers'] ?? 0?> Kilometer</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div>
            <h2 class="text-xl text-black mb-1 dark:text-white">Wöchentliche Statistiken</h2>
            <h3 class="mb-3 text-xs text-black dark:text-white">Verbrannte Kalorien</h3>
            <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px]">
                <div class="flex items-end p-4 h-full justify-between" style="display: <?= empty($weeklyStatistics) ? 'none' : 'flex' ?>">
                    <?php foreach($weeklyStatistics as $date => $value): ?>
                        <?php
                            $barHeight = ($value / $maxValue) * 100;
                        ?>
                    <div class="flex flex-col items-center justify-end h-full">
                        <div class="bg-blue-500 w-4 rounded-md" style="height: <?= $barHeight ?>%;">
                        </div>
                        <span class="mt-2 text-xs text-center text-black dark:text-white">
                          <?= (new DateTimeImmutable($date))->format('D') ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex p-4 h-full justify-center items-center content-center justify-items-center text-black dark:text-white" style="display: <?= empty($weeklyStatistics) ? 'flex' : 'none' ?>">
                    <p>Keine Statistiken vorhanden, du fauler Hund</p>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div>
            <h2 class="text-xl text-black mb-3 dark:text-white">Deine verdienten Medaillen</h2>
            <a class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden" href="medals.php">
                <div class="flex gap-[10px] p-4">
                    <?php foreach ($medals as $medal): ?>
                        <div class="flex flex-col">
                            <img src="<?= $medal['image'] ?>" class="rounded-full w-[80px] h-[80px]">
                            <span class="mt-2 text-xs text-center text-black dark:text-white">
                              <?= match($medal['key']) {
                                  'steps' => 'Schritte',
                                  'kilometers' => 'Kilometers',
                                  'calories_burned' => 'Verbrannte Kalorien'
                              } ?>
                            </span>
                            <span class="mt-2 text-xs text-center text-black dark:text-white">
                              <?= $medal['date'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </a>
        </div>

            <hr class="my-5">

            <div>
                <h2 class="text-xl mb-5 text-black dark:text-white">Training of the Day 🏋️‍♂️</h2>
                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex">
                    <img src="https://placehold.co/100x100" class="left-0 w-auto h-full rounded-[15px]">
                    <article class="overflow-scroll my-5 mx-3">
                        <h3 class="mb-2 font-semibold text-black dark:text-white"><?php echo $title; ?></h3>
                        <p class="text-black dark:text-white"><?php echo $description; ?></p>
                    </article>
                </div>
            </div>

            <hr class="my-5">

        <div>
            <h2 class="text-xl mb-5 text-black dark:text-white">Beispielvideos:</h2>
            <div class="grid grid-cols-2 gap-[20px]">
                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/2qOOGrcxuTE?si=ecegmp6snrdyksBd"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/vSl23jffAAg?si=341glG97D0mDidLL"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-2 row-2">
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

                <!-- Versteckte Videos -->
            <div id="moreVideos" class="grid grid-cols-2 gap-[20px] mt-4 hidden">
                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/4-ZJj9qd9cQ?si=X4DJ0p13SrNxWtm6"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/wND4NmTQjwk?si=crTft3VRGBuMwn2T"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-2 row-2">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/89F7Gcpi_Rk?si=vB9nCjdIjJKmIOI1"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/uEacokDMo-A?si=Lpr1NlY1HT_LtR4V"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="bg-gray-100 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                    <iframe
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/9Glp2VoF51k?si=WKlulyoxoLX61dzN"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Button -->
            <button id="toggleButton" class="mt-4 bg-gray-100 dark:bg-[#2e2e2e] text-black dark:text-white px-4 py-2 rounded-lg w-full">
                    Mehr anzeigen
            </button>

        </div>

        <script>
    document.getElementById("toggleButton").addEventListener("click", function () {
        var moreVideos = document.getElementById("moreVideos");
        var button = document.getElementById("toggleButton");

        if (moreVideos.classList.contains("hidden")) {
            moreVideos.classList.remove("hidden");
            button.textContent = "Weniger anzeigen";
        } else {
            moreVideos.classList.add("hidden");
            button.textContent = "Mehr anzeigen";
        }
    });
</script>
        
        </div>

            <hr class="my-5">

        </div>
    </body>
        <script src="https://cdn.tailwindcss.com"></script>

        <script src="scripts/darkModeHandler.js"></script>
        <script src="scripts/tailwind.config.js"></script>
</html>
