$(document).ready(function(){
    setInterval(function(){
        let incomingid = $("#incoming").val();
        $.ajax({
            type: "post",
            url: "php/getReports.php",
            data: {incomingid: incomingid},
            success: function(response){
                $("#onlineUsers").html(response);
            }
        });
    }, 500);
});

$(".noselect").on("click", function(e) {
    e.preventDefault();
        location.href = "home";
    });

