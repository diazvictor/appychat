<?php
if (isset($_POST["form"])) {
    include_once("config.php");
    session_start();

    $id = $_SESSION["id"];
    $titulo = ($_POST['titulo'] != "") ? $_POST['titulo'] : "";
    $contenido = ($_POST['contenido'] != "") ? $_POST['contenido'] : "";
    $image = (!empty($_FILES['imagen'])) ? $_FILES['imagen'] : null;
    $fondo = (!empty($_FILES['fondo'])) ? $_FILES['fondo'] : null;

    if($titulo == "" || $contenido == ""){
        echo "No se puede enviar un mensaje vacío";
        exit();
    }

    $uniqueFondoName = "";
    $uniqueImageName = "";

    if($image['size'] > 0){
        $uniqueImageName = uploadImage($image);
    }

    if($fondo['size'] > 0){
        $uniqueFondoName = uploadImage($fondo);
    }

    $saveMsgQuery = "INSERT INTO `grupo` (id_usuario,titulo,descripcion,imagen,fondo)
    VALUES('$id','$titulo', '$contenido', '$uniqueImageName', '$uniqueFondoName')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if (!$runSaveQuery) {
        echo "query Failed";
        exit();
    }

	// Invitar al usuario creador del grupo
	$grupo = $conn->insert_id;
	$sql = "INSERT INTO grupo_invites (id_grupo, id_invitado, estado) VALUES ('$grupo', '$id', 1)";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        header("location: grupos");
        exit();
    } else {
        $sql = "SELECT * FROM grupo WHERE id = '{$grupo}'";
        $result = mysqli_query($conn, $sql);
        $grupoData = mysqli_fetch_assoc($result);

        $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$id}', 'Te has unido al grupo: {$grupoData['titulo']}', 0)";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);

        header("location: grupo?id=$grupo");
        exit();
    }

}

if (isset($_GET['comentario'])) {
    include_once("config.php");
    session_start();

    $id = $_SESSION["id"];
    $comentario = mysqli_real_escape_string($conn, $_POST['comentario']);
    $id_publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']);

    $saveMsgQuery = "INSERT INTO `anuncios_comentarios` (id_user,id_publicacion,texto)
    VALUES('$id','$id_publicacion', '$comentario')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if(!$runSaveQuery){
        echo "query Failed";
    }

    header("Location: ../foro");
}

function uploadImage($image){
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
        move_uploaded_file($imageTempName, "../images/Grupos/" . $uniqueImageName);

        return $uniqueImageName;
    }
}
