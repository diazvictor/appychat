$(document).ready(function () {
    setInterval(function () {
      if (!$("#search").val()) {
        $.ajax({
          url: "php/myNotifications.php",
          type: "POST",
          success: function (data) {
            $("#onlineUsers").html(data);
          },
        });
      } else {
        $("#search").on("keyup", function () {
          let search = $(this).val();
          $.ajax({
            url: "php/search.php",
            type: "POST",
            data: { search: search },
            success: function (data) {
              $("#onlineUsers").html(data);
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