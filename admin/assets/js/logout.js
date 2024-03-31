$('#logout').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: "../../php/logout.php",

        success: function() {
            window.location.replace('../');
        },
        error: function(errorThrown){
            $("#error").html(errorThrown);
        }
    });
})