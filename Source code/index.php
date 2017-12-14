<?php require 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <?php include('includes/head.php'); ?>
</head>
<body>
    <!--NAVBAR-->
    <?php include('includes/navbarTest.php'); ?>
    <!-- STAGE -->
     <div id="stage">
        <div id="stage-caption">
            <h1>Rien de plus pratique ! Installez-le et ...</h1>
            <p>Avec E-Monitor vos factures énergétiques deviennent plus claires.</p>
            <a href="registration_form.php" class="btn btn-outline-success btn-success">S'inscrire maintenant !</a>
        </div>
    </div>
    <!-- /STAGE -->

    <!-- SECTION -->

    <section id="aboutUs" class="aboutUs-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <img src="img/logo.svg" alt="Emonitor.logo" width="100%" height="auto">

                </div>
                <div class="col-lg-8 ">
                    <br>
                    <h1> A PROPOS</h1>

                    <h3>Une surveillance de votre consommation approfondie</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <p>
                        Avec E-Monitor, vérifiez l'évolution de vos dépenses énergétiques n'a jamais été aussi simple.
                    </p>
                    <p>
                        A l'aide de notre boitier de mesure, vos donnée de consommation seront retranscrites de manière entièrement autonome.
                    <p/>
                    <p>
                        Notre outil reprendra l'ensemble des informations qui vous seront utiles pour un suivi efficace de votre relevé de compteur, qu'il soit d'électricité, de gaz ou d'eau.
                        Plus de facture avec des détails obscurs qui ne vous informent en rien sur une possible surconsommation à un instant donné!
                    <p>
                        Vous saurez, en temps et en heure, grâce à des représentations graphiques évolutives, à quelle semaine, jour, heure vous avez anormalement utilisé vos ressources.
                        Vous n'avez pas constamment le nez sur vos dépenses? Notre système d'alerte vous notifiera en cas d'excédent notable sans délai.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="product" class="aboutUs-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <img src="img/logo.svg" alt="Emonitor.logo" width="100%" height="auto">

                </div>
                <div class="col-lg-8 ">
                    <br>
                    <h1>PRODUIT</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <p>
                        E-Monitor propose différents types de services : 
					</p>
					<p style="margin-left: 2em;">
                        Un premier service gratuit. Il suffit de vous <a href="registration_form.php">inscrire</a> sur la plateforme et vous aurez accès à différents types de service moyennant l'entrée de vos données manuellement sur la plateforme. 
                        <br><br>
                        Ces services comprennent des graphiques avec vos consommations journalières/mensuelles/annuelles ainsi que des détails précis sur ces données. Vous pourrez ainsi aller consulter vos données du 10 novembre 2017 aussi bien que vos données du mois de Novembre, ceux-ci regroupant une moyenne de consommation. Exemple graphique : 
                        <br>
                        <br>
                        <img src="img/graph.png">
                    </p>
                    <p style="margin-left: 2em;">
                    	Un second service avec une offre d'abonnement. Lors du choix de cette option, un module E-Monitor sera installé sur votre compteur <i class="fa fa-battery-full" aria-hidden="true"></i> électrique, <i class="fa fa-tint" aria-hidden="true"></i> eau, <i class="fa fa-free-code-camp" aria-hidden="true"></i> gaz ou encore sur l'entièreté de ceux-ci <i class="fa fa-battery-full" aria-hidden="true"></i> <i class="fa fa-tint" aria-hidden="true"></i> <i class="fa fa-free-code-camp" aria-hidden="true"></i>.
                    	<br><br>
                    	Voyez ci-dessous les schémas correspondants au module. 
                    </p>
                    <p>
                    	<img src="img/3dOblique.png">
                    	<img src="img/3dBas.png">
                    </p>
                    <p>
                    	Ce module est doté d'une caméra permettant la capture de l'image ainsi qu'une puce électronique permettant le traitement des images capturées. Par la suite, c'est ce même module qui envoie vos données en temps réel à votre compte sur notre plateforme. Cela permet d'automatiser vos relevés de compteurs et ainsi garde un oeil sur ceux-ci. 
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <br>
                    <h1>© 2017 E-Monitor</h1>
                    <h3><A HREF="mailto:contact@e-monitor.com">Contactez-nous</A></h3>
                </div>
            </div>
        </div>
    </section>


    <!-- /SECTION -->

    <!-- JQUERRY & JS BOOTSTRAP -->
    <script src="js/jquerry/jquery.min.js"></script>
    <script src="js/jquerry/popper.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
</body>
</html>
