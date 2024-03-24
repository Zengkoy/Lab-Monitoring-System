window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
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

function generate_report_table() {
    $.ajax({
        url: "../../php/listReports.php",

        success: function() {
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