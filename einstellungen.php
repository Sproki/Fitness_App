<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Document</title>
    </head>

    <header>
        <?php include("components/navbar.html"); ?>
    </header>

    <body>
        <div class="flex items-center flex-col justify-center min-h-screen bg-gray-100">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                <h2 class="text-xl font-semibold mb-4">Profilbild aktualisieren</h2>
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <img id="profileImage" src="<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'https://placehold.co/100x100'; ?>" alt="Profilbild" class="w-full h-full rounded-full object-cover border border-gray-300">
                </div>
                <form action="upload.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="file" name="profile_image" id="fileInput" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <button type="button" onclick="document.getElementById('fileInput').click()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Bild auswählen</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Hochladen</button>
                </form>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center my-5">
                <h2 class="text-xl font-semibold mb-4">Persönliche Daten aktualisieren</h2>
                <form action="updateInformation.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="text" name="firstname" placeholder="Vorname aktualisieren..." class="w-[80%] bg-gray-200 p-2 px-5 rounded-full text-black outline-none">
                    <input type="text" name="lastname" placeholder="Nachname aktualisieren..." class="w-[80%] bg-gray-200 p-2 px-5 rounded-full text-black outline-none">
                    <input type="text" name="email" placeholder="Email aktualisieren..." class="w-[80%] bg-gray-200 p-2 px-5 rounded-full text-black outline-none">
                    <input type="text" name="password" placeholder="Passwort aktualisieren..." class="w-[80%] bg-gray-200 p-2 px-5 rounded-full text-black outline-none">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 mt-2 w-[80%]">Aktualisieren</button>
                </form>
            </div>
            <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg mt-2 w-96" onclick="window.location.href='logout.php'">Ausloggen</button>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="scripts/darkModeHandler.js"></script>
    <script src="scripts/tailwind.config.js"></script>

    <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profileImage').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>
</html>