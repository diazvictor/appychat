<?php



include_once("config.php");
session_start();

if (isset($_GET["decline"])) {
    $chatRechazado = mysqli_real_escape_string($conn, $_GET['decline']);
    $rechazante = $_SESSION['id'];

    $rechazanteData = "SELECT * FROM users WHERE id = $rechazante";
    $runRechazante = mysqli_query($conn, $rechazanteData);
    $rechazanteData = mysqli_fetch_assoc($runRechazante);

    $selectChat = "SELECT * FROM mensajeria WHERE id = $chatRechazado";
    $runSelectChat = mysqli_query($conn, $selectChat);
    $chatData = mysqli_fetch_assoc($runSelectChat);


    $deleteChat = "DELETE FROM mensajeria WHERE id = $chatRechazado";
    $submitSql = mysqli_query($conn, $deleteChat);

    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$chatData['id_outgoing']}', 'El usuario {$rechazanteData['firstName']} {$rechazanteData['lastName']} rechazo tu peticion para hablar', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);


    if ($submitSql) {
        header("location: ../bandeja");
    } else {
        header("location: ../bandeja");
    }
}

if (isset($_GET["accept"])) {
    $chatAceptado = mysqli_real_escape_string($conn, $_GET['accept']);
    $aceptante = $_SESSION['id'];

    $aceptanteData = "SELECT * FROM users WHERE id = $aceptante";
    $runAceptante = mysqli_query($conn, $aceptanteData);
    $aceptanteData = mysqli_fetch_assoc($runAceptante);

    $selectChat = "SELECT * FROM mensajeria WHERE id = $chatAceptado";
    $runSelectChat = mysqli_query($conn, $selectChat);
    $chatData = mysqli_fetch_assoc($runSelectChat);

    $modifyChat = "UPDATE mensajeria SET estado = 1 WHERE id = $chatAceptado";
    $submitSql = mysqli_query($conn, $modifyChat);

    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$chatData['id_outgoing']}', 'El usuario {$aceptanteData['firstName']} {$aceptanteData['lastName']} acepto tu peticion para hablar', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);


    if ($submitSql) {
        header("location: ../bandeja");
    } else {
        header("location: ../bandeja");
    }
}

if (isset($_GET["delete"])) {
	$id_mensajeria = mysqli_real_escape_string($conn, $_GET["delete"]);
	$sql = "
		DELETE FROM mensajeria WHERE id_incoming = $id_mensajeria OR id_outgoing = $id_mensajeria;
	";
	$query = mysqli_query($conn, $sql);
	if ($query) {
        header("location: ../users");
    } else {
        header("location: ../users");
    }
}

if (isset($_GET["block"])) {
    $chatBloqueado = mysqli_real_escape_string($conn, $_GET['block']);
    $bloqueador = $_SESSION['id'];

    $bloqueadorData = "SELECT * FROM users WHERE id = $bloqueador";
    $runBloqueador = mysqli_query($conn, $bloqueadorData);
    $bloqueadorData = mysqli_fetch_assoc($runBloqueador);

    $selectChat = "SELECT * FROM mensajeria WHERE id_incoming = $chatBloqueado OR id_outgoing = $chatBloqueado";
    $runSelectChat = mysqli_query($conn, $selectChat);
    $chatData = mysqli_fetch_assoc($runSelectChat);

    if($chatData['estado'] == 1 || $chatData['estado'] == 0){
        $blockedID = ($bloqueador == $chatData['id_incoming']) ? $chatData['id_outgoing'] : $chatData['id_incoming'];

        $modifyChat = "UPDATE mensajeria SET estado = 2 WHERE id = {$chatData['id']}";
        $submitSql = mysqli_query($conn, $modifyChat);

        $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$blockedID}', 'El usuario {$bloqueadorData['firstName']} {$bloqueadorData['lastName']} te ha bloqueado', 0)";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);
    } else {
        $blockedID = ($bloqueador == $chatData['id_incoming']) ? $chatData['id_outgoing'] : $chatData['id_incoming'];

        $modifyChat = "UPDATE mensajeria SET estado = 1 WHERE id = {$chatData['id']}";
        $submitSql = mysqli_query($conn, $modifyChat);

        $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$blockedID}', 'El usuario {$bloqueadorData['firstName']} {$bloqueadorData['lastName']} te ha desbloqueado', 0)";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);
    }

    if(isset($_GET['from'])){
        header("location: ../messages?userid=$chatBloqueado");
        die();
    } else {
        header("location: ../messages?userid=$chatBloqueado");
        die();
    }

    if ($submitSql) {
        header("location: ../bandeja");
    } else {
        header("location: ../bandeja");
    }
}



