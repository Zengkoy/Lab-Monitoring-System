window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                list_subjects();
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
        type: "POST",
        data: { 
            'lab': $("#lab-select").val(),
            'date': $("#date-select").val(),
            'pc': $("#pc-select").val(),
            'subject': $("#sub-select").val(),
            'course': $("#course-select").val()
        },

        success: function(msg) {
            $("#logsTable").html(msg);
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function list_subjects() {
    $.ajax({
        url: "../../php/listLogs.php",

        success: function(msg) {
            msg = JSON.parse(msg);
            $("#sub-select").html(msg['subjects']);
            $("#course-select").html(msg['courses']);
            generate_logs_table();
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

$("#lab-select").change(function() { 
    $("#date-select").val("");
    $("#pc-select").val(""); 
    $("#sub-select").val("").change();
    $("#course-select").val("").change();
    generate_logs_table();
});
$("#date-select").change(function() { generate_logs_table(); });
$("#pc-select").change(function() { generate_logs_table(); });
$("#sub-select").change(function() { generate_logs_table(); });
$("#course-select").change(function() { generate_logs_table(); });

$("#clear-date-button").click(function() { 
    $("#date-select").val("");
    $("#pc-select").val("");
    $("#sub-select").val("").change();
    $("#course-select").val("").change();
    generate_logs_table();
});

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });