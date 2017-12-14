<?php
require 'includes/session.php';
require 'includes/residence.functions.php';
if($_GET['modify']){
  $isOwner = isOwner($_SESSION['user']['id'],$_GET['modify']);
  if($isOwner){
    //load INFORMATION
    $diff = false;
    if(isset($_SESSION['residence']['R_id'])){
      if($_SESSION['residence']['R_id']!=$_GET['modify']){
        $diff=true;
      }
    }
    $_SESSION['residence']['R_id'] = $_GET['modify'];
    if(!isset($_SESSION['residence']['loaded']) || $diff){
      $data =getResidenceDetails($_GET['modify']);
      $_SESSION['residence']['loaded'] = $data[0];
    }
    $_SESSION['residence']['loaded']['R_residencyType'] =
    strtolower($_SESSION['residence']['loaded']['R_residencyType']);
    if(isset($_POST['mod_r'])){
      $verify = verifyForm($_POST);
      if($verify instanceof Residence){
        $residence = $verify;
        $isModified = compareData($_SESSION['residence']['loaded'],$_POST);
        if($isModified){
          $success = modifyResidence($residence);
          unset($_SESSION['residence']);
        }else{
          unset($_SESSION['residence']['errors']);
          $_SESSION['residence']['post'] = $_POST;
        }
      }else{
        $_SESSION['residence']['errors'] = $verify;
        $_SESSION['residence']['post'] = $_POST;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Modification de votre résidence</title>
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
      <p><a href="residence.php">Mes résidences</a> > Modification</p>
      <hr>
      <?php if(!$isOwner){ ?>
        <div class="alert alert-danger">
          <h6 class="alert-heading">Tentative de modification échouée.</h6>
          <p><b>Cette résidence ne vous appartient pas</b>.
            Par conséquent, il vous sera impossible de la modifier.
            <br>Cependant, si cette résidence vous appartient, veuillez contacter
            le webmaster du site.
          </p>
        </div>
      <?php
      if(isset($_SESSION['residence'])){
        unset($_SESSION['residence']);
      }
    }else{ ?>
        <form method="POST" action="">
          <h4>Modification de votre résidence</h4>
          <div>
            <?php if(isset($isModified) && !$isModified){?>
              <div class="alert alert-warning">
                <h6 class="alert-heading">Auncunes modifications effectuées!</h6>
                <p>Vous navez pas modifié votre résidence.</p>
              </div>
            <?php } ?>
            <?php if(!empty($_SESSION['residence']['errors'])){ ?>
              <div class="alert alert-danger">
                <h6 class="alert-heading">champs incorrects!</h6>
                <?php if(!isset($_SESSION['residence']['errors']['exist'])){ ?>
                <p>Veuillez compléter les champs incorrects.</p>
              <?php }else{ ?>
                <p><?= $_SESSION['residence']['errors']['exist']; ?></p>
              <?php } ?>
              </div>
            <?php }else if(isset($success)){ ?>
              <div class="alert alert-success">
                <h6 class="alert-heading">Félicitations!</h6>
                <p>Votre résidence a été modifiée avec succès.</p>
                <a href="residence.php">Retourner à mes résidences</a>
              </div>
            <?php }else{ ?>
          <div class="alert alert-info">
            <p>Libre à vous de modifier les informations de votre résidence.
              <br><b>Tous les champs sont obligatoires</b>.</p>
          </div>
        <?php } ?>
          <div>
              <div class="form-group col-md-6">
                <!-- varchar(16) -->
                <label for="R_residencyType">Type d'habitation</label>
                <select name="R_residencyType" class="form-control">
                  <option value="maison"
                  <?php if((isset($_SESSION['residence']['post']['R_residencyType']) &&
                  $_SESSION['residence']['post']['R_residencyType'] == "maison")
                  || (isset($_SESSION['residence']['loaded']['R_residencyType'])
                  && $_SESSION['residence']['loaded']['R_residencyType'] == 'maison')){echo 'selected';} ?>>
                  Maison</option>
                  <option value="appartement"
                  <?php if((isset($_SESSION['residence']['post']['R_residencyType']) &&
                  $_SESSION['residence']['post']['R_residencyType'] == "appartement")
                  || (isset($_SESSION['residence']['loaded']['R_residencyType'])
                  && $_SESSION['residence']['loaded']['R_residencyType'] == 'appartement')){echo 'selected';} ?>>
                  Appartement</option>
                   <option value="kot"
                   <?php if((isset($_SESSION['residence']['post']['R_residencyType']) &&
                   $_SESSION['residence']['post']['R_residencyType'] == "kot")
                   || (isset($_SESSION['residence']['loaded']['R_residencyType'])
                   && $_SESSION['residence']['loaded']['R_residencyType'] == 'kot')){echo 'selected';} ?>>
                   Kot</option>
                </select>
              </div>
              <!-- int 11 -->
              <div class="form-group col-md-6">
                <label for="R_nbRooms">Nombre de pièces</label>
                <input name="R_nbRooms" type="number"
                value='<?php if(isset($_SESSION['residence']['post']['R_nbRooms'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['R_nbRooms']);
                }else if(isset($_SESSION['residence']['loaded']['R_nbRooms'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['R_nbRooms']);
                }
                 ?>' class="form-control" placeholder="5" min=1 max=30>
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
                value='<?php if(isset($_SESSION['residence']['post']['R_area'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['R_area']);
                }else if(isset($_SESSION['residence']['loaded']['R_area'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['R_area']);
                }
                 ?>' class="form-control" placeholder="150" min=10 max=300>
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
                value='<?php if(isset($_SESSION['residence']['post']['R_inhabitants'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['R_inhabitants']);
                }else if(isset($_SESSION['residence']['loaded']['R_inhabitants'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['R_inhabitants']);
                }
                 ?>' class="form-control" placeholder="">
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
                value="<?php if(isset($_SESSION['residence']['post']['L_address'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['L_address']);
                }else if(isset($_SESSION['residence']['loaded']['L_address'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['L_address']);
                }
                 ?>" class="form-control" placeholder="">
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
                value="<?php if(isset($_SESSION['residence']['post']['M_zipCode'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['M_zipCode']);
                }else if(isset($_SESSION['residence']['loaded']['M_zipCode'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['M_zipCode']);
                }
                 ?>" class="form-control" placeholder="" min="1000" max="9999" disabled>
                <?php if(isset($_SESSION['residence']['errors']['M_zipCode'])){
                echo '<p style="font-size:10px;color:#c56600;">';
                echo $_SESSION['residence']['errors']['M_zipCode'];
                echo '</p>';
              }?>
              </div>
              <!-- varchar(32) -->
              <div class="form-group col-md-6">
                <label for="T_name">Ville</label>
                <input type="text" name="T_name" class="form-control" disabled
                value="<?php if(isset($_SESSION['residence']['post']['T_name'])){
                  echo htmlspecialchars($_SESSION['residence']['post']['T_name']);
                }else if(isset($_SESSION['residence']['loaded']['T_name'])){
                  echo htmlspecialchars($_SESSION['residence']['loaded']['T_name']);
                }
                 ?>" >
                <!-- <select name="T_name" class="form-control">
                </select> -->
                <?php if(isset($_SESSION['residence']['errors']['T_name'])){
                echo '<p style="font-size:10px;color:#c56600;">';
                echo $_SESSION['residence']['errors']['T_name'];
                echo '</p>';
              }?>
              </div>
              <button type="submit" name="mod_r" class="btn btn-primary">Modifier</button>
          </div>
        </form>
      <?php }
      // echo '<pre>'.print_r($_POST,1).'</pre>';
      // echo '<pre>'.print_r($_SESSION,1).'</pre>';
      ?>
    </div>
    <!-- /PAGE CONTENT-->
    <!-- JS & JQUERRY & JS BOOTSTRAP -->
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
