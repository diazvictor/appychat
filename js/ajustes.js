$(".noselect").on("click", function (e) {
    e.preventDefault();
    location.href = "home";
  });
  
  // $("#publicacionArea").on("submit", function (e) {
    // e.preventDefault();
    // let formData = new FormData(this);
    // formData.append("form", "form");
    // $.ajax({
      // type: "POST",
      // url: "/php/ajustarGrupo.php",
      // data: formData,
      // contentType: false,
      // processData: false,
      // success: function (response) {
        // $("#titulo").val(""); // Limpia el campo de mensaje
        // $("#contenido").val("");
        // $("#imagen").val(""); // Limpia el campo de imagen (si es necesario)
      // },
    // });
    // $("#publicacionArea").scrollTop($("#publicacionArea")[0].scrollHeight);
    // location.reload();
  // });
  
