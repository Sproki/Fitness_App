<!DOCTYPE html>
<html lang="en" class="">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Document</title>
        <link rel="stylesheet" href="global.css">
    </head>
    <header>
        <?php include("components/profile.html"); ?>
        <?php include("components/navbar.html"); ?>

        <?php include("components/darkModeButton.html"); ?>
    </header>
    <body>
        <div class="mx-[40px]">
            <div class="my-5">
                <h2 class="text-2xl text-black">Willkommen zurück💪</h2>
                <h1 class="text-3xl text-black font-bold">Pascal</h1>
            </div>
            <div class="grid grid-flow-col grid-rows-2 gap-[10px]">
                <div class="bg-gray-100 w-[150px] h-[150px] row-span-2 rounded-[15px]">

                </div>
                
                <div class="bg-gray-100 w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                    <div class="flex items-center gap-2">
                    <p class="text-3xl">🔥</p>
                    <div>
                        <p class="text-[11px] font-semibold">Heute verbrannt</p>
                        <p class="text-[11px] font-bold">300 kcal</p>
                    </div>
                    </div>
                </div>
                
                <div class="bg-gray-100 w-full h-[70px] col-span-2 rounded-[15px] flex items-center px-4">
                    <div class="flex items-center gap-2">
                        <p class="text-3xl">🏃</p>
                        <div>
                            <p class="text-[11px] font-semibold">Heute gelaufen</p>
                            <p class="text-[11px] font-bold">12 Kilometer</p>
                        </div>
                    </div>
                </div>
            </div>


            <hr class="my-5">


            <div>
                <h2 class="text-xl text-black mb-3">Deine verdienten Medallien</h2>
                <div class="bg-gray-100 w-full h-[100px] rounded-[15px] flex items-center justify-center overflow-x-auto">
                    <div class="flex gap-[10px]">
                        <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                        <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                        <img src="https://placehold.co/100x100" class="rounded-full w-[80px] h-[80px]">
                    </div>
                </div>
            </div>
            <hr class="my-5">

            <div>
                <h2 class="text-xl mb-5">Beispielvideos:</h2>
                <div class="grid grid-cols-2 gap-[20px]">              
                    <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                        <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/2qOOGrcxuTE?si=ecegmp6snrdyksBd" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                        </iframe>
                    </div>
                    <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-1">
                        <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/vSl23jffAAg?si=341glG97D0mDidLL" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                        </iframe>
                    </div>
                    <div class="bg-gray-100 w-full h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden col-span-2 row-2">
                        <iframe 
                        class="w-full h-full rounded-[15px]"
                        src="https://www.youtube.com/embed/uXFjLXgIcYc?si=y3_1UbWkfTkFn29M" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                        </iframe>
                    </div>
                </div>             
            </div>
             <hr class="my-5">
        </div>
    </body>
        <script src="https://cdn.tailwindcss.com"></script>

        <script src="scripts/darkModeHandler.js"></script>
        <script src="scripts/tailwind.config.js"></script>
</html>