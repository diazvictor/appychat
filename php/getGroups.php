<?php
	include_once("config.php");
	session_start();
	$id_user = $_SESSION['id'];

	$sql = "
		SELECT
			g.id as grupo,
			g.imagen as image,
			g.titulo as title,
			g.descripcion as description,
			u.id as user
		FROM grupo_invites gi
		INNER JOIN grupo g ON gi.id_grupo = g.id
		INNER JOIN users u ON gi.id_invitado = u.id
		WHERE u.id = $id_user;
	";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		if (!$result) {
			echo '<div id="errors">Grupo no encontrado</div>';
		} else {
			while($group = mysqli_fetch_assoc($result)) {
				$template = '
					<div class="profile">
						<!-- profile image -->
						<div class="image">
							<img src="images/Grupos/'. $group["image"] .'" alt="">
						</div>
						<!-- name -->
						<h2 class="name">'. $group["title"] .'</h2>
						<!-- last message -->
						<p class="lastMessage">'. $group["description"] .'</p>
						<!-- status => Online or Offline -->
						<div style="display:flex;flex-direction:row; gap:5px; justify-content:center; align-items:center;" class="status '.$status.'">
							<a href="/grupo?id='. $group["grupo"] .'">
								<img  src="images/chat.png" class="chat-icon">
							</a>

							<a href="php/myGroups.php?salirGrupo='. $group["grupo"] .'">
								<img src="images/Iconos/Basura.png" class="chat-icon">
							</a>
						</div>
					</div>
				';
				echo $template;
			}
		}
	} else {
		echo '<div id="errors">Grupo no encontrado</div>';
	}

