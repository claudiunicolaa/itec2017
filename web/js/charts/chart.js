function drawChart(title, name, data, valueSuffix, max, url) {
    Highcharts.mapChart('map', {
        chart: {
            map: 'countries/ro/ro-all',
            height: (9 / 16 * 100) + '%'
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

function showCharts(url) {
    axios.get(url).then(function (response) {
        console.log(response);
    }).catch(function (error) {
        alert(error);
    });
}

function density() {
    fetchData('densityData');
}

function fetchData(url) {
    axios.get(url).then(function (response) {
        drawChart(
            response.data.title,
            response.data.name,
            response.data.data,
            response.data.valueSuffix,
            response.data.max,
            response.data.url)
    }).catch(function (error) {
        alert(error);
    });
}

function driverLicenses() {
    fetchData('driverData');
}