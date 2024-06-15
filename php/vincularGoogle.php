<?php
	include_once("config.php");
	session_start();

	// (A) NOT LOGGED IN
	if (!isset($_SESSION["token"])) {
		header("Location: ../home.php"); exit;
	}

	// (B) TOKEN EXPIRED - TO LOGIN PAGE
	require "../google.php";

	$goo->setAccessToken($_SESSION["token"]);
	if ($goo->isAccessTokenExpired()) {
		unset($_SESSION["token"]);
		header("Location: ../configuracion.php");
		exit();
	}

	// (C) GET USER PROFILE
	if (!$goo->getAccessToken()) {
		exit();
	}

	$gooUser = (new Google_Service_Oauth2($goo))->userinfo->get();

	$firstName = $gooUser->givenName;
	$lastName = $gooUser->familyName;
	$email = $gooUser->email;
	$avatar = $gooUser->picture;

	print_r($avatar);
?>
