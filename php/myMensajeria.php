<?php
// include config file
include_once("config.php");
session_start();

$user_id = $_SESSION['id'];
$mensajeriaQuery = "SELECT * FROM mensajeria WHERE id_incoming = {$user_id} ORDER BY id DESC";

// Run Query 

$query = mysqli_query($conn, $mensajeriaQuery);
if (!$query) {
    echo "La consulta fallo";
} else {


    while ($solicitud = mysqli_fetch_assoc($query)) {

        $getUser = "SELECT * FROM users WHERE id = {$solicitud['id_outgoing']}";
        $runGetUser = mysqli_query($conn, $getUser);
        $user = mysqli_fetch_assoc($runGetUser);

        if ($solicitud['estado'] == 0) {

            // Print all notifications
            echo '
        <div class="profile">
            <!-- profile image -->
            <div class="image">
                <img src="images/Profiles/' . $user["image"] . '" alt="">
                
            </div>
            <!-- last message -->
            <p style="margin-left: 75px;">El usuario ' . $user['firstName'] . ' ' . $user['lastName'] . ' quiere hablar contigo.</p>
            <!-- status => Online or Offline -->';

            if ($solicitud["estado"] == 0) {
                echo '
                    <div class="report-buttons">
                        <a class="report-button" href="php/mensajeria?accept=' . $solicitud["id"] . '&user">
                            <img src="images/Iconos/Mensajeria.png" class="chat-icon">
                        </a>
                        <a class="report-button" href="php/mensajeria?decline=' . $solicitud["id"] . '">
                            <img src="images/prohibido.png" class="chat-icon">
                        </a>
                    </div>
            ';
            }

            echo          '
        </div>
        
        ';
        }
    }
}
