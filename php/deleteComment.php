<?php

include_once("config.php");
session_start();

if (isset($_GET["id"])) {
    $deleteComment = "DELETE FROM comentarios_perfil WHERE id = '" . $_GET["id"] . "'";
    $runDeleteComment = mysqli_query($conn, $deleteComment);
    header("location: ../profile?id=" . $_GET['from']);
}
