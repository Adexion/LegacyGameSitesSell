import '../css/shopStatistic.css'
import Chart from 'chart.js';

let backgroundColor = [];
let borderColor = [];

function randomInt(min, max) {
    return min + Math.floor((max - min) * Math.random());
}

function getItemsBoughtCount(statistics) {
    let itemBoughtCount = [];
    statistics.forEach((el) => {
        if (!itemBoughtCount[el.itemListName]) {
            itemBoughtCount[el.itemListName] = 1;

            const red = randomInt(0, 255);
            const green = randomInt(0, 255);
            const blue = randomInt(0, 255);

            backgroundColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', 0.2)');
            borderColor.push('rgba(' + red + ', ' + blue + ', ' + green + ', 1)');
        } else {
            itemBoughtCount[el.itemListName] = itemBoughtCount[el.itemListName] + 1;
        }
    });

    return itemBoughtCount;
}

let items = getItemsBoughtCount(
    JSON.parse(
        document.querySelector('.statistics').dataset.statistics
    )
);

new Chart(document.querySelector('#shop-statistic'), {
    type: 'horizontalBar',
    data: {
        labels: Object.keys(items),
        datasets: [{
            label: 'Ilość sprzedanych sztuk',
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
});
