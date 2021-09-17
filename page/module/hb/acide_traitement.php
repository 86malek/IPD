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
if (checkAdmin()) {
    $query = $bdd->prepare("SELECT * FROM hb_cat_acide WHERE id_cat_acide = :id_cat_acide");
$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();  
$document = $doc['nom_cat_acide'];
$datetime = date("Y-m-d");
$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM hb_cat_synthese_fiche_obj WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$ligne = $donnees['nbligne_objectif'];
$query->closeCursor();
}else{
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
	
	$query = $bdd->prepare("SELECT count(*) FROM hb_cat_synthese_acide WHERE id_cat_acide = :id AND niveau = 1 AND id_intervenant_cat_acide = :user_id");
	$query->bindParam(":id", $id, PDO::PARAM_INT);
	$query->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
	$query->execute();
	$rowcount = $query->fetchColumn();
	$query->closeCursor();
	
	if ($rowcount == 0){
		
			$query = $bdd->prepare("INSERT INTO hb_cat_synthese_acide SET intervenant_cat_acide = :intervenant_cat_acide, id_intervenant_cat_acide = :id_intervenant_cat_acide, statut_cat_fichier = 2, id_cat_acide = :id, niveau = 1");
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

			$query = $bdd->prepare("UPDATE hb_cat_synthese_acide SET statut_cat_fichier = 1, niveau = 2 WHERE id_cat_acide = :id_cat_acide AND id_intervenant_cat_acide = :id_intervenant_cat_acide");
			$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
			$query->bindParam(":id_intervenant_cat_acide", $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
}


$query = $bdd->prepare("SELECT * FROM hb_cat_acide WHERE id_cat_acide = :id_cat_acide");
$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['nom_cat_acide'];

$datetime = date("Y-m-d");

$query_ligne_taiter = $bdd->prepare("SELECT count(*) FROM `hb_acide` WHERE reporting = 1 AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter_fois_2 = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();


$query_ligne_taiter = $bdd->prepare("SELECT count(*) FROM `hb_acide` WHERE (reporting = 2 OR reporting = 3 OR reporting = 4 OR reporting = 5 OR reporting = 7 OR reporting = 8) AND user_id = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$total_traite = ($ligne_taiter_fois_2*2)+$ligne_taiter;

$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM hb_cat_synthese_fiche_obj WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$ligne = $donnees['nbligne_objectif'];
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
  <title>HARD BOUNCE (HB)</title>
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
<link rel="stylesheet" href="module/hb/table/css/layout_hb.css">
  

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
          <h2 class="content-heading">Traitement : <b><?php echo $document; ?></b> / Objectif : <b><?php echo $ligne;?></b>  <?php if (checkAdmin()) { ?><?php }else{ echo '/ Réalisé : <b>'.$total_traite.'</b>';}?></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <?php if (checkAdmin()) { ?>
            <a class="btn btn-success icon-left btn-sm mr-3" href="HB">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <?php }else{ ?> 
				<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>                
                <form id="fin" method="post" action="HBBiblio-fin-<?php echo $_GET['id']; ?>">
                <input type="hidden" name="fin" value="fin">
                <button type="submit" form="fin" value="Submit" class="btn btn-danger icon-left btn-sm mr-3">Fin de traitement<span class="btn-icon iconfont iconfont-info"></span></button>
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
            <div class="col-lg-6" style="text-align:right">            
            <a class="btn btn-primary icon-right btn-sm" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>           
            </div>
            
            </div>
            <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            
            <div class="col-lg-12" style="text-align:right">            
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?> 
            <a class="btn btn-info icon-right btn-lg " href="#" id="add_ligne">Ajouter une nouvelle ligne <span class="btn-icon iconfont iconfont-plus"></span></a>
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
                    	<?php if (checkAdmin()) {?>

                        <th>ADMIN</th>
                        
                        <th>QUALIF</th>

                        <th>CORRECTION</th>
                        <th>TÉLÉPHONE</th>

                        <th>NEW RAISON</th>
                        <th>NEW SIRET</th>

                        <th>NEW CIVILITÉ</th>
                        <th>NEW NOM</th>

                        <th>NEW PRÉNOM</th>
                        <th>NEW FONCTION</th>

                        <th>INTERVENANT</th>
                        <th>TRAITEMENT</th>
                        <th>ALERTE</th>
                        <th>RS</th>
                        <th>CODE</th>
                        <th>VILLE</th>
                        <th>SIRET</th>
                        <th>ID CONTACT</th>
                        <th>ID REF STATUT</th>
                        <th>STATUT</th>
                        <th>ID REF CIVILITÉ</th>
                        <th>CIVILITÉ</th>
                        <th>NOM</th>
                        <th>PRÉNOM</th>
                        <th>ID SOCIÉTÉ CONTACT</th>
                        <th>ID SOCIÉTÉ</th>
                        <th>ID REF FONCTION</th>
                        <th>FONCTION</th>
                        <th>CODE FONCTION</th>
                        <th>FONCTION EXACTE</th>
                        <th>EMAIL</th>
                        <th>EMAIL COLLECT</th>
                        <th>EMAIL ACTIF</th>
                        <th>DATE HB</th>
                        

                        <?php }else{ ?>

                        <th>ADMIN</th>
                        <th>STATUT</th>
                        <th>COEECTION</th>                        
                        <th>TÉLÉPHONE</th>

                        <th>RS</th>
                        <th>VILLE</th>
                        <th>SIRET</th>
                        <th>ID CONTACT</th>
                        <th>CIVILITÉ</th>
                        <th>NOM</th>
                        <th>PRÉNOM</th>
                        <th>ID SOCIÉTÉ</th>
                        <th>FONCTION</th>
                        <th>FONCTION EXACTE</th>
                        <th>EMAIL</th>
                        
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
                <label for="nature">COLLAB :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="user_name" name="user_name" disabled>
                    </div>
            </div>

            <div class="input_container">
                <label for="nature">RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="raison" name="raison" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="newraison" style="color: red">NOUVELLE RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newraison" name="newraison" style="border: 1px dotted red" disabled>
                    </div>
            </div>

            <div class="input_container">
                <label for="nature">NOM et PRÉNOM:</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nomprenom" name="nomprenom" disabled>
                    </div>
            </div> 
            <div class="input_container">
                <label for="newprenom" style="color: red">NEW PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newprenom" name="newprenom" style="border: 1px dotted red" disabled>
                    </div>
            </div>

            <div class="input_container">
                <label for="newnom" style="color: red">NEW NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newnom" name="newnom" style="border: 1px dotted red" disabled>
                    </div>
            </div>

            <div class="input_container">
                <label for="nature">CORRECTION EMAIL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="correctemail" name="correctemail" disabled>
                    </div>
            </div>

            <div class="input_container">
            <label for="reporting">STATUT : </label>
            <div class="field_container">
                <select id="reporting" name="reporting" class="form-control" disabled>
                    <option value="0" selected>..</option>
                    <option value="1">AJOUT/SUPPRESSION</option>
                    <option value="2">FERMÉE</option>
                    <option value="3">MODIFICATION</option>
                    <option value="4">OK</option>
                    <option value="5">SUPPRESSION</option>
                    <option value="6">EN COURS</option>
                    <option value="7">KO</option>
                    <option value="8">AJOUT</option>
                </select>
            </div>
            </div> 
            <div class="input_container">
                <label for="reporting">COMMENTAIRE :</label>
                <div class="field_container">
              <textarea rows="2" placeholder="" class="form-control" id="commentaire_collab" name="commentaire_collab" disabled></textarea>
            </div>
            </div>            
            <hr>           
            <div class="form-group">
                <label for="nature">ADMINISTRATION :</label>
                    <div class="field_container">
                         <textarea rows="4" placeholder="..." class="form-control" id="commentaire" name="commentaire"></textarea>
                    </div>
            </div>
            <hr>
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
                <label for="raison">RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="raison" name="raison" readonly>
                    </div>
            </div>

            <div class="input_container">
                <label for="newraison" style="color: red">NOUVELLE RAISON SOCALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newraison" name="newraison" style="border: 1px dotted red">
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="siret">SIRET :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="siret" name="siret" readonly>
                    </div>
            </div> 

            <div class="input_container">
                <label for="newsiret" style="color: red">NOUVEAU SIRET :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newsiret" name="newsiret" maxlength="14" minlength="14" style="border: 1px dotted red">
                    </div>
            </div>

            <div class="input_container">
                <label for="fonction">FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fonction" name="fonction" readonly>
                    </div>
            </div>

            <div class="input_container">
                <label for="newfonction" style="color: red">NOUVELLE FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newfonction" name="newfonction" style="border: 1px dotted red">
                    </div>
            </div>


            <div class="input_container">
                <label for="nomprenom">NOM et PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nomprenom" name="nomprenom" readonly>
                    </div>
            </div>   
            <div class="input_container">
            <label for="newtitle" style="color: red">NOUVELLE CIVILITÉ :</label>
            <div class="field_container">
                <select id="newtitle" name="newtitle" class="form-control" required  style="border: 1px dotted red">
                    <option value="." selected>..</option>
                    <option value="M.">M.</option>
                    <option value="Mme">Mme</option>
                </select>
            </div>
            </div>
            <div class="input_container">
                <label for="newprenom" style="color: red">NOUVEAU PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newprenom" name="newprenom" style="border: 1px dotted red">
                    </div>
            </div>

            <div class="input_container">
                <label for="newnom" style="color: red">NOUVEAU NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="newnom" name="newnom" style="border: 1px dotted red">
                    </div>
            </div>        

            <div class="input_container">
                <label for="email">EMAIL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="email" name="email" readonly>
                    </div>
            </div>

            <div class="input_container">
                <label for="correctemail">TÉLÉPHONE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="phone" name="phone" maxlength="10" minlength="10">
                    </div>
            </div>

            <div class="input_container">
                <label for="nature">CORRECTION EMAIL : </label>
                    <div class="field_container">
                        <input type="email" class="form-control" id="correctemail" name="correctemail">
                    </div>
            </div>

            <div class="input_container">
            <label for="reporting">STATUT :  <span class="required">*</span></label>
            <div class="field_container">
                <select id="reporting" name="reporting" class="form-control" required>
                    <option value="" selected>..</option>
                    <option value="1">AJOUT/SUPPRESSION</option>
                    <option value="2">FERMÉE</option>
                    <option value="3">MODIFICATION</option>
                    <option value="4">OK</option>
                    <option value="5">SUPPRESSION</option>
                    <option value="6">EN COURS</option>
                    <option value="7">KO</option>
                    <option value="8">AJOUT</option>
                </select>
            </div>
            </div> 
            <div class="input_container">
                <label for="reporting">COMMENTAIRE :</label>
                <div class="field_container">
              <textarea rows="2" placeholder="" class="form-control" id="commentaire_collab" name="commentaire_collab"></textarea>
            </div>
            </div>
            <hr>
            
            <div class="form-group">
              <textarea rows="2" placeholder="Partie reservée à l'administrateur ... !" class="form-control" id="commentaire" name="commentaire" readonly></textarea>
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
<script charset="utf-8" src="module/hb/table/js/webapp_acide_traitement_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/hb/table/js/webapp_acide_traitement.js"></script>
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
<script src="module/hb/table/js/form-mask-input-hb.js"></script>

</body>
</html>