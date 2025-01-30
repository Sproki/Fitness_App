<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["vorname"] = $_POST["vorname"];
    $_SESSION["nachname"] = $_POST["nachname"];
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Anmeldemaske</title>
    <!-- Link zu deinem externen CSS -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
<div class="background"></div>
    <div class="formInputs">
        <form action="" method="POST">
            <input class="inputField" type="text" name="vorname" id="vorname" placeholder="Vorname..." required>
            <input class="inputField" type="text" name="nachname" id="nachname" placeholder="Nachname..." required>
            <button type="submit" class="button">Anmelden</button>
        </form>
    </div>
    <p class="foundersText">by Pascal, Gabriel & Maurice</p>
</body>
</html>
