<?php
include_once("config.php");
session_start();
$outgoingid = $_SESSION['id'];
$incomingid = mysqli_real_escape_string($conn, $_POST['incomingid']);

// get message query
$getMsgQuery = "SELECT *, g.id AS message_id FROM grupo_messages AS g INNER JOIN users AS u ON g.outgoing = u.id WHERE g.id_group = $incomingid ORDER BY g.id ASC";
$runGetMsgQuery = mysqli_query($conn, $getMsgQuery);
if(!$runGetMsgQuery){
    echo "Query Failed";
}else{
    if(mysqli_num_rows($runGetMsgQuery) > 0){
        while($row = mysqli_fetch_assoc($runGetMsgQuery)){

            $messageContent = $row["message"];
            $messageContent = makeUrlsClickable($messageContent);

            if($row['id'] == $outgoingid){
                echo '<div class="responseCard outgoing">
                <input type="text" id="messageId" value="'.$row["id"].'" class="disabledInput">
                <a href="php/delete.php?message='.$row["message_id"].'&grupo='.$incomingid.'" id="deleteButton">
                    <img src="./images/Iconos/Basura.png" class="basura-icon">
                </a>
                <div class="response">
                    <!-- name -->
                    <h3 class="name">Tu</h3>
                    <!-- outgoing message -->
                    <p class="messages">'.$messageContent.'</p>';

                    if($row["imagen"] != ""){
                        echo '<img src="images/Grupos/'.$row["imagen"].'" class="imagen" alt="">';
                    }
                
                    echo '</div>
                    </div>';

            }else{
                echo '<div class="request incoming">
                <!-- name -->
                <h3 class="name">'.$row["firstName"]." ".$row["lastName"].'</h3>
                <!-- incoming -->
                <p class="messages">'.$row["message"].'</p>
            </div>';
            }
        }
    }else{
        echo '<div id="errors">No hay mensajes disponibles</div>';
    }
}

function makeUrlsClickable($text) {
    $pattern = '/(http[s]?:\/\/[^\s]+)/';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    return preg_replace($pattern, $replacement, $text);
}
?>
