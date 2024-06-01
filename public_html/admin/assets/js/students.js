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

function list_subjects() {
    $.ajax({
        url: "../../php/students.php",

        success: function(msg) {
            $("#course").html(msg);
            generate_students_table();
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    })
}

function generate_students_table() {
    $.ajax({
        type: "POST",
        url: "../../php/students.php",
        data: $("#course").serialize(),

        success: function(msg) {
            $("#students-table").html(msg);
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function remove_student(button) {
    if(confirm("Remove Student from Database?")) {
        var values = { 'student': button.dataset.student };
        $.ajax({
            url: "../../php/students.php",
            type: "POST",
            data: values,

            success: function(msg) {
                if(msg == "OK") {
                    generate_students_table();
                }
                else
                {
                    console.log(msg);
                }
            }
        });
    }
}

$("#course").change(function() { generate_students_table(); });

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });