<?php 
$page = '';
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
if($_GET['id'] == ''){$id = "";}else{$id = $_GET['id'];}

if(!empty($_POST['debut']) && $_POST['debut'] == 'debut'){
	
	
				
			$query = $bdd->prepare("SELECT count(*) FROM hb_cat_synthese_acide_details WHERE id_cat_acide = :id_cat_acide AND actif = 0 AND id_intervenant_cat_acide = :id_intervenant_cat_acide ORDER BY id_cat_synthese_acide DESC LIMIT 1");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowcountjour = $query->fetchColumn();
			$query->closeCursor();
					
	
	
	if ($rowcountjour == 0){
		
			$query = $bdd->prepare("INSERT INTO hb_cat_synthese_acide_details SET id_intervenant_cat_acide = :id_intervenant_cat_acide, intervenant_cat_acide = :intervenant_cat_acide, id_cat_acide = :id_cat_acide, date_debut_traitement = now(), date_calcul = now(), actif = 0");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->bindParam(":intervenant_cat_acide", $_SESSION['user_name'], PDO::PARAM_STR);
			$query->execute();
			$query->closeCursor();
			
	}
	
			$query = $bdd->prepare("SELECT count(*) FROM hb_cat_synthese_acide WHERE id_cat_acide = :id_cat_acide AND date_debut_traitement <> '0000-00-00 00:00:00' AND id_intervenant_cat_acide = :id_intervenant_cat_acide");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowcount = $query->fetchColumn();
			$query->closeCursor();
	
	if ($rowcount == 0){
			$query = $bdd->prepare("INSERT INTO hb_cat_synthese_acide SET `intervenant_cat_acide` = :intervenant_cat_acide, id_intervenant_cat_acide = :id_intervenant_cat_acide, statut_cat_fichier = 'En cours', id_cat_acide = :id_cat_acide, date_debut_traitement = now(), niveau = 1");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->bindParam(":intervenant_cat_acide", $_SESSION['user_name'], PDO::PARAM_STR);
			$query->execute();
			$query->closeCursor();

	}else{
			$query = $bdd->prepare("UPDATE hb_cat_synthese_acide SET statut_cat_fichier = 'En cours', niveau = 1 WHERE id_cat_acide = :id_cat_acide AND id_intervenant_cat_acide = :id_intervenant_cat_acide");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();	
	}

}elseif(!empty($_POST['fin']) && $_POST['fin'] == 'fin'){

			$query = $bdd->prepare("UPDATE hb_cat_synthese_acide SET `date_fin_traitement` = now(), `statut_cat_fichier` = 'Cloturer', `niveau` = 2 WHERE id_cat_acide = :id_cat_acide AND `id_intervenant_cat_acide` = :id_intervenant_cat_acide");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
			
			$query = $bdd->prepare("UPDATE hb_cat_synthese_acide_details SET `date_fin_traitement` = now(), `actif` = 1 WHERE id_cat_acide = :id_cat_acide AND `id_intervenant_cat_acide` = :id_intervenant_cat_acide ORDER BY id_cat_synthese_acide DESC LIMIT 1");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
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
  <title>Hard Bounce (HB)</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/layout_global.css">
  

<script src="js/ie.assign.fix.min.js"></script>
  
</head>
<body class="js-loading  sidebar-md">

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
          <h2 class="content-heading">Traitement : <b>Cartes Visites (LK)</b></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-12">
            <?php if (checkAdmin()) { ?>
            <a class="btn btn-success icon-left mr-3" href="HB">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <?php }else{ ?> 
				<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>
                
                <form id="fin" method="post" action="HBBiblio-fin-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="fin" value="fin">
                <button type="submit" form="fin" value="Submit" class="btn btn-warning icon-left btn-sm mr-3">Fin de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="HBBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>            
                
                <?php }else{?>
                
                <form id="debut" method="post" action="HBBiblio-debut-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="debut" value="debut">
                <button type="submit" form="debut" value="Submit" class="btn btn-success icon-left btn-sm mr-3">Débuter de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="HBBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>                
                <?php }?>
            <?php }?>
            
            </div>           
            </div>
            
            </div>
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">
            
				<?php 
				if (checkAdmin()) {
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-mode="">';
                }else{
                    if(isset($_GET['mode'])){                        
                    echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'"  data-mode="'.$_GET['mode'].'">';
                    }else{
                        echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'" data-mode="">';
                    }
                }
                ?>
                <thead>
                	
                    <tr>
                        <th>Raison<br>Sociale</th>
                        <th>Code<br>postal</th>
                        <th>Ville</th>
                        <th>Siret</th>
                        <th>ID<br>contact</th>
                        <th>Civilité</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>ID société<br>contact</th>
                        <th>ID société</th>
                        <th>Ref<br>Fonction</th>
                        <th>Fonction</th>
                        <th>Code<br>Fonction</th>
                        <th>Fonction Exacte</th>
                        <th>Email</th>
                        <th>Email<br>Collecte</th>
                        <th>Action</th>
                    </tr>
                    
                </thead>
                
                <tbody></tbody>
                
            </table>
                                             
            </div>
            </div>                                
            </div>
            </div>
                                    
                                    
        </div>
  	</div>


</div>

<div class="lightbox_bg"></div>   
    

<div class="lightbox_container">
  <div class="lightbox_close"></div>
  <div class="lightbox_content">
        
			<h2></h2>
            
            <form class="form add" id="form_company" data-id="">
            
            <div class="input_container">
                    <div class="field_container">
                        <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                    </div>
            </div>
                
            <div class="input_container">
                    <div class="field_container">
                        <input type="text" class="form-control" id="user" name="user" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">Email : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="email" class="form-control" id="email" name="email" readonly>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">Email Collecte :</label>
                    <div class="field_container">
                        <input type="email" class="form-control" id="email_collecte" name="email_collecte">
                    </div>
            </div>             
              
            <div class="input_container">
            <label for="reporting">Statut reporting : <span class="required">*</span></label>
            <div class="field_container">
                <select id="reporting" name="reporting" class="form-control" required>
                    <option value="" selected></option>
                    <option value="1">OK</option>
                    <option value="2">Modification OK</option>
                    <option value="3">Doublant</option>
                    <option value="4">Ajout</option>
                </select>
            </div>
            </div>        
            
            
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-secondary"></button>
            </div>
            </form>
			
		</div>
	</div>
	<div id="message_container">
		<div class="success" id="message">
			<p>Opération réussie.</p>
		</div>
	</div>
	<div id="loading_container">
		<div id="loading_container2">
			<div id="loading_container3">
				<div id="loading_container4">
					Chargement...
				</div>
			</div>
		</div>
	</div>
    
    
<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script charset="utf-8" src="js/jquery.validate.min.js"></script>
<script charset="utf-8" src="module/hb/table/js/webapp_acide_traitement_hb.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>

<div class="sidebar-mobile-overlay"></div>  
</body>
</html>