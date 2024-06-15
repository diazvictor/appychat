<?php
	include_once("config.php");
    session_start();

	$searchValue = mysqli_real_escape_string($conn, $_POST["search"]);

	$sql = "SELECT * FROM grupo WHERE (titulo LIKE '%$searchValue%' OR descripcion LIKE '%$searchValue%') ORDER BY id DESC";
	$result = mysqli_query($conn, $sql);

	if (!$result) {
		echo '<div id="errors">Grupo no encontrado</div>';
	} else {
		while ($grupo = mysqli_fetch_assoc($result)) {
			$template = '
				<div class="profile">
					<!-- profile image -->
					<div class="image">
						<img src="images/Grupos/'. $grupo["imagen"] .'" alt="">
					</div>
					<!-- name -->
					<h2 class="name">'. $grupo["titulo"] .'</h2>
					<!-- last message -->
					<p class="lastMessage">Estado del grupo: Activo, Rango del usuario: '. $grupo["type"] .'</p>
					<!-- status => Online or Offline -->
					<div class="report-buttons">
						<a class="report-button" href="php/manageGroups?upgrade='. $grupo["id"] .'">
							<img src="images/upload.png" class="chat-icon">
						</a>
						<a class="report-button" href="php/manageGroups?downgrade='. $grupo["id"] .'">
							<img src="images/downgrade.png" class="chat-icon">
						</a>
					</div>
				</div>
			';

			echo $template;
		}
	}
