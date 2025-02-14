<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

global $con;
require("autoload.php");

$userId = $_SESSION["user_id"];
$firstname = $_SESSION['firstname'];

$stmt = $con->prepare("SELECT energy_expenditure FROM users WHERE id = :userId");
$stmt->execute([':userId' => $userId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$energyExpenditure = $row['energy_expenditure'] ?? 3500;

$sql = "SELECT COALESCE(SUM(r.calories), 0) AS consumedCaloriesToday FROM meals m JOIN recipes r ON m.recipes_id = r.id WHERE m.user_id = :userId AND DATE(m.meal_time) >= :date";
$stmt = $con->prepare($sql);
$stmt->execute([
    ':userId' => $userId,
    ':date' => date('Y-m-d')
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$consumedCaloriesToday = $row['consumedCaloriesToday'] ?? 0;

$progress = ($consumedCaloriesToday / $energyExpenditure) * 100; // Prozentwert von 0 bis 100

$progressColor = '#F01E2C';

if ($progress <= 100) {
    $progressColor = '#3B82F6';
}

$meals = [];
$mealsLimit = isset($_GET['mealsLimit']) ? (int)$_GET['mealsLimit'] : 3;
$mealsDate = $_GET['mealsDate'] ?? date('Y-m-d');

$sql = "SELECT m.*, r.* from meals m join recipes r on r.id = m.recipes_id where m.user_id = :userId and DATE(m.meal_time) = :date LIMIT " . $mealsLimit;
$stmt = $con->prepare($sql);
$stmt->execute([
    ':userId' => $userId,
    ':date' => $mealsDate,
]);
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $meals[] = [
        'mealTime' => $row['meal_time'],
        'name' => $row['name'],
        'description' => $row['description'],
        'calories' => $row['calories'],
        'amountInGrams' => $row['amount_in_grams'],
    ];
}

$sql = "SELECT SUM(r.calories) as 'calories', date(m.meal_time) as 'date' from meals m join recipes r on r.id = m.recipes_id where m.user_id = :userId group by date(m.meal_time) LIMIT 5";
$stmt = $con->prepare($sql);
$stmt->execute([
    ':userId' => $userId,
]);
$rows = $stmt->fetchAll();

$caloriesStats = [];
foreach ($rows as $row) {
    $caloriesStats[] = [
        'calories' => $row['calories'],
        'date' => $row['date'],
    ];
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

</head>
<header>
    <?php include("components/profile.php"); ?>
    <?php include("components/navbar.html"); ?>

    <?php include("components/darkModeButton.html"); ?>
</header>
<body class="z-10 bg-white">
<div class="mb-20 mx-[40px]">
    <h2 class="text-2xl text-black dark:text-white">Dein Mahlzeiten</h2>
    <hr class="my-5">
    <!-- Ring für die Anzeige der aufgenommenen Kalorien-->
    <div class="w-full h-[200px] flex justify-center content-center mb-4">
        <div class="w-[200px] h-[200px] relative flex justify-center content-center">
            <div class="w-full h-full rounded-[50%] flex justify-center content-center"
                 style="background: conic-gradient(<?php echo $progressColor; ?> <?php echo $progress; ?>%, transparent <?php echo $progress; ?>%);">
            </div>
            <div class="absolute bg-white w-[93%] h-[93%] rounded-full place-self-center flex justify-center items-center">
                <p class="text-center">Heute zugeführte <br>Kalorien: <?php echo $consumedCaloriesToday ?> kcal</p>
            </div>
        </div>
    </div>
    <div class="flex gap-5 w-full">
        <a href="energy_expenditure.php" class="bg-blue-400 w-full h-[50px] rounded-[15px] flex items-center justify-center mb-3"><p
                    class="text-white font-bold text-center text-lg">Energiebedarf berechnen</p></a>
    </div>
    <div class="flex gap-5 w-full">
        <a href="recipes.php" class="bg-green-400 w-full h-[125px] rounded-[15px] flex items-center justify-center"><p
                    class="text-white font-bold text-center text-lg">Rezept <br>auswählen</p></a>
        <a href="add_recipes.php" class="bg-green-400 w-full h-[125px] rounded-[15px] flex items-center justify-center"><p
                    class="text-white font-bold text-center text-lg">Rezept <br>hinzufügen</p></a>
    </div>

    <div class="my-3 border border-solid"></div>

    <div class="w-full">
        <form method="GET" class="w-full">
            <select
                    name="mealsDate"
                    id="mealsDate"
                    class="border border-gray-300 rounded w-full"
                    onchange="this.form.submit()"
            >
                <option value="<?= (new DateTimeImmutable())->format('Y-m-d') ?>" <?= ((new DateTimeImmutable())->format('Y-m-d') === $mealsDate) ? 'selected' : '' ?>>
                    Heute
                </option>
                <option value="<?= (new DateTimeImmutable('-1 days'))->format('Y-m-d') ?>" <?= ((new DateTimeImmutable('-1 days'))->format('Y-m-d') === $mealsDate) ? 'selected' : '' ?>>
                    Gestern
                </option>
                <option value="<?= (new DateTimeImmutable('-2 days'))->format('Y-m-d') ?>" <?= ((new DateTimeImmutable('-2 days'))->format('Y-m-d') === $mealsDate) ? 'selected' : '' ?>>
                    Vorgestern
                </option>
            </select>
        </form>
    </div>

    <div class="flex flex-col gap-4 mt-4">
        <?php foreach ($meals as $meal): ?>
            <div class="bg-gray-100 dark:bg-[#2e2e2e] p-4 rounded-xl">
                <div>
                    <h1><?= htmlspecialchars($meal['name']) ?></h1>
                    <p><?= htmlspecialchars($meal['description']) ?? "Keine Beschreibung angegeben" ?></p>
                    <br>
                    <p>Kalorien: <?= htmlspecialchars($meal['calories']) ?? "N/A" ?> kcal</p>
                    <p>Menge: <?= htmlspecialchars($meal['amountInGrams']) ?? "N/A" ?> g</p>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="flex items-center justify-center <?= $mealsLimit >= 10 ? 'hidden' : '' ?>">
            <a class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
               href="?mealsLimit=<?php echo min($mealsLimit + 5, 10); ?>&mealsDate=<?php echo $mealsDate; ?>">Mehr
                laden</a>
        </div>
    </div>

    <div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
        <div class="flex justify-between">
            <div>
                <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">Kalorien</h5>
            </div>
        </div>
        <div id="area-chart"></div>
    </div>

    <div class="mb-10"></div>
</div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/darkModeHandler.js"></script>
<script src="scripts/tailwind.config.js"></script>

<script>
    const options = {
        chart: {
            height: "100%",
            maxWidth: "100%",
            type: "area",
            fontFamily: "Inter, sans-serif",
            dropShadow: {
                enabled: false,
            },
            toolbar: {
                show: false,
            },
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
        },
        fill: {
            type: "gradient",
            gradient: {
                opacityFrom: 0.55,
                opacityTo: 0,
                shade: "#1C64F2",
                gradientToColors: ["#1C64F2"],
            },
        },
        dataLabels: {
            enabled: true,
            style: {
                cssClass: 'text-xs text-white font-medium'
            },
        },
        stroke: {
            width: 6,
        },
        grid: {
            show: true,
            strokeDashArray: 4,
            padding: {
                left: 20,
                right: 25,
                top: 0
            },
        },
        series: [
            {
                name: "Kalorien",
                data: [
                    <?php
                    $calories = implode(', ', array_map(static function(array $stat) {
                        return (int) $stat['calories'];
                    }, $caloriesStats));

                    echo $calories;
                    ?>
                ],
                color: "#1A56DB",
            },
        ],
        xaxis: {
            show: true,
            categories: [
                <?php
                $dates = implode(', ', array_map(static function(array $stat) {
                    return sprintf('\'%s\'', $stat['date']);
                }, $caloriesStats));

                echo $dates;
                ?>
            ],
            labels: {
                show: true,
                style: {
                    fontFamily: "Inter, sans-serif",
                    cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                },
                formatter: function (value) {
                    const options = { month: 'short', day: 'numeric' };

                    return new Date(value).toLocaleDateString('de-DE', options);
                }
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            show: false,
        },
    }

    if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("area-chart"), options);
        chart.render();
    }
</script>
</html>
