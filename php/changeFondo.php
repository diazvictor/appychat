<?php


include_once("config.php");
session_start();
$uploader = $_SESSION['id'];

$idUsuario = $_POST['id'];
$fondo = $_FILES['file'];

$fondo = uploadImage($fondo);


$mensajeriaQuery = "SELECT * FROM mensajeria WHERE id_outgoing = '$idUsuario' AND id_incoming = '$uploader' OR id_outgoing = '$uploader' AND id_incoming = '$idUsuario'";
$runMensajeria = mysqli_query($conn, $mensajeriaQuery);
$mensajeria = mysqli_fetch_assoc($runMensajeria);

$updateMensajeriaFondo = "UPDATE mensajeria SET fondo = '$fondo' WHERE id = '{$mensajeria['id']}'";
$runUpdateMensajeriaFondo = mysqli_query($conn, $updateMensajeriaFondo);


if (!$runUpdateMensajeriaFondo) {
    // JSON response code & success message
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 400);
    echo json_encode(array("message" => "Error al reportar"));
    die();
} else {
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 200);
	echo json_encode(array("message" => "Fondo cambiado correctamente"));
    die();
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
        move_uploaded_file($imageTempName, "../images/Fondos/" . $uniqueImageName);

        return $uniqueImageName;
    }
}
