<?php
// ---- 1) Vérifier si le champ "question" est envoyé ---- //
if (!isset($_POST['question']) || trim($_POST['question']) === "") {
    die("Aucune donnée envoyée.");
}

$message = htmlspecialchars(trim($_POST['question'])); // Sécuriser la saisie

// ---- 2) Lire ou créer le cookie unique ---- //
if (!isset($_COOKIE['user_id'])) {
    // Générer un ID unique de 32 caractères
    $uniqueID = bin2hex(random_bytes(16));
    // Durée : 1 an
    setcookie("user_id", $uniqueID, time() + 365*24*3600, "/", "", false, true);
} else {
    $uniqueID = $_COOKIE['user_id'];
}

// ---- 3) Infos utiles ---- //
$ip = $_SERVER['REMOTE_ADDR'];
$datetime = date("Y-m-d H:i:s");

// ---- 4) Créer la ligne de log ---- //
$logLine = "$datetime | Cookie-ID: $uniqueID | IP: $ip | Message: \"$message\"\n";

// ---- 5) Écrire dans logs.txt ---- //
file_put_contents("logs.txt", $logLine, FILE_APPEND | LOCK_EX);

// ---- 6) Redirection après envoi ---- //
header("Location: mes3.html");
exit;
?>

