<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard</title>
    
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-reboot.min.css">
    
    <!-- CUSTOM CSS -->
    
    <link rel="stylesheet" href="css/style.css">
    
    <!-- font awesome CSS -->
    
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    
    <!-- CHARTJS JS -->
    
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
                        <li>
                            <a href="dashboard.html"><i class="fa fa-pie-chart fa-lg"></i> <span class="menu-item"> Dashboard</span></a>
                        </li>
                        <li class="active">
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
	<h1>Récapitulatif de votre consommation</h1>
	<table class="table">
		<thead>
			<tr>
				<th>Utilisation/kWh</th>
				<th>Date/Heure</th>
				<th>Type de compteur</th>
				<th>Alerte (☄)</th>
			</tr>
		</thead>
	                <tbody>
                        <tr>
                                <td>28776</td>
                                <td>05-10-2017-15-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28778</td>
                                <td>05-10-2017-16-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28879</td>
                                <td>05-10-2017-17-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28880</td>
                                <td>05-10-2017-18-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28883</td>
                                <td>05-10-2017-19-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28885</td>
                                <td>05-10-2017-20-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28885</td>
                                <td>05-10-2017-21-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28886</td>
                                <td>05-10-2017-22-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28886</td>
                                <td>05-10-2017-23-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28886</td>
                                <td>05-10-2017-00-00</td>
                                <td>Électrique</td>
                                <td>☑</td>
                        </tr>
                        <tr>
                                <td>28890</td>
                                <td>05-10-2017-01-00</td>
                                <td>Électrique</td>
                                <td>☄</td>
                        </tr>
                </tbody>
        </table>

    </div>
    
    <!-- /PAGE CONTENT-->
    
    
    
    
    
    <!-- JS & JQUERRY & JS BOOTSTRAP -->

    <script src="js/chartTest.js"></script>
    
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>    
</html>
