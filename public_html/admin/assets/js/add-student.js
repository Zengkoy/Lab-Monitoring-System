$.ajax({
    url: "../../php/loginCheck.php",

    success: function(msg) {
        if(msg == "OK") {
            registerForm();
        }
        else {
            window.location.replace('../');
        }
    },
    error: function(errorThrown){
        alert(errorThrown);
    }
});

// Form
function registerForm() {
    if ($('#register-form').length > 0 ) {
        $( "#register-form" ).validate( 
        {
            rules: 
            {
                usn: "required",
                name: "required",
                email: "required",
                course: "required",
            },
            messages: 
            {
                usn: "Please enter USN",
                name: "Please enter Name",
                email: "please enter Email",
                course: "Please enter Course",
            },
            /* submit via ajax */
            
            submitHandler: function(form) {		
                var $submit = $('.submitting'),
                    waitText = 'Submitting...';

                $.ajax({   	
                    type: "POST",
                    url: "../../php/addStudent.php",
                    data: $(form).serialize(),

                    beforeSend: function() { 
                        $submit.css('display', 'block').text(waitText);
                    },
                    success: function(msg) {
                        if (msg.substr(0,2) == 'OK') {
                            $("#result").html("<div class='alert alert-success py-0'>Success! Check your Email for the password and QR Code</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 6000);
                        } 
                        else {
                            $("#result").html("<div class='alert alert-danger py-0'>" + msg + "</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        }
                    },
                    error: function() {
                        $('#form-message-warning').html("Something went wrong. Please try again.");
                        $('#form-message-warning').fadeIn();
                        $submit.css('display', 'none');
                    }
                });
            } // end submitHandler

        });
    }
};

function submit_file() {
    var data = new FormData();
    $.each($("#file")[0].files, function(i, file) {
        data.append("file-"+i, file);
    });

    $.ajax({
        url: "../../php/addStudent.php",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: "POST",
        type: "POST",

        success: function(msg) {
            if(msg == "OK") {
                $("#result").html("<div class='alert alert-success py-0'>Success! All entries added Successfully</div>");
                $("#result").show();
                setTimeout(function() {
                    $("#result").fadeOut();
                }, 3000);
            }
            else {
                $("#result").html("<p class='alert alert-danger'>" + msg + "</p>");
                $("#result").show();
                /* setTimeout(function() {
                    $("#result").fadeOut();
                }, 3000); */
            }
        }
    })
}

$("#submit-file-btn").click(function() {submit_file();});


var qr = $("#qr-container");

$("#done-button").click(function() { 
    $("#usn").val("");
    $("#name").val("");
    $("#email").val("");
    $("#course").val("");
    qr.hide() 
});

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });