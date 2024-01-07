window.onload = function() {
    $.ajax({
        url: "../../php/listReports.php",

        success: function() {
            $("#reportsTable").load("../../php/table.txt");
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

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