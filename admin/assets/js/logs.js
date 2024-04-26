window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_logs_table();
            }
            else {
                window.location.replace('../');
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

function generate_logs_table() {
    $.ajax({
        url: "../../php/listLogs.php",

        success: function() {
            $("#logsTable").load("../../php/logs.txt");
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}