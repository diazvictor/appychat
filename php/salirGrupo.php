<?php



include_once("config.php");
session_start();


if (isset($_GET["id"])) {
    $grupo = mysqli_real_escape_string($conn, $_GET['id']);
    $usuarioSaliente = $_SESSION['id'];

    $sql = "DELETE FROM grupo_invites WHERE id_invitado = $usuarioSaliente AND id_grupo = $grupo";
    $submitSql = mysqli_query($conn, $sql);

    // Notify

    $usuarioSalienteData = "SELECT * FROM grupo WHERE id = $grupo";
    $runUsuarioSaliente = mysqli_query($conn, $usuarioSalienteData);
    $usuarioSalienteData = mysqli_fetch_assoc($runUsuarioSaliente);

    $notify = "INSERT INTO notifications (id_user, message, type) VALUES ('{$usuarioSaliente}', 'Abandonaste el grupo {$usuarioSalienteData['titulo']}', 0)";
    $runNotify = mysqli_query($conn, $notify);

    if ($submitSql) {
        header("location: ../grupos");
    } else {
        header("location: ../grupos");
    }
}
