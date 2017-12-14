<?php require 'includes/session.php';
      require 'includes/residence.functions.php';
      if(isset($_SESSION['residence'])){
        unset($_SESSION['residence']);
      }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion de vos résidences</title>
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
      <h3>Mes résidences</h3>
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
                <a href='modifyResidence.php?modify=<?= $residencies[$i]['R_id'] ?>'><button class="btn-sm btn-warning"
                value="<?= $residencies[$i]['R_id'] ?>" name="modify">Modifier</button></a>
                <a href='#'><button onclick="deleteR(this);return false;" class="btn-sm btn-danger"
                value="<?= $residencies[$i]['R_id'] ?>" name="delete">Supprimer</button></a>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <a href='addResidence.php' style=""><button name="add" class="btn btn-success">Ajouter une nouvelle résidence</button></a>
      <?php }else{ ?>
        <p>Vous ne possédez aucune résidence.</p>
        <div class="alert alert-warning">
          <p>
            Nous sommes désolés <i><?= $_SESSION['user']['name'] ?></i> mais il semblerait
          qu'aucune résidence ne soit enregistrée à votre compte.
         </p>
       </div>
       <a href='addResidence.php' style=""><button name="add" class="btn btn-success">Ajouter une nouvelle résidence</button></a>
      <?php } ?>
      <div id="details">
      </div>
    </div>
    <!-- /PAGE CONTENT-->
    <!-- JS & JQUERRY & JS BOOTSTRAP -->
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.3/sweetalert2.all.min.js"></script>
</body>
</html>
