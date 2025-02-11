<?php
session_start();
global $con;
require("autoload.php");

$currentYear = date('Y');
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;

$stmt = $con->prepare("SELECT id, `key`, stage, `value`, image_path from medals_settings");
$stmt->execute();
$rows = $stmt->fetchAll();

$startMonth = 12;

if ($selectedYear == $currentYear) {
    $startMonth = (int) date('n');
}

$sortedMedals = [];

for ($month = $startMonth; $month >= 1; $month--) {
    foreach($rows as $row) {
        $sortedMedals[$month][] = [
            'id' => $row['id'],
            'key' => $row['key'],
            'stage' => $row['stage'],
            'value' => $row['value'],
            'image' => $row['image_path'],
            'userValue' => 0
        ];
    }
}

$userId = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT `date`, `key`, SUM(`value`) as 'value' from statistic where user_id = :userId group by `date`, `key`");
$stmt->execute([':userId' => $userId]);
$rows = $stmt->fetchAll();
foreach ($rows as $row) {
    $date = new DateTime($row['date']);
    if ($date->format('Y') != $selectedYear) {
        continue;
    }
    foreach ($sortedMedals as $month => $medals) {
        if ($month != $date->format('n')) {
            continue;
        }

        foreach ($medals as $index => $medal) {
            if ($medal['key'] != $row['key']) {
                continue;
            }

            $medal['userValue'] = $row['value'];
            $sortedMedals[$month][$index] = $medal;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Medaillen</title>
</head>
<header>
    <?php include("components/navbar.html"); ?>
</header>

<body class="p-4 bg-white dark:bg-[#121212]">
<h1 class="text-xl text-black mb-3 dark:text-white">Medaillen aus dem Jahr <?= $selectedYear ?></h1>
<div class="w-full">
    <form method="GET" class="w-full">
        <select
                name="year"
                id="year"
                class="border border-gray-300 rounded w-full"
                onchange="this.form.submit()"
        >
            <?php
            for ($year = 2024; $year <= $currentYear; $year++) {
                $selected = ($year == $selectedYear) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
            }
            ?>
        </select>
    </form>
</div>
<div class="my-5 border border-solid"></div>
<div class="flex flex-col gap-4">
<?php foreach($sortedMedals as $month => $medals): ?>
<h2 class="text-xl text-black mb-1 dark:text-white">
    <?= [1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'][$month] ?>
    <?= $selectedYear ?>
</h2>
<div class="flex flex-wrap gap-1 items-center justify-center">
<?php foreach($medals as $medal): ?>
    <div class="flex flex-col gap-4 bg-gray-100 p-3 w-[120px] items-center justify-center">
        <img src="<?= $medal['image'] ?>" class="rounded-full w-[80px] h-[80px]">
        <span class="mt-2 text-xs text-cente text-black dark:text-whiter">
          <?= match($medal['key']) {
              'steps' => 'Schritte',
              'kilometers' => 'Kilometers',
              'calories_burned' => 'Verbrannte Kalorien'
          } ?>
        </span>
        <div class="relative w-full bg-gray-400 rounded-full h-8 overflow-hidden">
            <div class="bg-blue-600 h-8 rounded-full" style="width: <?= round(($medal['userValue'] / $medal['value']) * 100) ?>%;"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-white whitespace-nowrap text-xs">
                    <?=
                        ($medal['userValue'] - $medal['value'] == 0) ? number_format((int) $medal['value'], 0, '', '.') :
                        sprintf('%s / %s', number_format((int) $medal['userValue'], 0, '', '.'), number_format((int) $medal['value'], 0, '', '.'))
                    ?>
                </span>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>
<div class="pb-20"></div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/darkModeHandler.js"></script>
<script src="scripts/tailwind.config.js"></script>
</html>

