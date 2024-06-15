<?php



include_once("config.php");
session_start();


if(isset($_GET["like"])){
    $likeExist = "SELECT * FROM `publicaciones_likes` WHERE id_publicacion = '{$_GET["like"]}' AND id_user = '{$_SESSION["id"]}'"; 
    $runLikeExist = mysqli_query($conn, $likeExist);
    if(mysqli_num_rows($runLikeExist) > 0){
        $like = "DELETE FROM `publicaciones_likes` WHERE id_publicacion = '{$_GET["like"]}' AND id_user = '{$_SESSION["id"]}'";
        $runLike = mysqli_query($conn, $like);
        if($runLike){
            header("Location: ../publicaciones");
            die();
        }
        die();
    }

    $like = "INSERT INTO `publicaciones_likes`(`id_publicacion`, `id_user`, `punto`) VALUES ('{$_GET["like"]}','{$_SESSION["id"]}', 1)";
    $runLike = mysqli_query($conn, $like);
    if($runLike){
        header("Location: ../publicaciones");
        die();
    }
    die();
}

if (isset($_GET["delete"])) {
	$id_publicacion = mysqli_real_escape_string($conn, $_GET["delete"]);
	$sql = "
		DELETE FROM publicaciones WHERE id = $id_publicacion AND id_usuario = {$_SESSION["id"]};
	";
	$query = mysqli_query($conn, $sql);
	if ($query) {
        header("location: ../publicaciones");
    } else {
        header("location: ../publicaciones");
    }
}

if(isset($_GET["dislike"])){
    $likeExist = "SELECT * FROM `publicaciones_likes` WHERE id_publicacion = '{$_GET["dislike"]}' AND id_user = '{$_SESSION["id"]}'"; 
    $runLikeExist = mysqli_query($conn, $likeExist);
    if(mysqli_num_rows($runLikeExist) > 0){
        $like = "DELETE FROM `publicaciones_likes` WHERE id_publicacion = '{$_GET["dislike"]}' AND id_user = '{$_SESSION["id"]}'";
        $runLike = mysqli_query($conn, $like);
        if($runLike){
            header("Location: ../publicaciones");
            die();
        }
        die();
    }

    $like = "INSERT INTO `publicaciones_likes`(`id_publicacion`, `id_user`, `punto`) VALUES ('{$_GET["dislike"]}','{$_SESSION["id"]}', -1)";
    $runLike = mysqli_query($conn, $like);
    if($runLike){
        header("Location: ../publicaciones");
        die();
    }
    die();
}
