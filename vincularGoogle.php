<?php
	include_once("php/config.php");
	session_start();
	$id_user = $_SESSION["id"];

	require "google3.php";
	$goo3->setAccessToken($_SESSION["token"]);
	if ($goo3->isAccessTokenExpired()) {
		unset($_SESSION["token"]);
		echo "ERROR 2";
	}

	// (C) GET USER PROFILE
	if (!$goo3->getAccessToken()) {
		echo "ERROR 3";
		exit();
	}

	$gooUser = (new Google_Service_Oauth2($goo3))->userinfo->get();

	$goo3->revokeToken();
	unset($_SESSION["token"]);

	$firstName = $gooUser->givenName;
	$lastName = $gooUser->familyName;
	$email = $gooUser->email;
	$usertoken = $gooUser->id;
	$avatar = $gooUser->picture;

	$tmpfname = tempnam("/tmp", "UL_IMAGE");
	$image = file_get_contents($avatar);
	file_put_contents($tmpfname, $image);

	$image_type = mime_content_type($tmpfname);
	$image_filename = "";

	if ($image_type == "image/jpeg") {
		$image_filename = $tmpfname . ".jpeg";
	} else if ($image_type == "image/png") {
		$image_filename = $tmpfname . ".png";
	} else if ($image_type == "image/jpg") {
		$image_filename = $tmpfname . ".jpg";
	} else if ($image_type == "image/gif") {
		$image_filename = $tmpfname . ".gif";
	} else {
		echo "Este archivo de extensión no está permitido, elija JPG, PNG o GIF.";
		exit();
	}

	$fileinfo = pathinfo($image_filename);
	$imageName = $fileinfo['basename'];

	file_put_contents("images/Profiles/$imageName", $image);

	$sql = "
		UPDATE
			users
		SET
			firstName = '$firstName',
			lastName = '$lastName',
			email = '$email',
			image = '$imageName',
			token = '$usertoken'
		WHERE id = $id_user
	";
	$result = mysqli_query($conn, $sql);

	if (!$result) {
		echo "Fallo al vincular la cuenta";
	} else {
	   header("Location: /users.php");
	}
