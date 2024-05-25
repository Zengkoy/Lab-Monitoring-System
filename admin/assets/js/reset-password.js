(function($) {

    // Form
      var registerForm = function() {
          if ($('#reset-form').length > 0 ) {
              $( "#reset-form" ).validate( 
              {
                  rules: 
                  {
                      password: "required",
                      cpassword: {
                        required: true,
                        equalTo: '#password'
                      }
                  },
                  messages: 
                  {
                      password: "Please enter New Password",
                      cpassword: "Passwords must match"
                  },
                  /* submit via ajax */
                  
                  submitHandler: function(form) {		
                      var $submit = $('.submitting'),
                          waitText = 'Submitting...';
  
                      $.ajax({   	
                          type: "POST",
                          url: "../../php/resetPassword.php",
                          data: $(form).serialize(),
  
                          beforeSend: function() { 
                              $submit.css('display', 'block').text(waitText);
                          },
                          success: function(msg) {
                              if (msg == 'OK') {
                                  $("#result").html("<div class='alert alert-success py-0'>Success! Your Password has been Reset.</div>");
                                  $("#reset-form").css('display', 'none');
                              }
                              else {
                                  $("#result").html(msg);
                              }
                          },
                          error: function() {
                              $('#result').html("Something went wrong. Please try again.");
                              $('#result').fadeIn();
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