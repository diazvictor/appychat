// Evento de clic para el botón de eliminación
$(".deleteButton").on("click", function(e) {
     // Prevenir el comportamiento predeterminado del botón
     e.preventDefault();
 
     // Obtener el ID del mensaje asociado al botón
     const messageId = $(this).attr("data-message-id");
 
     // Confirmar si el usuario realmente desea eliminar el mensaje
     if (confirm("¿Estás seguro de que deseas eliminar este mensaje?")) {
         // Realizar una solicitud AJAX para eliminar el mensaje
         $.ajax({
             type: "POST", // Método de solicitud POST
             url: "delete_message.php", // URL del archivo PHP que maneja la eliminación
             data: { messageId: messageId }, // Datos a enviar al servidor (ID del mensaje)
             dataType: "json", // Tipo de datos esperados en la respuesta del servidor
             success: function(response) { // Función para manejar la respuesta del servidor
                 // Verificar si la eliminación fue exitosa
                 if (response.success) {
                     // Eliminar el mensaje de la interfaz de usuario
                     $(this).closest(".profile").remove();
                     alert("El mensaje se ha eliminado exitosamente.");
                 } else {
                     // Mostrar un mensaje de error si la eliminación falló
                     alert("Error al intentar eliminar el mensaje.");
                 }
             },
             error: function(xhr, status, error) { // Función para manejar errores de la solicitud AJAX
                 // Mostrar un mensaje de error si la solicitud falló
                 alert("Error en la solicitud AJAX: " + status);
             }
         });
     }
 });
 