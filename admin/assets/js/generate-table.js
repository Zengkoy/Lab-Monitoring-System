window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_table();
            }
            else {
                window.location.replace('pages/sign-in.html');
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

function generate_table() {
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
            window.location.replace('pages/sign-in.html');
        },
        error: function(errorThrown){
            $("#error").html(errorThrown);
        }
    });
}

function resolve() {
    $.ajax({
        url: "../php/resolveIssue.php",
        type: "POST",
        data: $("#hidForm").serialize(),

        success: function(msg) {
            if(msg == "OK") {
                console.log(msg);
            }
            else
            {
                console.log(msg);
            }
        }
    });
}