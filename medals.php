<?php
global $con;
require("autoload.php");

$currentYear = date("Y");
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;

$allMedals = [];

$stmt = $con->prepare("SELECT `key`, stage, `value`, image_path from medals_settings");
$stmt->execute();
$rows = $stmt->fetchAll();

//for ($month = (new DateTime))

foreach($rows as $row) {
    $allMedals[] = [
        'key' => $row['key'],
        'stage' => $row['stage'],
        'value' => $row['value'],
        'image' => $row['image_path']
    ];
}


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Medalien</title>
</head>
<header>
    <?php include("components/navbar.html"); ?>
</header>

<body class="p-8">
<h1 class="text-xl text-black mb-3">Medallien aus dem Jahr <?= $selectedYear ?></h1>
<div class="w-full">
    <form method="GET" class="w-full">
        <select
                name="year"
                id="year"
                class="border border-gray-300 rounded w-full"
                onchange="this.form.submit()"
        >
            <?php
            for ($year = 2023; $year <= $currentYear; $year++) {
                $selected = ($year === $selectedYear) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
            }
            ?>
        </select>
    </form>
</div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/darkModeHandler.js"></script>
<script src="scripts/tailwind.config.js"></script>
</html>

