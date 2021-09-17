<?php
$result = '';
if (empty($page)) {
 $page = "dbc";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/config/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
}

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../config/".$page) && $page != 'index.php') {
       include("../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}

session_start();
	
if (isset($_POST['doct']) && $_POST['doct'] == 'envoyer')
{
sleep(1); // Une pause de 1 sec

	
	$query = $bdd->prepare("INSERT INTO `contact` (message, np_message, email_message, sujet_message, date_message, stat_message)VALUES (:message, :np_message, :email_message, :sujet_message, now(), 0)");
	$query->bindParam(":message", $_POST['message'], PDO::PARAM_STR);
	$query->bindParam(":np_message", $_POST['usr_np'], PDO::PARAM_STR);
	$query->bindParam(":email_message", $_POST['usr_email'], PDO::PARAM_STR);
	$query->bindParam(":sujet_message", $_POST['usr_obj'], PDO::PARAM_STR);
	$result = $query->execute();
	
	$query->closeCursor();
	
	
}
		


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Contact - ENERGISDATA</title>
  <link rel="shortcut icon" href="PAGE/img/logo/logop.ico">
<link rel="stylesheet" href="page/fonts/open-sans/style.min.css">
<link rel="stylesheet" href="page/fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="page/vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="page/vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="page/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="page/css/style.min.css" id="stylesheet">

<script src="page/js/ie.assign.fix.min.js"></script>
<style>
body {
	margin:0;
	padding:0;
	<?php
	$numero = rand(1, 15);
	echo 'background: url("page/img/home/'.$numero.'.jpg");';
	?>
	-webkit-background-size: cover; /* pour anciens Chrome et Safari */
	background-size: cover; /* version standardisée */
}
</style>
</head>

<body class="p-front-bg">
<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="page/img/logo/LogoEnr.png" alt="" width="200"></div>
  </div>
</div>

<div class="p-front__content">
<div class="p-signin">


<?php if($result != FALSE || $result != ''){
	echo '<div class="alert alert-success" role="alert">Message envoyé avec succès - Merci</div>';
	}else{}
	?>
<form class="p-signin__form" action="Contact" method="post" name="ctForm" id="ctForm">
  
    <h2 class="p-signin__form-heading"><img src="page/img/logo/LogoEnr.png" alt="" width="170"></h2>
    <div class="p-signin__form-content">
      <div class="row">
      	<p class="p-signin-a__form-description">
          Un administrateur vous contactera bientôt pour gérer votre demande !
        </p>
        <div class="form-group col-md-12">
          <label for="p-name">Nom et Prénom :</label>
          <input type="text" name="usr_np" class="form-control" id="p-name" autofocus="autofocus" required>
        </div>
        <div class="form-group col-md-12">
          <label for="p-email">Courrier électronique</label>
          <input type="email" name="usr_email" class="form-control" id="p-email" placeholder="you@yourcompany.com"  autofocus="autofocus" autocapitalize="off" required>
        </div>
        <div class="form-group col-md-12">
          <label for="p-sujet">Sujet : </label>
          <input type="text" name="usr_obj" class="form-control" id="p-sujet" required>
        </div>
        <div class="form-group col-md-12">
          <label for="p-message">Message : </label>
          <textarea class="form-control" rows="4" name="message" id="p-message" required></textarea>
        </div>
    
       
      <div class="form-group col-md-12">
        <button class="btn btn-info btn-block btn-lg iconfont icon-left" name="doct" type="submit" id="doct" value="envoyer">Envoyer votre message
        
        </button>
      </div>
      </div>
      <div class="p-signin-a__form-separator"><span>Sinon</span></div>

    
    <div class="p-signin__form-links">
        <div class="p-signin__form-link">
          <a href="./" class="link-info">Retour à la connexion</a>
        </div>
      </div>
    </div>
    
  </form>
    
  
</div>
</div>
<footer class="p-front__footer">
  <ul class="nav">
    <li class="nav-item">
    </li>
    <li class="nav-item">
    </li>
    <li class="nav-item">
    </li>
  </ul>
  <span>2018 &copy; EnergisData</span>
</footer>
<script src="page/vendor/jquery/jquery.min.js"></script>
<script src="page/vendor/popper/popper.min.js"></script>
<script src="page/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="page/vendor/select2/js/select2.full.min.js"></script>
<script src="page/vendor/simplebar/simplebar.js"></script>
<script src="page/vendor/text-avatar/jquery.textavatar.js"></script>
<script src="page/vendor/flatpickr/flatpickr.min.js"></script>
<script src="page/vendor/wnumb/wNumb.js"></script>
<script src="page/js/main.js"></script>
<div class="sidebar-mobile-overlay"></div>
</body>
</html>