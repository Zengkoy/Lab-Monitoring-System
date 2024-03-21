window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            /* if(msg == "OK") {
                generate_report_table();
            }
            else {
                window.location.replace('../');
            } */
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

function generate_computer_table() {
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

function show_computer_form() {
    $("#add-pc-form").toggle();
    if($("#show-hide-btn").html() == "Add Computer") {
        $("#show-hide-btn").html("Cancel");
    }
    else {
        $("#show-hide-btn").html("Add Computer");
    }
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