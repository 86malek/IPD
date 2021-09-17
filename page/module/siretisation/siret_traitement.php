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
	
	$query = $bdd->prepare("SELECT count(*) FROM data_cat_synthese_siretisation WHERE id_cat_siretisation = :id AND niveau = 1 AND user_id = :user_id");
	$query->bindParam(":id", $id, PDO::PARAM_INT);
	$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
	$query->execute();
	$rowcount = $query->fetchColumn();
	$query->closeCursor();
	
	if ($rowcount == 0){
		
			$query = $bdd->prepare("INSERT INTO data_cat_synthese_siretisation SET user_name = :user_name, user_id = :user_id, statut_cat_fichier_siretisation = 2, id_cat_siretisation = :id, niveau = 1");
			$query->bindParam(":id", $id, PDO::PARAM_INT);
			$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->bindParam(":user_name", $_SESSION['user_name'], PDO::PARAM_STR);
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

			$query = $bdd->prepare("UPDATE data_cat_synthese_siretisation SET statut_cat_fichier_siretisation = 1, niveau = 2 WHERE id_cat_siretisation = :id_cat_siretisation AND user_id = :user_id");
			$query->bindParam(":id_cat_siretisation", $id, PDO::PARAM_INT);
			$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
}


$query = $bdd->prepare("SELECT * FROM data_cat_siretisation WHERE id_cat_siretisation = :id_cat_siretisation");
$query->bindParam(":id_cat_siretisation", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['nom_cat_siretisation'];

$datetime = date("Y-m-d");
$query_ligne_taiter = $bdd->prepare("SELECT count(*) FROM `data_siret` WHERE reporting <> 0 AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM data_cat_synthese_fiche_obj_siretisation WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
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
  <title>Sirétisation (DATA)</title>
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
<link rel="stylesheet" href="module/siretisation/table/css/layout_siret.css">
  

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
          <h2 class="content-heading"><?php echo $document; ?></h2>
          <div class="content-description">Objectif : <b><?php echo $ligne;?></b>  <?php if (checkAdmin()) { ?><?php }else{ echo '/ Réalisé : <b>'.$ligne_taiter.'</b>';}?></div>
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-3">
            
            <?php if (checkAdmin()) { ?>
            
            <a class="btn btn-success btn-sm icon-left mr-3" href="Siret">Retour aux statistique <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            
            <?php }else{ ?> 
				<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>                
                <form id="fin" method="post" action="SiretBiblio-fin-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="fin" value="fin">
                <button type="submit" form="fin" value="Submit" class="btn btn-warning icon-left btn-sm mr-3">Fin de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="SiretBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>    
                
                <?php }else{?>
                
                <form id="debut" method="post" action="SiretBiblio-debut-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="debut" value="debut">
                <button type="submit" form="debut" value="Submit" class="btn btn-success icon-left btn-sm mr-3">Débuter de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-info icon-left btn-sm mr-3" href="SiretBiblio">Retour à la liste<span class="btn-icon iconfont iconfont-info"></span></a>
                </form>                
                <?php }?>
            <?php }?>
            
            </div> 
            <div class="col-lg-9" style="text-align:right">
            Filtre par statut : 
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?> 
            <?php
			
			$query = $bdd->prepare("SELECT * FROM `data_siret` WHERE (id_cat_siretisation = :idcat AND user_id = :user_id) OR (id_cat_siretisation = :idcat AND user_id = 0) GROUP BY reporting");
			$query->bindParam(":idcat", $_GET['id'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();	
			while($query_qualif = $query->fetch()){
				
			if($query_qualif['reporting'] == 0){
				echo' <a class="btn btn-purple icon-right btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">EN ATTENTE</a>';			

			}elseif($query_qualif['reporting'] == 5){
				echo' <a class="btn btn-success icon-right  btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">OK</a>';
				
				
			}elseif($query_qualif['reporting'] == 2){
				echo' <a class="btn btn-info icon-right btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">Ste Etrangère</a>';
				
				
			}elseif($query_qualif['reporting'] == 1){
				echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">NT (non trouvé)</a>';
				
				
			}elseif($query_qualif['reporting'] == 3){
				echo' <a class="btn btn-danger icon-right  btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">Ste Fermée</a>';
				
				
			}elseif($query_qualif['reporting'] == 4){
				echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="SiretBiblioQalif-debut-'.$_GET['id'].'-'.$query_qualif['reporting'].'">En cours de liquidation</a>';
				
				
			}
                
                
                
            	
			
			}
            echo' <a class="btn btn-primary icon-right btn-sm mr-3" href="SiretBiblio-debut-'.$_GET['id'].'">Supp Filtre</a>';
			$query->closeCursor();						
			?>
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
						if(isset($_GET['reid'])){                        
                    echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'"  data-mode="'.$_GET['mode'].'" data-reporting="'.$_GET['reid'].'">';
						}else{
					echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'"  data-mode="'.$_GET['mode'].'" data-reporting="">';
						}
                    }else{
                        echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'" data-mode="">';
                    }
					
                }
                ?>
                <thead>
                	
                    <tr>
                    	<?php if (checkAdmin()) {?>
                        
                        <th></th>
                        <th>URL / RAISON SOCIALE</th>
                        <th>SIRET</th>
                        <th>STATUT</th>
                        <th>INTERVENANT</th>
                        <th>TRAITEMENT</th>
                        <th>ALERTE</th>
                        
                        <?php }else{ ?>
                        
                        <th>ALERTE</th>
                        <th>JOUR</th>
                        <th>STATUT</th>
                        <th>SIRET</th>
                        <th>URL / RAISON SOCIALE</th>
                        
                        
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
            <?php if (checkAdmin()) { ?>
            <form class="form add" id="form_company" data-id="">
            
            <div class="input_container">
                <label for="nature">COLLAB : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="user" name="user" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">URL / RAISON SOCIALE :</label>
                        <textarea rows="4" placeholder="..." class="form-control" id="url" name="url" disabled></textarea>
            </div>
            <div class="input_container">
                <label for="nature">SIRET :</label>
                    <div class="field_container">
                        <input type="text" maxlength="15" class="form-control" id="siret" name="siret" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
            	<label for="nature">STATUT :</label>
                    <div class="field_container">
                        	<select id="reporting" name="reporting" class="form-control" disabled>
                                <option value="0" selected>..</option>
                                <option value="5">OK</option>
                                <option value="1">NT (non trouvé)</option>
                                <option value="2">Ste Etrangère</option>
                                <option value="3">Ste Fermée</option>
                                <option value="4">En cours de liquidation</option>
                            </select>
                    </div>
            </div>           
            
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire"></textarea>
            </div>
            
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-secondary"></button>
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
                <label for="nature">URL / RAISON SOCIALE : <span class="required">*</span></label>
                    	<textarea rows="4" placeholder="..." class="form-control" id="url" name="url" disabled></textarea>
            </div>
           	
            <div class="input_container">
                <label for="nature">SIRET : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" maxlength="15" class="form-control" id="siret" name="siret">
                    </div>
            </div>
            
            <div class="input_container">
            	<label for="nature">STATUT : <span class="required">*</span></label>
                    <div class="field_container">
                        	<select id="reporting" name="reporting" class="form-control" required>
                                <option value="0" selected>..</option>
                                <option value="5">OK</option>
                                <option value="1">NT (non trouvé)</option>
                                <option value="2">Ste Etrangère</option>
                                <option value="3">Ste Fermée</option>
                                <option value="4">En cours de liquidation</option>
                            </select>
                    </div>
            </div>
            
            <hr>
            <!--<p style="color:#fe6f60">Cette section est à renseigner que si c'est une fiche "<b>SUPPRESSION</b>"</p>-->
            <hr>
            
             
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur ..." class="form-control" id="commentaire" name="commentaire" readonly></textarea>
            </div>
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-info"></button>
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
<script charset="utf-8" src="module/siretisation/table/js/webapp_siret_traitement_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/siretisation/table/js/webapp_siret_traitement.js"></script>
<?php }?>


<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
<script src="vendor/imaskjs/imask.min.js"></script>
<script src="vendor/card/card.js"></script>
<script src="js/preview/form-mask-input-siret.js"></script>
<div class="sidebar-mobile-overlay"></div> 

</body>
</html>