<?php
// include config file
include_once("config.php");

while($row = mysqli_fetch_assoc($query)){
// show last message send
$outgoing = $_SESSION['id'];
$incoming = $row['id'];
$sql = "SELECT * FROM `messages` WHERE (incoming = '{$incoming}' AND outgoing = '{$outgoing}') OR (incoming = '{$outgoing}' AND outgoing = '{$incoming}') ORDER BY messages_id DESC LIMIT 1";

$runSQL = mysqli_query($conn, $sql);

// obtener mensajeria donde fue aceptado o acepto a un usuario.

$mensajeriaAceptada = "SELECT * FROM mensajeria WHERE id_incoming = $outgoing OR id_outgoing = $outgoing AND estado = 1";
$runMensajeriaAceptada = mysqli_query($conn, $mensajeriaAceptada);
$mensajeriaArray = mysqli_fetch_array($runMensajeriaAceptada);

$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$incoming}'";
$runCheckMembresia = mysqli_query($conn, $checkMembresia);

if($runSQL){
    $row2 = mysqli_fetch_assoc($runSQL);
    if(mysqli_num_rows($runSQL) > 0){
        $lastMessage = $row2['messages'];
    }else{
        $lastMessage = "No hay mensajes disponibles";
    }
}else{
    echo "Query Failed";
}


// show status online or offine
if($row['status'] == "Online"){
    $status = "online";
}else{
    $status = "offline";
}
// show Online users
    $onlineUsers = '<a href="messages?userid='.$row["id"].'">
    <div class="profile">
        <!-- profile image -->
        <div class="image">
            <img src="images/Profiles/'.$row["image"].'" alt="">
        </div>
        <!-- name -->
                       <h2 style="display:flex; flex-direction:row; gap:5px;  align-items:center" class="name">'.$row["firstName"]." ".$row["lastName"];
        // Verificar si el usuario está verificado
        if ($row["type"] === 'Verificado') {
            $onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
        }

		if ($row['type'] === 'CEO') {
			$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
		}

		if ($row['type'] === 'Moderador') {
			$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
		}

		if (mysqli_num_rows($runCheckMembresia) > 0) {
			$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
		}
      
        $onlineUsers .= '</h2>
        <!-- last message -->
        <p class="lastMessage">'.$lastMessage.'</p>
        <!-- status => Online or Offline -->
        <div class="status '.$status.'">
            <img src="images/chat.png" class="chat-icon">
        </div>
    </div>
</a>';
echo $onlineUsers;
};
