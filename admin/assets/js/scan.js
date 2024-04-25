function insertLog(usn) {
    var values = { 'usn': usn };

    $.ajax({
        url: "../../php/inputLog.php",
        type: "POST",
        data: values,

        success: function(msg) {
            console.log(msg);
        }
    });
}

function onScanSuccess(decodedText, decodedResult) {
    // handles the scanned code
    //insertLog(decodedText);
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