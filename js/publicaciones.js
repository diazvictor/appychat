
  $(".noselect").on("click", function (e) {
    e.preventDefault();
    location.href = "home";
  });


$("#publicacionArea").on("submit", function(e) {
e.preventDefault();
	let formData = new FormData(this);
	formData.append("form","form");
	if($("#titulo").val() == "") return false;
	if($("#contenido").val() == "") return false;
	$.ajax({
		type: "POST",
		url: "php/crearPublicacion.php",
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
		  $("#titulo").val(""); // Limpia el campo de mensaje
		  $("#contenido").val("");
		  $("#imagen").val(""); // Limpia el campo de imagen (si es necesario)
		  location.reload();
		}
	});
	$("#publicacionArea").scrollTop($("#publicacionArea")[0].scrollHeight);
  
});

    // There will be multiple #comentar, so we need to use a class instead of an id
