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
                        	setTimeout(function() {
								$("#error-message").fadeOut();
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

function qrLogin(code) {
    var usn = "";
    var password = "";

    for(let i=0; i < code.length; i++) {
        var character = code.charAt(i);
        if(character == '|') { break; }
        else {
            usn += character;
        }
        console.log(character);
    }

    for(let i=0; i < code.length; i++) {
        var character = code.charAt(code.length - i);
        if(character == '|') { break; }
        else {
            password = character + password;
        }
    }

    var values = { 
        'usn': usn,
        'hash': password,
    };

    $.ajax({
        url: "php/studentLogin.php",
        type: "POST",
        data: values,
        datatype: "text",

        success: function(msg) {
            if(msg == "OK") {
                window.location.replace('student/send_report.html');
            }
            else {
                $("#error-message").show();
				setTimeout(function() {
					$("#error-message").fadeOut();
				}, 3000);
            }
        },

        error: function(errorThrown) {
            console.log(errorThrown);
        }
    });
}

$("#go").click(submit_form());


function onScanSuccess(decodedText, decodedResult) {
	qrLogin(decodedText);
	console.log(`Code matched = ${decodedText}`, decodedResult);
	$("#close-btn").click();
}

function onScanFailure(error) {
	// handle scan failure, usually better to ignore and keep scanning.
	// for example:
	console.warn(`Code scan error = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
"reader",
{ fps: 10, qrbox: {width: 250, height: 250} },
/* verbose= */ false);

$("#close-btn").click(function() { 
	$("#html5-qrcode-button-camera-stop").click();
	$("#reader-container").hide(); 
});

$("#scan").click(function() { 
	html5QrcodeScanner.render(onScanSuccess, onScanFailure);
	$("#reader-container").show(); 
});