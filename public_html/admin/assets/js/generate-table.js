window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_labs_options();
                generate_report_table();
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

function generate_labs_options() {
    $.ajax({
        url: "../../php/generateLabOpt.php",

        success: function(msg) {
            $("#lab-select").html(msg);
        },
        error: function(errorThrown) {
            alert(errorThrown);
        }
    })
}

function generate_report_table() {
    $.ajax({
        url: "../../php/listReports.php",
        type: "POST",
        data: { 
            'lab': $("#lab-select").val(),
            'status': $("#status-select").val(),
            'type': $("#type-select").val(),
            'recent': $("#recent-select").val(),
            'spec-date': $("#month-select").val()
         },

        success: function(msg) {
            console.log(msg);
            $("#reportsTable").load("../../php/table.txt");
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function logout() {
    $.ajax({
        url: "../../php/logout.php",

        success: function() {
            window.location.replace('../');
        },
        error: function(errorThrown){
            $("#error").html(errorThrown);
        }
    });
}

function resolve(button) {
    var values = {
        'key': button.dataset.report,
        'pc': button.dataset.computer
    };
    $.ajax({
        url: "../../php/resolveIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_report_table();
            }
            else
            {
                console.log(msg);
            }
        }
    });
}

function unresolve(button) {
    var values = {
        'key': button.dataset.report,
        'pc': button.dataset.computer
    };
    $.ajax({
        url: "../../php/unresolveIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_report_table();
            }
            else
            {
                console.log(msg);
            }
        }
    });
}

function delete_report(button) {
    var values = {
        'key': button.dataset.report,
        'pc': button.dataset.computer
    };
    $.ajax({
        url: "../../php/deleteIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_report_table();
            }
            else
            {
                console.log(msg);
            }
        }
    });
}

function show_description(button) {
    var reportId = button.dataset.id;
    var desc = "#desc";
    var overflow = $(desc + reportId).css("overflow");

    if (overflow == "visible"){
        $(desc + reportId).css("overflow", "hidden");
        $(desc + reportId).css("white-space", "normal");
    }
    else {
        $(desc + reportId).css("overflow", "visible");
        $(desc + reportId).css("white-space", "");
    }
}

$("#print-button").click(function() { window.print(); });
$("#lab-select").change(function() { generate_report_table(); });
$("#status-select").change(function() { generate_report_table(); });
$("#type-select").change(function() { generate_report_table(); });

var isChanged = 0;
$("#recent-select").change(function() {
    if($("#month-select").val() != "" && isChanged != 1) {
        $("#month-select").val("").change();
    }
    isChanged = 1;
    generate_report_table(); 
});
$("#month-select").change(function() { 
    if($("#recent-select").val() != "" && isChanged != 2) {
        $("#recent-select").val("").change();
    }
    isChanged = 2;
    generate_report_table();
 });

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });