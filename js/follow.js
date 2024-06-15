$(document).ready(function () {
  setInterval(function () {
    if (!$("#search").val()) {
      $.ajax({
        url: "php/followUsers.php",
        type: "POST",
        success: function (data) {
          $("#recomendedUsers").html(data);
        },
      });
    } else {
      $("#search").on("keyup", function () {
        let search = $(this).val();
        $.ajax({
          url: "php/searchFollowUsers.php",
          type: "POST",
          data: { search: search },
          success: function (data) {
            $("#onlineUsers").html(data);
            $("#getUsers").scrollTop(0);
          },
        });
      });
    }
  }, 500);
});

$(".noselect").on("click", function (e) {
  e.preventDefault();
  location.href = "home";
});
