<?php
	include_once("config.php");
	session_start();
	$id = $_SESSION["id"];
	if (isset($_POST["form"])) {
		$titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
		$contenido = mysqli_real_escape_string($conn, $_POST['contenido']);
		$image = $_FILES['imagen'];

		$imageName = $image['name'];
		$imageSize = $image['size'];
		$imageTempName = $image['tmp_name'];
		$imageType = $image['type'];
		$explode = explode(".", $imageName);
		$lowercase = strtolower(end($explode));

		// image extension required
		$extension = ["png","gif","jpg","jpeg"];

		// if extension not matched
		if (in_array($lowercase,$extension) == false) {
			echo "Este archivo de extensión no está permitido, elija JPG o PNG GIF.";
		} else {
			// random number
			$random = rand(999999999,111111111);
			$time = time();
			// image unique name 
			$uniqueImageName = $random . $time . $imageName;

			// save image
			move_uploaded_file($imageTempName, "../images/Publicaciones/" . $uniqueImageName);
		}

		$saveMsgQuery = "
			INSERT INTO `anuncios` (id_usuario,titulo,texto,imagen)
			VALUES('$id','$titulo', '$contenido', '$uniqueImageName')
		";
		$runSaveQuery = mysqli_query($conn, $saveMsgQuery);

		if(!$runSaveQuery){
			echo "query Failed";
		}

		$id_anuncio = mysqli_insert_id($conn);

		// Obtener todos los usuarios
		$sql = "SELECT id FROM users";
		$result = mysqli_query($conn, $sql);

		// Generar notificaciones para cada usuario (excepto el autor)
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row['id'] != $id) {
				$sql = "
					INSERT INTO notificaciones_anuncios (id_user, id_anuncio)
					VALUES ('{$row['id']}', '$id_anuncio')
				";
				mysqli_query($conn, $sql);
			}
		}

		header("Location: ../foro");

		if (!$runSaveNotification) {
			echo "query Failed";
		}
	}

	if (isset($_GET['comentario'])) {
		$comentario = mysqli_real_escape_string($conn, $_POST['comentario']);
		$id_publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']);

		$saveMsgQuery = "INSERT INTO `anuncios_comentarios` (id_user,id_publicacion,texto)
		VALUES('$id','$id_publicacion', '$comentario')";
		$runSaveQuery = mysqli_query($conn, $saveMsgQuery);

		if(!$runSaveQuery){
			echo "query Failed";
		}

		header("Location: ../foro");
	}
