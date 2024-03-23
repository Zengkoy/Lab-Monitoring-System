$(function() {
    $('#contactForm').validate({
        rules: {
            lab: {
                required: true,
                number: true,
                min: 1,
                max: 2
            },
            pc: {
                required: true,
                number: true,
                min: 1,
                max: 20
            },
            issue: "required",
            usability: "required"
        },
        messages: {
            lab: "Please enter proper Lab number",
            pc: "Please enter proper computer number",
            issue: "Please describe the issue",
            usability: "Please confirm PC status"
        },

        submitHandler: function(form) {
            var $submit = $('.submitting'),
                waitText = 'Submitting...';

            $.ajax({
                type: "POST",
                url: "php/submitTicket.php",
                data: $(form).serialize(),

                beforeSend: function() { 
                    $submit.css('display', 'block').text(waitText);
                },

                success: function(msg) {
                    if (msg == "OK") {
                        $("#form-message-warning").hide();
                        setTimeout(function(){
                            $("#contactForm").fadeIn();
                        }, 1000);

                        setTimeout(function(){
                            $("#form-message-success").fadeIn();
                        }, 1400);

                        setTimeout(function(){
                            $("#form-message-success").fadeOut();
                        }, 8000);

                        setTimeout(function(){
                            $submit.css('display', 'none').text(waitText);  
                        }, 1400);

                        setTimeout(function(){
                            $( '#contactForm' ).each(function(){
                                this.reset();
                            });
                        }, 1400);
                    }
                    else {
                        $("#form-message-warning").html(msg)
                        $('#form-message-warning').fadeIn();
                        $submit.css('display', 'none');
                    }
                },
                
                error: function() {
                    $('#form-message-warning').html("Something went wrong. Please try again.");
                    $('#form-message-warning').fadeIn();
                    $submit.css('display', 'none');
                }
            })
        }
    });
});