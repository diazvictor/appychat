<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}

$grupo = $_GET['id'];

$grupoQuery = "SELECT * FROM `grupo` WHERE id = '{$grupo}'";

$runGrupoQuery = mysqli_query($conn, $grupoQuery);

$assoc = mysqli_fetch_assoc($runGrupoQuery);

$fetchOwnerData = "SELECT * FROM `users` WHERE id = '{$assoc['id_usuario']}'";

$runFetchOwnerData = mysqli_query($conn, $fetchOwnerData);

$ownerData = mysqli_fetch_assoc($runFetchOwnerData);

$groupMembers = "SELECT * FROM `grupo_invites` WHERE id_grupo = '{$grupo}' AND estado = 1";

$runGroupMembers = mysqli_query($conn, $groupMembers);



$isOwner = true;

if ($assoc['id_usuario'] != $_SESSION['id']) {
    $isOwner = false;
}

$usuarios = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $usuarios = "SELECT * FROM users WHERE firstName LIKE '%$search%' OR lastName LIKE '%$search%'";
}

$usuariosQuery = mysqli_query($conn, $usuarios);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <title>Grupos | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/ajustes.css">

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

        <?php if (!$isOwner) { ?>


            <div class="publicacion">
                <div class="image">
                    <img src="images/Grupos/<?= $assoc['imagen'] ?>" alt="">
                </div>
                <div class="info">
                    <h3><?= $assoc['titulo'] ?></h3>
                    <p><?= $assoc['descripcion'] ?></p>


                    <h4><?= $ownerData['firstName'] ?> <?= $ownerData['lastName'] ?> es el dueño del grupo</h4>
                    <p>Actualmente hay <?= mysqli_num_rows($runGroupMembers) ?> miembros</p>
                </div>
                <button class="salirgrupo" id="salirGrupo">Salir del grupo</button>
            </div>


        <?php } ?>

        <?php if ($isOwner) { ?>
            <form class="form-publicacion" id="publicacionArea" method="POST" action="php/ajustarGrupo.php">

                <input type="text" id="titulo" name="titulo" placeholder="Nuevo titulo o nombre del grupo">
                <input type="hidden" id="id" name="id" value="<?=$_GET['id']?>">

                <textarea name="contenido" id="contenido" cols="30" rows="2" placeholder="Cambia la descripcion del grupo"></textarea>

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

                <button type="submit">Actualizar grupo</button>

            </form>


            <form id="searchBox" action="ajustes" method="GET">
                <!-- Visita o vista "fontawesome.com" for icons  -->
                <input type="hidden" value="<?= $grupo ?>" name="id" id="id">
                <input type="text" id="search" name="search" placeholder="Invita usuarios" autocomplete="OFF">
                <button class="fas fa-search searchButton" type="submit"></button>
            </form>
        <?php } ?>


        <?php if ($_GET['search'] != "") { ?>


            <div id="onlineUsers">

                <h3 class="tusgrupos">Usuarios</h3>


                <?php while ($usuario = mysqli_fetch_assoc($usuariosQuery)) {

                ?>

                    <?php if ($usuario['id'] != $_SESSION['id']) { ?>

                        <a class="profile" href="invitar?id=<?= $usuario['id'] ?>&grupo=<?= $grupo ?>" style="text-decoration: none; color: black;">

                            <?php if ($usuario['image'] != "") { ?>
                                <div class="image">
                                    <img src="images/Profiles/<?= $usuario['image'] ?>" alt="">
                                </div>
                            <?php } ?>

                            <!-- last message -->
                            <div class="info">

                                <h3><?= $usuario['firstName'] ?></h3>
                                <p><?= $usuario['lastName'] ?></p>

                            </div>
                            <!-- status => Online or Offline -->
                        </a>
                    <?php } ?>

                <?php
                } ?>
            </div>


        <?php } ?>

        <script>
            // Mostrar alerta al clickear reportButton

            const salirButton = document.getElementById("salirGrupo");
            salirButton.addEventListener("click", async () => {
                await Swal.fire({
                    title: "¿Abandonaras el Grupo?",
                    text: "Tus mensajes seguiran en el grupo, y podras unirte denuevo cuando quieras.",
                    confirmButtonText: "Salir",
                    confirmButtonColor: "#fd0000",
                })
                .then((result) => {
                    if(result.isConfirmed){
                        location.href="php/salirGrupo.php?id=<?= $grupo ?>";
                    }
                })

                const urlId = window.location.search;
                const userId = urlId.split("=")[1];
            })
        </script>


        <!-- display online users -->
        <!-- uses list -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/ajustes.js"></script>
</body>

</html>
