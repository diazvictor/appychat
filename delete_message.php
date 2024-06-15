<?php
// Incluir el archivo de configuración de la base de datos
include_once "config.php";

// Verificar si se recibió un ID de mensaje válido
if (isset($_POST['messageId']) && !empty($_POST['messageId'])) {
    $messageId = $_POST['messageId'];

    // Construir la consulta para eliminar el mensaje
    $deleteQuery = "DELETE FROM messages WHERE id = '$messageId'";
        
    // Ejecutar la consulta de eliminación
    if(mysqli_query($conn, $deleteQuery)) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("error" => "Error al intentar borrar el mensaje"));
    }
} else {
    echo json_encode(array("error" => "ID de mensaje inválido"));
}
?>
