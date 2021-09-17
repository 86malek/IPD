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
	
	$query = $bdd->prepare("SELECT count(*) FROM client_cat_synthese WHERE id_cat = :id AND niveau = 1 AND id_intervenant_cat = :user_id");
	$query->bindParam(":id", $id, PDO::PARAM_INT);
	$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
	$query->execute();
	$rowcount = $query->fetchColumn();
	$query->closeCursor();
	
	if ($rowcount == 0){
		
			$query = $bdd->prepare("INSERT INTO client_cat_synthese SET intervenant_cat = :intervenant_cat, id_intervenant_cat = :id_intervenant_cat, statut_cat_fichier = 2, id_cat = :id, niveau = 1");
			$query->bindParam(":id", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->bindParam(":intervenant_cat", $_SESSION['user_name'], PDO::PARAM_STR);
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

			$query = $bdd->prepare("UPDATE client_cat_synthese SET statut_cat_fichier = 1, niveau = 2 WHERE id_cat = :id_cat AND id_intervenant_cat = :id_intervenant_cat");
			$query->bindParam(":id_cat", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
}


$query = $bdd->prepare("SELECT * FROM client_cat WHERE id_cat = :id_cat");
$query->bindParam(":id_cat", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['nom_cat'];

$datetime = date("Y-m-d");
$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting <> 0 AND (n_stat_contact = 3 OR n_stat_contact = 11 OR n_stat_contact = 12) AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":id_cat", $id, PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM client_cat_synthese_fiche_obj WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
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
  <title>TRAITEMENT CLIENT</title>
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

<link rel="stylesheet" href="module/client/table/css/layout_client.css">
  

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
            <div class="col-lg-3">
            <?php if (checkAdmin()) { ?>
            <a class="btn btn-success icon-left mr-3" href="Client">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <?php }else{ ?> 
				<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>                
                <form id="fin" method="post" action="ClientBiblio-fin-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="fin" value="fin">
                <button type="submit" form="fin" value="Submit" class="btn btn-warning icon-left btn-sm mr-3">Fin<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-success icon-left mr-3" href="ClientBiblio">Retour <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
                </form>    
                
                <?php }else{?>
                
                <form id="debut" method="post" action="ClientBiblio-debut-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="debut" value="debut">
                <button type="submit" form="debut" value="Submit" class="btn btn-success icon-left btn-sm mr-3">Début<span class="btn-icon iconfont iconfont-info"></span></button>
                <a class="btn btn-success icon-left mr-3" href="ClientBiblio">Retour <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
                </form>                
                <?php }?>
            <?php }?>
            
            </div> 
            <div class="col-lg-6" style="text-align:center">
            <?php
			$query = $bdd->prepare("SELECT * FROM `client_traitement` WHERE id_cat = :idcat AND user_id_contact = :user_id_contact GROUP BY n_stat_contact");
			$query->bindParam(":idcat", $_GET['id'], PDO::PARAM_INT);
			$query->bindParam(":user_id_contact", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();	
			while($query_qualif = $query->fetch()){
				
			if($query_qualif['reporting_contact'] == 0 && $query_qualif['n_stat_contact'] == 0){
			echo' <a class="btn btn-warning icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">EN ATTENTE</a>';			

			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 1){
				echo' <a class="btn btn-warning icon-right  btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">Non Vérifié</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 2){
				echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">A quitté</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 3){
				echo' <a class="btn btn-success icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">OK</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 4){
				echo' <a class="btn btn-success icon-right  btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">OK avec modif</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 5){
				echo' <a class="btn btn-info icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">Remplacé</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 6){
				echo' <a class="btn btn-warning icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">HORS CIBLE</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 7){
				echo' <a class="btn btn-success icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">AJOUT</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 8){
				echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">Refus</a>';
				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 9){
				echo' <a class="btn btn-warning icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">NRP</a>';				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 10){
				echo' <a class="btn btn-info icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">EN COURS</a>';				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 11){
				echo' <a class="btn btn-success icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">OK / En charge du Transport</a>';				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 12){
				echo' <a class="btn btn-success icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">OK / Prise en charge externe</a>';				
				
			}elseif($query_qualif['reporting_contact'] == 1 && $query_qualif['n_stat_contact'] == 13){
                echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">KO</a>';              
                
            }else{
				echo' <a class="btn btn-danger icon-right btn-sm mr-3" href="ClientBiblioContactQalif-'.$_GET['id'].'-'.$query_qualif['n_stat_contact'].'">INDISPONIBLE</a>';
				
			}	
			
			}
			$query->closeCursor();						
			?>
            
            </div>
            <div class="col-lg-3" style="text-align:right">
            <a class="btn btn-primary icon-right mr-3" href="javascript:window.location.reload()">Rafraîchir <span class="btn-icon iconfont iconfont-refresh"></span></a>
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
                        <th></th>

                        <th>ALERTE</th>
                        <th></th> 
                        <th>RS</th>
                        <th>AD1</th>
                        <th>AD2</th>
                        <th>AD3</th>
                        <th>CP</th>
                        <th>VILLE</th>
                        <th>TEL</th>
                        <th>FAX</th>
                        <th>SIRET</th>
                        <th>EFFECTIF SITE</th>     
                        <th>EFFECTIF GROUPE</th> 
                        <th>CA</th> 

                        <th></th>
                        <th></th>
                        <th></th>
                        
                        <?php }else{ ?>

                        <th>ALERTE</th>
                        <th>SOCIÉTÉ</th>
                        <th>CONTACT</th> 
                        <th>RS</th>
                        <th>AD1</th>
                        <th>AD2</th>
                        <th>AD3</th>
                        <th>CP</th>
                        <th>VILLE</th>
                        <th>TEL</th>
                        <th>FAX</th>
                        <th>SIRET</th>
                        <th>New SIRET</th>
                        <th>EFFECTIF SITE</th>     
                        <th>EFFECTIF GROUPE</th> 
                        <th>CA</th>    
                                         
                        
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
                    <label for="nature">RAISON SOCIALE ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="rs_o" name="rs_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 1 ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad1_o" name="ad1_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 2 ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad2_o" name="ad2_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 3 ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad3_o" name="ad3_o" disabled>
                    </div>
            </div>
            
            
            <div class="input_container">
                    <label for="nature">CP ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="cp_o" name="cp_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">VILLE ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville_o" name="ville_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">TÉL ORIGINAL : <span class="required">*</span></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel_o" name="tel_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">FAX ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fax_o" name="fax_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">SIRET ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="siret_o" name="siret_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">EFFECTIF SITE ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="esite_o" name="esite_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">EFFECTIF GROUPE ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="egroupe_o" name="egroupe_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">CA ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ca_o" name="ca_o" disabled>
                    </div>
            </div>
            <hr style="color:#F00; font-weight:bolder">
            <div class="input_container">
                <label for="nature" style="color:#F00; font-weight:bolder">STATUT : </label>
                    <div class="field_container">
                        <input type="text" class="form-control form-control-danger" id="stat" name="stat">
                    </div>
            </div>
            <hr style="color:#F00; font-weight:bolder">
            <br>
            <hr>
            <center><p><b>Partie traitement :</b></p></center>
            <hr>
            
            <div class="input_container">
                <label for="nature">NEW RAISON SOCIALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="rs" name="rs" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW ADRESSE 1 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad1" name="ad1" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW ADRESSE 2 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad2" name="ad2" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">NEW ADRESSE 3 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad33" name="ad33" disabled>
                    </div>
            </div> 
            
                     
            <div class="input_container">
                <label for="nature">NEW CODE POSTAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" maxlength="5" id="cp" name="cp" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">NEW VILLE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville" name="ville" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW TÉLÉPHONE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel" name="tel" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW FAX :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fax" name="fax" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW SIRET :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="siret" name="siret" maxlength="14" disabled>
                    </div>
            </div>              
             
            <div class="input_container">
                <label for="nature">NEW EFFECTIF SITE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="esite" name="esite" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE SITE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t1" name="t1" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">NEW EFFECTIF GROUPE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="egroupe" name="egroupe" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE GROUPE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t2" name="t2" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">NEW EFFECTIF NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="enat" name="enat" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t3" name="t3" disabled>
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">NEW CA : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ca" name="ca" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW CA TRANCHE: </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="catt" name="catt" disabled>
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
                        <input type="hidden" class="form-control" id="user" name="user" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                    </div>
            </div>
            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                    </div>
            </div>
            <input type="hidden" class="form-control" id="siret_o" name="siret_o" readonly>
            <input type="hidden" class="form-control" id="rs_o" name="rs_o" readonly>
            
            
            <div class="input_container">
            		<label for="nature">RAISON SOCIALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="rs" name="rs">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">ADRESSE 1 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad1" name="ad1">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">ADRESSE 2 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad2" name="ad2">
                        
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">ADRESSE 3 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad33" name="ad33">
                    </div>
            </div>
            
            
            <div class="input_container">
            		<label for="nature">CP :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" maxlength="5" id="cp" name="cp">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">VILLE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville" name="ville">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">TÉL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel" name="tel">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">FAX :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fax" name="fax">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">SIRET : <strong></strong></label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="siret" name="siret" maxlength="14"  minlength="14">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">EFFECTIF SITE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="esite" name="esite" placeholder="">
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">TRANCHE SITE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t1" name="t1" placeholder="">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">EFFECTIF GROUPE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="egroupe" name="egroupe">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">TRANCHE GROUPE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t2" name="t2">
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">EFFECTIF NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="enat" name="enat">
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">TRANCHE EFFECTIF NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="t3" name="t3">
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">CA :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ca" name="ca">
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">TRANCHE CA :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="catt" name="catt">
                    </div>
            </div>
            <br>
            <hr>
            <center><p><b style="color:#F00;">Société Fermée :</b></p></center>
            <hr>
            <div class="input_container">
                <label for="nature" style="color:#F00; font-weight:bolder">STATUT : </label>
                    <div class="field_container">
                        <input type="text" class="form-control form-control-danger" id="stat" name="stat">
                    </div>
            </div> 
            
            <hr>
            <center><p><b>Partie Administrateur :</b></p></center>
            <hr>
            
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire" readonly></textarea>
            </div>
            <hr>
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
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement.js"></script>
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
<script src="js/preview/form-mask-input.js"></script>
<div class="sidebar-mobile-overlay"></div> 

</body>
</html>