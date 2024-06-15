<?php

if($_GET['id']){
    include_once("config.php");
    session_start();
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM `notifications` WHERE `id` = '{$id}' && `id_user` = '{$_SESSION["id"]}'";
    $runDeleteQuery = mysqli_query($conn, $deleteQuery);
    if(!$runDeleteQuery){
        echo "Query Failed";
    }else{
        header("location: ../notifications");
    }
}