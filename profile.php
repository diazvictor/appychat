<?php
include_once("php/config.php");
session_start();
if ($_GET['id'] == "") {
    if (!isset($_SESSION['id'])) {
        header("location: home");
    }
    header("location: users");
}

$id = $_GET['id'];
$myid = $_SESSION['id'];

$getPublicaciones = "SELECT * FROM publicaciones WHERE id_usuario = '$id' ORDER BY id DESC";

$publicacionesQuery = mysqli_query($conn, $getPublicaciones);

$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$id}'";
$runCheckMembresia = mysqli_query($conn, $checkMembresia);

if ($_SESSION['id'] != $id) {
    // Check if user have a view row of this profile

    $sql = "SELECT * FROM vistas_perfil WHERE id_user = '$myid' AND id_profile = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($result);

    if ($row > 0) {
        $sql = "UPDATE vistas_perfil SET times = times + 1 WHERE id_user = '$myid' AND id_profile = '$id'";
        $result = mysqli_query($conn, $sql);
    } else {
        $sql = "INSERT INTO vistas_perfil (id_user, id_profile, times) VALUES ('$myid', '$id', 1)";
        $result = mysqli_query($conn, $sql);
    }
}

// Cargar visitas totales del perfil sumar la columna times

$sql = "SELECT * FROM vistas_perfil WHERE id_profile = '$id'";
$result = mysqli_query($conn, $sql);
$views = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $views += $row['times'];
}

$sql = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


// Conseguir seguidores

$sql = "SELECT * FROM follow WHERE followed_id = '$id'";
$result = mysqli_query($conn, $sql);
$followers = mysqli_num_rows($result);

// Check if user follows 

$sql = "SELECT * FROM follow WHERE user_id = '$myid' AND followed_id = '$id'";
$result = mysqli_query($conn, $sql);
$follow = mysqli_num_rows($result);

// Cuentas que sigue

$sql = "SELECT * FROM follow WHERE user_id = '$id'";
$result = mysqli_query($conn, $sql);
$following = mysqli_num_rows($result);

$userid = mysqli_real_escape_string($conn, $_GET['id']);

$fetchDetailsUser = "SELECT * FROM details_user WHERE id_user = '$userid'";
$runFetchDetailsUser = mysqli_query($conn, $fetchDetailsUser);
$detailsUser = mysqli_fetch_assoc($runFetchDetailsUser);

// Cargar comentarios

$comentariosQuery = "SELECT cp.comentario, u.firstName, u.lastName, u.image, cp.fecha, cp.id FROM comentarios_perfil AS cp INNER JOIN users AS u ON cp.id_usuario = u.id WHERE id_comentado = '$userid' ORDER BY cp.id DESC";
$runQuery = mysqli_query($conn, $comentariosQuery);
$comentarios = mysqli_fetch_all($runQuery);
$numComentarios = mysqli_num_rows($runQuery);

// Es amigo

$amistad = "SELECT * FROM amistades WHERE id_solicitado = $myid AND id_solicitante = $id OR id_solicitado = $id AND id_solicitante = $myid";
$queryAmistad = mysqli_query($conn, $amistad);
$queryRows = mysqli_num_rows($queryAmistad);
$amistadData = mysqli_fetch_assoc($queryAmistad);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
</head>

