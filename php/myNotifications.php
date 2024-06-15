<?php
// include config file
include_once("config.php");
session_start();

$user_id = $_SESSION['id'];
$notificationsByUserQuery = "SELECT * FROM notifications WHERE id_user = {$user_id} ORDER BY id DESC";

// Run Query 
$query = mysqli_query($conn, $notificationsByUserQuery);
if (!$query) {
    echo "La consulta fallo";
} else {
    // Obtener la información del usuario actual
    $getUser = "SELECT * FROM users WHERE id = {$user_id}";
    $runGetUser = mysqli_query($conn, $getUser);
    $user = mysqli_fetch_assoc($runGetUser);

    // Recorrer todas las notificaciones
    while ($notifications = mysqli_fetch_assoc($query)) {
        // Obtener el mensaje de la notificación
        $message = $notifications["message"];
        
        // Dividir el mensaje en palabras individuales
        $words = explode(' ', $message);

              // Eliminar las partes específicas del mensaje
            foreach ($words as $key => $word) {
                if ($word === "Te" && isset($words[($key+1)]) && $words[($key+1)] === "has" && isset($words[($key+2)]) && $words[($key+2)] === "unido" && isset($words[($key+3)]) && $words[($key+3)] === "al" && isset($words[($key+4)]) && $words[($key+4)] === "grupo:") {
                    // Si coincide, eliminar estas palabras del mensaje
                    unset($words[($key+4)]);
                    unset($words[($key+3)]);
                    unset($words[($key+2)]);
                    unset($words[($key+1)]);
                    unset($words[$key]);
                } elseif ($word === "Abandonaste" && isset($words[($key+1)]) && $words[($key+1)] === "el" && isset($words[($key+2)]) && $words[($key+2)] === "grupo") {
                    // Si coincide, eliminar estas palabras del mensaje
                    unset($words[($key+2)]);
                    unset($words[($key+1)]);
                    unset($words[$key]);
                }
            }
        
            // Reconstruir el mensaje sin las partes eliminadas
            $message = implode(' ', $words);
        // Inicializar una variable para almacenar el usuario encontrado
        $user2 = null;

        // Recorrer cada palabra del mensaje para buscar coincidencias en la base de datos
        foreach ($words as $word) {
            // Consultar la base de datos para buscar coincidencias en firstName o lastName
            $getUserQuery = "SELECT * FROM users WHERE firstName = '$word' OR lastName = '$word'";
            $getGroupQuery = "SELECT * FROM grupo WHERE titulo LIKE '%$word%'";
            $runGetUserQuery = mysqli_query($conn, $getUserQuery);
            $runGetGroupQuery = mysqli_query($conn, $getGroupQuery);
            // Verificar si se encontraron resultados
            if (mysqli_num_rows($runGetUserQuery) > 0) {
                // Obtener la primera fila coincidente
                $user2 = mysqli_fetch_assoc($runGetUserQuery);
                $imagePath = "images/Profiles/" . $user2['image'];
                // Romper el bucle porque ya se encontró un usuario
                break;
            }
            
            if(mysqli_num_rows($runGetGroupQuery) > 0   ){
                $user2 = mysqli_fetch_assoc($runGetGroupQuery);
                 $imagePath = "images/Profiles/" . $user2['imagen'];
                     // Romper el bucle porque ya se encontró un usuario
                break;
            }
        }
        echo '
                <div class="profile">
                    <!-- profile image -->
                    <div id="mainImage" class="image">';

       if ($user2["image"] === null) {
                echo '<img src="images/Grupos/' . $user2["imagen"] . '" alt="">';
            } else {
                echo '<img src="images/Profiles/' . $user2["image"] . '" alt="">';
            }

            
  
        echo '</div>
                    <!-- last message -->
                    <p style="margin-left: 75px;">' . $notifications["message"] . '</p>
              
                    <!-- status => Online or Offline -->';

        // Resto del código para mostrar los botones según el tipo de notificación

        echo '<div class="report-buttons">';
        if ($notifications["type"] == 1) {
            echo '
                                <a class="report-button" href="php/deleteNotification.php?id=' . $notifications["id"] . '">
                                    <img src="images/prohibido.png" class="chat-icon">
                                </a>
                                <a class="report-button" href="invitar?grupo=' . $notifications["link"] . '&user">
                                    <img src="images/Iconos/Verificacion.png" class="chat-icon">
                                </a>';
        } else if ($notifications["type"] == 2) {
            echo '
                                <a class="report-button" href="php/deleteNotification.php?id=' . $notifications["id"] . '">
                                    <img src="images/prohibido.png" class="chat-icon">
                                </a>
                                <a class="report-button" href="php/friend?accept=' . $notifications["link"] . '">
                                    <img src="images/Iconos/Agregar.png" class="chat-icon">
                                </a>';
        } else {
            echo '
                                <a class="report-button" href="php/deleteNotification.php?id=' . $notifications["id"] . '">
                                    <img src="images/prohibido.png" class="chat-icon">
                                </a>';
        }
        echo '</div>
                </div>';
    }
}
?>
