window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_subjects_table();
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


function generate_subjects_table() {
    $.ajax({
        type: "POST",
        url: "../../php/listSubjects.php",
        data: $("#lab-select").serialize(),

        success: function(msg) {
            $("#subjects-list").html(msg);
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

function remove_subject(button) {
    if(confirm("Remove Subject from Database?\n(This will also delete the PC Assignments)")) {
        var values = { 'subject': button.dataset.subject };
        $.ajax({
            url: "../../php/removeSubject.php",
            type: "POST",
            data: values,

            success: function(msg) {
                if(msg == "OK") {
                    generate_subjects_table();
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

function addSubject() {
    var subject = $("#subject-name").val();

    if(subject != "") {
        $.ajax({
            url: "../../php/addSubject.php",
            type: "POST",
            data: {'subject': $("#subject-name").val()},
    
            success: function(msg) {
                if(msg == "OK") {
                    alert("Subject Added!");
                    $("#subject-name").val("");
                    showSubForm();
                    generate_subjects_table();
                }
                else {
                    $("#error").html(msg);
                }
            }
        })
    }
    else {
        $("#error").html("Please enter a subject name");
    }
}

function showSubForm() {
    if($("#show-hide-btn").html() == "Cancel") {
        $("#show-hide-btn").html("Add Subject");
    }
    else {
        $("#show-hide-btn").html("Cancel");
    }
    $("#add-subject").toggle();
    
}

$("#show-hide-btn").click(function() { showSubForm(); });
$("#add-sub-btn").click(function() { addSubject(); });