(function($) {

  // Form
	var registerForm = function() {
		if ($('#forgot-form').length > 0 ) {
			$( "#forgot-form" ).validate( 
			{
				rules: 
				{
					email: "required"
				},
				messages: 
				{
					email: "Please enter Email"
				},
				/* submit via ajax */
				
				submitHandler: function(form) {		
					var $submit = $('.submitting'),
						waitText = 'Submitting...';

					$.ajax({   	
                        type: "POST",
                        url: "../../php/forgotPassword.php",
                        data: $(form).serialize(),

                        beforeSend: function() { 
                            $submit.css('display', 'block').text(waitText);
                        },
                        success: function(msg) {
                            if (msg.substr(0,2) == 'OK') {
                                $("#result").html("<div class='alert alert-success py-0'>Success! Check your Email for the Link.</div>");
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
    registerForm();

})(jQuery);

var $loading = $("#loading").hide();

$(document)
    .ajaxStart(function() { $loading.show(); })
    .ajaxStop(function() { $loading.hide(); });