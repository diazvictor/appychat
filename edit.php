<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AppyChat</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/editusers.css">
    <link rel="stylesheet" href="css/edit.css">
    <link rel="stylesheet" href="css/guardar.css">

    <!-- Icons -->
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="css/upload.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">
    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div id="container" style="height: 1050px">
        <!-- header -->
        <div id="header">

            <?php
            $headerQuery = "SELECT * FROM `users` WHERE id = '{$_SESSION["id"]}'";
            $runHeaderQuery = mysqli_query($conn, $headerQuery);

            if (!$runHeaderQuery) {
                echo "connection failed";
            } else {
                setlocale(LC_TIME, 'es_ES');
                $info = mysqli_fetch_assoc($runHeaderQuery);
                $fecha = $info['fecha'];

                // Obtener el año
                $ano = date('Y', strtotime($fecha));

                // Obtener el mes
                $meses = array(
                    1 => 'Enero',
                    2 => 'Febrero',
                    3 => 'Marzo',
                    4 => 'Abril',
                    5 => 'Mayo',
                    6 => 'Junio',
                    7 => 'Julio',
                    8 => 'Agosto',
                    9 => 'Septiembre',
                    10 => 'Octubre',
                    11 => 'Noviembre',
                    12 => 'Diciembre'
                );


                // Obtener el número del mes
                $mes_numero = date('n', strtotime($fecha));

                // Obtener el nombre del mes en español
                $mes_nombre = $meses[$mes_numero];
            ?>
                <!-- profile image -->
                <div id="headerProfile">
                    <img src="images/Profiles/<?php echo $info['image']; ?>" alt="">
                </div>
                <div id="details">
                    <!-- full name -->
                    <div class="full-name">
                        <div class="name">
                            <h3 id="headerName"><?php echo $info['firstName'] . " " . $info['lastName']; ?></h3>
                            <?php if ($info['type'] == "verified") {
                                echo '<img class="verificado-icon" src="images/verificado.png">';
                            } else if ($info['type'] == "ceo") {
                                echo '<img class="verificado-icon" src="images/ceo.png">';
                            } else if ($info['type'] == "mod") {
                                echo '<img class="verificado-icon" src="images/mod.png">';
                            }; ?>
                        </div>


                        <h3 id="headerStatus"><?php echo $info['status']; ?></h3>
                        <h3 id="headerStatus">Se unio a AppyChat en <?php echo $mes_nombre; ?> del <?php echo $ano; ?></h3>

                    </div>

                    <div class="btn-group">
                        <button class="noselect"><span class='text'>Regresar</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" />
                                </svg></span></button>
                    </div>
                    <!-- status => Onine or Offline -->
                </div>
            <?php
            }
            ?>
            <!-- cerrar button -->
        </div>

        <!-- Buscador box -->
        <div id="credentialsBox">

            <form id="editForm">
                <!-- Visita o vista "fontawesome.com" for icons  -->
                <input type="text" placeholder="Nuevo Nombre" name="fname" id="fname" autocomplete="off">
                <input type="text" placeholder="Nuevo Apodo" name="lname" id="lname" autocomplete="off">
                <input type="text" placeholder="Nueva Biografia" name="bio" id="bio" autocomplete="off">
                <input type="password" placeholder="Nueva Contraseña" name="password" id="password" autocomplete="off">
                <input type="text" placeholder="Usuario de Instagram" name="instagram" id="instagram" autocomplete="off">
                <input type="text" placeholder="Usuario de Facebook" name="facebook" id="facebook" autocomplete="off">
                <input type="text" placeholder="Usuario de X" name="twitter" id="twitter" autocomplete="off">
                <input type="text" placeholder="Usuario de Youtube" name="youtube" id="youtube" autocomplete="off">
                <input type="text" placeholder="Usuario de Twitch" name="twitch" id="twitch" autocomplete="off">

                <div class="imagesInput" style="display: block;">
<!--
                    <div class="fondoInput">
                        <label for="fondo">Sube tu fondo de pantalla</label>
                        <input type="file" placeholder="Fondo de pantalla" name="fondo" id="fondo" autocomplete="off">
                    </div>
-->
                    <div class="bannerInput">
                        <label for="banner">Sube tu banner de perfil</label>
                        <input type="file" placeholder="Banner para tu perfil" name="banner" id="banner" autocomplete="off">
                    </div>
                </div>

                <div class="toggleDataSelect">
                    <label for="toggleData">Usuarios pueden comentar tu perfil</label>
                    <select name="data" id="toggleData">
                        <option value="1" selected>Habilitado</option>
                        <option value="0">Deshabilitado</option>
                    </select>
                </div>



                <div class="container-image">
                    <h1>Seleciona una Imagen
                        <small>Vista Previa</small>
                    </h1>
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload" name="image" class="image" accept=".png, .jpg, .gif, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="boton-guardar">
                    <div class="button-save">
                        <button class="text-save" type="submit">Guardar cambios</button>
                    </div>
                    <div class="progress-bar"></div>
                    <svg x="0px" y="0px" viewBox="0 0 25 30" style="enable-background:new 0 0 25 30;">
                        <path class="check" class="st0" d="M2,19.2C5.9,23.6,9.4,28,9.4,28L23,2" />
                    </svg>
                </div>
            </form>



            <div id="errors">Problemas encontrados</div>


        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/edit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.js"></script>
    <script src="js/upload.js"></script>
    <script src="js/guardar.js"></script>
</body>

</html>
