<?php
    session_start();

    global $con;
    require("autoload.php");

    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    $sql = "SELECT * FROM recipes ORDER BY created_at DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <body>
        <div class="mx-[40px] mb-20">
            <h2 class="text-2xl text-black">Öffentliche Rezepte</h2>
            <h3 class="text-lg text-gray-600">Wähle ein Rezept aus (klicke drauf)</h3>
            <hr class="my-5">
            <?php foreach ($recipes as $recipe): ?>
                <div class="w-full h-[150px] bg-gray-100 rounded-[15px] flex mb-3 cursor-pointer recipe-item"
                     data-recipe-id="<?= htmlspecialchars($recipe['id']) ?>">
                    <img src="https://placehold.co/50x100" class="left-0 w-auto h-full rounded-[15px]">
                    <article class="overflow-scroll w-full my-5 mx-3">
                        <h1><?= htmlspecialchars($recipe['name']) ?></h1>
                        <p><?= htmlspecialchars($recipe['description']) ?? "Keine Beschreibung angegeben" ?></p>
                        <br>
                        <p>Kalorien: <?= htmlspecialchars($recipe['calories']) ?? "N/A" ?> kcal</p>
                        <p>Menge: <?= htmlspecialchars($recipe['amount_in_grams']) ?? "N/A" ?> g</p>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

    <script>
        document.querySelectorAll('.recipe-item').forEach(item => {
            item.addEventListener('click', function () {
                let recipeId = this.getAttribute('data-recipe-id');

                fetch('save_meal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ recipe_id: recipeId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "nutrition_plan.php"
                        } else {
                            alert("Fehler: " + data.message);
                        }
                    })
                    .catch(error => console.error('Fehler:', error));
            });
        });
    </script>

</html>