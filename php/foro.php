<?php
	include_once("config.php");
	session_start();

	if (isset($_GET["delete"])) {
		$id_anuncio = mysqli_real_escape_string($conn, $_GET["delete"]);
		$sql = "
			DELETE FROM anuncios WHERE id = $id_anuncio AND id_usuario = {$_SESSION["id"]};
		";
		$query = mysqli_query($conn, $sql);

		if ($query) {
			header("location: ../foro");
		} else {
			header("location: ../foro");
		}
	}
