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
        url: "../../php/students-archive.php",

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
        url: "../../php/students-archive.php",
        data: {
            'course': $("#course").val(),
            'search': $("#search").val()
        },

        success: function(msg) {
            $("#students-table").html(msg);
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function restore_student(button) {
    if(confirm("Restore Student to Active Database?")) {
        var values = { 
            'student': button.dataset.student,
            'action': 'restore'
        };
        $.ajax({
            url: "../../php/students-archive.php",
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

function remove_student(button) {
    if(confirm("Remove Student from Database?")) {
        var values = { 
            'student': button.dataset.student,
            'action': 'remove'
        };
        $.ajax({
            url: "../../php/students-archive.php",
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
$("#search-btn").click(function() { generate_students_table(); });
$("#search").change(function() { generate_students_table(); });
$("#clear-btn").click(function(){
    $("#course").val("").change();
    $("#search").val("");
    generate_students_table();
})

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });