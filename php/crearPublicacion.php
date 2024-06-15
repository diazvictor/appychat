<?php
if(isset($_POST["form"])){
    include_once("config.php");
    session_start();

    $id = $_SESSION["id"];
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $contenido = mysqli_real_escape_string($conn, $_POST['contenido']);
    $image = $_FILES['imagen'];

    
    $imageName = $image['name'];
    $imageSize = $image['size'];
    $imageTempName = $image['tmp_name'];
    $imageType = $image['type'];
    $explode = explode(".", $imageName);
    $lowercase = strtolower(end($explode));

    // image extension required
    $extension = ["png","gif","jpg","jpeg"];

    // if extension not matched
    if(in_array($lowercase,$extension) == false){
        echo "Este archivo de extensión no está permitido, elija JPG o PNG GIF.";
    }else{
        // random number
        $random = rand(999999999,111111111);
        $time = time();
        // image unique name 
        $uniqueImageName = $random . $time . $imageName;

        // save image
        move_uploaded_file($imageTempName, "../images/Publicaciones/" . $uniqueImageName);
    }

    $saveMsgQuery = "INSERT INTO `publicaciones` (id_usuario,titulo,texto,imagen)
    VALUES('$id','$titulo', '$contenido', '$uniqueImageName')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if(!$runSaveQuery){
        echo "query Failed";
    }

}


if(isset($_GET['comentario'])) {
    include_once("config.php");
    session_start();

    $id = $_SESSION["id"];
    $comentario = mysqli_real_escape_string($conn, $_POST['comentario']);
    $id_publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']);

    $saveMsgQuery = "INSERT INTO `publicaciones_comentarios` (id_user,id_publicacion,texto)
    VALUES('$id','$id_publicacion', '$comentario')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if(!$runSaveQuery){
        echo "query Failed";
    }

    header("Location: ../publicaciones");
}