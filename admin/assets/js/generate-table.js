window.onload = function() {
    $("#2ndtable").hide();
    const parser = new DOMParser;
    $.ajax({
        url: "../../php/submitTicket.php",

        success: function(table) {
            $("#reportsTable").html(parser.parseFromString(table, 'text/html').firstChild.body);
        },
        error: function() {
            $("#reportsTable").innerHTML = "<p>something went wrong</p>";
        }
    });
}