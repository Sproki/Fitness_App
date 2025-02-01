<?php
    require("connection.php");

    if (isset($_POST["submit"])) {

        $firstname = trim($_POST["firstname"] ?? '');
        $lastname = trim($_POST["lastname"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            die("Fehler: Alle Felder müssen ausgefüllt werden.");
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $userAlreadyExist = $stmt->fetchColumn();

        if ($userAlreadyExist) {
            die("Fehler: Ein Benutzer mit dieser E-Mail existiert bereits.");
        }

        $stmt = $con->prepare("INSERT INTO users (firstname, lastname, email, password, joined) 
                            VALUES (:firstname, :lastname, :email, :password, SYSDATE())");
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $passwordHashed);
        
        if ($stmt->execute()) {
            session_start();
            $_SESSION["firstname"] = $firstname;

            header("Location: index.php");
            exit;
        } else {
            die("Fehler: Beim Speichern in der Datenbank ist ein Problem aufgetreten.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
    <link rel="stylesheet" href="register.css">
</head>
<body class="flex items-center justify-center h-screen">
<div class="fixed inset-0 bg-cover bg-center bg-no-repeat scale-110 blur-[3px] -z-10" style="background-image: url('img/loginBackgroundImg.png');"></div>
    <form action="register.php" method="POST" class="flex flex-col gap-4">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-center text-center">
                <h1 class="text-3xl font-bold text-white">Registrieren</h1>
                <hr class="w-1/2 border-t-2 border-gray-400 mt-2">
            </div>
            <input placeholder="Vorname" type="text" name="firstname" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
            <input placeholder="Nachname" type="text" name="lastname" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
            <input placeholder="Email" type="email" name="email" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
            <input placeholder="Passwort" type="password" name="password" class="w-[300px] h-[45px] rounded-full bg-gray-600 p-4 outline-none text-white">
        </div>
        <button name="submit" class="text-center bg-gray-300 w-[300px] h-[45px] rounded-full text-gray-600">Account Erstellen</button>
        <a href="index.php" class="text-center text-gray-300 hover:text-gray-600">Anmelden</a>
    </form>
    <p class="absolute bottom-2 text-gray-600 text-center">by Pascal, Gabriel & Maurice</p>
</body>

<script src="https://cdn.tailwindcss.com"></script>

</html>