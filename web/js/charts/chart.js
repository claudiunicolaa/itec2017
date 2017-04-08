function drawChart(title, name, data, valueSuffix, max) {
    Highcharts.mapChart('map', {
        chart: {
            map: 'countries/ro/ro-all'
        },
        title: {
            text: title
        },
        subtitle: {
            text: 'Source map: <a href="highcharts/ro-all.js">Romania</a>'
        },
        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            }
        },
        colorAxis: {
            min: 1,
            max: max,
            type: 'logarithmic'
        },
        series: [{
            data: data,
            name: name,
            borderColor: 'black',
            states: {
                hover: {
                    borderWidth: 1,
                    color: '#dacf43'
                }
            },
            tooltip: {
                valueSuffix: valueSuffix
            },
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }]
    });
}
drawChart();

function density() {
    axios.get('/densityData').then(function (response) {
        drawChart(response.data.title, response.data.name, response.data.data, response.valueSuffix, response.max);
    });
}

function driverLicenses() {
    axios.get('/driverData').then(function (response) {
        drawChart(response.data.title, response.data.name, response.data.data, response.valueSuffix, response.max);
    });
}