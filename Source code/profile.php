<?php require 'includes/session.php';
      require 'includes/residence.functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion de résidence</title>
    <?php include('includes/head.php'); ?>
    <script src='js/functions.js'></script>
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
    </br>
      <h2>Mes résidences</h2>
      <br>
      <?php
      $residencies = getResidenciesInfo();
      if(!empty($residencies)){ ?>
        <p>Vous possédez <b><?= count($residencies) ?></b> résidence(s):</p>
        <table class="table table-striped table-hover">
          <thead class="thead-default">
            <th>Adresse</th>
            <th>Ville</th>
            <th>Commune</th>
            <th>Actions</th>
          </thead>
          <tbody>
            <?php for($i=0;$i<count($residencies);$i++){ ?>
            <tr>
              <td><?= ucfirst($residencies[$i]['L_address']); ?></td>
              <td><?= ucfirst($residencies[$i]['T_name']); ?></td>
              <td><?= ucfirst($residencies[$i]['M_name']); ?></td>
              <td>
                <button onclick="ajax(this);return false;" class="btn-sm btn-info"
                value="<?= $residencies[$i]['R_id'] ?>" name="details">Détails</button>
                <button onclick="ajax(this);return false;" class="btn-sm btn-warning"
                value="<?= $residencies[$i]['R_id'] ?>" name="modify">Modifier</button>
                <button onclick="ajax(this);return false;" class="btn-sm btn-danger"
                value="<?= $residencies[$i]['R_id'] ?>" name="delete">Supprimer</button>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <button name="add" onclick="relocate();" class="btn btn-success">Ajouter une nouvelle résidence</button>
      <?php }else{ ?>
        <p>Vous ne possédez aucune résidence.</p>
        <div class="alert alert-warning">
          <p>
            Nous sommes désolés <i><?= $_SESSION['user']['name'] ?></i> mais il semblerait
          qu'aucune résidence ne soit enregistrée à votre compte.
         </p>
       </div>
       <button name="add" type="submit" class="btn btn-success">Ajouter une résidence</button>
      <?php } ?>
      <div id="details">
      </div>
    </div>
    <!-- /PAGE CONTENT-->
    <!-- JS & JQUERRY & JS BOOTSTRAP -->
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
