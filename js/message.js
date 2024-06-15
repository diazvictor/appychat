$("#typingArea").on("submit", function(e) {
	e.preventDefault();
	var formData = new FormData(this);

	formData.append("send","send");
	if ($("#typingField").val())
	$.ajax({
		type: "POST",
		url: "php/messages.php",
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			$("#typingField").val(""); // Limpia el campo de mensaje
			$("#image").val(""); // Limpia el campo de imagen (si es necesario)
		}
	});
	$("#mainSection").scrollTop($("#mainSection")[0].scrollHeight);
});
// Esto lo hizo Gemini
// function reiniciarPag (){
	// window.location.reload()
// }
$(document).ready(function() {
	$("#custom-button").click(function() {
		$("#image").click();
	});
});
