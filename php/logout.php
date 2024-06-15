<?php
include_once("login.php");
$statusOffline = "Offline";
$logoutQuery = "UPDATE `users` SET status = '{$statusOffline}' WHERE id = '{$_SESSION["id"]}'";
$runLogoutQuery = mysqli_query($conn, $logoutQuery);

if ($runLogoutQuery) {
    unset($_SESSION["id"]); // Eliminar la variable de sesión sin argumentos
    session_destroy();
    header("location: ../home");
}
?>
