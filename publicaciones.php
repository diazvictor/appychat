<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}

$publicaciones = "SELECT * FROM publicaciones ORDER BY id DESC";

$publicacionesQuery = mysqli_query($conn, $publicaciones);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Publicaciones | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/publicaciones.css">
    <link rel="stylesheet" href="css/online.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">

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

                        <img src="images/Iconos/Agregar.png" class="onlineimg">
                        <h1 id="announce">Publicaciones</h1>
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

        <form class="form-publicacion" id="publicacionArea">

            <input type="text" id="titulo" name="titulo" placeholder="Ingresa tu titulo">

            <textarea name="contenido" id="contenido" cols="30" rows="10"></textarea>

            <div class="imagenInput">
                <label for="imagen">
                    <img src="./images/Iconos/Save.png" alt="" srcset="">
                </label>
                <input type="file" id="imagen" name="imagen">
            </div>

            <button type="submit">Crear publicacion</button>
        </form>



        <!-- display online users -->
        <!-- uses list -->
        <div id="onlineUsers">

            <?php while ($publicacion = mysqli_fetch_assoc($publicacionesQuery)) {
                $user = "SELECT * FROM users WHERE id = {$publicacion["id_usuario"]}";

                $userQuery = mysqli_query($conn, $user);

                $userResult = mysqli_fetch_assoc($userQuery);

                $dislikes = "SELECT * FROM publicaciones_likes WHERE id_publicacion = {$publicacion["id"]} AND punto = -1";

                $dislikesQuery = mysqli_query($conn, $dislikes);

                $dislikesResult = mysqli_num_rows($dislikesQuery);

                $likes = "SELECT * FROM publicaciones_likes WHERE id_publicacion = {$publicacion["id"]} AND punto = 1";

                $likesQuery = mysqli_query($conn, $likes);

                $likesResult = mysqli_num_rows($likesQuery);

                $comentarios = "SELECT * FROM publicaciones_comentarios WHERE id_publicacion = {$publicacion["id"]}";

                $comentariosQuery = mysqli_query($conn, $comentarios);
            ?>
                <div class="profile">
                    <!-- last message -->
                    <h3><?= $publicacion['titulo'] ?></h3>
                    <p><?= $publicacion['texto'] ?></p>

                    <?php if ($publicacion['id_usuario'] == $_SESSION["id"]) { ?>
						<!-- Borrar publicación -->
						<a href="php/publicaciones?delete=<?= $publicacion['id'] ?>" style="display: block;position: absolute;right: 15px;top: 15px;height: 30px;width: 30px;">
							<img src="images/basura.png" style="width:100%">
						</a>
                    <?php } ?>

                    <!-- status => Online or Offline -->

                    <?php if ($publicacion['imagen'] != "") { ?>
                        <div class="image">
                            <img src="images/Publicaciones/<?= $publicacion['imagen'] ?>" alt="">
                        </div>
                    <?php } ?>


                    <div class="publication-buttons">
                        <a class="like-button" href="php/publicaciones.php?like=<?= $publicacion['id'] ?>">
                            <img src="images/Iconos/Like.png" class="chat-icon">

                            <?= $likesResult ?>
                        </a>
                        <a class="like-button" href="php/publicaciones.php?dislike=<?= $publicacion['id'] ?>">
                            <img src="images/Iconos/Dislike.png" class="chat-icon">
                            <?= $dislikesResult ?>
                        </a>
                    </div>

                    <div class="autor">
						<div class="autorMarco" style="background: url('./images/Marcos/<?= $userResult['marco'] ?>');background-position: center;background-repeat: no-repeat;background-size: contain;height: 100px;padding: 5px;width: 100px;display: grid;place-items: center; display:flex; flex-direction:row; gap:5px; position: relative;/* z-index: 1; */">
						<img src="images/Profiles/<?= $userResult['image'] ?>" alt="userImage" class="autorImage">
						<?php
							$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$userResult['id']}'";
							$runCheckMembresia = mysqli_query($conn, $checkMembresia);

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

							$membresia = "";

							if (mysqli_num_rows($runCheckMembresia) > 0) {
								$membresia = '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
							}

							echo $onlineUsers;

							echo $membresia;
						?>
					</div>

                        <h5>Escrito por <?= $userResult['firstName'] ?> <?= $userResult['lastName'] ?> el <?= $publicacion['fecha'] ?></h5>
                        
                    </div>

                    <div class="comentarios">
                        <h3>Comentarios</h3>
                        <?php while ($comentario = mysqli_fetch_assoc($comentariosQuery)) {
                            $comentarioUser = "SELECT * FROM users WHERE id = {$comentario["id_user"]}";
                            $comentarioUserQuery = mysqli_query($conn, $comentarioUser);
                            $comentarioUserResult = mysqli_fetch_assoc($comentarioUserQuery);

                            $checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$comentario['id_user']}'";
							$runCheckMembresia = mysqli_query($conn, $checkMembresia);
                        ?>

                            <div class="comentario-box">
                                <div class="image-marco" style="background: url('./images/Marcos/<?= $comentarioUserResult['marco'] ?>'); background-position: center; background-repeat: no-repeat;display:flex; flex-direction:row; gap:5px; background-size: contain;">
                                    <img src="images/Profiles/<?= $comentarioUserResult['image'] ?>" alt="userImage" class="comentarioImage">
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

										if (mysqli_num_rows($runCheckMembresia) > 0) {
											$membresia = '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
										}

										echo $onlineUsers;

										echo $membresia;
									?>
                                </div>
                                <div class="comentario-text">
                                    <h5>Comentario de <?= $comentarioUserResult['firstName'] ?> <?= $comentarioUserResult['lastName'] ?> escrito el <?= $comentario['fecha'] ?></h5>
                                    <p><?= $comentario['texto'] ?></p>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                    <form class="comentar" id="comentar" action="php/crearPublicacion.php?comentario=<?= $publicacion['id'] ?>" method="POST">
                        <input type="hidden" id="publicacion" name="publicacion" value="<?= $publicacion['id'] ?>">
                        <input type="text" id="comentario" name="comentario" placeholder="Ingresa tu comentario">
                        <button class="comentar-button" type="submit">Enviar</button>
                    </form>
                </div>

            <?php
            } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/publicaciones.js"></script>
</body>

</html>
