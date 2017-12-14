/**
 * Created by Nathan on 22/11/2017.
 */



var chart = AmCharts.makeChart( "chartdiv", {
    "type": "stock",
    "theme": "light",
    "language": "fr",
    "pathToImages": "amcharts/images/",
    "categoryAxesSettings": {
        "minPeriod": "mm"
    },
    "titles": [{
            "text": "Consommation électrique du ménage kwh",
            "size": 15
        }
    ],
    "dataSets": [{
        "color": "#5cb85c",
        "dataProvider": chartData,
        "fieldMappings": [{
            "fromField": "kw",
            "toField": "kw"
        }],
        "categoryField": "date"
    }],
    "panels": [{
        "stockGraphs": [{
            "id": "g1",
            "valueField": "kw",
            "type": "smoothedLine",
            "title": "test",
            "lineThickness":2,
            "periodValue":"Sum"
        }]


    }],
    "panelsSettings": {
        "startDuration": 1
    },
    "chartScrollbarSettings": {
        "graph": "g1"
    },
    "chartCursorSettings": {
        "valueBalloonsEnabled": true,
        "graphBulletSize": 1,
        "valueLineAlpha": 0.5
    },

    "periodSelector": {
        "periods": [ {
            "period": "DD",
            "count": 1,
            "label": "1 jour"
        }, {
            "period": "DD",
            "count": 7,
            "label": "1 semaine",
            "selected": true
        }, {
            "period": "MM",
            "count": 1,
            "label": "1 mois"
        }, {
            "period": "YYYY",
            "count": 1,
            "label": "1 ans"
        }, {
            "period": "YTD",
            "label": "YTD"
        }, {
            "period": "MAX",
            "label": "MAX"
        }
        ]
    }
});

var dateGraph;
var dateStart;
var dateEnd;
var dateStartOld;
var dateEndOld;


setInterval(function (){
    ecrireDate();

    try {
        document.querySelector("[title='JavaScript charts']").remove();
    } catch(err) {}
    try {
        dateGraph = document.getElementsByClassName("amChartsInputField ");
        dateStart = dateGraph[0].value;
        dateEnd = dateGraph[1].value;
    } catch(err) {}
    moyenneEnCours();

}, 100);


function moyenneEnCours() {

    var dateStartDate =  new Date(dateStart.split("-")[2],dateStart.split("-")[1]-1,dateStart.split("-")[0]);
    var dateEndDate =  new Date(dateEnd.split("-")[2],dateEnd.split("-")[1]-1,dateEnd.split("-")[0]);

    if(dateStart != dateStartOld || dateEnd != dateEndOld){
        var consomationTotPeriode = 0;
        for (var i=0; i<(chartData.length);i++){
            if (chartData[i].date.setHours(0,0,0,0) >= dateStartDate.setHours(0,0,0,0) && chartData[i].date.setHours(0,0,0,0) <= dateEndDate.setHours(0,0,0,0) ){
                consomationTotPeriode += chartData[i].kw;
            }
        }
        dateStartOld = dateGraph[0].value;
        dateEndOld = dateGraph[1].value;
        document.getElementsByClassName("dataDashboard")[1].innerHTML = "<h3>Consommation totale</h3><h5>sur la période sélectionnée :</h5><h1>" + consomationTotPeriode + "kwh</h1>";
    };
}

function ecrireDate(){
    var dateNow = new Date();
    var jours = ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];
    var mois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    var heure = dateNow.getHours();
    var min = dateNow.getMinutes();

    if (heure < 10) {heure = "0"+heure;}
    if (min < 10) {min = "0"+min;};

    document.getElementsByClassName("dataDashboard")[0].innerHTML = jours[dateNow.getDay()] + "<br> " + dateNow.getDate() + " " +  mois[dateNow.getMonth()] + " " + dateNow.getFullYear()  + "<br> " + heure +":"+min ;
};
