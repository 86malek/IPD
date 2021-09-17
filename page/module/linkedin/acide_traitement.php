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

/*$query = $bdd->prepare("SELECT collect_lot_nom, collect_lot_objectif FROM collectivite_lot WHERE collect_lot_id = :collect_lot_id");
$query->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
$query->execute();
$query_titre = $query->fetch();
$query->closeCursor();
$datetime = date("Y-m-d");
$query_ligne_taiter_lot = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche` WHERE collect_lot_id = :collect_lot_id AND collect_fiche_statut <> 0 AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter_lot->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
$query_ligne_taiter_lot->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter_lot->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter_lot->execute();
$ligne_taiter_lot = $query_ligne_taiter_lot->fetchColumn();
$query_ligne_taiter_lot->closeCursor();*/
			
if(!empty($_POST['debut']) && $_POST['debut'] == 'debut'){
	
	$query = $bdd->prepare("SELECT count(*) FROM cat_synthese_acide WHERE id_cat_acide = :id AND niveau = 1 AND id_intervenant_cat_acide = :user_id");
	$query->bindParam(":id", $id, PDO::PARAM_INT);
	$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
	$query->execute();
	$rowcount = $query->fetchColumn();
	$query->closeCursor();
	
	if ($rowcount == 0){
		
			$query = $bdd->prepare("INSERT INTO cat_synthese_acide SET intervenant_cat_acide = :intervenant_cat_acide, id_intervenant_cat_acide = :id_intervenant_cat_acide, statut_cat_fichier = 2, id_cat_acide = :id, niveau = 1");
			$query->bindParam(":id", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->bindParam(":intervenant_cat_acide", $_SESSION['user_name'], PDO::PARAM_STR);
			$query->execute();
			$query->closeCursor();

	}else{
			/*$query = $bdd->prepare("UPDATE collectivite_lot_synthese SET collect_lot_synthese_statut = 2, niveau = 1 WHERE collect_lot_id = :collect_lot_id AND collect_lot_synthese_id_intervenant = :collect_lot_synthese_id_intervenant");
			$query->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
			$query->bindParam(":collect_lot_synthese_id_intervenant", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();*/	
	}

}elseif(!empty($_POST['fin']) && $_POST['fin'] == 'fin'){

			$query = $bdd->prepare("UPDATE cat_synthese_acide SET statut_cat_fichier = 1, niveau = 2 WHERE id_cat_acide = :id_cat_acide AND id_intervenant_cat_acide = :id_intervenant_cat_acide");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
}


$query = $bdd->prepare("SELECT * FROM cat_acide WHERE id_cat_acide = :id_cat_acide");
$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['nom_cat_acide'];

$datetime = date("Y-m-d");
$query_ligne_taiter = $bdd->prepare("SELECT count(*) FROM `acide` WHERE reporting <> 0 AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM cat_synthese_fiche_obj WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$ligne = $donnees['nbligne_objectif'];
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
  <title>CARTES VISITES (LK)</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">
<link rel="stylesheet" href="vendor/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="module/linkedin/table/css/layout_lk.css">
  

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
          <h2 class="content-heading">Traitement : <b><?php echo $document; ?></b> / Objectif : <b><?php echo $ligne;?></b>  <?php if (checkAdmin()) { ?><?php }else{ echo '/ Réalisé : <b>'.$ligne_taiter.'</b>';}?></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <?php if (checkAdmin()) { ?>
            <a class="btn btn-success icon-left btn-sm mr-3" href="Linkedin">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <?php }else{ ?> 
				<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>                
                <form id="fin" method="post" action="LinkedinBiblio-fin-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="fin" value="fin">
                <button type="submit" form="fin" value="Submit" class="btn btn-warning icon-left btn-sm mr-3">Fin de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="LinkedinBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>    
                
                <?php }else{?>
                
                <form id="debut" method="post" action="LinkedinBiblio-debut-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="debut" value="debut">
                <button type="submit" form="debut" value="Submit" class="btn btn-success icon-left btn-sm mr-3">Débuter de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="LinkedinBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>                
                <?php }?>
            <?php }?>
            
            </div> 
            <div class="col-lg-6" style="text-align:right">
            <a class="btn btn-primary icon-right btn-sm mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
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
                    	<?php if (checkAdmin()) {?>
                        <th>ADMIN</th>
                        <th>Raison<br>Sociale</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Fonction</th>
                        <th>URL</th>
                        <th>NEW RS</th>
                        <th>SIRET</th>
                        <th>NT</th>
                        <th>QUALIF</th>
                        <th>INTERVENANT</th>
                        <th>TRAITEMENT</th>
                        <th>ALERTE</th>
                        <?php }else{ ?>
                        <th>ADMIN</th>
                        <th>STATUT</th>
                        <th>Raison<br>Sociale</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>URL</th>
                        <th>New Poste</th>
                        <th>Old Poste</th>
                        <th>New<br>Entreprise</th>
                        <th>Old<br>Entreprise</th>
                        <th>NT</th>
                        <th>SIRET</th>                        
                        
                        <?php }?>
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
            <hr>
            <?php if (checkAdmin()) { ?>

            <form class="form add" id="form_company" data-id="">

            <div class="input_container">
            <label for="title">CIVILITÉ :</label>
            <div class="field_container">
                <select id="title" name="title" class="form-control" disabled>
                    <option value="" selected></option>
                    <option value="M">M.</option>
                    <option value="MME">Mme</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
                <label for="prenom">PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="prenom" name="prenom" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nom">NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nom" name="nom" disabled>
                    </div>
            </div>  
            
                     
            <div class="input_container">
                <label for="url">LINKEDIN :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="url" name="url" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="ville">VILLE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville" name="ville" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="cp">CODE POSTAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="cp" name="cp" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="raison">RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="raison" name="raison" disabled>
                    </div>
            </div>              
             
             <div class="input_container">
                <label for="ids">ID SOCIÉTÉ : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ids" name="ids" disabled>
                    </div>
            </div>
             
            <div class="input_container">
            <label for="reporting">STATUT :</label>
            <div class="field_container">
                <select id="reporting" name="reporting" class="form-control" disabled>
                    <option value="0" selected>CHOISIR UN STATUT</option>
                    <option value="1">OK</option>
                    <option value="2">MODIFICATION OK</option>
                    <option value="3">SUPPRESSION</option>
                    <option value="4">AJOUT</option>
                    <option value="5">DOUBLONS</option>
                </select>
            </div>
            </div> 
            
            <hr>
            
            <div class="input_container">
                <label for="nt">NT :</label>
                    <div class="field_container">
                        	<select id="nt" name="nt" class="form-control" disabled>
                                <option value="0" selected>..</option>
                                <option value="1">SOCIÉTÉ NT</option>
                            </select>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="newe">NOUVELLE ENTREPRISE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newe" name="newe" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nfonction">NOUVELLE FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nfonction" name="nfonction" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="siret">SIRET :</label>
                    <div class="field_container">
                        <input type="text" maxlength="14" class="form-control" id="siret" name="siret" disabled>
                    </div>
            </div>
            <hr> 
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire"></textarea>
            </div>
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-info"></button>
            </div>
            </form>
            <!---------------------------------------->
            <?php }else{ ?>
            <form class="form add" id="form_company" data-id="">
            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="debut" name="debut" readonly>
                    </div>
            </div>
            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="lot" name="lot" value="<?php echo $_GET['id']; ?>" readonly>
                    </div>
            </div> 
            
            <div class="input_container">
                    <div class="field_container">
                        <input type="text" class="form-control" id="user" name="user" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                    </div>
            </div>

            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                    </div>
            </div>

            <div class="input_container">
            <label for="reporting">CIVILITÉ : <span class="required">*</span></label>
            <div class="field_container">
                <select id="title" name="title" class="form-control" required>
                    <option value="" selected></option>
                    <option value="M">M.</option>
                    <option value="MME">Mme</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
                <label for="nature">PRÉNOM : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NOM : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
            </div>  
            
                     
            <div class="input_container">
                <label for="nature">LINKEDIN : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="url" name="url" required>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">VILLE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville" name="ville" readonly>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">CODE POSTAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="cp" name="cp" readonly>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="raison" name="raison" readonly>
                    </div>
            </div>              
             
             <div class="input_container">
                <label for="nature">ID SOCIÉTÉ : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ids" name="ids" readonly>
                    </div>
            </div>
             
            <div class="input_container">
            <label for="reporting">STATUT : <span class="required">*</span></label>
            <div class="field_container">
                <select id="reporting" name="reporting" class="form-control" required>
                    <option value="0" selected>CHOISIR UN STATUT</option>
                    <option value="1">OK</option>
                    <option value="2">MODIFICATION OK</option>
                    <option value="3">SUPPRESSION</option>
                    <option value="4">AJOUT</option>
                    <option value="5">DOUBLONS</option>
                </select>
            </div>
            </div>
            <hr>
            <div class="input_container">
                    <div class="field_container">
                        	<select id="nt" name="nt" class="form-control" required>
                                <option value="0" selected>..</option>
                                <option value="1">SOCIÉTÉ NT</option>
                            </select>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NOUVELLE ENTREPRISE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newe" name="newe">
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">NOUVELLE FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nfonction" name="nfonction">
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">SIRET :</label>
                    <div class="field_container">
                        <input type="text" maxlength="14" class="form-control" id="siret" name="siret">
                    </div>
            </div> 
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire" readonly></textarea>
            </div>
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-secondary"></button>
            </div>
            </form>
            <?php } ?>
			
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

<?php if (checkAdmin()) {?>
<script charset="utf-8" src="module/linkedin/table/js/webapp_acide_traitement_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/linkedin/table/js/webapp_acide_traitement.js"></script>
<?php }?>


<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
<div class="sidebar-mobile-overlay"></div> 

</body>
</html>