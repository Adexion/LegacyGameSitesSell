import Chart from 'chart.js';

let backgroundColor = [];
let borderColor = [];
let statistics = JSON.parse(document.querySelector('.statistics').dataset['statistics']) ?? [];

export default function (name, description, type = 'horizontalBar') {
    generateTableOfColors(Object.entries(statistics[name] ?? {}), type);

    let items = Array.of(statistics[name] ?? [])[0];
    new Chart(document.querySelector('#' + name), getChartOptions(items, description, type));
}

function generateTableOfColors(array, type) {
    backgroundColor = [];
    borderColor = [];

    array.forEach(() => {
        const red = randomInt(0, 255);
        const green = randomInt(0, 255);
        const blue = randomInt(0, 255);

        backgroundColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', ' + (type === 'pie' ? '1)' : '0.5)'));
        borderColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', 1)');
    });

    function randomInt(min, max) {
        return min + Math.floor((max - min) * Math.random());
    }
}

function getChartOptions(items, name, type) {
    let options = {
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
        }
    };

    if (type === 'pie' || type === 'polarArea') {
        return options;
    }

    options.options = {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    };

    return options;
}

