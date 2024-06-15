$(document).ready(function(){
	$("#cardProfile").addClass("is-active");

	$('#cardProfile').hover(function() {}, function(){
		$("#cardProfile").removeClass("is-active");
	});
});

$("#cmtBox").on("submit", function(e) {
	e.preventDefault();
	let formData = new FormData(this);
	formData.append("comentario", "comentario");
	if($("#typingField").val())
	$.ajax({
		type: "POST",
		url: "php/comentario.php",
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			$("#typingField").val(""); // Limpia el campo de mensaje
			location.reload();
		  // if (response == "success") {
		  // }
		}
	});
});
