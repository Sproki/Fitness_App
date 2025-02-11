<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    global $con;
    require("autoload.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Document</title>
<!--        <link rel="stylesheet" href="global.css">-->
    </head>

    <header>
        <?php include("components/profile.php"); ?>
        <?php include("components/navbar.html"); ?>

        <?php include("components/darkModeButton.html"); ?>
    </header>

    <body class="z-10 bg-white dark:bg-[#121212]">
        <div class="mb-20 mx-[40px]">
            <div class="bg-gray-200 dark:bg-[#2e2e2e] w-full h-[150px] rounded-[15px] flex">
                <img src="https://placehold.co/100x100" class="left-0 w-auto h-full rounded-[15px]">
                <div class="my-5 mx-3">
                    <h3 class="text-xl mb-[5px] text-black dark:text-white">Mittagessen</h3>
                    <h4 class="font-bold mb-[3px] text-black dark:text-white">Hamburger</h4>
                    <h4>300 kcal</h4>
                    <a href="diet_plan.php" class="bg-blue-500 p-1 rounded-full text-white mt-2">Mehr erfahren</a>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="bg-gray-200 dark:bg-[#2e2e2e] w-[170px] h-[170px] mt-[20px] rounded-[15px]">

                </div>
                <div class="bg-gray-200 dark:bg-[#2e2e2e] w-[170px] h-[170px] mt-[20px] rounded-[15px]">

                </div>
            </div>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

</html>
