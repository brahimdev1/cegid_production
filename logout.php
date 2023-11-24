<?php
// Démarrez la session
session_start();

// Détruisez toutes les variables de session
$_SESSION = array();

// Si vous utilisez des cookies de session, détruisez également le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 6,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruisez la session
session_destroy();

// Redirigez l'utilisateur vers la page de connexion ou une autre page appropriée
header("Location: index.php");
exit();
?>