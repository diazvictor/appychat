<?php
	include_once("php/config.php");
	session_start();
	if (!isset($_SESSION['id'])) {
		header("location: home");
	}

	$checkMembresia = "SELECT * FROM `membresias` WHERE id_user = '{$_GET['userid']}'";
	$runCheckMembresia = mysqli_query($conn, $checkMembresia);

	$mensajeria = "SELECT * FROM mensajeria WHERE id_outgoing = {$_GET['userid']} AND id_incoming = {$_SESSION['id']} OR id_outgoing = {$_SESSION['id']} AND id_incoming = {$_GET['userid']}";
	$runMensajeria = mysqli_query($conn, $mensajeria);
	$mensajeria = mysqli_fetch_assoc($runMensajeria);

	$sqlUnreadMessages = "
		SELECT
			count(messages_id) AS messages_not_see
		FROM messages
		WHERE outgoing = {$_GET['userid']} AND incoming = {$_SESSION['id']} AND see = 0
	";
    $runUnreadMessages = mysqli_query($conn, $sqlUnreadMessages);
    $unreadMessages = mysqli_fetch_assoc($runUnreadMessages)["messages_not_see"];

    if (mysqli_num_rows($runUnreadMessages) > 0) {
		$markMessagesAsRead = "
			UPDATE
				messages
			SET see = 1
			WHERE outgoing = {$_GET['userid']} AND incoming = {$_SESSION['id']}
		";
		$runMarkMessagesAsRead = mysqli_query($conn, $markMessagesAsRead);
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <!-- css linked -->
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="css/messages.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/css/emoji.css" rel="stylesheet">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>

    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div id="container" style="background: url('./images/Fondos/<?=$mensajeria['fondo']?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        
        <?php
            $myid = $_SESSION['id'];
            // getting from messages url
            $userid = mysqli_real_escape_string($conn, $_GET['userid']);

            $headerQuery = "SELECT * FROM `users` WHERE id = '{$userid}'";
            $runHeaderQuery = mysqli_query($conn, $headerQuery);


            $detailsUserQuery = "SELECT * FROM details_user WHERE id_user = {$userid}";
            $runDetailsUserQuery = mysqli_query($conn, $detailsUserQuery);
            $fetchData = mysqli_fetch_assoc($runDetailsUserQuery);

            if (!$runHeaderQuery) {
                echo "Connection failed";
            } else {
                $info = mysqli_fetch_assoc($runHeaderQuery);
                
            ?>
        <!-- header -->
        <div id="header" style="background: url('./images/Banners/<?=$info['banner']?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">

            
                <!-- profile -->
                <div class="separator">
                    <a href="users">
                        <!-- arrow icon -->
                        <i class="fas fa-arrow-left"></i>
                    </a>

                    <div id="profileImage" style="background: url('./images/Marcos/<?= $info['marco'] ?>'); background-position: center; background-repeat: no-repeat; background-size: contain;">
                        <img src="images/Profiles/<?php echo $info['image']; ?>" alt="">
                    </div>

                    <!-- user Detail (name & status) -->
                    <div id="userDetail">
                        <div class="full-name">
                            <a href="profile?id=<?= $info['id'] ?>" id="name"><?php echo $info['firstName'] . " " . $info['lastName']; ?></a>
                            <?php if ($info['type'] == "Verificado") {
                                echo '<img class="verificado-icon" src="images/Iconos/Verificacion.png">';
                            } else if ($info['type'] == "CEO") {
                                echo '<img class="verificado-icon" src="images/Iconos/CEO.png">';
                            } else if ($info['type'] == "Moderador") {
                                echo '<img class="verificado-icon" src="images/Iconos/Moderador.png">';
                            }; ?>
                            <?php if (mysqli_num_rows($runCheckMembresia) > 0) {
                                echo '<img class="verificado-icon" src="images/Iconos/Insignia.png">';
                            } ?>
                        </div>
                        <p id="status"><?php echo $info['status'];  ?></p>
                    </div>
                </div>

                <div class="buttons">
                    <button id="fondoButton">
                        <img src="images/Iconos/Save.png">
                    </button>
                    <button id="blockButton">
                        <img src="images/prohibido.png">
                    </button>
                    <button id="reportButton">
                        <img src="images/report.png">
                    </button>
                </div>
        </div>
    <?php
            }
    ?>

    <!-- main section => messages section -->
    <div style="padding-bottom:70px" id="mainSection">
        <!-- incoming -->
        <!-- <div class="request incoming"> -->
        <!-- name -->
        <!-- <h3 class="name">Code Smacher</h3> -->
        <!-- incoming -->
        <!-- <p class="messages">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci excepturi sit officiis aliquid nesciunt explicabo maiores voluptates repellat nisi unde!</p>
        </div> -->

        <!-- outgoing -->
        <!-- <div class="responseCard outgoing">
            <div class="response"> -->
        <!-- name -->
        <!-- <h3 class="name">You</h3> -->
        <!-- outgoing message -->
        <!-- <p class="messages">Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur veniam dolores nam unde! Quas fugit error veniam mollitia nam quisquam!</p>
            </div>
        </div> -->
    </div>

    <!-- input messages -->
    <form action="" id="typingArea">
        <div id="messagingTypingSection">
            <input type="text" name="outgoing" placeholder="Escribe un mensaje" id="outgoing" class="setid" autocomplete="off" value="<?php echo $myid; ?>" hidden>
            <input type="text" name="incoming" placeholder="Escribe un mensaje" id="incoming" class="setid" autocomplete="off" value="<?php echo $userid ?>" hidden>
            <button id="custom-button">
                <i class="fas fa-save"></i>
            </button>
            <input type="file" name="image" class="image" id="image" style="display: none;" accept=".png, .jpg, .gif, .jpeg">
            <input type="text" name="typingField" placeholder="Escribe un mensaje" id="typingField" autocomplete="off" data-emojiable="true">
            <input onclick="borrarTexto()" type="submit" value="Enviar" id="sendMessage">
        </div>
    </form>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="js/message.js"></script>
    <script src="js/showChat.js"></script>
    <script src="js/deleteMessage.js"></script>

    <script src="lib/js/config.min.js"></script>
    <script src="lib/js/util.min.js"></script>
    <script src="lib/js/jquery.emojiarea.min.js"></script>
    <script src="lib/js/emoji-picker.min.js"></script>


    <script>
        // Mostrar alerta al clickear reportButton
		function borrarTexto() {
			document.getElementsByClassName("emoji-wysiwyg-editor")[0].innerHTML = "";
			const mainSection = document.getElementById('mainSection');
			mainSection.scrollTop = mainSection.scrollHeight;
		}

        const reportButton = document.getElementById("reportButton");
        reportButton.addEventListener("click", async () => {
            const {
                value: motivo
            } = await Swal.fire({
                title: "Motivo de reporte",
                input: "text",
                inputPlaceholder: "Ingresa el motivo de tu reporte",
            });
            if (motivo) {
                Swal.fire(`Reportado por: ${motivo}`);
            }
            const urlId = window.location.search;
            const userId = urlId.split("=")[1];

				await fetch("php/report.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        reported: userId,
                        reason: motivo
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    Swal.fire(data.message)
                });
        })

        

        const fondoButton = document.getElementById("fondoButton");
        fondoButton.addEventListener("click", async () => {
            const formData = new FormData();
            const {
                value: file
            } = await Swal.fire({
                title: "Cambiar fondo del chat",
                input: "file",
                inputPlaceholder: "Sube tu nuevo fondo",
            });
            if (file) {
                const urlId = window.location.search;
                const userId = urlId.split("=")[1];
                formData.append("file", file);
                formData.append("id", userId)
                Swal.fire(`Fondo cambiado`);
            }

            // Send formdata
            await fetch("php/changeFondo.php", {
				method: "POST",
				body: formData,
			})
			.then((response) => response.json())
			.then((data) => {
				Swal.fire(data.message).then((result) => {
				  if (result.isConfirmed) {
					location.reload();
				  }
				})
			});
        })
    </script>

    <script>
        // Mostrar alerta al clickear reportButton

        const blockButton = document.getElementById("blockButton");
        blockButton.addEventListener("click", async () => {
            await Swal.fire({
                title: "Bloquear usuario",
                text: "Estas seguro de querer bloquear a este usuario?, podras desbloquearlo nuevamente dando click a este boton",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Bloquear",
                cancelButtonText: "Cancelar",

            }).then(result => {
                if (result.isConfirmed) {
                    const urlId = window.location.search;
                    const userId = urlId.split("=")[1];
                    location.href = "php/mensajeria?block=" + userId + "&from=chat";
                }
            });

        })
    </script>


    <script>
        $(document).ready(function() {
            // Inicializa y crea un conjunto de emojis desde la hoja de sprites
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: 'lib/img',
                popupButtonClasses: 'fa fa-smile-o' // far fa-smile if you're using FontAwesome 5
            });
            window.emojiPicker.discover();
        });
    </script>
</body>

</html>
