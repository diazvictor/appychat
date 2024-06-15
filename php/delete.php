<?php
include_once("config.php");



if(isset($_GET['grupo']) && isset($_GET['message'])){
    $grupoId = $_GET['grupo'];
    $deleteQuery = "DELETE FROM `grupo_messages` WHERE id = '{$_GET['message']}' AND id_group = '{$grupoId}'";
    $runDeleteQuery = mysqli_query($conn, $deleteQuery);
    if(!$runDeleteQuery){
        header("location: ../grupo?id=$grupoId");
    }else{
        header("location: ../grupo?id=$grupoId");
    }
    die();
}


if(isset($_GET['message'])){
    $messageId = $_GET['message'];
    $deleteQuery = "DELETE FROM `messages` WHERE messages_id = '{$messageId}'";
    $runDeleteQuery = mysqli_query($conn, $deleteQuery);
    $user = $_GET['userid'];
    if(!$runDeleteQuery){
        echo "Query Failed";
    }else{
        header("location: ../messages?userid=$user");
    }
    die();
}

?>