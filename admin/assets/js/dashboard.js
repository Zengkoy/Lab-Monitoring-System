window.onload = function() {
    $.ajax({
        url: "../../php/loginCheck.php",

        success: function(msg) {
            if(msg == "OK") {
                generate_data();
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

function generate_data() {
    $.ajax({
        url: "../../php/generateDashboard.php",

        success: function(msg) {
            msg = JSON.parse(msg);
            $("#pending-reports").html(msg["reports"]);
            $("#logs-today").html(msg["logs"]);
            $("#pc-issues").html(msg["computers"]);
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
}

function chart_data() {
    var dataArray = Array();
    $.ajax({
        url: "../../php/generateChart.php",
        async: false,

        success: function(msg) {
            let fullData = JSON.parse(msg);
            dataArray = fullData;
        },
        error: function(errorThrown){
            alert(errorThrown);
        }
    });
    return dataArray;
}

var chartData = chart_data();

var ctx2 = document.getElementById("chart-line").getContext("2d");

var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

new Chart(ctx2, {
    type: "line",
    data: {
    labels: chartData["labels"],
    datasets: [{
        label: "Reports",
        tension: 0.4,
        borderWidth: 0,
        pointRadius: 0,
        borderColor: "#cb0c9f",
        borderWidth: 3,
        backgroundColor: gradientStroke1,
        fill: true,
        data: chartData["reports"],
        maxBarThickness: 6

        },
    ],
    },
    options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
        display: false,
        }
    },
    interaction: {
        intersect: false,
        mode: 'index',
    },
    scales: {
        y: {
        grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5]
        },
        ticks: {
            display: true,
            padding: 10,
            color: '#b2b9bf',
            font: {
            size: 11,
            family: "Open Sans",
            style: 'normal',
            lineHeight: 2
            },
        }
        },
        x: {
        grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
        },
        ticks: {
            display: true,
            color: '#b2b9bf',
            padding: 20,
            font: {
            size: 11,
            family: "Open Sans",
            style: 'normal',
            lineHeight: 2
            },
        }
        },
    },
    },
});

var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0');
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;
document.getElementById("date").innerHTML = today;