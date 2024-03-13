<?php
// Iniciar sesión
session_start();

// Destruir la sesión actual
session_destroy();
if (ini_set('session.use_cookies', 1)) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']);
}
// Prevenir el caché de la página para evitar que el usuario vuelva atrás y acceda a páginas anteriores logeadas
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir al usuario a la página de inicio de sesión
header("Location: ../index.html");
exit();
?>
