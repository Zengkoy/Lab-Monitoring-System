$.ajax({
    url: "../../php/loginCheck.php",

    success: function(msg) {
        if(msg == "OK") {
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

function generate_labs_options() {
    $.ajax({
        url: "../../php/generateLabOpt.php",

        success: function(msg) {
            $("#lab").html(msg);
        },
        error: function(errorThrown) {
            alert(errorThrown);
        }
    })
}

function form_submit() {
    if ($('#report-form').length > 0 ) {
        $( "#report-form" ).validate( {
            rules: {
                pc: "required",
                usability: "required",
                issueCheck: { 
                    required: function() {
                        return is_required();
                    }
                },
                hardwareName: { required: "#hardwareCheck:checked" },
                softwareName: { required: "#softwareCheck:checked" }
            },
            messages: 
            {
                pc: "Please enter PC Number",
                usability: "Please confirm if the computer is usable",
                issueCheck: "Please choose an option",
                hardwareName: "Enter broken hardware",
                softwareName: "Enter name of program or functionality"
            },
            errorElement: "div",
            errorLabelContainer: "error",
            errorClass: "text-sm text-danger",
            errorPlacement: function(error, element) {
                if($(element).attr("name") == "pc"){
                    error.insertBefore($(element));
                }
                else if($(element).attr("name") == "issueCheck"){
                    error.insertAfter($(element).parent().parent().next())
                }
                else {
                    error.insertAfter($(element).parent());
                }
            },

            /* submit via ajax */
            
            submitHandler: function(form) {
                var formData = $(form).serializeArray();
                var data = {};

                $(formData).each(function(index, obj) {
                    data[obj.name] = obj.value;
                });

                console.log(data);

                var issueCheck = "";
                var boxes = $("input[name=issueCheck]:checked");

                boxes.each(function() {
                    issueCheck += this.value;
                });

                $.ajax({   	
                    type: "POST",
                    url: "../../php/submitTicket.php",
                    data: {
                        'form': data,
                        'checks': issueCheck,
                        'is-admin': 'yes'
                    },

                    success: function(msg) {
                        if (msg == 'OK') {
                            $("#result").html("<div class='alert alert-success py-0'>Success!</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        } 
                        else {
                            console.log(msg)
                            $("#result").html("<div class='alert alert-danger text-white py-0'>"+ msg + "</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        }
                    },
                    error: function() {
                        $('#result').html("Something went wrong. Please try again.");
                        $("#result").show();
                        setTimeout(function() {$('#result').fadeOut();}, 3000);
                    }
                });
            } // end submitHandler

        });
    }
}

function is_required() {
    var required = true;
    var boxes = $("input[name=issueCheck]:checked");

    if((boxes.length > 0) || $("#softwareCheck").is(":checked") || $("#hardwareCheck").is(":checked")) {
        required = false;
    }
    console.log(required);
    return required;
}

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });