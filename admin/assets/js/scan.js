window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                listSubjects();
            }
            else {
                window.location.replace('../');
            }
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
};

function listSubjects() {
    
    $.ajax({
        url: "../../php/generateSubjects.php",

        success: function(msg) {
            $("#select-subject").html(msg);
        },
        error: function(errorThrown) {
            console.log(errorThrown);
        }
    });
}

function addSubject() {
    var subject = $("#subject-name").val();

    if(subject != "") {
        $.ajax({
            url: "../../php/addSubject.php",
            type: "POST",
            data: {'subject': $("#subject-name").val()},
    
            success: function(msg) {
                if(msg == "OK") {
                    alert("Success");
                    location.reload();
                }
                else {
                    $("#error").html(msg);
                }
            }
        })
    }
    else {
        $("#error").html("Please enter a subject name");
    }
}

function showSubForm() {
    if($("#show-hide-btn").html() == "Cancel") {
        $("#show-hide-btn").html("Add Subject");
    }
    else {
        $("#show-hide-btn").html("Cancel");
    }
    $("#add-subject").toggle();
    
}

function insertLog(code, subject, lab) {
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
        'password': password,
        'subject': subject,
        'lab': lab
    };

    $.ajax({
        url: "../../php/inputLog.php",
        type: "POST",
        data: values,
        datatype: "text",

        success: function(msg) {
            if(msg.substr(0, 2) == "OK") {
                alert(("User Logged. Assigned PC:").concat(msg.substr(3)));
            }
            else {
                $("#error").html(msg);
            }
        },

        error: function() {
            console.log("Error");
        }
    });
}

function onScanSuccess(decodedText, decodedResult) {
    // handles the scanned code
    var subject = $("#subject").val();
    var lab = $("#lab").val();
    insertLog(decodedText, subject, lab);
    console.log(`Code matched = ${decodedText}`, decodedResult);
    $("#html5-qrcode-button-camera-stop").click();
}

function onScanFailure(error) {
    // handle scan failure, usually better to ignore and keep scanning.
    console.warn(`Code scan error = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
"reader",
{ fps: 10, qrbox: {width: 400, height: 250} },
/* verbose= */ false);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);