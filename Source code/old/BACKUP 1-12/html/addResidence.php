<?php
  require 'includes/session.php';
  require 'includes/residence.functions.php';
  if(isset($_POST['add_r'])){
    $verify = verifyForm($_POST);
    if($verify instanceof Residence){
      $residence = $verify;
      unset($_SESSION['residence']);
      $_SESSION['residence']['exist'] = addResidence($residence);
      $success = 1;
    }else{
      $_SESSION['residence']['errors'] = $verify;
      $_SESSION['residence']['post'] = $_POST;
    }
  }else{ //IF PAGE REFRESH, RESET
    if(isset($_SESSION['residence'])){
      unset($_SESSION['residence']);
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajout d'une résidence</title>
    <?php include 'includes/head.php'; ?>
  </head>
  <body>
    <!--NAVBAR-->
    <?php require 'includes/navbarTest.php' ?>
    <!--/NAVBAR-->

    <!--SIDEBARNAV -->
    <?php include 'includes/sidebar.php'; ?>
    <!--/SIDEBARNAV-->
    <!-- INFORMATIONS ABOUT MY RESIDENCES -->
    <div class='container-fluid' id="content">
      <br>
      <p><a href="residence.php">Mes résidences</a> > Nouvelle résidence</p>
      <hr>
      <form method="POST" action="">
        <h4>Ajout d'une nouvelle résidence</h4>
        <div>
          <?php if(!empty($_SESSION['residence']['errors'])){ ?>
            <div class="alert alert-danger">
              <?php if(!isset($_SESSION['residence']['errors']['exist'])){ ?>
              <p>Veuillez compléter les champs incorrects.</p>
            <?php }else{ ?>
              <p><?= $_SESSION['residence']['errors']['exist']; ?></p>
            <?php } ?>
            </div>
          <?php }else if(isset($success)){ ?>
            <div class="alert alert-success">
              <p>Votre résidence a été ajoutée avec succès.</p>
              <a href="residence.php">Retourner à mes résidences</a>
            </div>
          <?php }else{ ?>
        <div class="alert alert-info">
          L'intégralités des champs de ce formulaire sont obligatoires. Veuillez donc
          tous les compléter.
        </div>
      <?php } ?>
        <div>
            <div class="form-group col-md-6">
              <!-- varchar(16) -->
              <label for="R_residencyType">Type d'habitation</label>
              <select name="R_residencyType" class="form-control">
                <option value="0"   <?php if(isset($_SESSION['residence']['post']['R_residencyType']) &&
                  empty($_SESSION['residence']['post']['R_residencyType']))echo 'selected' ?>>-- Sélectionnez --</option>
                <option value="Maison"
                <?php if(isset($_SESSION['residence']['post']['R_residencyType']) &&
                $_SESSION['residence']['post']['R_residencyType'] == "Maison")echo 'selected' ?>>
                Maison</option>
                <option value="Appartement"
                <?php if(isset($_SESSION['residence']['post']['R_residencyType']) &&
                $_SESSION['residence']['post']['R_residencyType'] == "Appartement")echo 'selected' ?>>
                Appartement</option>
                 <option value="Kot"
                 <?php if(isset($_SESSION['residence']['post']['R_residencyType']) &&
                 $_SESSION['residence']['post']['R_residencyType'] == "Kot")echo 'selected' ?>>
                 Kot</option>
              </select>
            </div>
            <!-- int 11 -->
            <div class="form-group col-md-6">
              <label for="R_nbRooms">Nombre de pièces</label>
              <input name="R_nbRooms" type="number"
              value="<?= isset($_SESSION['residence']['post']['R_nbRooms']) ?
              htmlspecialchars($_SESSION['residence']['post']['R_nbRooms'])
              : '' ?>"
              class="form-control" placeholder="5" min=1 max=30>
              <?php if(isset($_SESSION['residence']['errors']['R_nbRooms'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['R_nbRooms'];
              echo '</p>';
              }
              ?>
            </div>
            <!-- varchar(32) -->
            <div class="form-group col-md-6">
              <label for="R_area">Surface (m²)</label>
              <input name="R_area" type="number"
              value="<?= isset($_SESSION['residence']['post']['R_area']) ?
              htmlspecialchars($_SESSION['residence']['post']['R_area'])
              : '' ?>"
              class="form-control" placeholder="150" min=10 max=300>
              <?php if(isset($_SESSION['residence']['errors']['R_area'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['R_area'];
              echo '</p>';
            }
            ?>
            </div>
            <!-- int 0-20 -->
            <div class="form-group col-md-6">
              <label for="R_inhabitants">Nombre d'habitants</label>
              <input name="R_inhabitants" type="number"
              value="<?= isset($_SESSION['residence']['post']['R_inhabitants']) ?
              htmlspecialchars($_SESSION['residence']['post']['R_inhabitants'])
              : '' ?>"
              class="form-control" placeholder="">
              <?php if(isset($_SESSION['residence']['errors']['R_inhabitants'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['R_inhabitants'];
              echo '</p>';
            }?>
            </div>
            <div class="form-group col-md-6">
              <!-- varchar 64 -->
              <label for="L_address">Adresse</label>
              <input name="L_address" type="text"
              value="<?= isset($_SESSION['residence']['post']['L_address']) ?
              htmlspecialchars($_SESSION['residence']['post']['L_address'])
              : '' ?>"
              class="form-control" placeholder="">
              <?php if(isset($_SESSION['residence']['errors']['L_address'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['L_address'];
              echo '</p>';
            }?>
            </div>
            <!-- int(11) -->
            <div class="form-group col-md-6">
              <!-- APPLIQUER UN ONCHANGE DESSUS -->
              <label for="M_zipCode">Code postal</label>
              <input name="M_zipCode" type="number"
              value="<?= isset($_SESSION['residence']['post']['M_zipCode']) ?
              htmlspecialchars($_SESSION['residence']['post']['M_zipCode'])
              : '' ?>"
              class="form-control" placeholder="" min="1000" max="9999">
              <?php if(isset($_SESSION['residence']['errors']['M_zipCode'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['M_zipCode'];
              echo '</p>';
            }?>
            </div>
            <!-- varchar(32) -->
            <div class="form-group col-md-6">
              <label for="T_name">Ville</label>
              <input type="text" name="T_name" class="form-control">
              <!-- <select name="T_name" class="form-control">
              </select> -->
              <?php if(isset($_SESSION['residence']['errors']['T_name'])){
              echo '<p style="font-size:10px;color:#c56600;">';
              echo $_SESSION['residence']['errors']['T_name'];
              echo '</p>';
            }?>
            </div>
            <button type="submit" name="add_r" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
      <div>
        <?= var_dump($_POST); ?>
        <?= var_dump($_SESSION); ?>
        <?php
        if(isset($success)){
          var_dump($residence);
        }
        ?>
      </div>
    </div>
    <!-- /PAGE CONTENT-->
    <!-- JS & JQUERRY & JS BOOTSTRAP -->
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
