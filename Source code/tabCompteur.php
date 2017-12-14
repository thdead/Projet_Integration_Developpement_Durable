<?php
require 'includes/session.php'; 
require 'mysql/connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Liste de données - EMonitor</title>

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>
<body>
    <!--NAVBAR-->
    <?php include 'includes/navbarTest.php'; ?>
    <!--/NAVBAR-->

    <!--SIDEBARNAV -->
    <?php include 'includes/sidebar.php'; ?>
    <!--/SIDEBARNAV-->


    <!-- PAGE CONTENT-->

    <?php 
    	$selectResidence = "Select I.R_id, R.R_area, R.R_residencyType From Inhabit as I natural join Residency as R Where I.C_id = ?";
    	$reqResidence = $pdo->prepare($selectResidence);
    ?>


    <div class="container-fluid" id="content">
	<br>
    <h1 class="alert alert-success">Récapitulatif de votre consommation</h1>
    	<div id="filterDiv">
            <form id="filterForm" name="filterForm" action="#" method="GET">
                <label for="numResidence"> N° Résidence </label>
                <select id="numResidence" name="numResidence">
                	<?php 
	            	if($reqResidence->execute(array($_SESSION['user']['id']))) {
			    		while ($row = $reqResidence->fetch(PDO::FETCH_ASSOC)) {
					    	if(!isset($_GET['numResidence'])) $_GET['numResidence'] = $row['R_id'];
					    	?>
					    	<option value="<?= $row['R_id'] ?>" 
                                <?php if(isset($_GET['numResidence']) && $_GET['numResidence'] == $row['R_id']) echo "selected" ?>>
                                    <?= $row['R_id'] ?> - <?= $row['R_residencyType'] ?> - <?= $row['R_area'] ?></option>
					    	<?php
					    }
			    	}
			    	?>
                </select>
                <label for="type"> Type </label>
                <select id="type" name="type">
                    <option value="Electrique" <?php if(isset($_GET['type']) && $_GET['type'] == "Electrique") echo "selected" ?>>Électrique</option>
                    <option value="Eau" <?php if(isset($_GET['type']) && $_GET['type'] == "Eau") echo "selected" ?>>Eau</option>
                    <option value="Gaz" <?php if(isset($_GET['type']) && $_GET['type'] == "Gaz") echo "selected" ?>>Gaz</option>
                </select>
                <label for="dateDebut"> Date de début </label>
                <input id="dateDebut" type="date" name="dateDebut" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" 
                    value=<?= (isset($_GET['dateDebut']) && !empty($_GET['dateDebut'])) ? $_GET['dateDebut'] : "" ?> />
                <label for="dateFin"> Date de fin </label>
                <input id="dateFin" type="date" name="dateFin" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" 
                    max=<?= date('Y-m-d'); ?>
                    value=<?= (isset($_GET['dateFin']) && !empty($_GET['dateFin'])) ? $_GET['dateFin'] : date('Y-m-d') ?> />
                <input type="submit" value="Trier" />
            </form>
            <form action="#" method="GET">
                <input type="submit" value="Reinitialiser">
            </form>
        </div>
        <table class="table">
    		<thead>
    			<tr>
    				<th>N° Résidence</th>
                    <th>Date/Heure</th>
    				<th>Utilisation/kWh</th>
    				<th>Type de compteur</th>
    				<th>Alerte (☄)</th>
    			</tr>
    		</thead>
            <tbody>
            <?php
                $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
                $limite = 30;
                $debut = ($page - 1) * $limite;

                // TRIS
                $typeTri = isset($_GET['type']) ? $_GET['type'] : "";

                if(!isset($_SESSION['user']['id'])) {

                    $_SESSION['error'][0] = "Vous n'êtes actuellement pas connecté";

                } else {

                    if(isset($_GET['numResidence'])) {
                    	
                    	$meterId = null;
                    	
                    	$queryResDetails = "";

                    	$queryResDetails .= "SELECT Me_id from Meter";

                    	$queryResDetails .= " WHERE R_id = '" . $_GET['numResidence'] . "'";

                    	$queryResDetails .= (isset($_GET['type'])) ? " AND Me_type = '" . $_GET['type'] . "'" : "";


                    	foreach ($pdo->query($queryResDetails) as $metId) {
	                        $meterId = $metId['Me_id'];
	                    }

	                    if(is_null($meterId)) {
	                    	$_SESSION['error'][1] = "Il n'y a aucun compteur associées à cette résidence";
	                    } else {
	                    	$queryDetails = "";

	                    	$queryDetails .= "SELECT C.*, M.Me_type FROM Control as C natural join Meter as M";

	                    	$queryDetails .= " WHERE M.Me_id = :meterId";

	                    	$queryDetails .= (isset($_GET['type'])) ? " AND M.Me_type = '" . $_GET['type'] . "'" : "";

                            if(isset($_GET['dateDebut']) && !empty($_GET['dateDebut'])) {
                                $dateDebutSplit = explode("-", $_GET['dateDebut']);
                                $jour = $dateDebutSplit[2];
                                $mois = $dateDebutSplit[1];
                                $annee = $dateDebutSplit[0];
                                $dateDebut = $jour . "-" . $mois . "-" . $annee;

                                $queryDetails .= " AND SUBSTRING(Con_time, 1, 10) >= '" . $dateDebut . "'";
                            }

                            if(isset($_GET['dateFin']) && !empty($_GET['dateFin'])) {
                                $dateFinSplit = explode("-", $_GET['dateFin']);
                                $jour = $dateFinSplit[2];
                                $mois = $dateFinSplit[1];
                                $annee = $dateFinSplit[0];
                                $dateFin = $jour . "-" . $mois . "-" . $annee;

                                $queryDetails .= " AND SUBSTRING(Con_time, 1, 10) <= '" . $dateFin . "'";
                            }

	                    	$queryDetails .= " LIMIT :limite OFFSET :debut";

	                    	$req = $pdo->prepare($queryDetails);

		                    $req->bindValue('limite', $limite, PDO::PARAM_INT);
		                    $req->bindValue('debut', $debut, PDO::PARAM_INT);
		                    $req->bindValue('meterId', $meterId, PDO::PARAM_INT);

		                    $req->execute();

		                    if(!($req->rowCount() > 0)) {
		                        $_SESSION['error'][2] = "Vous n'avez actuellement aucune donnée.";
		                    } else {
		                        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
		                        ?>
		                            <tr>
		                                <td><?= $_GET['numResidence'] ?></td>
		                                <td><?= $row['Con_time'] ?></td>
		                                <td><?= $row['Con_measure'] ?></td>
		                                <td><?= $row['Me_type'] ?></td>
		                                <td>☑</td>
		                            </tr>
		                        <?php
		                        }
		                    }
	                    }
                    } else {
                    	$_SESSION['error'][3] = "Veuillez sélectionner le numéro de votre résidence";
                    }
                }

                if(isset($_SESSION['error'])) {
                	foreach ($_SESSION['error'] as $key => $value) {
                ?>
                    <tr>
                        <td><p class="alert alert-danger"><?= $value ?></p></td>
                    </tr>
                <?php
                    }
                    $_SESSION['error'] = null;
                }
                ?>
            </tbody>
        </table>
        <div style="text-align: center; margin : 2em;">
            <a href="?page=<?php echo $page - 1; ?>">Page précédente</a>
            —
            <a href="?page=<?php echo $page + 1; ?>">Page suivante</a>
        </div>
    </div>

    <!-- /PAGE CONTENT-->





    <!-- JS & JQUERRY & JS BOOTSTRAP -->

    <script src="js/chartTest.js"></script>

    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
