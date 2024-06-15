<?php



include_once("config.php");
session_start();


if(isset($_GET["like"])){
    $likeExist = "SELECT * FROM `anuncios_likes` WHERE id_publicacion = '{$_GET["like"]}' AND id_user = '{$_SESSION["id"]}'"; 
    $runLikeExist = mysqli_query($conn, $likeExist);
    if(mysqli_num_rows($runLikeExist) > 0){
        $like = "DELETE FROM `anuncios_likes` WHERE id_publicacion = '{$_GET["like"]}' AND id_user = '{$_SESSION["id"]}'";
        $runLike = mysqli_query($conn, $like);
        if($runLike){
            header("Location: ../foro");
            die();
        }
        die();
    }

    $like = "INSERT INTO `anuncios_likes`(`id_publicacion`, `id_user`, `punto`) VALUES ('{$_GET["like"]}','{$_SESSION["id"]}', 1)";
    $runLike = mysqli_query($conn, $like);
    if($runLike){
        header("Location: ../foro");
        die();
    }
    die();
}


if(isset($_GET["dislike"])){
    $likeExist = "SELECT * FROM `anuncios_likes` WHERE id_publicacion = '{$_GET["dislike"]}' AND id_user = '{$_SESSION["id"]}'"; 
    $runLikeExist = mysqli_query($conn, $likeExist);
    if(mysqli_num_rows($runLikeExist) > 0){
        $like = "DELETE FROM `anuncios_likes` WHERE id_publicacion = '{$_GET["dislike"]}' AND id_user = '{$_SESSION["id"]}'";
        $runLike = mysqli_query($conn, $like);
        if($runLike){
            header("Location: ../foro");
            die();
        }
        die();
    }

    $like = "INSERT INTO `anuncios_likes`(`id_publicacion`, `id_user`, `punto`) VALUES ('{$_GET["dislike"]}','{$_SESSION["id"]}', -1)";
    $runLike = mysqli_query($conn, $like);
    if($runLike){
        header("Location: ../foro");
        die();
    }
    die();
}
