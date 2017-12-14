<?php require 'includes/session.php';
require 'includes/residence.functions.php';
$residencies = getResidenciesInfo();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Test résidence</title>
    <?php include 'includes/head.php'; ?>
    <!-- <script src="js/functions.js"></script> -->
  </head>
  <body>
    <?php include 'includes/navbarTest.php'; ?>
    <?php include 'includes/sideBar.php'; ?>
    <!-- INFORMATIONS ABOUT MY RESIDENCES -->
    <div class='container-fluid' id="content">
    </br>
      <h2>Mes résidences</h2>
      <br>
      <form method="POST" action="">
      <?php if(!empty($residencies)){ ?>
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
              <!-- FAIRE UNE BOUCLE FOREACH ICI -->
            <tr>
              <td><?= ucfirst($residencies[$i]['L_address']); ?></td>
              <td><?= ucfirst($residencies[$i]['T_name']); ?></td>
              <td><?= ucfirst($residencies[$i]['M_name']); ?></td>
              <td>
                <button name="action[details]" type="submit" class="btn-sm btn-info"
                value="<?= $i ?>">Détails</button>
                <button name="action[modify]" type="submit" class="btn-sm btn-warning"
                value="<?= $i ?>">Modifier</button>
                <button name="action[delete]" type="submit" class="btn-sm btn-danger"
                value="<?= $i ?>">Supprimer</button>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <button name="action[add]" type="submit" class="btn btn-success">Ajouter une nouvelle résidence</button>
      <?php }else{ ?>
        <p>Vous ne possédez aucune résidence.</p>
        <div class="alert alert-warning">
          <p>
            Nous sommes désolés <i><?= $_SESSION['user']['name'] ?></i> mais il semblerait
          qu'aucune résidence ne soit enregistrée à votre compte.
         </p>
       </div>
       <button name="action[add]" type="submit" class="btn btn-success">Ajouter une résidence</button>
      <?php } ?>
      </form>
      <div id="loaded_content">
        <?php if(isset($_POST['action']) && !empty($_POST['action'])){
          echo '<hr>';
          // var_dump($_POST);
          $action = array_keys($_POST['action'])[0];
          $nb =$_POST['action'][$action];
          switch($action){
            case 'details':
            echo details($nb,getResidenceDetails($residencies[$nb]['R_id']));
              break;
            case 'modify': echo 'mod';
            break;
          case 'delete': echo 'del';
            break;
          case 'add':echo 'add';
            break;
          default: echo "error";
          }
        }
        ?>
      </div>
    </div>
  </body>
</html>
