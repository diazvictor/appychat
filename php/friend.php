<?php



include_once("config.php");
session_start();

if (isset($_GET["add"])) {
    $solicitado = mysqli_real_escape_string($conn, $_GET['add']);
    $solicitante = $_SESSION['id'];

    $sql = "INSERT INTO amistades (id_solicitante, id_solicitado, amistad_status) VALUES ('$solicitante', '$solicitado', 1)";

    $solicitanteData = "SELECT * FROM users WHERE id = $solicitante";
    $runSolicitanteQuery = mysqli_query($conn, $solicitanteData);
    $solicitanteData = mysqli_fetch_assoc($runSolicitanteQuery);

    
    $submitSql = mysqli_query($conn, $sql);
    $idAmistad = mysqli_insert_id($conn);

    $notifyQuery = "INSERT INTO notifications (id_user, message, type, link) VALUES ('{$solicitado}', 'El usuario {$solicitanteData['firstName']} {$solicitanteData['lastName']} te ha enviado una solicitud de amistad', 2, '{$idAmistad}')";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);


    if ($submitSql) {
        header("location: ../profile?id=$solicitado");
    } else {
        header("location: ../profile?id=$solicitado");
    }
}

if (isset($_GET["accept"])) {
    $aceptado = mysqli_real_escape_string($conn, $_GET['accept']);
    $aceptante = $_SESSION['id'];

    $selectAmistad = "SELECT * FROM amistades WHERE id = $aceptado";
    $runSelectAmistad = mysqli_query($conn, $selectAmistad);
    $amistadData = mysqli_fetch_assoc($runSelectAmistad);

    $sql = "UPDATE amistades SET amistad_status = 2 WHERE id = $aceptado";
    $submitSql = mysqli_query($conn, $sql);

    $idUsuarioAceptado = ($amistadData['id_solicitante'] == $aceptante) ? $amistadData['id_solicitado'] : $amistadData['id_solicitante'];

    $usuarioAceptado = "SELECT * FROM users WHERE id = {$idUsuarioAceptado}";
    $runUsuarioAceptado = mysqli_query($conn, $usuarioAceptado);
    $usuarioAceptado = mysqli_fetch_assoc($runUsuarioAceptado);

    $usuarioAceptante = "SELECT * FROM users WHERE id = $aceptante";
    $runUsuarioAceptante = mysqli_query($conn, $usuarioAceptante);
    $usuarioAceptante = mysqli_fetch_assoc($runUsuarioAceptante);
    
    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$aceptante}', 'Aceptaste la solicitud de {$usuarioAceptado['firstName']} {$usuarioAceptado['lastName']}', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);
    
    
    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$idUsuarioAceptado}', '{$usuarioAceptante['firstName']} {$usuarioAceptante['lastName']} acepto la solicitud de amistad', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);


    if ($submitSql) {
        header("location: ../profile?id={$amistadData['id_solicitante']}");
    } else {
        header("location: ../profile?id={$amistadData['id_solicitante']}");
    }
}



if (isset($_GET["cancel"])) {
    $cancelado = mysqli_real_escape_string($conn, $_GET['cancel']);
    $cancelante = $_SESSION['id'];

    $sql = "DELETE FROM amistades WHERE id = $cancelado";
    $submitSql = mysqli_query($conn, $sql);

    if ($submitSql) {
        header("location: ../profile?id=$cancelante");
    } else {
        header("location: ../profile?id=$cancelante");
    }
}


if (isset($_GET["quit"])) {
    $cancelado = mysqli_real_escape_string($conn, $_GET['quit']);
    $cancelante = $_SESSION['id'];

    $selectAmistad = "SELECT * FROM amistades WHERE id = $cancelado";
    $runSelectAmistad = mysqli_query($conn, $selectAmistad);
    $amistadData = mysqli_fetch_assoc($runSelectAmistad);

    $canceladoUsuarioId = ($amistadData['id_solicitante'] == $cancelante) ? $amistadData['id_solicitado'] : $amistadData['id_solicitante'];

    $sql = "DELETE FROM amistades WHERE id = $cancelado";
    $submitSql = mysqli_query($conn, $sql);

    $usuarioCancelado = "SELECT * FROM users WHERE id = {$canceladoUsuarioId}";
    $runUsuarioCancelado = mysqli_query($conn, $usuarioCancelado);
    $usuarioCancelado = mysqli_fetch_assoc($runUsuarioCancelado);
    
    $usuarioCancelante = "SELECT * FROM users WHERE id = $cancelante";
    $runUsuarioCancelante = mysqli_query($conn, $usuarioCancelante);
    $usuarioCancelante = mysqli_fetch_assoc($runUsuarioCancelante);

    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$cancelante}', 'Eliminaste al usuario {$usuarioCancelado['firstName']} {$usuarioCancelado['lastName']}', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);

    
    $notifyCancelado = "INSERT INTO notifications (id_user, message, type) VALUES ('{$canceladoUsuarioId}', 'El usuario {$usuarioCancelante['firstName']} {$usuarioCancelante['lastName']} te elimino', 0)";
    $runNotifyCancelado = mysqli_query($conn, $notifyCancelado);

    if ($submitSql) {
        header("location: ../profile?id=$cancelante");
    } else {
        header("location: ../profile?id=$cancelante");
    }
}




