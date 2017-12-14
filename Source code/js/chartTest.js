var ctx;

ctx = document.getElementById('chartAve');

var donnee = {
        Lundi:[2.2,2.3,2.4,2.5,2.6,2.8,8,10.2,12.5,11.9,13.1,13.8,14,12.3,7.5,3.6,5.3,7,8.6,9.4,11.5,7,3.6,2.5],
        Mardi:[2.2,1.6,1.6,3.3,2.5,2.7,8.7,9.7,11.8,12.4,12.5,14.8,15.0,12.1,8.0,4.3,4.9,7.3,8.3,8.6,12.0,6.8,4.5,2.3],
        Mercredi:[3.0,1.4,0.9,3.3,1.6,2.4,9.2,9.9,12.8,12.7,11.8,15.4,15.9,11.9,8.4,4.7,4.8,6.8,7.6,8.2,12.4,6.6,4.7,3.0],
        Jeudi:[2.2,1.0,0.2,4.1,1.5,2.0,9.2,10.4,12.3,12.9,10.8,14.5,15.4,12.7,7.6,5.2,5.1,6.3,6.8,7.4,13.3,6.5,4.2,3.7],
        Vendredi:[2.2,1.9,0.0,5.1,1.9,1.0,9.0,11.3,13.2,13.7,10.9,14.1,15.0,13.2,7.7,4.2,4.2,6.3,7.4,7.8,12.6,6.5,3.7,3.4],
        Samedi:[3.1,1.2,0.2,5.2,2.7,1.3,9.6,10.6,13.2,13.1,9.9,14.5,14.2,13.6,7.7,5.1,3.8,5.5,7.9,7.9,13.5,7.0,3.3,3.9],
        Dimanche:[3.8,0.6,0.4,5.1,2.7,0.8,9.1,11.3,12.2,13.9,10.5,13.5,14.1,14.3,7.2,5.4,3.6,4.9,7.7,8.8,13.5,7.5,2.9,3.4]
    };

var moyenne = {};
moyenne = {};
for(var i= 0; i < Object.keys(donnee).length; i++){
    var tempName = Object.keys(donnee)[i];
    var moyenneTemp = 0;
    for (var y=0; y<donnee[tempName].length;y++){
        moyenneTemp += donnee[tempName][y]
    }
    moyenne[tempName] = moyenneTemp/donnee[tempName].length;
}
console.log(moyenne);

var data1 = {
    labels: ["00:00","01:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
    datasets: [{
        label: " kW",
        backgroundColor: 'rgba(50, 99, 132,.3)',
        borderColor: 'rgb(50, 99, 132)',
        data: donnee.Jeudi,
        pointRadius: 0,
        pointHitRadius:2
    }]
};
var data2 = {
        labels: [Object.keys(donnee)[3], Object.keys(donnee)[4], Object.keys(donnee)[5], Object.keys(donnee)[0], Object.keys(donnee)[1], Object.keys(donnee)[2], Object.keys(donnee)[3]],
        datasets: [{
            label: " kW",
            backgroundColor: 'rgba(50, 99, 132,.3)',
            borderColor: 'rgb(50, 99, 132)',
            data: [7, 7.4 , 12, 13.6, 6.7, 7.2, 8.6],
            pointRadius: 0,
            pointHitRadius:2
        }]
};
var data3 = {
        labels: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
        datasets: [{
            label: " kW",
            backgroundColor: 'rgba(50, 99, 132,.3)',
            borderColor: 'rgb(50, 99, 132)',
            data: [12, 14 , 11, 10, 7, 7.5, 5,4,7,8,8,12],
            pointRadius: 0,
            pointHitRadius:2
        }]
};

var options = {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
        display: false
    },
    title:{
        display: true,
        fontSize: 15,
        text:"Consommation électrique moyenne du ménage"
    },
    tooltips: {
        mode: 'nearest',
        intersect: false,
    },
    hover: {
        mode: 'nearest',
        intersect: true
    },
    scales: {
        xAxes: [{
            display: true,
        }],
        yAxes: [{
            display: true,
            ticks: {
                callback: function(value, index, values){
                    return value + ' kW';
                },
                beginAtZero: true,
                scaleLabel: {
                    display: true,
                    labelString: 'kW'
                }
            }
        }]
    }
};


var myLineChart = new Chart(ctx, {
    type: 'line',
    data: data2,
    options: options
});

function jour() {
    myLineChart.clear();
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: data1,
        options: options
    });
}
function semaine() {
    myLineChart.clear();
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: data2,
        options: options
    });
}
function mois() {
    myLineChart.clear();
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: data3,
        options: options
    });
}