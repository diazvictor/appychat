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
		header("Location: ../home.php"); exit;
	}

	// (C) GET USER PROFILE
	if (!$goo->getAccessToken()) {
		exit();
	}

	$gooUser = (new Google_Service_Oauth2($goo))->userinfo->get();

	$goo->revokeToken();
	unset($_SESSION["token"]);

	$firstName = $gooUser->givenName;
	$lastName = $gooUser->familyName;
	$email = $gooUser->email;
	$usertoken = $gooUser->id;
	$avatar = $gooUser->picture;
	$password = md5($usertoken);

	$sql = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query($conn, $sql);

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			$tokenQuery = "SELECT * FROM users WHERE email = '$email' AND token = '$usertoken'";
            $runTokenQuery = mysqli_query($conn, $tokenQuery);

            if (!$runTokenQuery) {
                echo "Consulta Fallida";
            } else {
                if(mysqli_num_rows($runTokenQuery) > 0){
                    $fetchData = mysqli_fetch_assoc($runTokenQuery);

                    $_SESSION['id'] = $fetchData['id'];
                    $_SESSION['rol'] = $fetchData['type'];

                    // update status
                    $status = "Online";

                    // status query
                    $statusQuery = "UPDATE users SET status = '{$status}' WHERE id = '{$_SESSION["id"]}'";
                    $runStatusQuery = mysqli_query($conn, $statusQuery);
                    if (!$runStatusQuery) {
                        echo "Fallo al actualizar estado";
                    } else {
					   header("Location: ../users.php");
                    }
                } else {
                    echo "Contraseña Incorrecta";
                }
            }
		} else {
			$tmpfname = tempnam("/tmp", "UL_IMAGE");
			$image = file_get_contents($avatar);
			file_put_contents($tmpfname, $image);

			// random number
			$random = rand(999999999, 111111111);
			$time = time();
			// image unique name
			$uniqueImageName = $random . $time . "UL_IMAGE.png";

			// save image
			move_uploaded_file($tmpfname, "../images/Profiles/" . $tmpfname);

			$sql = "
				INSERT INTO users (
					firstName,
					lastName,
					email,
					password,
					image,
					token,
					status
				)
				VALUES (
					'$firstName',
					'$lastName',
					'$email',
					'$password',
					'$usertoken',
					'$uniqueImageName',
					'Offline'
				)
			";

			$result = mysqli_query($conn, $sql);

			if (!$result) {
				echo "Error al introducir datos en la base de datos";
			} else {
				// Create details

				$lastid = $conn->insert_id;

				$sql = "
					INSERT INTO details_user (
						id_user
					)
					VALUES (
						'$lastid'
					)
				";
				$result = mysqli_query($conn, $sql);

				// Start session
				session_start();

				// Store data in session variables

				$_SESSION["id"] = $lastid;
				$_SESSION["rol"] = "Usuario";

				// Status query
				$sql = "
					UPDATE users
						SET status = 'Online'
					WHERE id = '{$_SESSION["id"]}'
				";
				$result = mysqli_query($conn, $sql);

				if ($result) {
					header("Location: ../users.php");
				} else {
					echo "ERROR";
				}
			}
		}
	}
?>
