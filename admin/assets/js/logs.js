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

function generate_logs_table(date, pc) {
    $.ajax({
        url: "../../php/listLogs.php",
        type: "POST",
        data: { 
            'lab': $("#lab-select").val(),
            'date': date,
            'pc': pc
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
var pcElement = $("#pc-select");

$("#lab-select").change(function() { 
    generate_logs_table("", "");
    dateElement.val("");
    pcElement.val(""); 
});
$("#date-select").change(function() { generate_logs_table(dateElement.val(), pcElement.val()); });
$("#pc-select").change(function() { generate_logs_table(dateElement.val(), pcElement.val()); });

$("#clear-date-button").click(function() { 
    dateElement.val("");
    pcElement.val("");
    generate_logs_table("");
});

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });