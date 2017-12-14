<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <?php include('includes/head.php'); ?>
    <script src="js/chartjs/Chart.bundle.min.js"></script>
    <script src="js/chartjs/Chart.min.js"></script>

</head>
<body>
    <!--NAVBAR-->
    <div class="container">
        <nav class="navbar navbar-toggleable navbar-inverse bg-inverse fixed-top">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#emonitorNavbar" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="index.html"><img src="img/logo.png" alt="Emonitor.logo" height="28">&nbsp;&nbsp; E-Monitor</a>

            <div class="collapse navbar-collapse navbar-toggleable-xs" id="emonitorNavbar">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Nathan Meyer </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <a class="dropdown-item" href="#"><i class="fa fa-user" aria-hidden="true"></i>   Profil</a>
                          <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true"></i>   Paramètres</a>
                          <a class="dropdown-item" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i>   Se déconnecter</a>
                        </div>
                      </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-success mx-1" href="#"><i class="fa fa-power-off" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!--/NAVBAR-->

    <!--SIDEBARNAV -->
    <div class="container-fluid" id="sidebar">
        <div class="nav-side-menu">
                <div class="menu-list">
                    <ul id="menu-content" class="menu-content">
                        <li class="active">
                            <a href="#"><i class="fa fa-pie-chart fa-lg"></i> <span class="menu-item"> Dashboard</span></a>
                        </li>
                        <li>
                            <a href="tabCompteur.html"><i class="fa fa-signal fa-lg"></i><span class="menu-item"> Détails</span> </a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart fa-lg"></i><span class="menu-item"> Elements</span> </a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-line-chart fa-lg"></i><span class="menu-item"> Elements</span> </a>
                        </li>
                    </ul>
             </div>
        </div>
    </div>
    <!--/SIDEBARNAV-->


    <!-- PAGE CONTENT-->

    <div class="container-fluid" id="content">
        <div class="row">
          <div class="col-sm-8" height="100%"><canvas id="chartAve" width="100%"></canvas></div>
        </div>
        <div class="row">
          <div class="col-sm-8"></div>
	  <div class="col-sm-4"></div>
        </div>
    </div>

    <!-- /PAGE CONTENT-->





    <!-- JS & JQUERRY & JS BOOTSTRAP -->

    <script src="js/chartTest.js"></script>

    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
