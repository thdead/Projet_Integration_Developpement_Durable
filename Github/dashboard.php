<?php
require 'includes/session.php';
require 'mysql/connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <?php include('includes/head.php'); ?>

    <script src="amcharts/amcharts.js" type="text/javascript"></script>
    <script src="amcharts/serial.js" type="text/javascript"></script>
    <script src="amcharts/amstock.js" type="text/javascript"></script>
    <link rel="stylesheet" href="amcharts/style.css" type="text/css">
</head>
<body>
    <!--NAVBAR-->
    <?php require 'includes/navbarTest.php' ?>
    <!--/NAVBAR-->

    <!--SIDEBARNAV -->
    <?php include 'includes/sidebar.php'; ?>
    <!--/SIDEBARNAV-->

	
    <!-- PAGE CONTENT-->
	<div class="container-fluid" id="content" >
    <?php
        // $selectData = "SELECT * FROM Control where Me_id = 5";
        // $reqData = $pdo->prepare($selectData);

		//	--------------	//
		//Get the default residence.
		require 'includes/residence.functions.php';
		$defRes = getDefault($_SESSION['user']['id']);
		//Verify if residence has data.
		if(empty($defRes)){
			//Choose the last added residence as the default residence.
			$defRes = getLastR_id($_SESSION['user']['id']);
			?>
			<div class="alert alert-warning">
				<h6 class="alert alert-heading">Résidence par défaut non définie.</h6>
				<p>Vous ne possédez pas de résidence par défaut.
				<a href=#>Définir votre résidence par défaut.</a>
				</p>
			</div>
			<?php 
		}
		$mid = getMeter('',$defRes);
		if(hasData($defRes)){
			$liste = getData($defRes,1);
			$liste2 = getData($defRes,2);
		}else{
			$residence = getResidenceDetails($defRes);
			?>
			<div class="alert alert-danger">
				<h6 class="alert alert-heading">Aucune données disponibles...</h6>
				<p>Votre résidence à l'adresse <i><?= $residence[0]['L_address'] ?></i> ne contient aucune données.</p>
			</div>
			<?php die();
		}
		
		//	-------------- 	//
		//$noNotif = $pdo->query("SELECT noNotif FROM Customer WHERE C_id = '" . $_SESSION['user']['id'] . "'";
		
    ?>

        <script>
            var dataListe = <?php echo json_encode($liste, JSON_HEX_TAG); ?>;
            var chartData = [];
            generateChartData();

            function generateChartData() {

                for ( var i = 1; i < (dataListe.length-1); i++ ) {
                    var date = dataListe[i][1].split("-");
                    var newDate = new Date(date[2],date[1]-1,date[0],date[3],date[4]);
                    var value = dataListe[i][0]-dataListe[i-1][0];

                    chartData.push( {
                        "date": newDate,
                        "kw": value
                    } );
                }
            }
        </script>
		<script>
			function Average(numbersArr) {
				//--CALCULATE AVERAGE--
				var totalavg = 0;
				for(var key in numbersArr) 
					totalavg += parseInt(numbersArr[key]);
				var meanVal = totalavg/numbersArr.length;
				//--CALCULATE AVERAGE--
				return meanVal;
			}
			function StandardDeviation(numbersArr) {
				var meanVal = Average(numbersArr);
				//--CALCULATE STANDARD DEVIATION--
				var SDprep = 0;
				for(var key in numbersArr) 
					SDprep += Math.pow((parseInt(numbersArr[key]) - meanVal),2);
				var SDresult = Math.sqrt(SDprep/numbersArr.length);
				//--CALCULATE STANDARD DEVIATION--
				return SDresult;
			}
			
			var Ncncld;
			function notifyMe(notifyText) {
				// Let's check if the browser supports notifications
				if (!("Notification" in window)) {
					alert("This browser does not support desktop notification");
				}
				
				// Let's check whether notification permissions have already been granted
				else if (Notification.permission === "granted" && pasDeNotif!=1) {
					// If it's okay let's create a notification
					var options = {body: notifyText};
					var notification = new Notification("Alerte!", options);
				}
				
				// Otherwise, we need to ask the user for permission
				else if (Notification.permission !== "denied" && pasDeNotif!=1) {
					Notification.requestPermission(function (permission) {
					// If the user accepts, let's create a notification
					if (permission === "granted") {
						var options = {body: notifyText};
						var notification = new Notification("Alerte!", options);
					}
					});
				}
				// At last, if the user has denied notifications, and you 
				// want to be respectful there is no need to bother them any more.
				notification.onclick = function(event) {
					event.preventDefault(); // prevent the browser from focusing the Notification's tab
					var cancelNot = confirm("Stop notifications?");
					if (cancelNot == true){
					Ncncld = "Notifications cancelled";
					}
				}
			}	
		</script>
		<script>
			var pasDeNotif =  0;
			var sqlcom = "";
			var DAT0 = <?php echo json_encode($liste2, JSON_HEX_TAG); ?>;
	
			var DAT = DAT0;
			var DATdiff = [];
			for(var key in DAT){
				DATdiff[key]=DAT[key]["Con_measure"];
				if(key!=0){DATdiff[key]=parseInt(DAT[key]["Con_measure"])-parseInt(DAT[key-1]["Con_measure"]);
				}else{DATdiff[key]=0;}
			} 
			for(var key in DAT){
				if(key!=0){DAT[key]["diff"]=parseInt(DAT[key]["Con_measure"])-parseInt(DAT[key-1]["Con_measure"]);
				}else{DAT[key]["diff"]=0;}
				if(DAT[key]["Con_alert"]===null && typeof(DAT[key]["Con_alert"])==="object"){DAT[key]["Con_alert"]=0;}
				if(DAT[key]["Con_used"]===null && typeof(DAT[key]["Con_used"])==="object"){DAT[key]["Con_used"]=0;}
				if(DAT[key]["Con_alert"] == 0 && ((DATdiff[key])>Average(DATdiff)+2*StandardDeviation(DATdiff))){
					DAT[key]["Con_alert"] = 1;
					sqlcom += "UPDATE Console SET Con_alert=1 WHERE Con_id=" + DAT[key]["Con_id"] +";";
				}
				if(DAT[key]["Con_alert"] == 1 && DAT[key]["Con_used"] == 0){
					notifyMe("Attention, consommation de " + DAT[key]["diff"] + "kwh le " + DAT[key]["Con_time"] + ".");
					DAT[key]["Con_used"] = 1;
					sqlcom += "UPDATE Console SET Con_used=1 WHERE Con_id=" + DAT[key]["Con_id"] +";";
				}
			}
		</script>
		


        <div class="row" id="graph">
            <div class="container-perso">
                <div id="chartdiv" style="width:100%; height:100%;"></div>
            </div>
        </div>
        <div class="row" id="datas">
            <div class="data">
                <div class="container-perso" style="background-color: #292B2C; color: #FFFFFF;">
                    <h3 class="dataDashboard"><h3>
                </div>
            </div>
            <div class="data">
                <div class="container-perso" style="background-color: #292B2C; color: #FFFFFF;">
                    <div class="dataDashboard"></div>
                </div>
            </div>
            <div class="data">
                <div class="container-perso" style="background-color: #292B2C; color: #FFFFFF;"></div>
            </div>
        </div>
    </div>

    <!-- /PAGE CONTENT-->





    <!-- JS & JQUERRY & JS BOOTSTRAP -->

    <script src="js/chartTest2.js"></script>

    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
