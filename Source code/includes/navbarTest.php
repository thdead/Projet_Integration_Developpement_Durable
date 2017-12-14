<?php if(isConnected()){
    echo '
    <nav class="navbar navbar-toggleable navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#emonitorNavbar" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="dashboard.php"><img src="img/logo.png" alt="Emonitor.logo" height="28">&nbsp;&nbsp; E-Monitor</a>

        <div class="collapse navbar-collapse navbar-toggleable-xs" id="emonitorNavbar">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $_SESSION["user"]["name"].' '.$_SESSION["user"]["lastname"] . '</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <form action=# method="post">
                      <a class="dropdown-item" href="profil.php"><i class="fa fa-user" aria-hidden="true"></i>   Profil</a>
                      <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true"></i>   Paramètres</a>
                      <button name="disconnect" class="btn btn-link mx-1">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>   Se déconnecter</a>
                      </button>
                    </form>
                    </div>
                  </li>
                <li class="nav-item">
                  <form action=# method="post">
                    <button name="disconnect" id="disco" class="btn btn-outline-success mx-1">
                      <i class="fa fa-power-off" aria-hidden="true"></i>
                    </button>
                  </form>
                </li>
            </ul>
        </div>
    </nav>
    <!--/NAVBAR-->
    ';
}else{
    echo '
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#emonitorNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="index.php">
            <img src="img/logo.svg" alt="Emonitor.logo" height="28">
            &nbsp;&nbsp; E-Monitor
        </a>


        <div class="collapse navbar-collapse navbar-toggleable-xs" id="emonitorNavbar">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link" href="index.php#aboutUs">A Propos</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="index.php#product">Produit</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="index.php#contact">Contact</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="btn btn-outline-success mx-1" id="login" href="login.php">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-success mx-1" id="registration" href="registration_form.php">Inscription</a>
                </li>
            </ul>
        </div>
    </nav>
    <!--/NAVBAR-->
    ';
}
