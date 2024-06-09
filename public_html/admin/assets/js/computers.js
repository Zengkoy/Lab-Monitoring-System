window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_labs_options();
                generate_computer_table();
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

function show_reports() {
    $('#related-reports').show();
}

function generate_labs_options() {
    $.ajax({
        url: "../../php/generateLabOpt.php",

        success: function(msg) {
            $("#lab-select-dd").html(msg);
            $("#lab").html(msg);
        },
        error: function(errorThrown) {
            alert(errorThrown);
        }
    })
}

function generate_computer_table() {
    $.ajax({
        type: "POST",
        url: "../../php/listComputers.php",
        data: $("#lab-select").serialize(),

        success: function(msg) {
            $("#computer-list").html(msg);
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

function show_computer_details(button) {
    var values = { 'pc': button.dataset.pcid };

    $.ajax({
        url: "../../php/showComputerDetails.php",
        type: "POST",
        data: values,

        success: function(msg) {
            $("#computer-details").html(msg);
            $("#computer-details").show();
            window.scrollTo(0,0);
        }
    });
}

function hide_details() {
    $("#computer-details").hide();
    $('#related-reports').hide();
}

function add_computer() {
    $("#add-pc-form").validate({
        rules: {
            lab: "required",
            pcNumber: "required"
        },
        messages: {
            lab: "Choose Lab Number",
            pcNumber: "Enter PC Number"
        },

        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: "../../php/addComputer.php",
                data: $(form).serialize(),

                success: function(msg) {
                    if (msg == 'OK') {
                        $("#success-message").show();
                        setTimeout(function() {$("#success-message").fadeOut()}, 3000);
                        generate_computer_table();
                    }
                    else {
                        $("#error-message").show();
                        $("#error-message > span").html(msg);
                        setTimeout(function() {$("#error-message").fadeOut()}, 3000);
                    }
                },
                error: function() {
                    $("#error-message").show();
                    $("#error-message > span").html("Something went wrong.");
                    setTimeout(function() {$("#error-message").fadeOut()}, 3000);
                }
            })
        }
    })
}

function remove_computer(button) {
    if(confirm("Remove Computer from Databse?\n(This will also delete the reports)")) {
        var values = { 'pc': button.dataset.pcid };
        $.ajax({
            url: "../../php/removeComputer.php",
            type: "POST",
            data: values,

            success: function(msg) {
                if(msg == "OK") {
                    generate_computer_table();
                }
                else
                {
                    console.log(msg);
                }
            }
        });
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

function resolve(button) {
    var values = {
        'key': button.dataset.report,
        'pc': button.dataset.pcid
    };
    $.ajax({
        url: "../../php/resolveIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_computer_table();
                show_computer_details(button);
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
        'pc': button.dataset.pcid
    };
    $.ajax({
        url: "../../php/unresolveIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_computer_table();
                show_computer_details(button);
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
        'pc': button.dataset.pcid
    };
    $.ajax({
        url: "../../php/deleteIssue.php",
        type: "POST",
        data: values,

        success: function(msg) {
            if(msg == "OK") {
                generate_computer_table();
                show_computer_details(button);
            }
            else
            {
                console.log(msg);
            }
        }
    });
}

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });