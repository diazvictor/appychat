$(document).ready(function() {
    $("#deleteButton").on("click", function(e) {
        e.preventDefault();
        let messageId = $("#messageId").val();

        if (messageId) {
            console.log("messageId: " + messageId);
        }
    });
});