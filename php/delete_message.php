<?php
// Incluir el archivo de configuración de la base de datos
include_once("config.php");

// Verificar si se recibió un ID de mensaje válido a través de una solicitud POST
if(isset($_POST['message_id']) && !empty($_POST['message_id'])) {
    // Obtener el ID del mensaje de la solicitud
    $messageId = $_POST['message_id'];

    // Query para eliminar el mensaje de la tabla de mensajes
    $deleteQuery = "DELETE FROM messages WHERE messages_id = '$messageId'";

    // Ejecutar la consulta de eliminación
    if(mysqli_query($conn, $deleteQuery)) {
        // La eliminación fue exitosa
        header("Location: ../"); // Redirigir a la página principal o a cualquier otra página después de la eliminación
    } else {
        // La eliminación falló
        echo "Error al intentar borrar el mensaje";
    }
} else {
    // Si no se recibió un ID de mensaje válido, devolver un mensaje de error
    echo "ID de mensaje inválido";
}
?>
