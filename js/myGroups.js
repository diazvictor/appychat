$(document).ready(function () {
	$.ajax({
		url: "/php/getGroups.php",
		type: "GET",
		success: function (data) {
			$("#onlineUsers").html(data);
		},
    });

	$(".noselect").on("click", function (e) {
		e.preventDefault();
		location.href = "home";
	});
});
