<?php
include_once("config.php");

if(isset($_POST['send'])){

    $outgoing = mysqli_real_escape_string($conn, $_POST['outgoing']);
    $incoming = mysqli_real_escape_string($conn, $_POST['incoming']);

    // Check if user can message or not.

    $sqlMensajeria = "SELECT * FROM mensajeria WHERE id_incoming = {$incoming} AND id_outgoing = {$outgoing} OR id_incoming = {$outgoing} AND id_outgoing = {$incoming} ";
    $runMensajeria = mysqli_query($conn, $sqlMensajeria);

    if(mysqli_num_rows($runMensajeria) > 0){
        // user can message
        $row = mysqli_fetch_assoc($runMensajeria);
        if($row['estado'] == 0 || $row['estado'] == 2){
            die();
        }
    } else {
        // insert
        $sql = "INSERT INTO mensajeria (id_incoming, id_outgoing) VALUES ({$incoming}, {$outgoing})";
        $run = mysqli_query($conn, $sql);
        die();
    }


    $messages = mysqli_real_escape_string($conn, $_POST['typingField']);
    $image = $_FILES['image'];
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
        move_uploaded_file($imageTempName, "../images/" . $uniqueImageName);
    }

    $saveMsgQuery = "INSERT INTO `messages` (outgoing,incoming,messages,imagen)
    VALUES('$outgoing','$incoming', '$messages', '$uniqueImageName')";
    $runSaveQuery = mysqli_query($conn, $saveMsgQuery);

	$id_mensajeria = $row["id"];
    if ($runSaveQuery && $id_mensajeria) {
		$updateMsgQuery = "update mensajeria set fecha_mensaje = NOW() where id = $id_mensajeria";
		$runUpdateQuery = mysqli_query($conn, $updateMsgQuery);
	}
 
    if (!$runSaveQuery || $runNotificationMessage) {
        echo $notificationMessage;
    }
}
?>
