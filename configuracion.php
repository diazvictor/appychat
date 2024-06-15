<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}

$getUserData = "SELECT * FROM `users` WHERE id = '{$_SESSION["id"]}'";
$runGetUserData = mysqli_query($conn, $getUserData);
$assoc = mysqli_fetch_assoc($runGetUserData);

require "google3.php";
if (isset($_GET["code"])) {
	$token = $goo3->fetchAccessTokenWithAuthCode($_GET["code"]);
	if (!isset($token["error"])) {
		$_SESSION["token"] = $token;
		header("Location: /vincularGoogle.php");
		exit();
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuarios Recomendarios | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/configuracion.css">
    <link rel="stylesheet" href="css/config.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">

    <!-- Icons -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
    <div id="container" style="height: 100vh;">
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
                    <div class="" style="
    display: flex;
    gap: 25px;
    align-items: center;
">

                        <img src="images/config.png" class="onlineimg">
                        <h1 id="announce">Configuracion</h1>
                    </div>

                    <div class="btn-group">
                        <button class="noselect"><span class='text'>Regresar</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" />
                                </svg></span></button>
                    </div>
                </div>
            <?php
            }
            ?>


        </div>



        <div id="onlineUsers">

            <div class="button-group">

                <div class="buttons">
                    <a href="online">
                        <button class="blob-btn">
                            Recomendados
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>


                <div class="buttons">
                    <a href="grupos">
                        <button class="blob-btn">
                            Grupos
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>

                <?php if ($assoc['type'] == 'Moderador' || $assoc['type'] == 'CEO') { ?>

                    <div class="buttons">
                        <a href="manageUsers">
                            <button class="blob-btn">
                                Editar usuarios
                                <span class="blob-btn__inner">
                                    <span class="blob-btn__blobs">
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                    </span>
                                </span>
                            </button>
                        </a>

                    </div>

                <?php } ?>

				<div class="buttons">
					<a href="/myGroups">
						<button class="blob-btn">
							Tus grupos
							<span class="blob-btn__inner">
								<span class="blob-btn__blobs">
									<span class="blob-btn__blob"></span>
									<span class="blob-btn__blob"></span>
									<span class="blob-btn__blob"></span>
									<span class="blob-btn__blob"></span>
								</span>
							</span>
						</button>
					</a>
				</div>

                <?php if ($assoc['type'] == 'Moderador' || $assoc['type'] == 'CEO') { ?>

                    <div class="buttons">
                        <a href="/manageGroups">
                            <button class="blob-btn">
                                Editar grupos
                                <span class="blob-btn__inner">
                                    <span class="blob-btn__blobs">
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                    </span>
                                </span>
                            </button>
                        </a>

                    </div>

                <?php } ?>


                <?php if ($assoc['type'] == 'Moderador' || $assoc['type'] == 'CEO') { ?>

                    <div class="buttons">
                        <a href="reports">
                            <button class="blob-btn">
                                Reportes
                                <span class="blob-btn__inner">
                                    <span class="blob-btn__blobs">
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                        <span class="blob-btn__blob"></span>
                                    </span>
                                </span>
                            </button>
                        </a>

                    </div>

                <?php } ?>


                <div class="buttons">
                    <a href="edit">
                        <button class="blob-btn">
                            Editar perfil
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>

                </div>


                <div class="buttons">
                    <a href="publicaciones">
                        <button class="blob-btn">
                            Publicaciones
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>

                </div>


                <div class="buttons">
                    <a href="prices">
                        <button class="blob-btn">
                            Membresias
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>


                <div class="buttons">
                    <a href="profile?id=<?= $_SESSION['id'] ?>">
                        <button class="blob-btn">
                            Tu Perfil
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>

                <div class="buttons">
                    <a href="follows">
                        <button class="blob-btn">
                            Seguir usuarios
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>

                <div class="buttons">
                    <a href="amigos">
                        <button class="blob-btn">
                            Amigos
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>

                </div>

                <div class="buttons">
                    <a href="marcos">
                        <button class="blob-btn">
                            Tienda de Marcos
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>

                </div>

                <div class="buttons">
                    <a href="php/logout.php">
                        <button class="blob-btn">
                            Cerrar Sesion
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>

                </div>

                <div class="buttons">
                    <a href="#" class="deleteAccount" id="deleteButton">
                        <button class="blob-btn">
                            Eliminar Cuenta
                            <span class="blob-btn__inner">
                                <span class="blob-btn__blobs">
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                    <span class="blob-btn__blob"></span>
                                </span>
                            </span>
                        </button>
                    </a>
                </div>

				<a href="<?= $goo3->createAuthUrl() ?>">
					<div class="g-sign-in-button">
						<div class="content-wrapper" style="position: relative">
							<div class="logo-wrapper">
								<img src="images/Google.jpeg">
							</div>
							<span class="text-container" style="position: absolute">
								<span>Vincular con Google</span>
							</span>
						</div>
					</div>
				</a>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="js/edit.js"></script>

    <script>
        // Mostrar alerta al clickear reportButton

        const deleteButton = document.getElementById("deleteButton");
        deleteButton.addEventListener("click", async () => {
            const {
                value: motivo
            } = await Swal.fire({
                title: "¿Quieres eliminar tu cuenta?",
                text: "Ingresa la palabra 'Eliminar', ten cuidado si eliminas tu cuenta perderas membresias, datos, mensajes y no podras recuperarlos.",
                input: "text",
                inputPlaceholder: "Ingresa la palabra para confirmar",
                showCancelButton: false,
                showCloseButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Eliminar",
            });
            if (motivo == undefined) {
                return;
            }
            if (motivo != "Eliminar") {
                Swal.fire(`Ingresaste la palabra incorrecta: ${motivo}`);
            }

            await fetch("php/deleteAccount.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({}),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.message) {
                        Swal.fire(data.message).then(() => {
                            window.location.href = "home";
                        });
                    } else {
                        Swal.fire(data.error);
                    }
                }).catch((error) => {
                    console.log(error)
                });
        })
    </script>
</body>

</html>
