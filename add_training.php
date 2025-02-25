<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    global $con;
    require("autoload.php");

    if (isset($_POST["submit"])) {
        $title = $_POST["title"];
        $description = $_POST["description"];
        $kcal = $_POST["kcal"];

        var_dump($title, $description, $kcal);

        if (empty($title) || empty($description) || empty($kcal)) {
            //die("Fehler: Alle Felder müssen ausgefüllt werden!");
            var_dump($title, $description, $kcal);
        }

        $stmt = $con->prepare("INSERT INTO exercises (title, description, kcal) VALUES (?, ?, ?)");
        $stmt->execute([
            $title, $description, $kcal
        ]);

        header("Location: training.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Document</title>
</head>

<header>
    <?php include("components/profile.php"); ?>
    <?php include("components/navbar.html"); ?>
</header>

<body class="z-10 bg-white dark:bg-[#121212]">
    <div class="mb-20 mx-[40px]">
        <h2 class="text-2xl text-black">Training hinzufügen</h2>
        <hr class="my-5">
        <form method="POST" action="add_training.php">
            <div class="flex flex-col w-full gap-5">
                <input type="text" name="title" placeholder="Übungsname" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                <textarea name="description" placeholder="Beschreibung" class="px-3 py-1 rounded-[15px] outline-none outline-gray-300 h-[150px]"></textarea>
                <input type="number" name="kcal" placeholder="Gesamt Kalorien" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                <input type="file" name="imageInput" id="imageInput">
                <button name="submit" type="submit" class="h-[35px] bg-green-500 text-white rounded-full text-lg outline-green-500">Speichern</button>
            </div>
        </form>
    </div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/tailwind.config.js"></script>

</html>
