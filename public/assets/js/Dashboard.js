$(document).ready(function () {
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback();


    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "admin/chartData",
        data: "data",
        dataType: "json",
        success: function (res) {
            drawChart(res)

        },
        error: function (err) {

        }
    });

});

function drawChart(res) {

    var data = google.visualization.arrayToDataTable(res);

    var options = {
        title: 'Expenses Chart',
        is3D: true
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);


}

