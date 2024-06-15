<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}
$grupo = mysqli_real_escape_string($conn, $_GET['id']);

$grupoQuery = "SELECT * FROM `grupo` WHERE id = '{$grupo}'";
$runGrupoQuery = mysqli_query($conn, $grupoQuery);
$row = mysqli_fetch_assoc($runGrupoQuery);

$checkIfJoined = "SELECT * FROM `grupo_invites` WHERE id_invitado = '{$_SESSION['id']}' AND id_grupo = '{$grupo}'";
if(mysqli_num_rows(mysqli_query($conn, $checkIfJoined)) == 0){
    header("location: grupos");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo</title>
    <!-- css linked -->
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="css/grupo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/css/emoji.css" rel="stylesheet">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div id="container" style="background: url('./images/Grupos/<?=$row['fondo']?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        <!-- header -->
        <div id="header">
            <a href="grupos">
                <!-- arrow icon -->
                <i class="fas fa-arrow-left"></i>
            </a>

            <?php
            $myid = $_SESSION['id'];
            // getting from messages url
            $grupo = mysqli_real_escape_string($conn, $_GET['id']);

            $headerQuery = "SELECT * FROM `grupo` WHERE id = '{$grupo}'";
            $runHeaderQuery = mysqli_query($conn, $headerQuery);

            if (!$runHeaderQuery) {
                echo "Connection failed";
            } else {
                $info = mysqli_fetch_assoc($runHeaderQuery);
            ?>
                <!-- profile -->
                <div id="profileImage">
                    <img src="images/Grupos/<?php echo $info['imagen']; ?>" alt="">
                </div>

                <!-- user Detail (name & status) -->
                <div id="userDetail">
                    <div class="full-name">
                        <a href="ajustes?id=<?= $info['id'] ?>&search" id="name"><?php echo $info['titulo'] ?></a>
                    </div>


                </div>

                <div class="buttons">
					<a id="settingsButton" href="ajustes?id=<?=$grupo?>&search">
						<img src="images/config.png">
					</a>

					<a id="reportButton">
						<img src="images/report.png">
					</a>
                </div>
        </div>
    <?php
            }
    ?>

    <!-- main section => messages section -->
    <div id="mainSection">
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
            <input type="text" name="incoming" placeholder="Escribe un mensaje" id="incoming" class="setid" autocomplete="off" value="<?php echo $grupo ?>" hidden>
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
    <script src="js/grupo.js"></script>
    <script src="js/deleteMessage.js"></script>

    <script src="lib/js/config.min.js"></script>
    <script src="lib/js/util.min.js"></script>
    <script src="lib/js/jquery.emojiarea.min.js"></script>
    <script src="lib/js/emoji-picker.min.js"></script>


    <script>
        // Mostrar alerta al clickear settingsButton
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
                    Swal.fire(data.message);
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