<body>
	<a style="position:absolute; left: 0; top: 0; min-width:100px; z-index:1" href="#" onclick="window.history.back(); return false;">
		<div  class="back">
			<svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 1024 1024">
				<path fill="white" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64"></path>
				<path fill="white" d="m237.248 512l265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312z"></path>
			</svg>
		</div>
	</a>
    <div id="cardProfile" class="card">
        <div class="linkBox d-none">
            <a href="https://www.facebook.com/profile.php?id=<?= $detailsUser['facebook'] ?>" target="_blank">
                <img src="./images/Iconos/Facebook.svg" alt="facebook">
            </a>
            <a href="https://www.instagram.com/<?= $detailsUser['instagram'] ?>" target="_blank">
                <img src="./images/Iconos/Instagram.svg" alt="instagram">
            </a>
            <a href="https://twitter.com/<?= $detailsUser['twitter'] ?>" target="_blank">
                <img src="./images/Iconos/Twitter.svg" alt="twitter">
            </a>
            <a href="https://www.youtube.com/@<?= $detailsUser['youtube'] ?>" target="_blank">
                <img src="./images/Iconos/Youtube.svg" alt="youtube">
            </a>
            <a href="https://twitch.tv/<?= $detailsUser['twitch'] ?>" target="_blank">
                <img src="./images/Iconos/Twitch.svg" alt="twitch">
            </a>
        </div>

        <div class="imageBanner" style="background: url('images/Banners/<?= $row['banner'] ?>'); background-size: 100%;">
            <div class="imgBox" style="background: url('./images/Marcos/<?= $row['marco'] ?>'); background-position: center; background-repeat: no-repeat; background-size: contain;">
                <img src="images/Profiles/<?= $row['image'] ?>" alt="">
            </div>
        </div>

        <div class="content">
            
            <div class="details">
            <h2 style="display:flex; flex-direction:row; gap:4px; justify-content:center"><?= $row['firstName']  ?> 
                <?php if ($row['type'] === 'Verificado'): ?>
                    <img width=20px src="images/Iconos/Verificacion.png" alt="Verificado">
				<?php endif; ?>
                <?php if ($row['type'] === 'Moderador'): ?>
                    <img width=20px src="images/Iconos/Moderador.png" alt="Moderador">
				<?php endif; ?>
                <?php if ($row['type'] === 'CEO'): ?>
                    <img width=20px src="images/Iconos/CEO.png" alt="CEO">
				<?php endif; ?>
                <?php if (mysqli_num_rows($runCheckMembresia) > 0) { ?>
					<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">
				<?php } ?>
               </h2>
                <span>Miembro AppyChat</span>
                <br>
                <span><?= $row['description'] ?></span>
                <div class='data'>
                    <a href="views?id=<?= $id ?>">
                        <?= $views ?>
                        <span>Visitas</span>
                    </a>
                    <a href="followers?id=<?= $id ?>">
                        <?= $followers ?>
                        <span>Seguidores</span>
                    </a>
                    <a href="followed?id=<?= $id ?>">
                        <?= $following ?>
                        <span>Seguidos</span>
                    </a>
                </div>
                <div class='cta'>
                    <?php if ($_SESSION['id'] != $id) { ?>
                        <a href="php/newFollow.php?id=<?= $id ?>&from=profile"><?php if ($follow != 0) { ?> Unfollow <?php } ?> <?php if ($follow == 0) { ?> Seguir <?php } ?></a>
                        <a href="messages?userid=<?= $id ?>">Mensaje</a>
                        <?php if (isset($_GET['publicaciones'])) { ?>

                            <a href="profile?id=<?= $id ?>">Comentarios</a>

                        <?php } else { ?>

                            <a href="profile?id=<?= $id ?>&publicaciones=1">Publicaciones</a>

                        <?php } ?>
                        <?php if ($queryRows != 0) { ?>
                            <?php if ($amistadData['amistad_status'] == 1) { ?>
                                <?php if ($amistadData['id_solicitante'] != $myid) { ?>
                                    <a href="php/friend.php?accept=<?= $amistadData['id'] ?>">Aceptar</a>
                                <?php } else { ?>
                                    <a href="php/friend.php?cancel=<?= $amistadData['id'] ?>">Cancelar</a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="php/friend.php?quit=<?= $amistadData['id'] ?>">Desagregar</a>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($queryRows == 0) { ?>
                            <a href="php/friend.php?add=<?= $id ?>">Agregar</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($_SESSION['id'] == $id) { ?>
                        <a href="edit">Editar perfil</a>
                    <?php } ?>
                </div>

                <?php if (!isset($_GET['publicaciones'])) { ?>

                    <div class="cmt">
						<div style="display: flex;justify-content: center;gap: 1.5rem;">
							<h3>Comentarios</h3><h3><?= $numComentarios ?></h3>
						</div>
                        <div class="cmtUsersBox">
                            <!-- Index an show comments -->

                            <?php foreach ($comentarios as $comentario) {
                                echo '
                            <div class="cmtUser">
                                <img src="images/Profiles/' . $comentario[3] . '" alt="">
                                <div class="cmtUserDetails">
                                    <h4>' . $comentario[1] . " " . $comentario[2] . '</h4>
                                    <span>' . $comentario[0] . '</span><br>
                                    <span>Escrito el: ' . $comentario[4] . '</span>
                                    </div>';
                                if ($_SESSION['id'] == $userid) {
                                    echo '<a href="php/deleteComment.php?id=' . $comentario[5] . '&from=' . $id . '" class="btn-delete">X</a>';
                                };
                                echo '
                            </div>
                            ';
                            } ?>
                        </div>
                        <?php if ($row['toggledData'] == 1) { ?>

                            <form class="cmtBox" id="cmtBox" action="">
                                <div class="cmtInput">
                                    <input type="hidden" name="user" class="setid" id="user" value="<?php echo $myid; ?>">
                                    <input type="hidden" name="comentado" class="setid" id="comentado" value="<?php echo $userid ?>">
                                    <input type="text" name="mensaje" id="typingField" placeholder="Escribe un comentario..." id="mensaje">
                                </div>
                                <button class="cmtSend" type="submit">></button>
                            </form>

                        <?php } else { ?>


                            <form class="cmtBox" id="cmtBox" action="">
                                <div class="cmtInput">

                                </div>
                            </form>

                        <?php } ?>
                    </div>

                <?php } else { ?>

                    <div class="cmt">
                        <h3>Publicaciones</h3>
                        <div class="cmtUsersBox">
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
                                        <div class="autorMarco" style="background: url('./images/Marcos/<?= $userResult['marco'] ?>');background-position: center;background-repeat: no-repeat;background-size: contain;height: 100px;width: 100px;position: relative;place-content: center;display: grid;">
                                            <img src="images/Profiles/<?= $userResult['image'] ?>" alt="userImage" class="autorImage">
                                        </div>
                                        <h5>Escrito por <?= $userResult['firstName'] ?> <?= $userResult['lastName'] ?> el <?= $publicacion['fecha'] ?></h5>
                                    </div>

                                    <div class="comentarios">
                                        <h3>Comentarios</h3>
                                        <?php while ($comentario = mysqli_fetch_assoc($comentariosQuery)) {
                                            $comentarioUser = "SELECT * FROM users WHERE id = {$comentario["id_user"]}";
                                            $comentarioUserQuery = mysqli_query($conn, $comentarioUser);
                                            $comentarioUserResult = mysqli_fetch_assoc($comentarioUserQuery);
                                        ?>

                                            <div class="comentario-box">
                                                <img src="images/Profiles/<?= $comentarioUserResult['image'] ?>" alt="userImage" class="autorImage">
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
                                        <button class="comentar-button" type="submit">></button>
                                    </form>
                                </div>

                            <?php
                            } ?>
                        </div>
                        <?php if ($row['toggledData'] == 1) { ?>

                            <form class="cmtBox" id="cmtBox" action="">
                                <div class="cmtInput">
                                    <input type="hidden" name="user" class="setid" id="user" value="<?php echo $myid; ?>">
                                    <input type="hidden" name="comentado" class="setid" id="comentado" value="<?php echo $userid ?>">
                                    <input type="text" name="mensaje" id="typingField" placeholder="Escribe un comentario..." id="mensaje">
                                </div>
                                <button class="cmtSend" type="submit">></button>
                            </form>

                        <?php } else { ?>


                            <form class="cmtBox" id="cmtBox" action="">
                                <div class="cmtInput">

                                </div>
                            </form>

                        <?php } ?>
                    </div>

                <?php } ?>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/comentario.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</body>

</html>
