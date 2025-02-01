<?php
    require("connection.php");

    if(isset($_POST["submit"])) {

        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($email) || empty($password)) {
            die("Fehler: Alle Felder müssen ausgefüllt werden.");
        }

        $stmt = $con->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userExists) {
            die("Login fehlgeschlagen, Benutzer nicht gefunden.");
        }

        $passwordHashed = $userExists["password"];
        $checkPassword = password_verify($password, $passwordHashed);

        if (!$checkPassword) {
            die("Login fehlgeschlagen, Passwort stimmt nicht überein.");
        }

        session_start();
        $_SESSION["firstname"] = $userExists["firstname"];
        $_SESSION["user_id"] = $userExists["id"];

        header("Location: dashboard.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmelden</title>
    <link rel="stylesheet" href="register.css">
</head>
<body class="flex items-center justify-center h-screen">
<div class="fixed inset-0 bg-cover bg-center bg-no-repeat scale-110 blur-[3px] -z-10" style="background-image: url('img/loginBackgroundImg.png');"></div>
    <form action="login.php" method="POST" class="flex flex-col gap-4">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-center text-center">
                <h1 class="text-3xl font-bold text-white">Anmelden</h1>
                <hr class="w-1/2 border-t-2 border-gray-400 mt-2">
            </div>
            <input placeholder="Email" type="email" name="email" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
            <input placeholder="Passwort" type="password" name="password" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
        </div>
        <button type="submit" name="submit" class="text-center bg-gray-300 w-[300px] h-[45px] rounded-full text-gray-600">Anmelden</button>
    </form>
    <p class="absolute bottom-2 text-gray-600 text-center">by Pascal, Gabriel & Maurice</p>
</body>

<script src="https://cdn.tailwindcss.com"></script>

</html>