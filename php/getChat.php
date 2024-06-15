<?php
include_once("config.php");
session_start();
$outgoingid = $_SESSION['id'];
$incomingid = mysqli_real_escape_string($conn, $_POST['incomingid']);

// Función para convertir URLs en enlaces clickeables
function makeUrlsClickable($text) {
    $pattern = '/(https?:\/\/[^\s]+)/';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    return preg_replace($pattern, $replacement, $text);
}

// Obtener mensajes
$getMsgQuery = "SELECT * FROM `messages` LEFT JOIN `users` ON messages.outgoing = users.id WHERE outgoing = '{$outgoingid}' AND incoming = '{$incomingid}' OR outgoing = '{$incomingid}' AND incoming = '{$outgoingid}'";
$runGetMsgQuery = mysqli_query($conn, $getMsgQuery);

if (!$runGetMsgQuery) {
    echo "Query Failed";
} else {
    if (mysqli_num_rows($runGetMsgQuery) > 0) {
        while ($row = mysqli_fetch_assoc($runGetMsgQuery)) {

            // Convertir URLs en enlaces clickeables
            $messageContent = makeUrlsClickable($row["messages"]);

            // Imprimir mensajes
            if ($row['outgoing'] == $outgoingid) {
                echo '<div class="responseCard outgoing">
                    <input type="text" id="messageId" value="'.$row["messages_id"].'" class="disabledInput">
                    <a href="php/delete.php?message='.$row["messages_id"].'&&userid='.$incomingid.'" id="deleteButton">
                        <img src="./images/Iconos/Basura.png" class="basura-icon">
                    </a>
                    <div class="response">
                        <!-- name -->
                        <h3 class="name">Tu</h3>
                        <!-- outgoing message -->
                        <p class="messages">'.$messageContent.'</p>';

                if ($row["imagen"] != "") {
                    echo '<img src="images/'.$row["imagen"].'" class="imagen" alt="">';
                }

                echo '</div>
                    </div>';
            } else {
                echo '<div class="request incoming">
                        <!-- name -->
                        <h3 class="name">'.$row["firstName"]." ".$row["lastName"].'</h3>
                        <!-- incoming -->
                        <p class="messages">'.$messageContent.'</p>
                    </div>';
            }
        }
    } else {
        echo '<div id="errors">Si no hablaste con este usuario antes, estaras en su bandeja. (Aprobacion)</div>';
    }
}
?>
