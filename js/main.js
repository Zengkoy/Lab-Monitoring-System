(function($) {

	"use strict";
	
  // Form
	var contactForm = function() {
		if ($('#contactForm').length > 0 ) {
			$( "#contactForm" ).validate( 
			{
				rules: 
				{
					lab: "required",
					pc: "required",
					issue: {
						required: true,
						minlength: 5
					},
					usability: "required"
				},
				messages: 
				{
					lab: "Please enter lab number",
					pc: "Please enter PC Number",
					issue: "Please describe the issue",
					usability: "Please confirm computer status"
				},
				/* submit via ajax */
				
				submitHandler: function(form) {		
					var $submit = $('.submitting'),
						waitText = 'Submitting...';

					$.ajax({   	
				      type: "POST",
				      url: "php/sendEmail.php",
				      data: $(form).serialize(),

				      beforeSend: function() { 
				      	$submit.css('display', 'block').text(waitText);
				      },
				      success: function(msg) {
		               if (msg == 'OK') {
		               	$('#form-message-warning').hide();
				        setTimeout(function(){
		               		$('#contactForm').fadeIn();
		               	}, 1000);
				        setTimeout(function(){
				            $('#form-message-success').fadeIn();   
		               	}, 1400);

		               	setTimeout(function(){
				               $('#form-message-success').fadeOut();   
		               	}, 8000);

		               	setTimeout(function(){
				               $submit.css('display', 'none').text(waitText);  
		               	}, 1400);

		               	setTimeout(function(){
		               		$( '#contactForm' ).each(function(){
											    this.reset();
											});
		               	}, 1400);
			               
			            } else {
			               $('#form-message-warning').html(msg);
				            $('#form-message-warning').fadeIn();
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