<?php
include_once("config.php");

if(isset($_POST['send'])){
    $id_usuario = mysqli_real_escape_string($conn, $_POST['outgoing']);
    $grupo = mysqli_real_escape_string($conn, $_POST['incoming']);
    $message = mysqli_real_escape_string($conn, $_POST['typingField']);
    $image = $_FILES['image'];

    $uniqueImageName = "";
    if(isset($_FILES['image'])){
        $uniqueImageName = uploadImage($image);
    }

    $saveMsgQuery = "INSERT INTO `grupo_messages` (outgoing,id_group,message, imagen)
    VALUES('$id_usuario','$grupo', '$message', '$uniqueImageName')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);
    
    if(!$runSaveQuery){
        echo "query Failed";
    }

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
?>
