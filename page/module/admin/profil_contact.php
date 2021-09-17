<?php 
$page = '';
$id = '';
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
    if (file_exists("../../../config/".$page) && $page != 'index.php') {
       include("../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();

$err = array();
$msg = array();

if(!empty($_POST['doUpdate']))  
{

/*$query = $bdd->prepare("SELECT pwd FROM users WHERE id = :user_id");
$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query->execute();
$old = $query->fetch();
$query->closeCursor();

$old_salt = substr($old['pwd'],0,9);

	if($old === PwdHash($_POST['pwd_new'],$old_salt))
	{*/
		if(passComplex($_POST['pwd_new']) == true){
		$newsha1 = PwdHash($_POST['pwd_new']);
		
		
		$query = $bdd->prepare("UPDATE users SET pwd = :pwd WHERE id = :user_id");
		$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
		$query->bindParam(":pwd", $newsha1, PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$msg[] = "Changement de mot de passe effectué avec succès";
		}else{
		
		 $err[] = "Mot de passe non conforme.";
		 
		}
	/*}else{
		
	 $err[] = "Ancien mot de passe vide ou erroné<br>Merci de le renseigner.";
	 
	}*/

}

$query = $bdd->prepare("SELECT * FROM users WHERE id = :user_id");
$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query->execute();
$row_settings = $query->fetch();
$query->closeCursor();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title><?php echo $_SESSION['user_name'];?></title>
  <link rel="shortcut icon" href="img/logo/logop.ico">  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">
<script src="js/ie.assign.fix.min.js"></script>
  
</head>
<body class="js-loading sidebar-md">

<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="img/logo/LogoEnr.png" alt="" width="200"></div>
  </div>
</div>


<?php
$page = '';
if (empty($page)) {
 $page = "top";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/include/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../include/".$page) && $page != 'index.php') {
       include("../../include/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
?>

<div class="page-wrap">
  
<?php
$page = '';
if (empty($page)) {
 $page = "sidebar";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/include/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../include/".$page) && $page != 'index.php') {
       include("../../include/".$page); 
    }

    else {
        echo "Page inexistantes !";
    }
}

?>

  

  <div class="page-content">
    
<div class="container-fluid">
  <div class="main-container">
  <div class="container-block">
	<h2 class="container-block__heading"><?php echo $_SESSION['user_name'];?></h2>
  </div> 
  <div class="container-block">
  <div class="row">
        <div class="col-6">
       
        <form action="" method="" name="" id="">
        
        <div class="form-group">
        <label for="read-only">NOM ET PRÉNOM</label>
        <input type="text" class="form-control" name="" id="" value="<?php echo $row_settings['full_name']; ?>" size="50" readonly>
        </div>
        
        <div class="form-group">
        <label for="read-only">EMAIL / LOG DE CONNEXION</label>
        <input value="<?php echo $row_settings['user_email']; ?>" name="" id="read-only" type="text" placeholder="" class="form-control" readonly>
        </div>
        
        </form>
        </div>
        
        <div class="col-6">
        
        <h3>Modification du mot de passe</h3> 
        <?php
		if(!empty($msg))  {
		echo '<div class="alert alert-message alert-message-gray" role="alert"><div class="alert-message-heading">Information</div><p>' . $msg[0] . '</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>';
		
		}
		?>      
        <?php	
		if(!empty($err))  {
		echo '<div class="alert alert-message alert-message-orange" role="alert"><div class="alert-message-heading">Information</div><p>';
		foreach ($err as $e) {
		echo "$e <br>";
		}
		echo '</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>';	
		}
		?>
        <form name="" id="" method="post" action="PUsers">
		
        
        <div class="input-group">
        <input name="pwd_new" type="password" id="pwd_new" class="form-control" minlength="6" pattern=".{6,}" placeholder=".6 caracteres minimum">
        </div>
        
        
        <div class="form-group">
        <button class="btn btn-primary btn-sm mb-2 mr-3 icon-right" name="doUpdate" type="submit" id="doUpdate"  value="Modifier">Enregistrement le nouveau mot de passe <span class="btn-icon iconfont iconfont-envelope"></span></button>
        </div>
        
        </form>
        </div>
        </div>
  </div>        
  </div>
</div>
  </div>
</div>


<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="vendor/sparkline/jquery.sparkline.min.js"></script>
<script src="js/preview/default-dashboard.min.js"></script>

<div class="sidebar-mobile-overlay"></div>  
</body>
</html>