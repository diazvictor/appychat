<?php
include_once("php/config.php");
session_start();
if(!isset($_SESSION['id'])){
    header("location: home");
}

// Tiene membresia?

$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$_SESSION['id']}'";
$runCheckMembresia = mysqli_query($conn, $checkMembresia);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>AppyChat</title>
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <!-- css linked -->
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="css/wrapper.css">

    <!-- Icons --> 

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">
    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

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

            $notificaciones = "SELECT * FROM notifications WHERE id_user = {$_SESSION['id']} AND see = 0";
            $runCheckNoti = mysqli_query($conn, $notificaciones);
            $numNotificaciones = mysqli_num_rows($runCheckNoti);

            $mesa =  "SELECT * FROM `mensajeria` WHERE id_incoming = {$_SESSION['id']} AND estado = 0";
            $runMesa =  mysqli_query($conn, $mesa);
            $numMesa = mysqli_num_rows($runMesa);

            $anuncios =  "SELECT * FROM notificaciones_anuncios WHERE id_user = {$_SESSION['id']} AND see = 0";
            $runAnuncios =  mysqli_query($conn, $anuncios);
            $totalAnuncios = mysqli_num_rows($runAnuncios);

			if (!$runHeaderQuery) {
               echo "connection failed";
			} else{
               setlocale(LC_TIME, 'es_ES');
				$info = mysqli_fetch_assoc($runHeaderQuery);
				$noti = mysqli_fetch_assoc($runCheckNoti);
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
            <div class="userProfile" >
                <div class="fullProfile" style="background: url('./images/Banners/<?=$info['banner']?>');">

                    <div id="headerProfile" style="background: url('./images/Marcos/<?=$info['marco']?>'); background-position: center; background-repeat: no-repeat; background-size: contain;">
                        <img src="images/Profiles/<?php echo $info['image']; ?>" alt="">
                    </div>

                    <div id="details">
                        <!-- full name -->
                        <div class="full-name" style="grid-template-columns: 1fr;">
                            <h3 id="headerName" style="display: flex;gap: 5px;align-items: center;">
								<?php echo $info['firstName']." ".$info['lastName']; ?>
								<?php
									$onlineUsers = "";

									if ($info['type'] === 'Verificado') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
									}

									if ($info['type'] === 'CEO') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
									}

									if ($info['type'] === 'Moderador') {
										$onlineUsers .= '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
									}

									echo $onlineUsers;

									$membresia = "";

									if (mysqli_num_rows($runCheckMembresia) > 0) {
										$membresia = '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
									}

									echo $membresia;
								?>
							</h3>
                        </div>
                        <!-- status => Onine or Offline -->
                        <h3 id="headerStatus"><?php echo $info['status']; ?></h3>
                        <h3 id="headerStatus">Se unio a AppyChat en <?php echo $mes_nombre; ?> del  <?php echo $ano; ?></h3>
                              
                    </div>
                    
                    <div class="buttons">
                            <a style="position:relative" href="bandeja">
                                <img src="./images/Iconos/Mensajeria.png" alt="Mensajeria">
                                
								<?php 
									if (mysqli_num_rows($runMesa) > 0) {
										echo "<span style='width: 25px; position:absolute; top:0; height: 25px;color:white; padding: 4px; background-color: red; border-radius: 50%; display: flex; flex-direction:column;justify-content:center; align-items:center; right:0px; animation: pulse 1s infinite;'>
											$numMesa
										</span>";
									} 
								?>
                            </a>
                            <a style="position:relative" href="notifications">
                               <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24"><g fill="none"><path d="M24 0v24H0V0zM12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036c-.01-.003-.019 0-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="#fdc030" d="M12 2a7 7 0 0 0-7 7v3.528a1 1 0 0 1-.105.447l-1.717 3.433A1.1 1.1 0 0 0 4.162 18h15.676a1.1 1.1 0 0 0 .984-1.592l-1.716-3.433a1 1 0 0 1-.106-.447V9a7 7 0 0 0-7-7m0 19a3.001 3.001 0 0 1-2.83-2h5.66A3.001 3.001 0 0 1 12 21"/></g></svg>

								<?php 
									if (mysqli_num_rows($runCheckNoti) > 0) {
										echo "<span style='width: 25px; position:absolute; top:0; height: 25px;color:white; padding: 4px; background-color: red; border-radius: 50%; display: flex; flex-direction:column;justify-content:center; align-items:center; right:0px; animation: pulse 1s infinite;'>
											$numNotificaciones
										</span>";
									} 
								?>
                            </a>
                            <a style="position:relative" href="foro">
                               <img src="./images/Iconos/Anuncio.png" alt="Mensajeria">

								<?php
									if (mysqli_num_rows($runAnuncios) > 0) {
										echo "
											<span style='width: 25px; position:absolute; top:0; height: 25px;color:white; padding: 4px; background-color: red; border-radius: 50%; display: flex; flex-direction:column;justify-content:center; align-items:center; right:0px; animation: pulse 1s infinite;'>
												$totalAnuncios
											</span>
										";
									}
								?>
                            </a>
                        </div>
                
            </div>

            <?php
            }
            ?>
            <!-- config button -->
                <div class="wrapper">
                    <div class="link_wrapper">
                        <a href="configuracion">Configuracion</a>
                            <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 268.832 268.832">
                                <path d="M265.17 125.577l-80-80c-4.88-4.88-12.796-4.88-17.677 0-4.882 4.882-4.882 12.796 0 17.678l58.66 58.66H12.5c-6.903 0-12.5 5.598-12.5 12.5 0 6.903 5.597 12.5 12.5 12.5h213.654l-58.66 58.662c-4.88 4.882-4.88 12.796 0 17.678 2.44 2.44 5.64 3.66 8.84 3.66s6.398-1.22 8.84-3.66l79.997-80c4.883-4.882 4.883-12.796 0-17.678z"/>
                            </svg>
                            </div>
                    </div>
                </div>


        <!-- Buscador box -->
        <div id="searchBox">
            <!-- Visita o vista "fontawesome.com" for icons  -->
            <input type="text" id="search" placeholder="Buscador Global" autocomplete="OFF">
            <i class="fas fa-search searchButton"></i>
        </div>

        <!-- display online users -->
        <!-- uses list -->
        <div style="display:flex; flex-direction:column; overflow-y:scroll;margin-top: 30px;" id="onlineUsers"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/users.js"></script>
    <script src="delete_message.js"></script>

	<script type="text/javascript">
		// document.addEventListener('DOMContentLoaded', function() {
			// Función para buscar botones de eliminación y agregarles un controlador de eventos
			// function addDeleteButtonListeners() {
				// const deleteButtons = document.querySelectorAll('.deleteButton');
				// deleteButtons.forEach(button => {
					// button.addEventListener('click', function() {
						// const messageId = button.getAttribute('data-message-id');
						// console.log(messageId);

						// Realizar una solicitud AJAX para eliminar el mensaje
						// $.ajax({
							// type: "POST", // Método de solicitud POST
							// url: "delete_message.php", // URL del archivo PHP que maneja la eliminación
							// data: { messageId: messageId }, // Datos a enviar al servidor (ID del mensaje)
							// dataType: "json", // Tipo de datos esperados en la respuesta del servidor
							// success: function(response) { // Función para manejar la respuesta del servidor
								// Verificar si la eliminación fue exitosa
								// if (response.success) {
									// Eliminar el mensaje de la interfaz de usuario
									// $(this).closest(".profile").remove();
									// console.log("El mensaje se ha eliminado exitosamente.");
								// } else {
									// Mostrar un mensaje de error si la eliminación falló
									// console.log("Error al intentar eliminar el mensaje.");
								// }
							// },
							// error: function(xhr, status, error) { // Función para manejar errores de la solicitud AJAX
								// Mostrar un mensaje de error si la solicitud falló
								// console.log("Error en la solicitud AJAX: " + status);
							// }
						// });
		  
					// });
				// });
			// }

			// Llamar a la función para agregar controladores de eventos a los botones de eliminación
			// addDeleteButtonListeners();

			// Observar cambios en el DOM en todo el documento
			// const observer = new MutationObserver(function(mutationsList) {
				// for (let mutation of mutationsList) {
					// if (mutation.type === 'childList') {
						// Si hay cambios en la lista de hijos, volver a buscar botones de eliminación
						// addDeleteButtonListeners();
					// }
				// }
			// });

			// Observar cambios en el DOM en todo el documento
			// observer.observe(document.documentElement, { childList: true, subtree: true });
		// });
	</script>
</body>
</html>
