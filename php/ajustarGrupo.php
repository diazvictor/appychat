<?php
include_once("config.php");
session_start();

if(isset($_POST["id"])){

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $getGrupo = "SELECT * FROM grupo WHERE id = '$id'";
    $runGetGrupo = mysqli_query($conn, $getGrupo);
    $assoc = mysqli_fetch_array($runGetGrupo);

    $titulo = ($_POST['titulo'] != "") ? $_POST['titulo'] : $assoc[2];
    $contenido = ($_POST['contenido'] != "") ? $_POST['contenido'] : $assoc[3];
    $image = (!empty($_FILES['imagen'])) ? $_FILES['imagen'] : null;
    $fondo = (!empty($_FILES['fondo'])) ? $_FILES['fondo'] : null;

    $uniqueFondoName = $assoc[5];
    $uniqueImageName = $assoc[4];

    if($image['size'] > 0){
        $uniqueImageName = uploadImage($image);
    }

    if($fondo['size'] > 0){
        $uniqueFondoName = uploadImage($fondo);
    }

    $saveMsgQuery = "UPDATE grupo SET titulo = '$titulo', descripcion = '$contenido', imagen = '$uniqueImageName', fondo = '$uniqueFondoName' WHERE id = '$id'";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if(!$runSaveQuery){
        echo "query Failed";
    }

    header("Location: ../grupo?id=$id");
}

function uploadImage($image){
    $imageName = $image['name'];
    $imageTempName = $image['tmp_name'];
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
        move_uploaded_file($imageTempName, "../images/Grupos/" . $uniqueImageName);

        return $uniqueImageName;
    }
}
