$("#typingArea").on("submit", function (e) {
  e.preventDefault();
  let formData = new FormData(this);
  formData.append("send", "send");
  if ($("#typingField").val())
    $.ajax({
      type: "POST",
      url: "php/grupo.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        $("#typingField").val(""); // Limpia el campo de mensaje
        $("#image").val(""); // Limpia el campo de imagen (si es necesario)
      },
    });
  $("#mainSection").scrollTop($("#mainSection")[0].scrollHeight);
});

$(document).ready(function () {
  $("#custom-button").click(function () {
    $("#image").click();
  });
});

$(document).ready(function () {
  setInterval(function () {
    let incomingid = $("#incoming").val();
    $.ajax({
      type: "post",
      url: "php/getGrupo.php",
      data: { incomingid: incomingid },
      success: function (response) {
        $("#mainSection").html(response);
      },
    });
  }, 500);
});
