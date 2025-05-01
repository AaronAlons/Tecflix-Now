<?php
session_start(); // Inicia la sesión para acceder a los datos
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header('Location: index.php'); // Redirige a la página principal
exit;
?>