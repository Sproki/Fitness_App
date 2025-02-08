<?php
session_start();

global $con;
require("autoload.php");

$userId = $_SESSION['user_id'];
$consumedCalories = (int) $_POST['caloriesConsumed']; // Umwandlung in eine Zahl zur Sicherheit
$consumedCaloriesToday = (int) $_SESSION['consumedCaloriesToday'];
$today = date("Y-m-d");

$sql = "UPDATE statistic 
    SET `value` = `value` + :calories 
    WHERE user_id = :userId 
    AND `key` = 'calories_consumed' 
    AND date = :today";

$stmt = $con->prepare($sql);
$stmt->execute([':userId' => $userId, ':calories' => $consumedCalories, ':today' => $today]);

header("location:diet_plan.php");