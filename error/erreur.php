<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Oupss !! / ENERGIS</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="../page/fonts/open-sans/style.min.css">
<link rel="stylesheet" href="../page/fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="../page/vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="../page/vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="../page/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../page/css/style.min.css" id="stylesheet">

  

<script src="../page/js/ie.assign.fix.min.js"></script>
</head>
<body class="js-loading sidebar-md">

<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="../page/img/logo/LogoEnr.png" alt="" width="200"></div>
  </div>
</div>





<div class="page-wrap">
  

  

   

  
  <div class="container-fluid p-error-page p-error-page--404">
  <div class="p-error-page__wrap">
    <div class="p-error-page__error">
      
      
      	<?php
		switch($_GET['erreur'])
		{
		   case '400':
		   echo 'Échec de l\'analyse HTTP.';
		   break;
		   case '401':
		   echo 'Le pseudo ou le mot de passe n\'est pas correct !';
		   break;
		   case '402':
		   echo 'Le client doit reformuler sa demande avec les bonnes données de paiement.';
		   break;
		   case '403':
		   echo 'Requête interdite !';
		   break;
		   case '404':
		   echo '<h3 class="p-error-page__code">404</h3><div class="p-error-page__desc">La page n\'existe pas ou plus !</div>';
		   break;
		   case '405':
		   echo 'Méthode non autorisée.';
		   break;
		   case '500':
		   echo 'Erreur interne au serveur ou serveur saturé.';
		   break;
		   case '501':
		   echo 'Le serveur ne supporte pas le service demandé.';
		   break;
		   case '502':
		   echo 'Mauvaise passerelle.';
		   break;
		   case '503':
		   echo ' Service indisponible.';
		   break;
		   case '504':
		   echo 'Trop de temps à la réponse.';
		   break;
		   case '505':
		   echo 'Version HTTP non supportée.';
		   break;
		   default:
		   echo 'Erreur !';
		}
		?>


      <a href="http://localhost/ipd/page/home.php" class="badge badge-shamrock mb-3 mr-3 p-error-page__home-link">Retour à la page principale</a>
    </div>
    <div class="p-error-page__image-container">
      <img src="../page/img/robot.png" alt="" class="p-error-page__image embed-responsive">
    </div>
  </div>
</div> 
</div>



<script src="vendor/echarts/echarts.min.js"></script>

<script src="../page/vendor/jquery/jquery.min.js"></script>
<script src="../page/vendor/popper/popper.min.js"></script>
<script src="../page/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../page/vendor/select2/js/select2.full.min.js"></script>
<script src="../page/vendor/simplebar/simplebar.js"></script>
<script src="../page/vendor/text-avatar/jquery.textavatar.js"></script>
<script src="../page/vendor/flatpickr/flatpickr.min.js"></script>
<script src="../page/vendor/wnumb/wNumb.js"></script>
<script src="../page/js/main.js"></script>


<script src="../page/vendor/sparkline/jquery.sparkline.min.js"></script>
<script src="../page/js/preview/default-dashboard.min.js"></script>


<div class="sidebar-mobile-overlay"></div>
</body>
</html>
