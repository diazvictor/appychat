<?php
// include config file
include_once("config.php");
session_start();
$sql = "SELECT 
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.id
                ELSE u_solicitante.id
            END AS friend_id,
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.firstName
                ELSE u_solicitante.firstName
            END AS firstName,
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.lastName
                ELSE u_solicitante.lastName
            END AS lastName,
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.image
                ELSE u_solicitante.image
            END AS image,
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.type
                ELSE u_solicitante.type
            END AS type,
            CASE
                WHEN a.id_solicitante = '{$_SESSION["id"]}' THEN u_solicitado.banner
                ELSE u_solicitante.banner
            END AS banner,
            a.id
        FROM amistades AS a
        INNER JOIN users AS u_solicitante ON u_solicitante.id = a.id_solicitante
        INNER JOIN users AS u_solicitado ON u_solicitado.id = a.id_solicitado
        WHERE 
            '{$_SESSION["id"]}' IN (a.id_solicitante, a.id_solicitado) AND
            a.amistad_status = '2'";
$query = mysqli_query($conn, $sql);


if(!$query){
    echo "La consulta fallo";
}else{
    if(mysqli_num_rows($query) == 0){
        echo '<div id="errors-online">No tienes amigos que mostrar.</div>';
    }else if(mysqli_num_rows($query) > 0){
        // return data in html


        while ($row = mysqli_fetch_assoc($query)) {
			$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$row["friend_id"]}'";
			$runCheckMembresia = mysqli_query($conn, $checkMembresia);

			if (mysqli_num_rows($runCheckMembresia) > 0) {
				$membresia = '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
			} else {
				$membresia = "";
			}

			if ($row['type'] === 'Verificado') {
				$avatar = '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
			} else if ($row['type'] === 'CEO') {
				$avatar = '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
			} else if ($row['type'] === 'Moderador') {
				$avatar = '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
			} else {
				$avatar = '';
			}
			$onlineUsers = '
				<div class="profile">
					<!-- profile image -->
					<div class="image" style="top: 10px;">
						<img src="images/Profiles/'.$row["image"].'" alt="">
					</div>
					<!-- name -->
					<a class="name" href="profile?id='.$row['friend_id'].'" style="text-decoration:none;">'.$row["firstName"]." ".$row["lastName"]. " " . $avatar . ' ' . $membresia .'</a>
					<!-- status => Online or Offline -->
			';

			$onlineUsers .= '
                <p class="lastMessage">Ustedes son amigos, puedes ir a su chat, regalarle una membresia o eliminarlo.</p>
                <div class="status">
                    <a href="php/friend.php?quit='.$row['id'].'" ><img src="images/prohibido.png" class="chat-icon"><a/>
                    <a href="regalar?userid='.$row['friend_id'].'" ><img src="images/regalo.png" class="chat-icon"><a/>
                    <a href="messages?userid='.$row['friend_id'].'" ><img src="images/chat.png" class="chat-icon"><a/>
                </div>
            </div>';

            echo $onlineUsers;
        }

    }else{
        echo "Hubo un fallo, mientras se buscaba usuarios";
    }
}
?>
