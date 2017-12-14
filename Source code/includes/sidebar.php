<div class="container-fluid" id="sidebar">
    <div class="nav-side-menu">
        <div class="menu-list">
          <ul id="menu-content" class="menu-content">
              <a href="dashboard.php">
                  <li>
                      <i class="fa fa-bolt" aria-hidden="true"></i> <span class="menu-item"> Dashboard Electricité</span>
                  </li>
              </a>
              <a href="dashboardEau.php">
                  <li>
                      <i class="fa fa-tint" aria-hidden="true"></i> <span class="menu-item"> Dashboard Eau</span>
                  </li>
              </a>
              <a href="dashboardGaz.php">
                  <li>
                      <i class="fa fa-fire" aria-hidden="true"></i> <span class="menu-item"> Dashboard Gaz</span>
                  </li>
              </a>

              <a href="tabCompteur.php">
                  <li >
                        <i class="fa fa-signal fa-lg"></i><span class="menu-item"> Détails</span>
                  </li>
              </a>
              <a href="residence.php">
                  <li >

                        <i class="fa fa-home"></i><span class="menu-item"> Résidences</span>
                  </li>
              </a>
          </ul>
      </div>
    </div>
</div>
<script>
  //Getting the current page & set current page menu class as "active"
  function setActive(){
    var page = document.location.pathname; // getting current PAGE
    var liArray = document.getElementById('menu-content').getElementsByTagName('li');
    //$('.menu-list li');
    //Getting all the page included in the menu
    for(i=0;i<liArray.length;i++){
      if(page.includes(liArray[i].firstElementChild.attributes.href.value)){ //if current page is detected in sidebar
        liArray[i].className = 'active';
      }
    }
  }
  setActive();
</script>
