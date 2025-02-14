<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    global $con;
    require("autoload.php");

    $user_id = $_SESSION["user_id"];

    if (isset($_POST["submit"])) {

        try {
            $gender = $_POST["gender"];
            $weight = $_POST["weight"];
            $height = $_POST["height"];
            $age = $_POST["age"];
            $activityLevel = $_POST["activityLevel"];
            $activityFactors = [
                1 => 1.2,
                2 => 1.375,
                3 => 1.55,
                4 => 1.725,
                5 => 1.9
            ];

            $activityFactor = $activityFactors[$activityLevel];

            switch ($gender) {
                case "male":
                    $energy_expenditure = (66 + (13.8 * $weight) + (5.0 * $height) + (6.8 * $age)) * $activityFactor;
                    break;

                case "female":
                    $energy_expenditure = (655 + (9.5 * $weight) + (1.9 * $height) + (4.7 * $age)) * $activityFactor;
                    break;

                default:
                    $energy_expenditure = 0;
            }

            $stmt = $con->prepare(
                    "UPDATE users 
                            SET `energy_expenditure` = :energyExpenditure 
                            WHERE user_id = :userId"
                            );
            $stmt->bindParam(":energyExpenditure", $energy_expenditure);
            $stmt->bindParam(":userId", $user_id);

            if ($stmt->execute()) {
                header("Location: nutrition_plan.php");
                exit;
            } else {
                die("Fehler: Beim Speichern in der Datenbank ist ein Problem aufgetreten.");
            }

        } catch (e) {
            die("Ein unerwarteter Fehler ist aufgetreten.");
        }
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
    </head>
    <header>
        <?php include("components/profile.php"); ?>
        <?php include("components/navbar.html"); ?>
    </header>
    <body class="z-10 bg-white">
        <div class="mb-20 mx-[40px]">
            <h2 class="text-2xl text-black">Dein Gesamtenergiebedarf brechnen</h2>
            <hr class="my-5">
            <form method="POST" action="energy_expenditure.php">
                <div class="flex justify-between w-full mb-5">
                    <label class="text-xl"><input type="radio" value="male" name="gender" id="radio_male" class="mr-5" checked>Männlich</label>
                    <label class="text-xl"><input type="radio" value="female" name="gender" id="radio_female" class="mx-5">Weiblich</label>
                </div>
                <div class="flex flex-col gap-5">
                    <input type="number" name="weight" placeholder="Gewischt in kg" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <input type="number" name="height" placeholder="Größe in cm" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <input type="number" name="age" placeholder="Alter in Jahren" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <div class="relative w-full">
                        <input name="activityLevel" type="range" min="1" max="5" step="1" value="1"
                               class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between w-full absolute top-6 text-sm text-gray-500">
                            <span>1</span>
                            <span>2</span>
                            <span>3</span>
                            <span>4</span>
                            <span>5</span>
                        </div>
                    </div>
                    <button name="submit" type="submit" class="mt-[50px] w-full h-[50px] bg-green-500 text-white rounded-lg text-lg">Berechnen</button>
                </div>
            </form>
            <hr class="my-5">
            <table class="w-full border-collapse rounded-lg shadow-md bg-white">
                <thead>
                <tr class="bg-gray-300 text-gray-700 text-left">
                    <th class="px-6 py-3">Beschreibung</th>
                    <th class="px-2 py-3">Nummer</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-6 py-3">Sitzend, kaum körperliche Aktivität (Bürotätigkeiten)</td>
                    <td class="px-6 py-3">1</td>
                </tr>
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-6 py-3">Sitzen, gehen und stehen (Lehrer, Studenten, Schüler)</td>
                    <td class="px-6 py-3">2</td>
                </tr>
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-6 py-3">Hauptsächlich stehen und gehen (Verkäufer, Kellner, Handwerker)</td>
                    <td class="px-6 py-3">3</td>
                </tr>
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-6 py-3">Körperlich anstrengende Arbeit (Landwirte, Bauarbeiter)</td>
                    <td class="px-6 py-3">4</td>
                </tr>
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                    <td class="px-6 py-3">Sehr schwere körperliche Arbeit oder Hochleistungssport (Athleten, Leistungssportler, Bergarbeiter)</td>
                    <td class="px-6 py-3">5</td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

</html>