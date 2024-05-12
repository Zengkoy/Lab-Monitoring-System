(function($) {

	"use strict";
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
	var registerForm = function() {
		if ($('#register-form').length > 0 ) {
			$( "#register-form" ).validate( 
			{
				rules: 
				{
					usn: "required",
                    name: "required",
                    course: "required",
					password: {
                        required: true,
                        minlength: 8
                    },
                    confirmpassword: {
                        required: true,
                        equalTo: "#password"
                    }
				},
				messages: 
				{
					usn: "Please enter USN",
                    name: "Please enter Name",
                    course: "Please enter Course",
					password: "Please enter password with at least 8 characters",
                    confirmpassword: {
                        required: "Please confirm password",
                        equalTo: "Password does not match"
                    }
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
                                $("#result").html("<div class='alert alert-success py-0'>Success!</div>");
                                $("#qr").html(msg.substr(2));
                                $("#qr-container").show();
                                console.log($("#qr-container").style.display);
                            } 
                            else {
                                $("#result").html(msg);
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

})(jQuery);

var qr = $("#qr-container");

$("#done-button").click(function() { 
    $("#usn").val("");
    $("#name").val("");
    $("#course").val("");
    $("#password").val("");
    $("#confirmpassword").val("");
    qr.hide() 
});