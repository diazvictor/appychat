$(document).ready(function () {
  $("#signupData").on("submit", function (e) {
    e.preventDefault();
    // get all form data
    let formData = new FormData(this);
    formData.append("signup", "signup");

    // get & post request in jquery
    $.ajax({
      // post or get => post
      type: "POST",
      // file name
      url: "php/signup.php",
      data: formData,
      contentType: false,
      processData: false,
      // get response
      success: function (response) {
        if (response == "Exito") {
          location.href = "users";
        } else {
          $("#errors").css("display", "block");
          $("#errors").html(response);
        }
      },
    });
  });
});
