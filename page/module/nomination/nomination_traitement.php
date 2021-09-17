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
if(isset($_GET['user_id'])){$id = $_GET['user_id'];}else{$id = '';}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>NOMINATION</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">
<link rel="stylesheet" href="vendor/date-range-picker/daterangepicker.css">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
<link rel="stylesheet" href="vendor/jquery-confirm/jquery-confirm.min.css">   
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="module/nomination/table/css/layout_nomination.css">
  

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
try 
{
$datetime = date("Y-m-d");
$query_ligne_taiter = $bdd->prepare("SELECT count(*) FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id AND date_calcul = :date_calcul");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();
}
catch(PDOException $x) 
{ 	
die("Secured");	
}	
$query = null;



try 
{		
$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM nomination_acide_obj WHERE debut_objectf <= '".date("Y-m-d")."' AND fin_objectif >= '".date("Y-m-d")."' ORDER BY id_objectif DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$ligne = $donnees['nbligne_objectif'];
$heure = $donnees['nbheure_objectif'];	
$query->closeCursor();				
}
catch(PDOException $x) 
{ 	
die("Secured");	
}	
$query = null;

?>

	<div class="page-content">

        <div class="container-fluid">
          <h2 class="content-heading"><?php if(!checkAdmin()) { ?>
            NOMINATION / Objectif : <b><?php echo $ligne; ?> Fiches</b> / Réalisé : <b><?php echo $ligne_taiter; ?></b>
            <?php }else{ ?>
           	NOMINATION / Objectif : <b><?php echo $ligne; ?> Fiches</b>
            <?php } ?></h2>
          	
          	<div class="main-container">  
            <div class="container-block">
            <div class="row">
            <?php if(!checkAdmin()) { ?>
            <div class="col-lg-6">
            <button type="button" class="btn btn-info icon-left mr-3" id="add_nomination">Ajouter une nouvelle fiche <span class="btn-icon iconfont iconfont-plus-v1"></span></button>
            </div>
			<div class="col-lg-6" style="text-align:right">
            <a class="btn btn-primary icon-left mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>
            <?php }else{ ?>
           	<div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="NominationBiblio">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>              
            </div>
			<div class="col-lg-6" style="text-align:right">
            <a class="btn btn-primary icon-left mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>
            <?php } ?>                  
            </div>            
            </div>
            
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">
            <?php if (checkAdmin()) {?>
			<table class="datatable table table-striped" id="table_traitement_nomination" data-mode="<?php if(empty($id)){echo 'total';}else{echo 'detail';}?>" data-id="<?php if(empty($id)){echo $id;}else{echo $id;}?>">
			<thead>
                    <tr>
                    	<th>ALERTE</th>
                    	<th>Collab</th>
                    	<th>DATE</th>
                        <th>PUBLICATION</th>
                        <th>RS</th>
                        <th>SIRET</th>
                        <th>CIV</th>
                        <th>NOM</th>
                        <th>PRENOM</th>
                        <th>FONCTION</th>                        
                        <th>OLD</th>
                        <th>STATUT</th>
                        <th>TYPE</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
			<?php }else{ ?>
            <table class="datatable table table-striped" id="table_traitement_nomination" data-id="<?php echo $_SESSION['user_id'];?>">
            <thead>
                    <tr>
                    	<th>ALERTE</th>
                    	<th>DATE</th>
                        <th>PUBLICATION</th>
                        <th>RS</th>
                        <th>SIRET</th>
                        <th>CIV</th>
                        <th>NOM</th>
                        <th>PRENOM</th>
                        <th>FONCTION</th>                        
                        <th>OLD</th>
                        <th>STATUT</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <?php } ?>
                
                                             
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
            
            
			
            <!------------------------------------------------------------------------>
            <?php if (checkAdmin()) {?>
            <form class="form add" id="form_company_admin" data-id="">
            <input type="hidden" id="user_id" name="user_id" value="<?php echo securite_bdd($db,$_SESSION['user_id']);?>" readonly>
            <input type="hidden" id="user" name="user" value="<?php echo securite_bdd($db,$_SESSION['user_name']);?>" readonly>
            <div class="input_container">
            <label for="reporting">TYPE : <span class="required">*</span></label>
            <div class="field_container">
                <select id="etat" name="etat" class="form-control" required>
                    <option value="" selected>Choisir un type</option>
                    <option value="1">SOCIÉTÉ NT</option>
                    <option value="2">BO-ACIDE</option>
                </select>
            </div>
            </div> 
                
            <div class="input_container">
            <label for="nature">PUBLICATION : <span class="required">*</span></label>
            <div class="field_container">
                    
            <select id="publication" name="publication" class="form-control" required>
                <option value="" selected>Choisir une publication</option>
                <option value="1">ETAT MAJORS</option>
                <option value="2">LE FAC</option>
                <option value="3">DECIDEURS MAGAZINE</option>
                <option value="4">CADREO</option>
                <option value="5">JDN IT</option>
                <option value="6">LE MONDE INFO</option>
                <option value="7">LES ECHOS</option>
                <option value="8">GOOGLE ALERTE</option>
                <option value="9">Alerte LINKEDIN</option>
                <option value="10">Alerte Nomination</option>
                <option value="11">Alerte KBC</option>
                <option value="12">LSA</option>
                <option value="13">AGEFI</option>
            </select>
                     
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">RAISON SOCIALE : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="rs" name="rs" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">SIRET : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="siret" name="siret" maxlength="20" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="reporting">CIVILITÉ : <span class="required">*</span></label>
            <div class="field_container">
                <select id="title" name="title" class="form-control" required>
                    <option value="" selected>Choisir une civilité</option>
                    <option value="Mme">MME</option>
                    <option value="M">M.</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">NOM : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">PRÉNOM : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">FONCTION EXACTE : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="fonction" name="fonction" required>
            </div>
            </div>
            <hr>
            <p style="color:#fe6f60">Cette section est à renseigner que si c'est une fiche "<b>BO-ACIDE</b>"</p>
            <hr>
            <div class="input_container" id="stat">
            <label for="reporting">STATUT :</label>
            <div class="field_container">
                <select id="statut" name="statut" class="form-control" disabled>
                    <option value="0" selected>..</option>
                    <option value="1">AJOUT</option>
                    <option value="2">MODIFICATION</option>
                    <option value="3">SUPP</option>
                </select>
            </div>
            </div>
            
            <div class="input_container" id="old">
            <label for="nature">ANCIENNE SOCIÉTÉ :</label>
            <div class="field_container">
                    <input type="text" class="form-control" id="ancienne" name="ancienne" disabled>
            </div>
            </div>   		
    		<div class="form-group">
              <textarea rows="4" placeholder="Commentaire" class="form-control" id="comm" name="comm"></textarea>
            </div>
              <div class="form-group" style="text-align:right">
                <button type="submit" class="btn btn-info"></button>
              </div>
              
            </form>
            <?php }else{ ?>
            <form class="form add" id="form_company" data-id="">
            <input type="hidden" id="debut" name="debut" value="" readonly>
            <input type="hidden" id="user_id" name="user_id" value="<?php echo securite_bdd($db,$_SESSION['user_id']);?>" readonly>
            <input type="hidden" id="user" name="user" value="<?php echo securite_bdd($db,$_SESSION['user_name']);?>" readonly>
            <div class="input_container">
            <label for="reporting">TYPE : <span class="required">*</span></label>
            <div class="field_container">
                <select id="etat" name="etat" class="form-control" required>
                    <option value="" selected>Choisir un type</option>
                    <option value="1">SOCIÉTÉ NT</option>
                    <option value="2">BO-ACIDE</option>
                </select>
            </div>
            </div> 
                
            <div class="input_container">
            <label for="nature">PUBLICATION : <span class="required">*</span></label>
            <div class="field_container">
                    
            <select id="publication" name="publication" class="form-control" required>
                <option value="" selected>Choisir une publication</option>
                <option value="1">ETAT MAJORS</option>
                <option value="2">LE FAC</option>
                <option value="3">DECIDEURS MAGAZINE</option>
                <option value="4">CADREO</option>
                <option value="5">JDN IT</option>
                <option value="6">LE MONDE INFO</option>
                <option value="7">LES ECHOS</option>
                <option value="8">GOOGLE ALERTE</option>
                <option value="9">Alerte LINKEDIN</option>
                <option value="10">Alerte Nomination</option>
                <option value="11">Alerte KBC</option>
                <option value="12">LSA</option>
            </select>
                    
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">RAISON SOCIALE : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="rs" name="rs" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">SIRET : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="siret" name="siret" maxlength="20" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="reporting">CIVILITÉ : <span class="required">*</span></label>
            <div class="field_container">
                <select id="title" name="title" class="form-control" required>
                    <option value="" selected>Choisir une civilité</option>
                    <option value="Mme">MME</option>
                    <option value="M">M.</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">NOM : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">PRÉNOM : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">FONCTION EXACTE : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="fonction" name="fonction" required>
            </div>
            </div>
            <hr>
            <p style="color:#fe6f60">Cette section est à renseigner que si c'est une fiche "<b>BO-ACIDE</b>"</p>
            <hr>
            <div class="input_container" id="stat">
            <label for="reporting">STATUT :</label>
            <div class="field_container">
                <select id="statut" name="statut" class="form-control" disabled>
                    <option value="0" selected>CHOISIR SAUF SI BO-ACIDE</option>
                    <option value="1">AJOUT</option>
                    <option value="2">MODIFICATION</option>
                    <option value="3">SUPP</option>
                </select>
            </div>
            </div>
            
            <div class="input_container" id="old">
            <label for="nature">ANCIENNE SOCIÉTÉ :</label>
            <div class="field_container">
                    <input type="text" class="form-control" id="ancienne" name="ancienne" disabled>
            </div>
            
            </div>   		
    		<div class="form-group">
              <textarea rows="4" placeholder="Partie reservée à l'administrateur" class="form-control" id="comm" name="comm" readonly></textarea>
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
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<?php if (checkAdmin()) { ?>   
<script charset="utf-8" src="module/nomination/table/js/webapp_acide_traitement_nomination_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/nomination/table/js/webapp_acide_traitement_nomination.js"></script>
<?php } ?>
<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="js/growl-notification/growl-notification.js"></script>
<script src="js/preview/growl-notifications.min.js"></script>
<script src="vendor/momentjs/moment-with-locales.min.js"></script>
<script src="vendor/date-range-picker/daterangepicker.js"></script>
<script src="js/preview/date-range-picker.js"></script>
<script src="vendor/nouislider/nouislider.min.js"></script>
<script src="vendor/tagify/tagify.min.js"></script>
<script src="js/preview/modal.min.js"></script>
<!--<script src="vendor/select2/js/select2.full.min.js"></script>-->
<div class="sidebar-mobile-overlay"></div> 
</body>
</html>