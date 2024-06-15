<?php
include_once("config.php");

session_start();
$searchValue = mysqli_real_escape_string($conn, $_POST['search']);

$sql = "SELECT * FROM `users` WHERE NOT id = '{$_SESSION["id"]}' AND (firstName LIKE '%$searchValue%' OR lastName LIKE '%$searchValue%')";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    while ($row = mysqli_fetch_assoc($query)) {
		$status = ($row["ban"] == 1) ? "Baneado" : "Activo";
		$icon = ($row["ban"] == 0) ? "images/prohibido.png" : "images/limpiar.png";

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
			  
				$onlineUsers .= '</h2>
					<!-- last message -->
					<p class="lastMessage">Estado de usuario: '.$status.', Rango del usuario: '.$row['type'].'</p>
					<!-- status => Online or Offline -->
					<div class="report-buttons">
						<a class="report-button" href="php/manageRank.php?upgrade='.$row["id"].'">
							<img src="images/upload.png" class="chat-icon">
						</a>
						<a class="report-button" href="php/manageRank.php?downgrade='.$row["id"].'">
							<img src="images/downgrade.png" class="chat-icon">
						</a>
						<a class="report-button" href="php/manageRank.php?ban='.$row["id"].'">
							<img src="'.$icon.'" class="chat-icon">
						</a>
					</div>
				</div>
		</a>';

		echo $onlineUsers;
	};
}else{
    echo '<div id="errors">Usuario No encontrado</div>';
}
?>
