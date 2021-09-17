<?php
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
    if (file_exists("config/".$page) && $page != 'index.php') {
       include("config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}

session_start();

// On récupère l'IP du visiteur
// $ip = securite_bdd($db,$_SERVER['REMOTE_ADDR']);

$ip = '127.0.0.1';
$count = '';
  
// On regarde s'il est autorisé à se connecter

$query = $bdd->prepare("SELECT count(*) FROM `user_connexion_ip` WHERE user_ip = :user_ip AND date_ip = '".date("Y-m-d")."'");
$query->bindParam(":user_ip", $ip, PDO::PARAM_INT);
$query->execute();
$count = $query->fetchColumn();
$query->closeCursor();
                  
// Si l'ip a essayé de se connecter moins de 10 fois ce jour là

if ($count < 100000000000){       

// Si la personne a déja essayé de se connecter 10 fois ce jour là

if(empty($_SESSION['user_id'])){
	
	$err = array();	
	foreach($_GET as $key => $value) {$get[$key] = filter($value);}
	
	if (isset($_POST['doLogin']) && $_POST['doLogin'] == 'Connexion')
	{
		sleep(1); // Une pause de 1 sec
		foreach($_POST as $key => $value) {$data[$key] = filter($value);}
	
		$user_email = $data['usr_email'];
		// On supprime les retour à la ligne
		$user_email = str_replace(array("\n","\r",PHP_EOL),'',$user_email);

		$pass = $data['pwd'];	
	
		if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {$user_cond = "user_email = '$user_email'";}else{$err[] = "Adresse mail invalide";}		
		
		$query = $bdd->prepare("SELECT count(*) FROM `users` WHERE ".$user_cond." AND `banned` = '0'");
		$query->execute();
		$num = $query->fetchColumn();
		$query->closeCursor();
		
		if ( $num > 0 ) { 
			
			$query = $bdd->prepare("SELECT id, pwd, full_name, approved, user_level, user_email, equipe_id FROM users WHERE ".$user_cond." AND `banned` = '0'");
			$query->execute();
			$result = $query->fetch();
			$query->closeCursor();
		
			if($result['approved'] == 0) {$err[] = "Compte non actif"; }
		 
			if ($result['pwd'] === PwdHash($pass,substr($result['pwd'],0,9))) { 
		
				if(empty($err)){			
		
				   session_regenerate_id (true);

					$cookie_name = "ticket";
					// On génère quelque chose d'aléatoire
					$ticket = session_id().microtime().rand(1000000000, 8888888888);
					// on hash pour avoir quelque chose de propre qui aura toujours la même forme
					$ticket = hash('sha512', $ticket);
					// On enregistre des deux cotés
					setcookie($cookie_name, $ticket, time() + (60 * 20)); // Expire au bout de 20 min
					$_SESSION['ticket'] = $ticket;

					$_SESSION['user_id']= $result['id'];  
					$_SESSION['user_name'] = $result['full_name'];
					$_SESSION['user_level'] = $result['user_level'];
					$_SESSION['team'] = $result['equipe_id'];
					$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
				
					$stamp = time();
					$ckey = GenKey();
					
					$query = $bdd->prepare("UPDATE users SET ctime = :ctime, users_ip = :users_ip, ckey = :ckey WHERE id = :id");
					$query->bindParam(":ctime", $stamp, PDO::PARAM_STR);
					$query->bindParam(":users_ip", $ip, PDO::PARAM_STR);
					$query->bindParam(":ckey", $ckey, PDO::PARAM_STR);
					$query->bindParam(":id", $result['id'], PDO::PARAM_INT);
					$query->execute();
					$query->closeCursor();
						
						if(isset($_POST['remember'])){
							  setcookie("user_id", $_SESSION['user_id'], time()+COOKIE_TIME_OUT, '/', null, false, true);
							  setcookie("user_key", sha1($ckey), time()+COOKIE_TIME_OUT, '/', null, false, true);
							  setcookie("user_name",$_SESSION['user_name'], time()+COOKIE_TIME_OUT, '/', null, false, true);
							  setcookie("team", $_SESSION['team'], time()+COOKIE_TIME_OUT, '/', null, false, true);
						}
						
				 	header("Location: page/TableadeBord");
				 }
			 
			}else{
				
					$query = $bdd->prepare("INSERT INTO user_connexion_ip (`user_ip`, `date_ip`) VALUES (:user_ip, now())");
					$query->bindParam(":user_ip", $ip, PDO::PARAM_INT);
					$query->execute();
					$query->closeCursor();
				
				$err[] = "Mot de passe invalide";
			}
			
		}else{$err[] = "Aucun utilisateur trouvé / Compte bloqué";}	
	}
		
}else{header("Location: page/TableadeBord");}
}else {
    $err[] = "Désolé vous êtes banni jusqu'à demain";
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
  <title>Connexion | ENERGISDATA1</title>
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

<?php if(!empty($err)){echo '<div class="alert alert-warning" role="alert"><span class="alert-icon iconfont iconfont-info"></span>';foreach ($err as $e) {echo $e."<br>";}echo '</div>';}?> 

  <form class="p-signin__form" action="index.php" method="post" name="logForm" id="logForm">
  
    <h2 class="p-signin__form-heading"><img src="page/img/logo/LogoEnr.png" alt="" width="170"></h2>
    <div class="p-signin__form-content">
      	<p class="p-signin-a__form-description">
		Connectez-vous sur votre compte :
        </p>
       
        <div class="p-signin-a__form-separator"><span>Identifiants</span></div>
        <div class="row">
        <div class="form-group col-md-12">
          <label for="p-signin-work-email">Courrier électronique</label>
          <input type="email" name="usr_email" class="form-control" id="p-signin-work-email" placeholder="you@yourcompany.com"  autofocus="autofocus" autocapitalize="off" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12">
          <label for="p-signin-set-password">Mot de passe</label>
          <input name="pwd" type="password" class="form-control" id="p-signin-set-password" placeholder="..." required>
        </div>
      
        <div class="form-group col-md-12">
          <label class="custom-control custom-checkbox">
            <input  name="remember" type="checkbox" id="remember" value="1" class="custom-control-input">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Se souvenir de moi ?</span>
          </label>
        </div>
       
          <div  class="form-group col-md-12">
            <button class="btn btn-info btn-block btn-lg iconfont icon-left " name="doLogin" type="submit" id="doLogin3" value="Connexion">Connexion
            
            </button>
          </div>
		
        <!--<div class="form-group col-md-12">
          <button class="btn btn-primary btn-block iconfont icon-left btn-lg"  name="doLoginQ" type="submit" id="doLoginQ" value="ConnexionQ">
            Section Qualité (EN COURS)
          </button>
        </div>-->
    </div>
    <div class="p-signin-a__form-separator"><span>Aides | Informations</span></div>
    <div class="p-signin__form-links">
        <div class="p-signin__form-link">
          <a href="Contact" class="link-info">Contactez un administrateur</a>
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