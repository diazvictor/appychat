<?php

include_once("./php/config.php");
session_start();


if(isset($_GET['user']) && isset($_GET['grupo'])){

    // user join to group


    $myid = $_SESSION['id'];
    $grupo = $_GET['grupo'];

    // Check if are in group.

    $query = "SELECT * FROM `grupo_invites` WHERE id_grupo = '{$grupo}' AND id_invitado = '{$myid}'";

    $run = mysqli_query($conn, $query);

    if(mysqli_num_rows($run) > 0){
        $row = mysqli_fetch_assoc($run);

        if($row['estado'] == 0){
            $query = "UPDATE `grupo_invites` SET `estado` = '1' WHERE `id_grupo` = '{$grupo}' AND `id_invitado` = '{$myid}'";
            $run = mysqli_query($conn, $query);

            if (!$run) {
                header("location: grupo?id=$grupo");
                exit();
            } else {

                $getGrupo = "SELECT * FROM grupo WHERE id = '{$grupo}'";
                $runGrupo = mysqli_query($conn, $getGrupo);
                $grupoData = mysqli_fetch_assoc($runGrupo);

                $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$myid}', 'Te has unido al grupo: {$grupoData['titulo']}', 0)";
                $runNotifyQuery = mysqli_query($conn, $notifyQuery);

                header("location: grupo?id=$grupo");
                exit();
            }
        }

        header("location: grupo?id=$grupo");
        exit();
    }

    
    $query = "INSERT INTO `grupo_invites` (`id_grupo`, `id_invitado`, `estado`) VALUES ('$grupo', '$myid',1)";

    $run = mysqli_query($conn, $query);

    if (!$run) {
        header("location: grupos");
        exit();
    } else {
        

        $getGrupo = "SELECT * FROM grupo WHERE id = '{$grupo}'";
        $runGrupo = mysqli_query($conn, $getGrupo);
        $grupoData = mysqli_fetch_assoc($runGrupo);

        $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$myid}', 'Te has unido al grupo: {$grupoData['titulo']}', 0)";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);

        header("location: grupo?id=$grupo");
        exit();
    }

}

if (isset($_GET["id"]) && isset($_GET["grupo"])) {
   
    $usuario = $_GET['id'];
    $grupo = $_GET['grupo'];

    // Check if session if it's equal to grupo owner

    $query = "SELECT * FROM `grupo` WHERE id_usuario = '{$_SESSION["id"]}' AND id = '{$grupo}'";

    $run = mysqli_query($conn, $query);

    if (!$run) {
        header("location: grupos");
        exit();
    } else {
        $info = mysqli_fetch_assoc($run);
    }
    
    // Check if user is already in group

    $query = "SELECT * FROM `grupo_invites` WHERE id_grupo = '{$grupo}' AND id_invitado = '{$usuario}'";

    $run = mysqli_query($conn, $query);

    if (mysqli_num_rows($run) > 0) {
        header("location: ajustes?id=$grupo&search");
        exit();
    } 

    $query = "INSERT INTO `grupo_invites` (`id_grupo`, `id_invitado`) VALUES ('$grupo', '$usuario')";

    $run = mysqli_query($conn, $query);

    if (!$run) {
        header("location: ajustes?id=$grupo&search");
        exit();
    } else {

        $getGrupo = "SELECT * FROM grupo WHERE id = '{$grupo}'";
        $runGrupo = mysqli_query($conn, $getGrupo);
        $grupoData = mysqli_fetch_assoc($runGrupo);

        $notifyQuery = "INSERT INTO notifications (id_user, message, type, link) VALUES ('{$usuario}', 'Te invitaron al grupo: {$grupoData['titulo']}', 1, {$grupoData['id']})";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);


        header("location: ajustes?id=$grupo&search");
        exit();
    }
} else {
    header("location: grupos");
}
