import '../css/shopStatistic.css'
import Chart from 'chart.js';

let backgroundColor = [];
let borderColor = [];
let statistics = JSON.parse(document.querySelector('.statistics').dataset['statistics']);

function drawChartByStatisticList(name, description, type = 'horizontalBar') {
    generateTableOfColors(Object.entries(statistics[name]));

    let items = Array.of(statistics[name])[0];
    new Chart(document.querySelector('#' + name), getChartOptions(items, description, type));
}

function generateTableOfColors(array) {
    backgroundColor = [];
    borderColor = [];

    array.forEach(() => {
        const red = randomInt(0, 255);
        const green = randomInt(0, 255);
        const blue = randomInt(0, 255);

        backgroundColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', 0.2)');
        borderColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', 1)');
    });

    function randomInt(min, max) {
        return min + Math.floor((max - min) * Math.random());
    }
}

function getChartOptions(items, name, type) {
    return {
        type: type,
        data: {
            labels: Object.keys(items),
            datasets: [{
                label: name,
                data: Object.values(items),
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    }
}

drawChartByStatisticList('buyers','Ilość sprzedanych sztuk');
drawChartByStatisticList('userBought', 'Kto kupił ile');
drawChartByStatisticList('dateTime', 'Kiedy kupowano najczęściej');
