<?php



include_once("config.php");
session_start();

if (isset($_POST["comentario"])) {
    $usuario = mysqli_real_escape_string($conn, $_POST['user']);
    $comentado = mysqli_real_escape_string($conn, $_POST['comentado']);
    $comentario = mysqli_real_escape_string($conn, $_POST['mensaje']);

    $sql = "INSERT INTO `comentarios_perfil` (id_usuario,id_comentado,comentario) VALUES ('$usuario','$comentado','$comentario')";

    $runSql = mysqli_query($conn, $sql);

    if (!$runSql) {
        echo "query Failed";
    }

    echo 'success';
}
