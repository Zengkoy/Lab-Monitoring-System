window.onload = function() {
    $.ajax({
        url: "../php/studentLoginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                fill_info();
                form_submit();
                console.log("#internetCheck:checked")
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

function fill_info() {
    $.ajax({
        url: "../php/getStudentProfile.php",

        success: function(msg) {
            console.log(msg);
            msg = JSON.parse(msg);
            if(msg['result'] == "OK") {
                $("#name").html(msg['student']);
                $("#report-list").html(msg['reports']);
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function form_submit() {
    if ($('#report-form').length > 0 ) {
        $( "#report-form" ).validate( {
            rules: {
                pc: "required",
                usability: "required",
                issueCheck: { 
                    required: function() {
                        return is_required("checkbox");
                    }
                },
                issue: { 
                    required: function() {
                        return is_required("issue");
                    }
                },
            },
            messages: 
            {
                pc: "Please enter PC Number",
                issueCheck: "Please choose an option or write description"
            },
            errorElement: "div",
            errorLabelContainer: "error",
            errorPlacement: function(error, element) {
                if($(element).attr("name") == "issueCheck"){
                    error.insertAfter($(element).parent().parent().next());
                }
                else {
                    error.insertBefore(element);
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
                    url: "../php/submitTicket.php",
                    data: {
                        'form': data,
                        'checks': issueCheck
                    },

                    success: function(msg) {
                        if (msg == 'OK') {
                            $("#result").html("<div class='alert alert-success py-0'>Success!</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        } 
                        else {
                            console.log(msg)
                            $("#result").html("<div class='alert alert-danger py-0'>"+ msg + "</div>");
                            $("#result").show();
                            setTimeout(function() {$('#result').fadeOut();}, 3000);
                        }
                    },
                    error: function() {
                        fill_profile_info();
                        $('#result').html("Something went wrong. Please try again.");
                        $("#result").show();
                        setTimeout(function() {$('#result').fadeOut();}, 3000);
                    }
                });
            } // end submitHandler

        });
    }
}


function is_required(id) {
    var required = true;
    var boxes = $("input[name=issueCheck]:checked");

    if((boxes.length > 0 && id == "issue") || $("#issue").val() != "") {
        required = false;
    }
    console.log(required);
    return required;
}

$('#logout').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: "../php/logout.php",

        success: function() {
            window.location.replace('../');
        },
        error: function(errorThrown){
            $("#error").html(errorThrown);
        }
    });
})