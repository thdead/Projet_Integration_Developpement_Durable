<div class="container-fluid" id="sidebar">
    <div class="nav-side-menu">
            <div class="menu-list">
                <ul id="menu-content" class="menu-content">
                    <li>
                        <a href="dashboard.php"><i class="fa fa-pie-chart fa-lg"></i> <span class="menu-item"> Dashboard</span></a>
                    </li>
                    <li >
                        <a href="tabCompteur.php"><i class="fa fa-signal fa-lg"></i><span class="menu-item"> Détails</span> </a>
                    </li>
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
