window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
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

function generate_computer_table() {
    $.ajax({
        type: "POST",
        url: "../../php/listComputers.php",
        data: $("#lab-select").serialize(),

        success: function(msg) {
            $("#computer-list").load("../../php/comp-table.txt");
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
        }
    });
}

function hide_details() {
    $("#computer-details").hide();
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