var ctx;

ctx = document.getElementById('chartAve');

var dataAve = {
        labels: ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
        datasets: [{
            label: "Consomation Elec en kwh",
            backgroundColor: 'rgba(0,0,0,0)',
            borderColor: 'rgb(50, 99, 132)',
            data: [6, 5, 7, 6, 7, 11, 10],
        }]
};


var myLineChart = new Chart(ctx, {
    type: 'line',
    data: dataAve,
    options: {
	title:{
	    display: true,
	    text:"Consommation électrique d'un ménage"
	},
        scales: {
	    xAxes: [{
		ticks: {
		    scaleLabel: {
			display: true,
			labelString: 'Jours'
		    }
		}
	    }],
            yAxes: [{
		    display: true,
                    ticks: {
		    	callback: function(value, index, values){
			    return value + 'kW';
		    	},
                    beginAtZero: true,
		    scaleLabel: {
			display: true,
			labelString: 'Consommation en kW'
		    }
                }
            }]
        }
    }
});

ctx = document.getElementById('chartPie');


dataAve.datasets[0].backgroundColor = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 110, 64, 0.2)'
            ];
dataAve.datasets[0].borderColor = [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 110, 64, 1)'
            ];


var myPieChart = new Chart(ctx,{
    type: 'pie',
    data: dataAve,
    options: {
        legend: {
            display: false
        }
    }
});
