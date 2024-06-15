$(document).ready(function(){
    $("#editForm").on("submit", function(e){
        e.preventDefault();
        // get all form data
        let formData = new FormData(this);
        formData.append("edit","edit");

        // get & post request in jquery
        $.ajax({
            // post or get => post
            type: "POST",
            // file name
            url: "php/edit.php",
            data: formData,
            contentType: false,
            processData: false,
            // get response
            success: function(response){
                if(response == "success"){
                }else{
                    $("#errors").css("display", "block");
                    $("#errors").html(response);
                }
            }
        });
    });
});



$(".noselect").on("click", function(e) {
    e.preventDefault();
        location.href = "home";
    });

