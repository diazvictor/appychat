$(document).ready(function () {

  // Function for handling both GET and POST requests based on search presence
  function updateOnlineUsers(search) {
    const url = search ? "php/searchManageUsers.php" : "php/getUsers.php";
    const method = search ? "POST" : "GET";
    const data = search ? { search: search } : null; // Only send data for POST

    $.ajax({
      url: url,
      type: method,
      data: data,
      success: function (data) {
        $("#onlineUsers").html(data);
      },
    });
  }

  // Initial call without search (GET)
  updateOnlineUsers();

  // Interval for refreshing with appropriate method based on search value
  setInterval(function () {
    const searchTerm = $("#search").val();
    updateOnlineUsers(searchTerm);
  }, 1500);

  $(".noselect").on("click", function (e) {
    e.preventDefault();
    location.href = "home";
  });
});
