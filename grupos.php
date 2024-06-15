<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}

$grupos = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $grupos = "
		SELECT
			g.id,
			g.id_usuario,
			g.titulo,
			g.descripcion,
			g.fondo,
			g.imagen,
			g.type,
			gi.estado,
			COUNT(DISTINCT gi.id) AS miembros
		FROM grupo g
		JOIN grupo_invites gi ON g.id = gi.id_grupo
		WHERE gi.estado = true AND g.titulo LIKE '%$search%' OR g.descripcion LIKE '%$search%'
		GROUP BY g.titulo
		ORDER BY miembros DESC
    ";
} else {
	// Se hace una referencia para usar una sola consulta
	// @TODO: Si se normalizan las consultas no haría falta hacer tantas
    $grupos = "
		SELECT
			g.id,
			g.id_usuario,
			g.titulo,
			g.descripcion,
			g.fondo,
			g.imagen,
			g.type,
			gi.estado,
			COUNT(DISTINCT gi.id) AS miembros
		FROM grupo g
		JOIN grupo_invites gi ON g.id = gi.id_grupo
		WHERE gi.estado = true
		GROUP BY g.titulo
		ORDER BY miembros DESC
	";
}

$gruposQuery = mysqli_query($conn, $grupos);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grupos | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/grupos.css">

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

                    <img src="images/Iconos/Group.png" class="onlineimg">
                    <h1 id="announce">Grupos</h1>

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

        <?php if (isset($_SESSION['rol'])) { ?>
            <form class="form-publicacion" id="publicacionArea">

                <input type="text" id="titulo" name="titulo" placeholder="Titulo/Nombre o Topico de tu grupo">

                <textarea name="contenido" id="contenido" cols="30" rows="2" placeholder="Describe tu grupo brevemente"></textarea>

                <div class="mediafiles">
                    <div class="imagenInput">
                        <h5>Icono</h5>
                        <label for="imagen">
                            <img src="./images/Iconos/Save.png" alt="" srcset="">
                        </label>
                        <input type="file" id="imagen" name="imagen">
                    </div>

                    <div class="imagenInput">
                        <h5>Fondo</h5>
                        <label for="fondo">
                            <img src="./images/Iconos/Save.png" alt="" srcset="">
                        </label>
                        <input type="file" id="fondo" name="fondo">
                    </div>
                </div>

                <button type="submit">Crear grupo</button>

            </form>


            <form id="searchBox" action="grupos" method="GET">
                <!-- Visita o vista "fontawesome.com" for icons  -->
                <input type="text" id="search" name="search" placeholder="Busca entre los grupos" autocomplete="OFF">
                    <button class="fas fa-search searchButton" type="submit"></button>
            </form>
        <?php } ?>



        <!-- display online users -->
        <!-- uses list -->
        <?php if (isset($_SESSION['rol'])) { ?>


            <div id="onlineUsers">

                <h3 class="tusgrupos">Grupos</h3>

            <?php } ?>

            <?php while ($grupo = mysqli_fetch_assoc($gruposQuery)) {
                $grupoMembers = "
					SELECT
						COUNT(DISTINCT gi.id) AS miembros
					FROM grupo g
					JOIN grupo_invites gi ON gi.id_grupo = g.id
					WHERE gi.id_grupo = {$grupo['id']} AND estado = 1;
				";
                $runGrupoMembers = mysqli_query($conn, $grupoMembers);
                $grupoMembersCount = mysqli_fetch_assoc($runGrupoMembers)["miembros"]
            ?>
                <a class="profile" href="invitar?grupo=<?= $grupo['id'] ?>&user" style="text-decoration: none; color: black;">

                    <?php if ($grupo['imagen'] != "") { ?>
                        <div class="image">
                            <img src="images/Grupos/<?= $grupo['imagen'] ?>" alt="">
                        </div>
                    <?php } ?>

                    <!-- last message -->
                    <div class="info">

                        <h3 style="display: flex;gap: 5px;align-items: center;">
							<?= $grupo['titulo'] ?>

							<?php if ($grupo['type'] == "Verificado") { ?>
								<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">
							<?php } ?>
                        </h3>
                        <p><?= $grupo['descripcion'] ?></p>
                        <small>Unete a este grupo, actualmente hay: <?=$grupoMembersCount?> miembros en el grupo!</small>

                    </div>
                    <!-- status => Online or Offline -->
                </a>

            <?php
            } ?>
            </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/grupos.js"></script>
</body>

</html>
