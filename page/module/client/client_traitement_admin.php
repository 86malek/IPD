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

$query = $bdd->prepare("SELECT * FROM client_cat WHERE id_cat = :id_cat");
$query->bindParam(":id_cat", $id, PDO::PARAM_INT);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['nom_cat'];

$datetime = date("Y-m-d");
$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting <> 0");
$query_ligne_taiter->bindParam(":id_cat", $id, PDO::PARAM_INT);
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
  <title><?php echo $document; ?></title>
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
<link rel="stylesheet" href="css/layout_admin_client.css">
  

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
          <h2 class="content-heading">Traitement : <b><?php echo $document; ?></b></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <?php if ($_GET['type'] == 1) { ?>
            
            <a class="btn btn-success icon-left mr-3" href="Client-<?php echo $_GET['idcatt'];?>">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            
            <?php }else{ ?> 
			
            <a class="btn btn-success icon-left mr-3" href="Contact-<?php echo $_GET['idcatt'];?>">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            	
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
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['id'].'"  data-mode="" data-idcatt="'.$_GET['idcatt'].'">';                
                ?>
                
                <thead>
                	
                    <tr>
<?php if ($_GET['type'] == 1) {?>
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

            <form class="form add" id="form_company" data-id="">
            
            
            <div class="input_container">
                    <label for="nature">RAISON SOCIALE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="rs_o" name="rs_o" disabled>
                        <input type="text" class="form-control new" id="rs" name="rs">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 1 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="ad1_o" name="ad1_o" disabled>
                        <input type="text" class="form-control new" id="ad1" name="ad1">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 2 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="ad2_o" name="ad2_o" disabled>
                        <input type="text" class="form-control new" id="ad2" name="ad2">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">ADRESSE 3 :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="ad3_o" name="ad3_o" disabled>
                        <input type="text" class="form-control new" id="ad33" name="ad33">
                    </div>
            </div>
            
            
            <div class="input_container">
                    <label for="nature">CP :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="cp_o" name="cp_o" disabled>
                        <input type="text" class="form-control new" maxlength="5" id="cp" name="cp">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">VILLE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="ville_o" name="ville_o" disabled>
                        <input type="text" class="form-control new" id="ville" name="ville">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">TÉL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="tel_o" name="tel_o" disabled>
                        <input type="text" class="form-control new" id="tel" name="tel">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">FAX :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="fax_o" name="fax_o" disabled>
                        <input type="text" class="form-control new" id="fax" name="fax">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">SIRET :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="siret_o" name="siret_o" disabled>
                        <input type="text" class="form-control new" id="siret" name="siret" maxlength="14"  minlength="14">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">EFFECTIF SITE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="esite_o" name="esite_o" disabled>
                        <input type="text" class="form-control new" id="esite" name="esite" >
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE SITE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control full new" id="t1" name="t1" >
                    </div>
            </div>
            <div class="input_container">
                    <label for="nature">EFFECTIF GROUPE :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="egroupe_o" name="egroupe_o" disabled>
                        <input type="text" class="form-control new" id="egroupe" name="egroupe" >
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE GROUPE : </label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="t2" name="t2" >
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">NEW EFFECTIF NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="enat" name="enat" >
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">TRANCHE NATIONAL : </label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="t3" name="t3" >
                    </div>
            </div>
            <div class="input_container">
                    <label for="nature">CA :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="ca_o" name="ca_o" disabled>
                        <input type="text" class="form-control new" id="ca" name="ca" >
                    </div>
            </div>
            <div class="input_container">
                <label for="nature">NEW CA TRANCHE: </label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="catt" name="catt" >
                    </div>
            </div>
            <hr>
            <div class="input_container">
                <label for="nature" style="color:#F00; font-weight:bolder">STATUT : </label>
                    <div class="field_container">
                        <input type="text" class="form-control statut " id="stat" name="stat">
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

<?php if ($_GET['type'] == 1) {?>
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_admin_societe.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_admin_contact.js"></script>
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