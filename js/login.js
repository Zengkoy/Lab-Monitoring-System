(function($) {

	"use strict";
	$.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                window.location.replace('../');
            }
			else {
				loginForm();
			}
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });

  // Form
	var loginForm = function() {
		if ($('#login-form').length > 0 ) {
			$( "#login-form" ).validate( 
			{
				rules: 
				{
					username: "required",
					password: "required"
				},
				messages: 
				{
					username: "Please enter username",
					password: "Please enter password"
				},
				/* submit via ajax */
				
				submitHandler: function(form) {		
					var $submit = $('.submitting'),
						waitText = 'Submitting...';

					$.ajax({   	
                        type: "POST",
                        url: "../../php/login.php",
                        data: $(form).serialize(),

                        beforeSend: function() { 
                            $submit.css('display', 'block').text(waitText);
                        },
                        success: function(msg) {
                        if (msg == 'OK') {
                            window.location.replace('../');
			               
			            } else {
			               $('#fail').html(msg);
				            // $('#form-message-warning').fadeIn();
				            $submit.css('display', 'none');
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