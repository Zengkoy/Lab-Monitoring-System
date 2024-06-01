window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                fill_profile_info();
                form_submit();
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

function fill_profile_info() {
    $.ajax({
        url: "../../php/getProfile.php",

        success: function(msg) {
            msg = JSON.parse(msg);
            $("#username").html(msg["username"]);
            $("#formUsername").val(msg["username"]);
            $("#formEmail").val(msg["email"]);
            if(msg["notif"]=="on") {
                $("#notif").prop("checked", true);
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function form_submit() {
    if ($('#edit-form').length > 0 ) {
        $( "#edit-form" ).validate( {
            rules: {
                formUsername: "required",
                formEmail: "required",
                formCurrentPassword: "required",
                formNewPassword: {
                    minlength: 8
                },
                formConfirmPassword: {
                    equalTo: "#formNewPassword"
                }
            },
            messages: 
            {
                formUsername: "Please enter Username",
                formEmail: "Please enter Email",
                formCurrentPassword: "Please enter Current Password",
                formNewPassword: "Please enter password with at least 8 characters",
                formConfirmPassword: "Password does not match"
                
            },
            /* submit via ajax */
            
            submitHandler: function(form) {

                $.ajax({   	
                    type: "POST",
                    url: "../../php/editAdmin.php",
                    data: $(form).serialize(),

                    success: function(msg) {
                        if (msg == 'OK') {
                            fill_profile_info();
                            $("#result").html("<div class='alert alert-success py-0'>Success!</div>");
                            $("#result").show();
                            $("#formCurrentPassword").val("");
                            $("#formNewPassword").val("");
                            $("#formConfirmPassword").val("");
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        } 
                        else {
                            fill_profile_info();
                            $("#result").html("<div class='alert alert-danger py-0'>"+ msg + "</div>");
                            $("#result").show();
                            $("#formCurrentPassword").val("");
                            $("#formNewPassword").val("");
                            $("#formConfirmPassword").val("");
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        }
                    },
                    error: function() {
                        fill_profile_info();
                        $('#result').html("Something went wrong. Please try again.");
                        $("#result").show();
                        $("#formCurrentPassword").val("");
                        $("#formNewPassword").val("");
                        $("#formConfirmPassword").val("");
                        setTimeout(function() {$('#result').fadeOut();}, 3000);
                    }
                });
            } // end submitHandler

        });
    }
}

function delete_admin() {
    if(confirm("Delete this Admin Account?")) {
        $.ajax({
            url: "../../php/deleteAdmin.php",
    
            success: function(msg) {
                if(msg == "OK") {
                    window.location.replace("../");
                }
                else {
                    $("#result").html("<div class='alert alert-danger py-0'>"+ msg + "</div>");
                    $("#result").show();
                    setTimeout(function() {$('#result').fadeOut();}, 3000);
                }
            },
            error: function(errorThrown){
                alert(errorThrown);
            }
        });
    }
    else {
        alert("HOY");
    }
}

function notif_change() {
    console.log($("#notif").prop("checked"));
    $.ajax({
        type: "POST",
        url: "../../php/editAdmin.php",
        data: { "notif": $("#notif").prop("checked") },

        success: function(msg) {
            if(msg == "OK") {
                $("#result").html("<div class='alert alert-success py-0'>Success!</div>");
                $("#result").show();
                setTimeout(function() {$('#result').fadeOut();}, 3000);
            }
            else {
                $("#result").html("<div class='alert alert-danger py-0'>"+ msg + "</div>");
                $("#result").show();
                setTimeout(function() {$('#result').fadeOut();}, 3000);
            }
        }
    })
}

$("#delete-admin-btn").click(function() {delete_admin();});
$("#notif").click(function() { notif_change(); });

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });