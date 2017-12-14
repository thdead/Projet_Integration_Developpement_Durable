<?php
require 'includes/session.php';
require 'mysql/connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Profil - EMonitor</title>

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

        <div class="container-fluid" id="content">
            <?php

                $selectCustomer = "Select C_firstname, C_lastname, C_birthdate, C_gender, C_email, C_phoneNumber, C_noNotif, C_notifFreq
                                         From Customer Where C_id = ?";
                $reqCustomer = $pdo->prepare($selectCustomer);
                $reqCustomer->execute(array($_SESSION['user']['id']));

                $results = $reqCustomer->fetch(PDO::FETCH_ASSOC);

                if(!empty($_POST)) {
                    $queryUpdate = "";

                    $queryUpdate .= "UPDATE Customer SET C_valid = 1";

                    $queryUpdate .= (!($_POST['lastName'] == $results['C_lastname'])) ? ", C_lastname = '" . $_POST['lastName'] . "'" : "";
                    $queryUpdate .= (!($_POST['firstName'] == $results['C_firstname'])) ? ", C_firstname = '" . $_POST['firstName'] . "'" : "";
                    $queryUpdate .= (!($_POST['birthDate'] == $results['C_birthdate'])) ? ", C_birthdate = '" . $_POST['birthDate'] . "'" : "";
                    $queryUpdate .= (!($_POST['email'] == $results['C_email'])) ? ", C_email = '" . $_POST['email'] . "'" : "";
                    $queryUpdate .= (!($_POST['gender'] == $results['C_gender'])) ? ", C_gender = '" . $_POST['gender'] . "'" : "";
                    $queryUpdate .= (!($_POST['phone'] == $results['C_phoneNumber'])) ? ", C_phoneNumber = '" . $_POST['phone'] . "'" : "";
					$queryUpdate .= (!($_POST['notifFreq'] == $results['C_notifFreq'])) ? ", C_notifFreq = '" . $_POST['notifFreq'] . "'" : "";
                    $queryUpdate .= (!($_POST['noNotif'] == $results['C_noNotif'])) ? ", C_noNotif = '" . $_POST['noNotif'] . "'" : "";
					
                    $queryUpdate .= " WHERE C_id = '" . $_SESSION['user']['id'] . "'";

                    $queryInitiale = "UPDATE Customer SET C_valid = 1 WHERE C_id = '" . $_SESSION['user']['id'] . "'";

                    if($queryUpdate == $queryInitiale) {
                        $_SESSION['error']['modifier'] = "Vous n'avez modifier aucunes informations";
                    } else {
                        $reqUpdate = $pdo->prepare($queryUpdate);
                        if($reqUpdate->execute()) {
                            $success = true;
                        }
                    }
                }


                if(isset($_GET['modifier'])) {
                ?>
                    <br>
                    <h4 class="alert alert-warning">Informations personnelles</h4>
                    <?php

                        if(isset($success)) {
                            $success = false;
                        ?>
                            <h5 class="alert alert-success">Modifications enregistrées</h5>
                        <?php
                        }
                        if(isset($_SESSION['error']['modifier'])) {
                        ?>
                            <h5 class="alert alert-warning"><?= $_SESSION['error']['modifier'] ?></h5>
                        <?php
                            unset($_SESSION['error']['modifier']);
                        }

                    ?>
                    <br>
                    <form method="post" action="">
                        <div class="form-group row">
                            <label for="lastName" class="col-3 col-form-label  col-form-label-sm">
                                Nom
                            </label>
                            <div class="col-9">
                                <input name="lastName" id="lastName" class="form-control form-control-sm"
                                    type="text"
                                    value="<?= isset($_POST['lastName']) ? $_POST['lastName'] : $results['C_lastname'] ?>" min=1 max=32 required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstName" class="col-3 col-form-label  col-form-label-sm">
                                Prénom
                            </label>
                            <div class="col-9">
                                <input name="firstName" id="firstName" class="form-control form-control-sm"
                                    type="text"
                                    value="<?= isset($_POST['firstName']) ? $_POST['firstName'] : $results['C_firstname'] ?>" min=1 max=32 required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="birthDate" class="col-3 col-form-label col-form-label-sm">
                                Date de naissance
                            </label>
                            <div class="col-9">
                                <input name="birthDate" id="birthDate" type="date" class="form-control form-control-sm"
                                    placeholder="jj-mm-aaaa"
                                    value="<?= isset($_POST['birthDate']) ? $_POST['birthDate'] : $results['C_birthdate'] ?>" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-3 col-form-label  col-form-label-sm">
                                Adresse mail
                            </label>
                            <div class="col-9">
                                <input name="email" id="email" type="email" class="form-control
                                    form-control-sm" value="<?= isset($_POST['email']) ? $_POST['email'] : $results['C_email'] ?>" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gender" class="col-3 col-form-label col-form-label-sm">
                                Sexe
                            </label>
                            <div class="col-9">
                                <select class="form-control form-control-sm" name="gender" required>
                                <option value="M" <?php if($results['C_gender'] == "M") echo 'selected' ?>>Homme</option>
                                <option value="W" <?php if($results['C_gender'] == "W") echo 'selected' ?>>Femme</option>
                                <option value="O" <?php if($results['C_gender'] == "O") echo 'selected' ?>>Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-3 col-form-label col-form-label-sm">
                                N° de téléphone
                            </label>
                            <div class="col-9">
                                <input name="phone" id="phone" type="tel" class="form-control form-control-sm"
                                    pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" value="<?= isset($_POST['phone']) ? $_POST['phone'] : $results['C_phoneNumber'] ?>" />
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="notifFreq" class="col-3 col-form-label col-form-label-sm">
                                Frequence de notification
                            </label>
                            <div class="col-9">
                                <select class="form-control form-control-sm" name="notifFreq">
                                <option value="4" <?php if($results['C_notifFreq'] == "4") echo 'selected' ?>>4 heures</option>
                                <option value="12" <?php if($results['C_notifFreq'] == "12") echo 'selected' ?>>12 heures</option>
								<option value="48" <?php if($results['C_notifFreq'] == "48") echo 'selected' ?>>48 heures</option>
								<option value="72" <?php if($results['C_notifFreq'] == "72") echo 'selected' ?>>72 heures</option>
                                </select>
							</div>
                        </div>
						<div class="form-group row">
                            <label for="noNotif" class="col-3 col-form-label col-form-label-sm">
                                Pas de notification
                            </label>
                            <div class="col-9">
                                <select class="form-control form-control-sm" name="noNotif" required>
                                <option value="0" <?php if($results['C_noNotif'] != 1) echo 'selected' ?>>Recevoir des notifications</option>
                                <option value="1" <?php if($results['C_noNotif'] == 1) echo 'selected' ?>>Pas de notifications</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col">
                            <button type="submit" class="btn btn-primary" value="modifier">
                                Modifier
                            </button>
                        </div>
                    </form>
                    <a href="/profil.php"><button class="btn btn-primary">Retour à la page précédente</button></a>
                <?php
                }
            ?>
            <?php
                if(!isset($_GET['modifier'])) {
                ?>
                    <br>
                    <h4 class="alert alert-warning">Informations personnelles</h4>
                    <br>
                    <table class="table">
                        <tr>
                            <td><b>Nom</b></td>
                            <td><?= $results['C_lastname'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Prénom</b></td>
                            <td><?= $results['C_firstname'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Date de naissance</b></td>
                            <td><?= $results['C_birthdate'] ?></td>
                        </tr>
                        <tr>
                            <td><b>E-Mail</b></td>
                            <td><?= $results['C_email'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Sexe</b></td>
                            <td><?= $results['C_gender'] ?></td>
                        </tr>
                        <tr>
                            <td><b>Téléphone</b></td>
                            <td><?= $results['C_phoneNumber'] ?></td>
                        </tr>
						<tr>
                            <td><b>Frequence de notification</b></td>
                            <td id="freqDeNotif"></td>
                        </tr>
						<tr>
                            <td><b>Pas de notification</b></td>
                            <td id="pasDeNotif"></td>
                        </tr>
                    </table>
                    <br>
                    <form action="#" method="GET">
                        <button class="btn btn-primary" type="submit" name="modifier" value="true">Modifer mes informations</button>
                    </form>
                <?php
                }
            ?>
        </div>


		<script>var noNotification="";
		if(<?= $results['C_noNotif'] ?>==1){noNotification = "Notifications interdites";}
		else{noNotification = "Notifications autorisées";} 
		document.getElementById("pasDeNotif").innerHTML = noNotification;
		var freqOfNotif="";
		if(<?= $results['C_notifFreq'] ?>=="4"){freqOfNotif = "4 Heures";}
		else if(<?= $results['C_notifFreq'] ?>=="12"){freqOfNotif = "12 Heures";}
		else if(<?= $results['C_notifFreq'] ?>=="48"){freqOfNotif = "48 Heures";}
		else if(<?= $results['C_notifFreq'] ?>=="72"){freqOfNotif = "72 Heures";}
		else{freqOfNotif = "Frequence inconnue";} 
		document.getElementById("freqDeNotif").innerHTML = freqOfNotif;
		</script>
		
        <script src="js/chartTest.js"></script>

        <script src="js/jquerry/jquery.min.js"></script>
        <script src="js/jquerry/popper.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
    </body>
</html>
