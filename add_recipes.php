<?php
    session_start();
    
    global $con;
    require("autoload.php");

    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }



    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $calories = $_POST["calories"];
        $amount = $_POST["amountInGrams"];
        $saveMeal = isset($_POST["saveMeal"]);

        if (empty($name) || empty($description) || empty($calories) || empty($amount)) {
            die("Fehler: Alle Felder müssen ausgefüllt werden!");
        }

        $stmt = $con->prepare("INSERT INTO recipes (name, description, calories, amount_in_grams, created_at, created_by) VALUES (?, ?, ?, ?, NOW() ,?)");
        $stmt->execute([
            $name, $description, $calories, $amount, $user_id
        ]);

        $lastID = $con->lastInsertId();

        if (isset($_POST['saveMeal'])) {
            $stmt = $con->prepare("INSERT INTO meals (user_id, recipes_id, meal_time) VALUES (?, ?, NOW())");
            $stmt->execute([
                $user_id, $lastID
            ]);
        }

        header("Location: nutrition_plan.php");
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

        <?php include("components/darkModeButton.html"); ?>
    </header>
    <body class="z-10 bg-white">
        <div class="mb-20 mx-[40px]">
            <h2 class="text-2xl text-black">Rezept hinzufügen</h2>
            <hr class="my-5">
            <form method="POST" action="add_recipes.php">
                <div class="flex flex-col w-full gap-5">
                    <input type="text" name="name" placeholder="Rezeptname" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <textarea name="description" placeholder="Beschreibung" class="px-3 py-1 rounded-[15px] outline-none outline-gray-300 h-[150px]"></textarea>
                    <input type="number" name="calories" placeholder="Gesamt Kalorien" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <input type="number" name="amountInGrams" placeholder="Menge in Gramm" class="px-3 py-1 rounded-full outline-none outline-gray-300">
                    <label><input type="checkbox" name="saveMeal" class="mr-2" value="1">Möchten Sie das Rezept direkt als Mahlzeit speichern?</label>
                    <button name="submit" type="submit" class="h-[35px] bg-green-500 text-white rounded-full text-lg outline-green-500">Speichern</button>
                </div>
            </form>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

</html>