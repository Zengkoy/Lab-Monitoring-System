window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_labs_table();
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


function generate_labs_table() {
    $.ajax({
        type: "POST",
        url: "../../php/labs.php",

        success: function(msg) {
            $("#labs-list").html(msg);
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

function remove_lab(button) {
    if(confirm("Remove Lab from Database?\n(This will also delete all PCs in the Lab)")) {
        var values = { 
            'action': "remove",
            'lab': button.dataset.lab 
        };
        $.ajax({
            url: "../../php/labs.php",
            type: "POST",
            data: values,

            success: function(msg) {
                if(msg == "OK") {
                    generate_labs_table();
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

function addLab() {
    var lab = $("#lab-num").val();
    lab = parseInt(lab);

    if(isNaN(lab)){
        alert("Please enter a valid number");
    }
    else {
        $.ajax({
            url: "../../php/labs.php",
            type: "POST",
            data: {
                'action': "add",
                'lab': lab
            },
    
            success: function(msg) {
                if(msg == "OK") {
                    alert("Lab Added!");
                    $("#lab-num").val("");
                    showLabForm();
                    generate_labs_table();
                }
                else {
                    alert(msg);
                }
            }
        })
    }
}

function showLabForm() {
    if($("#show-hide-btn").html() == "Cancel") {
        $("#show-hide-btn").html("Add Laboratory");
    }
    else {
        $("#show-hide-btn").html("Cancel");
    }
    $("#add-lab").toggle();
}

$("#show-hide-btn").click(function() { showLabForm(); });
$("#add-lab-btn").click(function() { addLab(); });