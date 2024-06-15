<?php
    include_once("config.php");
    session_start();

	$id_user = $_SESSION['id'];

	if (isset($_GET["upgrade"])) {
		$id_group = $_GET["upgrade"];

		$sql = "SELECT * FROM grupo WHERE id = '{$id_group}'";
		$query = mysqli_query($conn, $sql);
		$group = mysqli_fetch_assoc($query);

		$role = ($group["type"] == "Grupo") ? "Verificado" : "Grupo";

		$upgradeQuery = "UPDATE grupo SET type = '{$role}' WHERE id = '{$id_group}'";
		$runUpgradeQuery = mysqli_query($conn, $upgradeQuery);

		if (!$runUpgradeQuery) {
			header ("location: /manageGroups");
		} else {
			print_r([
				"error_msg" => "ERROR"
			]);
			header ("location: /manageGroups");
		}
	} else if (isset($_GET["downgrade"])) {
		$id_group = $_GET["downgrade"];

		$sql = "SELECT * FROM grupo WHERE id = '{$id_group}'";
		$query = mysqli_query($conn, $sql);
		$group = mysqli_fetch_assoc($query);

		$role = ($group["type"] == "Verificado") ? "Grupo" : "Verificado";

		$downgradeQuery = "UPDATE grupo SET type = '{$role}' WHERE id = '{$id_group}'";
		$runDowngradeQuery = mysqli_query($conn, $downgradeQuery);

		if (!$runDowngradeQuery) {
			header ("location: /manageGroups");
		} else {
			header ("location: /manageGroups");
		}
	} else {
		$sql = "SELECT * FROM grupo ORDER BY id DESC";
		$result = mysqli_query($conn, $sql);
		if (!$result) {
			echo '<div id="errors">Grupo no encontrado</div>';
		} else {
			while ($grupo = mysqli_fetch_assoc($result)) {
				$template = '
					<div class="profile">
						<!-- profile image -->
						<div class="image">
							<img src="images/Grupos/'. $grupo["imagen"].'" alt="">
						</div>
						<!-- name -->
						<h2 class="name">'. $grupo["titulo"].'</h2>
						<!-- last message -->
						<p class="lastMessage">Estado del grupo: Activo, Rango del usuario: '. $grupo["type"].'</p>
						<!-- status => Online or Offline -->
						<div class="report-buttons">
							<a class="report-button" href="php/manageGroups?upgrade='. $grupo["id"] .'">
								<img src="images/upload.png" class="chat-icon">
							</a>
							<a class="report-button" href="php/manageGroups?downgrade='. $grupo["id"].'">
								<img src="images/downgrade.png" class="chat-icon">
							</a>
						</div>
					</div>
				';
				echo $template;
			}
		}
	}
