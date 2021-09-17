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
if($_GET['idcat'] == ''){$idcat = "";}else{$idcat = $_GET['idcat'];}
if($_GET['idcatt'] == ''){$idcatt = "";}else{$idcatt = $_GET['idcatt'];}

$query = $bdd->prepare("SELECT * FROM client_traitement WHERE siret_client = :siret_client GROUP BY siret_client");
$query->bindParam(":siret_client", $idcat, PDO::PARAM_STR);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['raison_sociale_client'];

$datetime = date("Y-m-d");

$query_ligne_taiter = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE reporting_contact <> 0 AND (n_stat_contact = 3 OR n_stat_contact = 11 OR n_stat_contact = 12) AND user_id_contact = :user_id AND date_calcul_contact = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE reporting_contact <> 0 AND (n_stat_contact = 3 OR n_stat_contact = 11 OR n_stat_contact = 12) AND user_id_contact = :user_id AND date_calcul_contact = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter_s = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();

$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM client_cat_synthese_fiche_obj WHERE debut_objectf <= '".$datetime."' AND fin_objectif >= '".$datetime."' ORDER BY id_objectif DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$ligne = $donnees['nbligne_objectif'];
$query->closeCursor();

$query_save = $bdd->prepare("UPDATE client_traitement SET rtime = :rtime WHERE siret_client = :siret_client AND id_cat IN (SELECT id_cat FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga)");
$query_save->bindParam(":rtime", $_SESSION['user_id'], PDO::PARAM_INT);
$query_save->bindParam(":siret_client", $idcat, PDO::PARAM_STR);
$query_save->bindParam(":id_client_cat_oraga", $_GET['idcatt'], PDO::PARAM_INT);
$query_save->execute();
$query_save->closeCursor();
				
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Traitement dossier client : CONTACT</title>
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
          <h2 class="content-heading">Traitement client : <b><?php echo $document; ?></b> / Société : <?php echo $ligne_taiter_s; ?> / Contacts Traités : <?php echo $ligne_taiter; ?></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="ClientBiblio-debut-<?php echo $id; ?>">Retour au dossier <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <a class="btn btn-info icon-left mr-3" href="#" id="add_contact">Ajouter un contact <span class="btn-icon iconfont iconfont-plus-v1"></span></a>
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
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$idcat.'"  data-mode="" data-idcatt="'.$idcatt.'">';
                }else{
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$idcat.'"  data-name="'.$_SESSION['user_name'].'"  data-ide="'.$_SESSION['user_id'].'" data-idcatt="'.$idcatt.'">';                    
                }
                ?>
                <thead>
                	
                    <tr>
                    	<?php if (checkAdmin()) {?>
                        
                        <th></th>

                        <th>ALERTE</th>
                        <th>SOCIÉTÉ</th> 
                        <th>CIVILITÉ</th>
                        <th>PRÉNOM</th>
                        <th>NOM</th>
                        <th>FONCTION</th>
                        <th>EMAIL</th>                        

                        <th></th>
                        <th></th>
                        <th></th>
                        
                        <?php }else{ ?>

                        <th>ALERTE</th>
                        <th>SOCIÉTÉ</th>
                        <th>CIVILITÉ</th>
                        <th>PRÉNOM</th>
                        <th>NOM</th>
                        <th>FONCTION</th>
                        <th>EMAIL</th>  
                         <th></th>                
                        
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
                    <label for="nature">CIVILITÉ ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="civ_o" name="civ_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">PRÉNOM ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="prenom_o" name="prenom_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">NOM ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nom_o" name="nom_o" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">FONCTION ORIGINALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fonction_o" name="fonction_o" disabled>
                    </div>
            </div>
            
            
            <div class="input_container">
                    <label for="nature">EMAIL ORIGINAL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="email_o" name="email_o" disabled>
                    </div>
            </div>            
            <hr>
            <center><p><b>Partie traitement :</b></p></center>
            <hr>
            
            <div class="input_container">
            <label for="reporting">TYPE : </label>
            <div class="field_container">
                <select id="type" name="type" class="form-control" disabled>
                    <option value="0" selected>CHOISIR TYPE DE RECHERCHE</option>
                    <option value="1">PAR TEL</option>
                    <option value="2">PAR RECHERCHE</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
            <label for="reporting">CIVILITÉ : </label>
            <div class="field_container">
                <select id="civ" name="civ" class="form-control" disabled>
                    <option value="Non Renseigné" selected>CHOISIR UNE CIVILITÉ</option>
                    <option value="MR">MR</option>
                    <option value="MME">MME</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW PRENOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="prenom" name="prenom" disabled>
                    </div>
            </div>
            
            <div class="input_container">
                <label for="nature">NEW NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nom" name="nom" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">NEW FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="fc" name="fc" disabled>
                    </div>
            </div> 
            
                     
            <div class="input_container">
                <label for="nature">NEW EMAIL :</label>
                    <div class="field_container">
                        <input type="email" class="form-control" id="email" name="email" disabled>
                    </div>
            </div> 
            
            <div class="input_container">
                <label for="nature">NEW LINKEDIN :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="lk" name="lk" disabled>
                    </div>
            </div>

            <div class="form-group">
              <textarea rows="4" placeholder="Commentaire" class="form-control" id="commentaire_collab" name="commentaire_collab" disabled></textarea>
            </div>
            <br>
            <hr>
            
            <div class="input_container">
                <label for="nature" style="color:#F00; font-weight:bolder">STATUT : </label>
                    <div class="field_container">
                        <input type="text" class="form-control form-control-danger" id="stat" name="stat" placeholder="..." disabled>
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">TEL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel2" name="tel2" disabled>
                    </div>
            </div>
            
            <div class="form-group">
              <textarea rows="4" placeholder="Commentaire" class="form-control" id="commentaire_collab" name="commentaire_collab" disabled></textarea>
            </div>
			<div class="input_container">
            <label for="reporting" style="color:#F00; font-weight:bolder">STATUT : </label>
            <div class="field_container">
                <select id="stat" name="stat" class="form-control">
                    <option value="0" selected>CHOISIR UN STATUT</option>
                    
                    <option value="1">Non Vérifié</option>
                    <option value="2">A quitté</option>
                    <option value="3">OK</option>
                    <option value="4">OK avec modif</option>
                    <option value="5">Remplacé</option>
                    <option value="6">Hors Cible</option>
                    <option value="7">Ajout</option>
                    
                </select>
            </div>
            </div>
            <hr>
            <center><p><b>Partie Administrateur :</b></p></center>
            <hr>
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire" disabled></textarea>
            </div>

            </form>
            
            
            <!---------------------------------------->

            <?php }else{ ?>
            
            
            
            <form class="form add" id="form_company" data-id="">
            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="debut" name="debut" readonly>
                        <input type="hidden" class="form-control" id="idcatt" name="idcatt" value="<?php echo $_GET['idcatt']; ?>" readonly>
                    </div>
            </div>
            <div class="input_container">
                    <div class="field_container">
                        <input type="hidden" class="form-control" id="siret" name="siret" value="<?php echo $_GET['idcat']; ?>" readonly>
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
            <div class="input_container">
            		<label for="nature">RS :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="rs" name="rs" value="<?php echo $doc['raison_sociale_client']; ?>" readonly>
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">SIRET :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nsiret" name="nsiret" value="<?php echo $doc['siret_client']; ?>" readonly>
                    </div>
            </div>
            <div class="input_container">
            		<label for="nature">Adresse :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ad" name="ad" value="<?php echo $doc['adresse2_client']; ?>" readonly>
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">cp :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $doc['code_postal_client']; ?>" readonly>
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">Ville :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $doc['ville_client']; ?>" readonly>
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">TEL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel" name="tel" value="<?php echo $doc['tel_client']; ?>" readonly>
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">New TEL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel22" name="tel22" value="<?php echo $doc['n_tel_client']; ?>" readonly>
                    </div>
            </div>
            
            
            
            <div class="input_container">
            <label for="reporting">TYPE : </label>
            <div class="field_container">
                <select id="type" name="type" class="form-control">
                    <option value="0" selected>CHOISIR TYPE DE RECHERCHE</option>
                    <option value="1">PAR TEL</option>
                    <option value="2">PAR RECHERCHE</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
            <label for="reporting">CIVILITÉ : </label>
            <div class="field_container">
                <select id="civ" name="civ" class="form-control">
                    <option value="Non Renseigné" selected>CHOISIR UNE CIVILITÉ</option>
                    <option value="MR">MR</option>
                    <option value="MME">MME</option>
                </select>
            </div>
            </div>
            
            <!--<div class="input_container">
            		<label for="nature">CIVILITÉ :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="civ" name="civ">
                    </div>
            </div>-->
            
            <div class="input_container">
            		<label for="nature">PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="prenom" name="prenom">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="nom" name="nom">
                        
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="fc">FONCTION :</label>
                    <div class="field_container">
                        <!--<input type="text" class="form-control" id="fc" name="fc">-->
                        <select id="fc" name="fc" class="form-control">
                            <option value="0" selected>CHOISIR UNE FONCTION</option>
                            <option value="1">Responsable Achats</option>
                            <option value="2">Directeur Achat</option>
                            <option value="3">Responsable logistique</option>
                            <option value="4">Directeur logistique</option>
                            <option value="5">Responsable Service Généraux</option>
                            <option value="6">Directeur service généraux</option>
                            <option value="7">Directeur Général</option>
                            <option value="8">Gérant</option>
                        </select>

                    </div>
            </div>
            
            <div class="input_container">
            <label for="reporting">SERVICE : </label>
            <div class="field_container">
                <select id="service" name="service" class="form-control">
                    <option value="0" selected>CHOISIR UN SERVICE</option>
                    <option value="1">SERVICE ACHATS</option>
                    <!--<option value="2">INFORMATIQUE</option>-->
                    <option value="3">SERVICES GENERAUX</option>
                    <option value="4">SERVICE LOGISTIQUE</option>
                    <option value="5">DIRECTION GENERALE</option>
                    <option value="6">AUTRES</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">EMAIL : </label>
                    <div class="field_container">
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">LINKEDIN :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="lk" name="lk">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">TEL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control" id="tel2" name="tel2">
                    </div>
            </div>
            
            <div class="form-group">
              <textarea rows="4" placeholder="Commentaire" class="form-control" id="commentaire_collab" name="commentaire_collab"></textarea>
            </div>
			<div class="input_container">
            <label for="reporting" style="color:#F00; font-weight:bolder">STATUT : </label>
            <div class="field_container">
                <select id="stat" name="stat" class="form-control">
                    <option value="0" selected>INDISPONIBLE</option>
                    
                    <!--<option value="1">Non Vérifié</option>
                    <option value="2">A quitté</option>
                    
                    <option value="4">OK avec modif</option>
                    <option value="5">Remplacé</option>
                    <option value="6">Hors Cible</option>
                    <option value="7">Ajout</option>
                    
                    <option value="9">NRP</option>-->

                    <option value="3">OK</option>
                    <option value="8">Refus</option>
                    <option value="10">EN COURS</option>
                    <option value="11">OK / En charge du Transport</option>
                    <option value="12">OK / Prise en charge externe</option>
                    <option value="13">KO</option>
                    
                </select>
            </div>
            </div>
            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-info"></button>
            </div>

            <br>
            <hr>
            
            
            
            <!--<div class="input_container">
                <label for="nature" style="color:#F00; font-weight:bolder">STATUT : </label>
                    <div class="field_container">
                        <input type="text" class="form-control form-control-danger" id="stat" name="stat" placeholder="...">
                    </div>
            </div>-->
            
            <hr>
            <center><p><b>Partie Administrateur :</b></p></center>
            <hr>
            
            <div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="commentaire" name="commentaire" readonly></textarea>
            </div>

            <hr>

            <div class="form-group" style="text-align:right">
            <button type="submit" class="btn btn-danger"></button>
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
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_contact_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_contact.js"></script>
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