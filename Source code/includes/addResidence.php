<?php echo 'hello';
if(isset($_POST['add_r'])){
    //Checking inputs
  }
?>
    <!-- INFORMATIONS ABOUT MY RESIDENCES -->
    <div class='container'>
      <form method="POST" action=''>
        <h4>Ajout d'une nouvelle résidence</h4>
        <div class="alert alert-info">
          L'intégralités des champs de ce formulaire sont obligatoires. Veuillez donc
          tous les compléter.
        </div>
        <div class="container">
            <div class="form-group col-md-6">
              <label for="">Type d'habitation</label>
              <select name="" class="form-control">
                <option>-- Sélectionnez --</option>
                <option>Maison</option>
                <option>Appartement</option>
                <option>Kot</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="">Nombre de pièces</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="5" min=0 max=30>
            </div>
            <div class="form-group col-md-6">
              <label for="">Taille (m²)</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="150" min=10 max=300>
            </div>
            <div class="form-group col-md-6">
              <label for="">Nombre d'habitants</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="">
            </div>
            <div class="form-group col-md-6">
              <label for="">Adresse</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="">
            </div>
            <div class="form-group col-md-6">
              <label for="">Ville</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="">
            </div>
            <div class="form-group col-md-6">
              <label for="">Code postal</label>
              <input name="" type="number" value=""
              class="form-control" placeholder="" min="1000" max="9999">
            </div>
            <button type="submit" name="add_r" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
