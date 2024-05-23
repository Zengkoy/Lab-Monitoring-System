(function($) {

	"use strict";
	$.ajax({
        url: "../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") { window.location.replace('pages/dashboard.html'); }
			else { loginForm(); }
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

					$.ajax({   	
                        type: "POST",
                        url: "../php/login.php",
                        data: $(form).serialize(),

                        success: function(msg) {
                        if (msg == 'OK') {
                            window.location.replace('pages/dashboard.html');
			               
			            } else {
							$('#fail').show()
			               	$('#fail').html(msg);
							setTimeout(function() {$('#fail').fadeOut();}, 5000);
			            }
				        },
				        error: function() {
                            $('#form-message-warning').html("Something went wrong. Please try again.");
                            $('#form-message-warning').fadeIn();
				        }
			        });
		  		} // end submitHandler

			});
		}
	};

})(jQuery);