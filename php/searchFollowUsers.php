<?php
	// include config file
	include_once("config.php");
	session_start();
	$searchValue = mysqli_real_escape_string($conn, $_POST['search']);

	$getUsers = "
		SELECT
			u.id,
			u.firstName,
			u.lastName,
			u.type,
			u.image,
			u.status,
			u.type,
			f.user_id AS following_id
		FROM users AS u
		LEFT JOIN follow AS f ON u.id = f.followed_id AND f.user_id = {$_SESSION["id"]}
		WHERE NOT u.id = {$_SESSION["id"]} AND NOT u.type = 'Verificado' AND u.status = 'Online' AND (
			u.firstName LIKE '%$searchValue%' OR u.lastName LIKE '%$searchValue%'
		) ORDER BY u.type desc
	";
	$query = mysqli_query($conn, $getUsers);

	if ($searchValue !== "") {
		while($user = mysqli_fetch_assoc($query)) {
			$isFollowing = $user["following_id"];

			$onlineUsers = '
				<div class="profile">
					<!-- profile image -->
					<div class="image">
						<img src="images/Profiles/' . $user["image"] . '" alt="">
					</div>
					<!-- name -->
					<h2 style="display:flex; flex-direction:row; gap:5px;  align-items:center" class="name">'.$user["firstName"]." ".$user["lastName"];

			if ($user['type'] === 'Verificado') {
				$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
			} else if ($user['type'] === 'CEO') {
				$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
			} else if ($user['type'] === 'Moderador') {
				$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
			}

			$onlineUsers .= '</h2>
				<!-- last message -->
				<p class="lastMessage">No hay mensajes disponibles</p>
				<!-- status => Online or Offline -->
			';

			if ($isFollowing) {
				$onlineUsers .= '
					<!-- status => Online or Offline -->
					<a class="status ' . $user["status"] . '" href="php/newFollow.php?id=' . $user["id"] . '&from=profile" id="followUser">
						<img src="images/unfollow.png" class="chat-icon">
					</a>
				';
			} else {
				$onlineUsers .= '
					<!-- status => Online or Offline -->
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
	}
