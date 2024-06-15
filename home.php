<?php
ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

include_once("php/config.php");
session_start();
if (isset($_SESSION['id'])) {
    // Redirigir usando JavaScript
    // echo '<script>window.location.href = "";</script>';
    // Asegúrate de salir del script PHP después de la redirección
	header("Location: /users.php");
    exit();
}

require "google.php";
if (!isset($_GET["register"]) && isset($_GET["code"])) {
	$token = $goo->fetchAccessTokenWithAuthCode($_GET["code"]);
	if (!isset($token["error"])) {
		$_SESSION["token"] = $token;
		header("Location: php/loginGoogle.php");
		exit();
	}
}

require "google2.php";
if (isset($_GET["register"]) && isset($_GET["code"])) {
	$token2 = $goo2->fetchAccessTokenWithAuthCode($_GET["code"]);

	if (!isset($token2["error"])) {
		$_SESSION["token"] = $token2;

		$goo2->setAccessToken($_SESSION["token"]);
		if ($goo2->isAccessTokenExpired()) {
			unset($_SESSION["token"]);
			header("Location: /home.php");
			exit();
		}

		if (!$goo2->getAccessToken()) {
			echo "ERROR";
		}

		$gooUser = (new Google_Service_Oauth2($goo2))->userinfo->get();

		$tmpfname = tempnam("/tmp", "UL_IMAGE");
		$image = file_get_contents($gooUser->picture);
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
		}
		file_put_contents($image_filename, $image);
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AppyChat</title>
  <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css" />
  <!-- Icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" href="css/upload.css">


  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
  <?php if (isset($_GET["register"])) { ?>
  <main class="sign-up-mode">
  <?php } else { ?>
  <main>
  <?php } ?>
    <div class="box">
      <div class="inner-box">
        <div class="forms-wrap">
          <form action="" autocomplete="off" class="sign-in-form" id="loginForm">
            <div class="logo">

              <h4>AppyChat</h4>
            </div>

            <div class="heading">
              <h2><strong>Iniciar Sesion</strong></h2>
              <h6>Ya tienes cuenta de AppyChat?</h6>

              <a href="#" class="toggle">Registrarse</a>
              <br>
              <br>
              <a href="<?= $goo->createAuthUrl() ?>">
				<div class="g-sign-in-button">
				  <div class="content-wrapper">
					<div class="logo-wrapper">
					  <img src="images/Google.jpeg">
					</div>
					<span class="text-container">
					  <span>Continuar con Google</span>
					</span>
				  </div>
				</div>
			  </a>
            </div>

            <div class="actual-form">
              <div class="input-wrap">
                <input type="text" minlength="4" class="input-field" autocomplete="off" required name="email" id="email" />
                <label>Correo</label>
              </div>

              <div class="input-wrap">
                <input type="password" minlength="4" class="input-field" autocomplete="off" name="password" id="password" required />
                <label>Contraseña</label>
              </div>

              <?php if (isset($token["error"])) { ?>
			  <div><?= print_r($token); ?></div>
			  <?php } ?>
              <div id="errors2">Correo Invalido</div>
              <br>
              <a href="index" style="text-decoration: none; text-align: center; color: black; display: grid;">Informacion y Ayuda</a>
              <br>
              <div class="wrap">
                <button type="submit" name="login" id="login" class="button">Iniciar</button>
              </div>
            </div>
          </form>

          <form action="" autocomplete="off" class="sign-up-form" id="signupData">
			<?php if ($gooUser->id) { ?>
			<input type='hidden' name="token" value="<?= $gooUser->id; ?>">
			<?php } ?>
            <div class="logo">

              <h4>AppyChat</h4>
            </div>

            <div class="heading" style="margin-bottom: 20px;">
              <h2><strong>Registro</strong></h2>
              <h6>Ya tengo una cuenta de AppyChat?</h6>
              <br>
              <a href="#" class="toggle">Iniciar Sesion</a>
            <a href="<?= $goo2->createAuthUrl() ?>">
				<div class="g-sign-in-button">
				  <div class="content-wrapper">
					<div class="logo-wrapper">
					  <img src="images/Google.jpeg">
					</div>
					<span class="text-container">
					  <span>Continuar con Google</span>
					</span>
				  </div>
				</div>
			  </a>
            </div>

			<?php if (isset($token2["error"])) { ?>
			<div><?= $token2["error"] . ": " . $token2["error_description"]; ?></div>
			<?php } ?>

            <div id="errors">Correo Invalido</div>

            <div class="actual-form">
              <div class="input-wrap">
				<?php if ($gooUser->givenName) { ?>
				<input type="text" minlength="4" id="fname" class="input-field active" name="fname" autocomplete="off" value="<?= $gooUser->givenName; ?>" required />
				<?php } else { ?>
				<input type="text" minlength="4" id="fname" class="input-field" name="fname" autocomplete="off" required />
				<?php } ?>
                <label>Nombre</label>
              </div>

              <div class="input-wrap">
				<?php if ($gooUser->familyName) { ?>
				<input type="text" id="lname" name="lname" class="input-field active" autocomplete="off" value="<?= $gooUser->familyName; ?>" required />
				<?php } else { ?>
				<input type="text" id="lname" name="lname" class="input-field" autocomplete="off" required />
				<?php } ?>
				<label>Apodo</label>
              </div>

              <div class="input-wrap">
				<?php if ($gooUser->email) { ?>
				<input type="email" class="input-field active" name="email" id="email" autocomplete="off" value="<?= $gooUser->email; ?>" required />
				<?php } else { ?>
				<input type="email" class="input-field" name="email" id="email" autocomplete="off" required />
				<?php } ?>
				<label>Correo</label>
              </div>

              <div class="input-wrap">
                <input type="password" id="password" name="password" class="input-field" autocomplete="off" required />
                <label>Contraseña</label>
              </div>

              <div class="input-wrap">
                <input type="password" minlength="4" id="confirmPassword" name="confirmPassword" class="input-field" autocomplete="off" required />
                <label>Confirmar Contraseña</label>
              </div>

              <div class="container-image">
                <h1>Seleciona una Imagen
                  <small>Vista Previa</small>
                </h1>
                <div class="avatar-upload">
                  <div class="avatar-edit">
					<?php if ($gooUser->picture) { ?>
					<input type='hidden' name="tmpimage" value="<?= $image_filename; ?>">
					<?php } ?>
                    <input type='file' id="imageUpload" name="image" class="image" accept=".png, .jpg, .gif, .jpeg" />
                    <label for="imageUpload"></label>
                  </div>
                  <div class="avatar-preview">
					<?php if ($gooUser->picture) { ?>
                    <div id="imagePreview" style="background-image: url('<?= $gooUser->picture; ?>');">
					<?php } else { ?>
                    <div id="imagePreview">
					<?php } ?>
                    </div>
                  </div>
                </div>
              </div>

              <div id="errors">Correo Invalido</div>

              <div class="wrap">
                <button onclick="verify()" class="button" type="submit" id="signup" name="signup">Crear Cuenta</button>
              </div>
            </div>
          </form>
        </div>

        <div class="carousel">
          <div class="images-wrapper">
            <img src="assets/AppyChatAmigos.jpeg" class="image img-1 show" alt="" />
            <img src="images/App1.png" class="image img-2" alt="" />
            <img src="images/App2.png" class="image img-3" alt="" />
          </div>

          <div class="text-slider">
            <div class="text-wrap">
              <div class="text-group">
                <h2>Explora AppyChat</h2>
                <h2>Chatea y conoce</h2>
                <h2>Lanzamiento Global</h2>
              </div>
            </div>

            <div class="bullets">
              <span class="active" data-value="1"></span>
              <span data-value="2"></span>
              <span data-value="3"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Javascript file -->

  <script src="js/app.js"></script>
  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      // Prevenir el envío del formulario por defecto
      event.preventDefault();
      
      // Esperar 1 segundo antes de redirigir
      setTimeout(function() {
        // Redirigir al usuario a users.php
        window.location.href = "users.php";
      }, 1000); // 1000 milisegundos = 1 segundo
    });
    
    function verify(){
           console.log("Hi")
      setTimeout(function() {
        // Redirigir al usuario a users.php
        window.location.href = "users.php";
      }, 1000); // 1000 milisegundos = 1 segundo
    }
     document.getElementById('signup').addEventListener('submit', function(event) {
      // Prevenir el envío del formulario por defecto
      event.preventDefault();
      
      // Esperar 1 segundo antes de redirigir
      setTimeout(function() {
        // Redirigir al usuario a users.php
        window.location.href = "users.php";
      }, 1000); // 1000 milisegundos = 1 segundo
    });
  </script>
  <!-- jquery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="js/signup.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.js"></script>
  <script src="js/login.js"></script>
  <script src="js/upload.js"></script>
</body>


</html>
