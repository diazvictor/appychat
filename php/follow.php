<?php
// include config file
include_once("config.php");

while($user = mysqli_fetch_assoc($query)) {
		$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$user["id"]}'";
		$runCheckMembresia = mysqli_query($conn, $checkMembresia);
		$isFollowing = $user["following_id"];

		$onlineUsers = '
            <div class="profile">
				<!-- profile image -->
				<div class="image">
					<img src="images/Profiles/' . $user["image"] . '" alt="">
				</div>
				<!-- name -->
				<h2 style="display:flex; flex-direction:row; gap:5px;  align-items:center" class="name">'.$user["firstName"]." ".$user["lastName"];

		// Verificar si el usuario está verificado
		if ($user["type"] === 'Verificado') {
			$onlineUsers .= '<img width="20px" height="20px" src="css/assets/verificado.png" alt="Verificado">';
		}

		if (mysqli_num_rows($runCheckMembresia) > 0) {
			$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
		}

		if ($user["type"] == "Verificado" || $user["type"] == "CEO" || $user["type"] == "Moderador") {
			$lastMessage = "Perfil recomendado por AppyChat";
		} else {
			$lastMessage = "No hay mensajes disponibles";
		}

		$onlineUsers .= '</h2>
				<!-- last message -->
				<p class="lastMessage">'. $lastMessage .'</p>
				<!-- status => Online or Offline -->
		';

		if ($isFollowing) {
			$onlineUsers .= '
				<!-- status => Online or Offline -->
				<a class="status ' . $user["status"] . '" href="php/unFollow.php?id=' . $user["id"] . '" id="followUser">
					<img src="images/unfollow.png" class="chat-icon">
				</a>
			';
		} else {
			$onlineUsers .= '
				<a class="status '. $user["status"] .'" href="php/newFollow.php?id=' . $user["id"] . '" id="followUser">
					<img src="images/Follow.png" class="chat-icon">
				</a>
			';
		}

		$onlineUsers .= '
			</div>
		';

		echo $onlineUsers;
	}
