window.onload = function() {
    $.ajax({
        url: "php/studentLoginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
				window.location.replace('student/send_report.html');
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

function submit_form() {
	if ($("#login-form").length > 0) {
		$("#login-form").validate({
			rules: {
				usn: "required",
				password: "required"
			},
			messages: {
				usn: "Please enter USN",
				password: "Please enter password"
			},

			submitHandler: function(form) {
				$.ajax({
					type: "POST",
					url: "php/studentLogin.php",
					data: $(form).serialize(),
			
					success: function(msg) {
						if(msg == "OK") {
							window.location.replace('student/send_report.html');
						}
						else {
							$("#error-message").show();
							$("#login-form").css("height", "350px");
                        	setTimeout(function() {
								$("#error-message").fadeOut().promise().done(function(){
									$("#login-form").css("height", "280px");
								});
							}, 3000);
						}
					},
					error: function(errorThrown){
						alert(errorThrown);
					}
				});
			}
		});
	}
}

$("#go").click(submit_form());