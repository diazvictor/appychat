<?php
	include_once("php/config.php");
	session_start();

	if (!isset($_SESSION['id'])) {
		header("location: home");
	}

	$anuncios = "SELECT * FROM anuncios ORDER BY id DESC";
	$anunciosQuery = mysqli_query($conn, $anuncios);

	$sqlUnreadAnuncios = "
		SELECT
			count(id_anuncio) AS total
		FROM notificaciones_anuncios
		WHERE id_user = {$_SESSION['id']} AND see = 0
	";
    $runUnreadAnuncios = mysqli_query($conn, $sqlUnreadAnuncios);

    if (mysqli_num_rows($runUnreadAnuncios) > 0) {
		$sql = "UPDATE notificaciones_anuncios SET see = 1 WHERE id_user = '{$_SESSION['id']}'";
		mysqli_query($conn, $sql);
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Anuncios | Appy</title>
    <!-- css linked -->
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="css/anuncios.css">
    <link rel="stylesheet" href="css/online.css">

    <!-- Icons -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">
    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div id="container">
        <!-- header -->
        <div id="header">
            <?php
            $headerQuery = "SELECT * FROM `users` WHERE id = '{$_SESSION["id"]}'";
            $runHeaderQuery = mysqli_query($conn, $headerQuery);

            if (!$runHeaderQuery) {
                echo "connection failed";
            } else {
                $info = mysqli_fetch_assoc($runHeaderQuery);
            ?>
                <!-- profile image -->
                <div id="details-online">

                    <div class="divide">
                        <img src="images/Iconos/Anuncio.png" class="onlineimg">
                        <h1 id="announce">Anuncios</h1>
                    </div>

                    <div class="btn-group">
                        <button class="noselect"><a><span class='text'>Regresar</span></a><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" />
                                </svg></span></button>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- cerrar button -->


        </div>


        <!--Crear publicacion form-->

        <?php if ($_SESSION['rol'] == "CEO") { ?>
            <form class="form-publicacion" id="publicacionArea">

                <input type="text" id="titulo" name="titulo" placeholder="Ingresa  el titulo del anuncio">

                <textarea name="contenido" id="contenido" cols="30" rows="5"></textarea>

                <div class="imagenInput">
                    <label for="imagen">
                        <img src="./images/Iconos/Save.png" alt="" srcset="">
                    </label>
                    <input type="file" id="imagen" name="imagen">
                </div>

                <button type="submit">Crear anuncio</button>
            </form>
        <?php } ?>



        <!-- display online users -->
        <!-- uses list -->
        <?php if ($_SESSION['rol'] == "CEO") { ?>


            <div id="onlineUsers">

            <?php } else { ?>


                <div id="onlineUsers-user">

                <?php } ?>

                <?php while ($anuncio = mysqli_fetch_assoc($anunciosQuery)) {
                    $user = "SELECT * FROM users WHERE id = {$anuncio["id_usuario"]}";

                    $userQuery = mysqli_query($conn, $user);

                    $userResult = mysqli_fetch_assoc($userQuery);

                    $dislikes = "SELECT * FROM anuncios_likes WHERE id_publicacion = {$anuncio["id"]} AND punto = -1";

                    $dislikesQuery = mysqli_query($conn, $dislikes);

                    $dislikesResult = mysqli_num_rows($dislikesQuery);

                    $likes = "SELECT * FROM anuncios_likes WHERE id_publicacion = {$anuncio["id"]} AND punto = 1";

                    $likesQuery = mysqli_query($conn, $likes);

                    $likesResult = mysqli_num_rows($likesQuery);

                    $comentarios = "SELECT * FROM anuncios_comentarios WHERE id_publicacion = {$anuncio["id"]}";

                    $comentariosQuery = mysqli_query($conn, $comentarios);
                ?>
                    <div class="profile">
                        <!-- last message -->
                        <h3><?= $anuncio['titulo'] ?></h3>
                        <p><?= $anuncio['texto'] ?></p>

                        <?php if ($anuncio['id_usuario'] == $_SESSION["id"]) { ?>
							<!-- Borrar publicación -->
							<a href="php/foro?delete=<?= $anuncio['id'] ?>" style="display: block;position: absolute;right: 15px;top: 15px;height: 30px;width: 30px;">
								<img src="images/basura.png" style="width:100%">
							</a>
						<?php } ?>

                        <!-- status => Online or Offline -->

                        <?php if ($anuncio['imagen'] != "") { ?>
                            <div class="image">
                                <img src="images/Publicaciones/<?= $anuncio['imagen'] ?>" alt="">
                            </div>
                        <?php } ?>


                        <div class="publication-buttons">
                            <a class="like-button" href="php/anuncios.php?like=<?= $anuncio['id'] ?>">
                                <img src="images/Iconos/Like.png" class="chat-icon">

                                <?= $likesResult ?>
                            </a>
                            <a class="like-button" href="php/anuncios.php?dislike=<?= $anuncio['id'] ?>">
                                <img src="images/Iconos/Dislike.png" class="chat-icon">
                                <?= $dislikesResult ?>
                            </a>
                        </div>

                        <div class="autor">
							<div style="display: flex;flex-flow: column;align-items: center;gap: 10px;">
								<?php
									$authorMarco = "";
									if (isset($userResult['marco'])) {
										$authorMarco .= "background: url('./images/Marcos/{$userResult['marco']}');background-position: center;background-repeat: no-repeat;background-size: contain;height: 100px;width: 100px;position: relative;display: grid;place-content: center;";
									}
								?>
								<div class="autorMarco" style="<?= $authorMarco ?>">
									<img src="images/Profiles/<?= $userResult['image'] ?>" alt="userImage" class="autorImage">
								</div>
								<?php
									$onlineUsers = "";
									if ($userResult['type'] === 'Verificado') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
									}
									if ($userResult['type'] === 'CEO') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
									}
									if ($userResult['type'] === 'Moderador') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
									}

									echo $onlineUsers;
								?>
							</div>
                            <h5>Escrito por <?= $userResult['firstName'] ?> <?= $userResult['lastName'] ?> el <?= $anuncio['fecha'] ?> | <?= $userResult['type'] ?> de Appy Chat</h5>
                        </div>

                        <div class="comentarios">
                            <h3>Comentarios</h3>
                            <?php while ($comentario = mysqli_fetch_assoc($comentariosQuery)) {
                                $comentarioUser = "SELECT * FROM users WHERE id = {$comentario["id_user"]}";
                                $comentarioUserQuery = mysqli_query($conn, $comentarioUser);
                                $comentarioUserResult = mysqli_fetch_assoc($comentarioUserQuery);
                            ?>

                                <div class="comentario-box">
									<div style="display: flex;flex-flow: column;align-items: center;">
										<div class="image-marco" style="background: url('./images/Marcos/<?= $comentarioUserResult['marco'] ?>'); background-position: center; background-repeat: no-repeat; background-size: contain;">
											<img src="images/Profiles/<?= $comentarioUserResult['image'] ?>" alt="userImage" class="comentarioImage">
										</div>
										<?php
											$onlineUsers = "";
											if ($comentarioUserResult['type'] === 'Verificado') {
												$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
											}
											if ($comentarioUserResult['type'] === 'CEO') {
												$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
											}
											if ($comentarioUserResult['type'] === 'Moderador') {
												$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
											}

											echo $onlineUsers;
										?>
                                    </div>
                                    <div class="comentario-text">
                                        <h5>Comentario de <?= $comentarioUserResult['firstName'] ?> <?= $comentarioUserResult['lastName'] ?> escrito el <?= $comentario['fecha'] ?></h5>
                                        <p><?= $comentario['texto'] ?></p>
                                    </div>
                                </div>

                            <?php } ?>

                        </div>

                        <form class="comentar" id="comentar" action="php/crearAnuncio.php?comentario=<?= $anuncio['id'] ?>" method="POST">
                            <input type="hidden" id="publicacion" name="publicacion" value="<?= $anuncio['id'] ?>">
                            <input type="text" id="comentario" name="comentario" placeholder="Ingresa tu comentario">
                            <button class="comentar-button" type="submit">Enviar</button>
                        </form>
                    </div>

                <?php
                } ?>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script src="js/anuncios.js"></script>
</body>

</html>
