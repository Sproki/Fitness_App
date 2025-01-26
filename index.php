<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Document</title>
        <link rel="stylesheet" href="global.css">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <header>
        <?php include("components/profile.html"); ?>
        <?php include("components/navbar.html"); ?>
    </header>
    <body>
        <div class="mx-[40px]">
            <div class="my-5">
                <h2 class="text-2xl">Willkommen zurück💪</h2>
                <h1 class="text-3xl font-bold">Pascal</h1>
            </div>
            <div class="grid grid-flow-col grid-rows-2 gap-[10px]">
                <div class="bg-gray-300 w-[150px] h-[150px] row-span-2 rounded-[15px]"></div>
                <div class="bg-gray-300 w-[150px] h-[70px] col-span-2 rounded-[15px]">
                    <div class="grid grid-flow-col grid-rows-2">
                        <p class="text-center row-span-2">🔥</p>
                        <p class="text-[12px] col-span-2">Verbrannte Kalorien</p>
                        <p class="text-[12px] col-span-2">300 kcal</p>
                    </div>
                </div>
                <div class="bg-gray-300 w-[150px] h-[70px] col-span-2 rounded-[15px]"></div>
            </div>
        </div>
    </body>
</html>