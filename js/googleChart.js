/**
 * Created with JetBrains PhpStorm.
 * User: ijsbrand
 * Date: 20-5-13
 * Time: 15:26
 * To change this template use File | Settings | File Templates.
 */
<!--Load the AJAX API-->
function showGoogleChart(){
    loadChart();
}

function loadChart(){
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {'packages': ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
}

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    $.ajax();
    data.addRows([
        ['Kieskeurig.nl', 100],
        ['beslist.nl', 50],
        ['Vergelijk', 15],
        ['Tweakers Pricewatch', 10],
    ]);

    // Set chart options
    var options = {'title': 'Winst van de marketing kanalen',
        'width': 400,
        'height': 300};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}