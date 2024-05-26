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
					username: "required",
                    email: "required",
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
					username: "Please enter username",
                    email: "Please enter Email",
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
                        url: "../../php/register.php",
                        data: $(form).serialize(),

                        beforeSend: function() { 
                            $submit.css('display', 'block').text(waitText);
                        },
                        success: function(msg) {
                        if (msg == 'OK') {
                            $("#result").html("<p class='alert alert-success'>Success!</p>");
                            setTimeout(function() {
								$("#result").fadeOut();
							}, 3000);
			            } 
                        else {
			                $("#result").html("<p class='alert alert-danger'>" + msg + "</p>");
                            setTimeout(function() {
								$("#result").fadeOut();
							}, 3000);
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

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });