<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Document</title>
        <link rel="stylesheet" href="global.css">
    </head>

    <header>
        <?php include("components/profile.html"); ?>
        <?php include("components/navbar.html"); ?>
    </header>

    <body>
        

        

    <div class="mx-auto w-full flex justify-center">
        <div class="mx-[40px]">
            <div class="my-5 text-center">
                <h2 class="text-2xl text-black">Trainings Page</h2>
            </div>

            <div class="flex justify-center">
                <div class="bg-gray-100 w-[400px] h-auto rounded-[15px] p-4 flex flex-col gap-4 shadow-lg">
                    <p class="text-[20px] font-semibold text-center">Liegestütze:</p>
                    <p class="text-[15px] text-gray-700 text-center">
                        Liegestütze sind eine effektive Eigengewichtsübung zur Stärkung der Brust-, Schulter- 
                        und Armmuskulatur. Beginne in einer geraden Körperhaltung, senke dich langsam mit 
                        kontrollierter Bewegung ab und drücke dich kraftvoll nach oben. Achte darauf, den 
                        Rumpf stabil zu halten und nicht ins Hohlkreuz zu fallen. Diese Übung verbessert 
                        Kraft, Stabilität und Ausdauer.
                    </p>
                    <img src="img/liegestütze.png" alt="Liegestütze" class="rounded-lg w-full">
                    <div class="flex justify-center">
                        <iframe width="360" height="202" class="rounded-lg"
                            src="https://www.youtube.com/embed/vSl23jffAAg?si=341glG97D0mDidLL"
                            title="Liegestütze Anleitung"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>

            <br>
            <hr class="my-5">
            <div class="flex justify-center">
                <div class="bg-gray-100 w-[400px] h-auto rounded-[15px] p-4 flex flex-col gap-4 shadow-lg">
                    <p class="text-[20px] font-semibold text-center">Bankdrücken:</p>
                    <p class="text-[15px] text-gray-700 text-center">
                    Bankdrücken ist eine der effektivsten Übungen zum Aufbau von Brust-, Schulter- und Trizepsmuskulatur. 
                    Dabei liegt man auf einer Flachbank und drückt eine Langhantel kontrolliert von der Brust nach oben.
                    Wichtig ist eine stabile Haltung mit aufgesetzten Füßen und einer angespannten Rumpfmuskulatur. 
                    Senke die Hantel langsam bis zur Brust ab und drücke sie explosiv nach oben, ohne die Ellbogen komplett durchzustrecken. 
                    Bankdrücken verbessert nicht nur die Kraft, sondern auch die Körperstabilität und ist ein essenzieller Bestandteil vieler Trainingsprogramme.








                    </p>
                    <img src="img/bank.png" alt="Bankdrücken" class="rounded-lg w-full">
                    <div class="flex justify-center">
                        <iframe width="360" height="202" class="rounded-lg"
                            src="https://www.youtube.com/embed/2qOOGrcxuTE?si=ecegmp6snrdyksBd"
                            title="Liegestütze Anleitung"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
            <hr class="my-5">

        </div>
    </div>








    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

</html>