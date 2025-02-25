<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

global $con;
require("autoload.php");

$exercisesLimit = isset($_GET['exercisesLimit']) ? (int)$_GET['exercisesLimit'] : 4;
$exercisesMax = 0;
$exercises = [];

$sql = "SELECT value from statistic where user_id = :userId AND date = :date";
$stmt = $con->prepare($sql);
$stmt->execute([
    ':userId' => $_SESSION["user_id"],
    ':date' => date('Y-m-d')
]);
$caloriesBurned = (int) $stmt->fetchColumn();

$sql = "SELECT count(*) from exercises";
$stmt = $con->prepare($sql);
$stmt->execute([]);
$exercisesMax = (int)$stmt->fetchColumn();

$sql = "SELECT * from exercises LIMIT " . $exercisesLimit;
$stmt = $con->prepare($sql);
$stmt->execute([]);
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $exercises[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'kcal' => $row['kcal'], //ToDo rename
        'image' => $row['exerciseImage'], //ToDo rename
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <style>
        .card-container {
            perspective: 1000px;
        }

        .card {
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .card.flip {
            transform: rotateY(180deg);
        }

        .card-side {
            backface-visibility: hidden;
        }

        .card-back {
            transform: rotateY(180deg);
        }
    </style>
</head>

<header>
    <?php include("components/profile.php"); ?>
    <?php include("components/navbar.html"); ?>
</header>

<body class="bg-white dark:bg-[#121212]">
<div style="margin-bottom: 8rem">
    <div id="chart"></div>
    <div class="flex flex-col items-center justify-center gap-4">
        <?php foreach ($exercises as $exercise): ?>
            <div class="card-container">
                <div id="card-<?= $exercise['id'] ?>" class="card relative w-64 h-80 cursor-pointer">
                    <div class="card-side card-front absolute w-full h-full bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="<?= $exercise['image'] ?? 'https://placehold.co/256x320' ?>" alt="Bild"
                             class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-end justify-center mb-4">
                            <h2 class="text-lg font-bold text-white bg-gray-800 bg-opacity-50 px-4 py-2 rounded">
                                <?= $exercise['title'] ?>
                            </h2>
                        </div>
                    </div>
                    <div class="card-side card-back absolute w-full h-full bg-white rounded-lg shadow-lg p-4 flex flex-col justify-between gap-2 overflow-hidden">
                        <div class="flex flex-col justify-between gap-2 h-full">
                            <div class="flex flex-col gap-2">
                                <h2 class="text-lg font-bold">
                                    <?= $exercise['title'] ?>
                                </h2>
                                <p class="text-gray-700 mb-2"> <?= $exercise['description'] ?></p>
                            </div>
                            <p class="text-gray-700 mb-2">Kalorien: <?= $exercise['kcal'] ?></p>
                        </div>
                        <a href="timer.php?exercises=<?= $exercise['id'] ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Training starten
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="flex items-center justify-center <?= $exercisesLimit >= $exercisesMax ? 'hidden' : '' ?>">
            <a class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
               href="?exercisesLimit=<?php echo min($exercisesLimit + 4, $exercisesMax); ?>">Mehr
                laden</a>
        </div>
    </div>
</div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/tailwind.config.js"></script>

<script>
    const cards = document.querySelectorAll('.card');
    cards.forEach((card) => {
        card.addEventListener('click', function () {
            card.classList.toggle('flip');
        });
    });

    var options = {
        chart: {
            height: 280,
            type: "radialBar"
        },

        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 15,
                    size: "70%"
                },

                dataLabels: {
                    showOn: "always",
                    name: {
                        offsetY: -10,
                        show: true,
                        color: "#888",
                        fontSize: "11px"
                    },
                    value: {
                        color: "#111",
                        fontSize: "30px",
                        show: true
                    }
                }
            }
        },

        stroke: {
            lineCap: "round",
        },
        series: [<?= min(100, ($caloriesBurned / 100000) * 100) ?>],
        labels: ['Heutige Verbrannte Kalorien'],
    }

    if (document.getElementById("chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("chart"), options);
        chart.render();
    }
</script>

</html>
