<?php

include_once("config.php");
session_start();

$id = $_SESSION['id'];

// Delete from others tables

$deleteAmistades = "DELETE FROM amistades WHERE id_solicitado = '$id' OR id_solicitante = '$id'";
$deleteComentarios = "DELETE FROM comentarios_perfil WHERE id_usuario = '$id' OR id_comentado = '$id'";
$deleteDetails = "DELETE FROM details_user WHERE id_user = '$id'";
$deleteFollow = "DELETE FROM follow WHERE user_id = '$id' OR followed_id = '$id'";
$deleteMembresia = "DELETE FROM membresias WHERE id_user = '$id'";
$deleteMessages = "DELETE FROM messages WHERE outgoing = '$id' OR incoming = '$id'";
$deleteNotifications = "DELETE FROM notifications WHERE id_user = '$id'";
$deleteReports = "DELETE FROM reports WHERE reported_id = '$id' OR reporter_id = '$id'";
$deleteVistas = "DELETE FROM vistas_perfil WHERE id_user = '$id' OR id_profile = '$id'";
$deletePublicaciones = "DELETE FROM publicaciones WHERE id_usuario = '$id'";
$deletePublicacionesComentarios = "DELETE FROM publicaciones_comentarios WHERE id_user = '$id'";
$deletePublicacionesLikes = "DELETE FROM publicaciones_likes WHERE id_user = '$id'";
$deleteGrupo = "DELETE FROM grupo WHERE id_usuario = '$id'";
$deleteGrupoMessages = "DELETE FROM grupo_messages WHERE outgoing = '$id'";
$deleteAnuncios = "DELETE FROM anuncios WHERE id_usuario = '$id'";
$deleteAnunciosComentarios = "DELETE FROM anuncios_comentarios WHERE id_user = '$id'";
$deleteAnunciosLikes = "DELETE FROM anuncios_likes WHERE id_user = '$id'";
$deleteMensajeria = "DELETE FROM mensajeria WHERE id_incoming = '$id' OR id_outgoing = '$id'";

mysqli_query($conn, $deleteAmistades);
mysqli_query($conn, $deleteComentarios);
mysqli_query($conn, $deleteDetails);
mysqli_query($conn, $deleteFollow);
mysqli_query($conn, $deleteMembresia);
mysqli_query($conn, $deleteMessages);
mysqli_query($conn, $deleteNotifications);
mysqli_query($conn, $deleteReports);
mysqli_query($conn, $deleteVistas);
mysqli_query($conn, $deletePublicaciones);
mysqli_query($conn, $deletePublicacionesComentarios);
mysqli_query($conn, $deletePublicacionesLikes);
mysqli_query($conn, $deleteGrupo);
mysqli_query($conn, $deleteGrupoMessages);
mysqli_query($conn, $deleteAnuncios);
mysqli_query($conn, $deleteAnunciosComentarios);
mysqli_query($conn, $deleteAnunciosLikes);
mysqli_query($conn, $deleteMensajeria);



$deleteAccount = "DELETE FROM users WHERE id = '$id'";
$deleteResult = mysqli_query($conn, $deleteAccount);

// Execute

if ($deleteResult) {
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 200);
    echo json_encode(array("message" => "Cuenta eliminada"));
    session_destroy();
    exit();
} else {
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 200);
    echo json_encode(array("error" => "No fue posible eliminar la cuenta"));
    session_destroy();
    exit();
}
