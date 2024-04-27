window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_logs_table("");
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

function generate_logs_table(date) {
    $.ajax({
        url: "../../php/listLogs.php",
        type: "POST",
        data: { 
            'lab': $("#lab-select").val(),
            'date': date
        },

        success: function(msg) {
            $("#logsTable").load("../../php/logs.txt");
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

var dateElement = $("#date-select");
var defaultDate = dateElement.val();

$("#lab-select").change(function() { generate_logs_table(""); });
$("#date-select").change(function() { generate_logs_table(dateElement.val()); });

$("#clear-date-button").click(function() { 
    dateElement.val(""); 
    generate_logs_table("");
});