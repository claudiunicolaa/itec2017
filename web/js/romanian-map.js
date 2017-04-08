/**
 * Created by claudiu on 08.04.2017.
 */
/**
 * Data used by highcharts js.
 *
 * @type {Array}
 */
var data = [];

// Create the chart
Highcharts.mapChart('container', {
        chart: {
            map: 'countries/ro/ro-all'
        },
        title: {
            text: 'Romanian statistics data.'
        },

    }
);