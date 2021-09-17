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
if(!checkAdmin()) {
die("Secured");
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
  <title>Gazette DATA</title>
  
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
<link rel="stylesheet" href="css/layout_global.css">


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
        
          	<h2 class="content-heading">Gazette DATA</h2>
            
            <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <div class="widget widget-alpha widget-alpha--color-green-jungle">
                <div>
                    <div class="widget-alpha__amount">
                    <?php 
					
					// Solution Temporaire
					$query_time_n = $bdd->prepare("SELECT SUM(temps_sec) AS dateeN FROM collectivite_lot_synthese_details");
					$query_time_n->execute();	
					$query_temps_n = $query_time_n->fetch();
					$query_time_n->closeCursor();
					
					$query_time_n = $bdd->prepare("SELECT SUM(collect_fiche_traitement) AS dateeNN FROM collectivite_fiche WHERE etat = 1");
					$query_time_n->execute();	
					$query_temps_nn = $query_time_n->fetch();
					$query_time_n->closeCursor();
					
					if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
					if($query_temps_nn['dateeNN'] == NULL){$tempnn = 0;}else{$tempnn = $query_temps_nn['dateeNN'];}
					// Temporaire
					$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec) + ".$tempn." + ".$tempnn.") AS datee FROM collectivite_fiche_update");
					//$query_time->bindParam(":tempn", $tempn, PDO::PARAM_INT);	
					$query_time->execute();	
					$query_temps = $query_time->fetch();
					$query_time->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);			
		$jh_lot = round($duree_decimal/8, 2);
                    echo $jh_lot; 
                    ?></div>
                  <div class="widget-alpha__description">Cumul JH</div>
                </div>
                <span class="widget-alpha__icon iconfont iconfont-info"></span>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <div class="widget widget-alpha widget-alpha--color-green-jungle">
                <div>
                    <div class="widget-alpha__amount">
                    <?php 
					
					$query = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche` WHERE collect_fiche_statut <> 0");
					$query->execute();
					$ligne_taiter_lot = $query->fetchColumn();
					$query->closeCursor();	
                    echo $ligne_taiter_lot; 
                    ?></div>
                  <div class="widget-alpha__description">Cumul Traité</div>
                </div>
                <span class="widget-alpha__icon iconfont iconfont-info"></span>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <div class="widget widget-alpha widget-alpha--color-green-jungle">
                <div>
                    <div class="widget-alpha__amount">
                    <?php 
					
					$query = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche`");
					$query->execute();
					$ligne_g = $query->fetchColumn();
					$query->closeCursor();	
                    echo $ligne_g; 
                    ?></div>
                  <div class="widget-alpha__description">Cumul Total fiches</div>
                </div>
                <span class="widget-alpha__icon iconfont iconfont-info"></span>
              </div>
            </div>
            </div>
            
            
            
          	<div class="main-container"> 
            
           
                   
            <div class="container-block">
            
            <div class="row">
            
            <div class="col-lg-6">
            
            <a href="CollectAjout" class="btn btn-success icon-left btn-sm mr-3">Ajouter un fichier pour traitement <span class="btn-icon iconfont iconfont-plus-v1"></span></a>
            <button type="button" class="btn btn-info icon-left btn-sm mr-3" data-toggle="modal" data-target="#modal-settings">Filtrer les données <span class="btn-icon iconfont iconfont-plus-v1"></span></button>        
            
            <div id="modal-settings" class="modal fade custom-modal-tabs">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header has-border">
                    <h5 class="modal-title">Filtres de recherche :</h5>
                    <div class="btn-group btn-collection nav custom-modal__header-tabs" role="group" aria-label="Basic example">
                      <!--<button class="btn btn-secondary nav-item nav-link" type="button" data-toggle="tab" data-target="#modal-settings-account">Account</button>
                      <button class="btn btn-secondary nav-item nav-link" type="button" data-toggle="tab" data-target="#modal-settings-voice">Voice</button>-->
                      <button class="btn btn-secondary nav-item nav-link active" type="button" data-toggle="tab" data-target="#modal-settings-notifications">Configuration</button>
                    </div>
                    <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="iconfont iconfont-modal-close"></span>
                    </button>
                  </div>
                  <form action="CollectDetailsRech" method="post" name="filtre">
                  <div class="modal-body">
                    <div class="tab-content">
                      <!--<div class="tab-pane fade" id="modal-settings-account">Account</div>
                      <div class="tab-pane fade" id="modal-settings-voice">Voice</div>-->
                      <div class="tab-pane fade show active" id="modal-settings-notifications">
                      
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="input-device">Intervenant</label>
                              <select class="form-control" data-placeholder="Tous les intervenants disponibles" name="collab">
                                <option></option>
                                <?php
								$query = $bdd->prepare("SELECT user_name, user_id FROM `collectivite_fiche` WHERE user_id <> 0 GROUP BY user_id");
								$query->execute();	
								while($query_collab = $query->fetch()){
								 echo '<option value="'.$query_collab['user_id'].'">'.$query_collab['user_name'].'</option>';	
								}
								$query->closeCursor();							
								?>
                                <option value="">Tout</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="input-type">Intervalle</label>
                              <input id="custom-ranges" type="text" placeholder="Choisir un intervalle" class="js-date-custom-ranges form-control" name="intervalle">
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="input-device">LOT</label>
                              <select class="form-control" data-placeholder="Tous les lots disponibles" name="lot">
                                <option></option>
                                <?php
								$query = $bdd->prepare("SELECT collect_lot_id, collect_lot_nom FROM `collectivite_lot`");
								$query->execute();	
								while($query_lot = $query->fetch()){
								 echo '<option value="'.$query_lot['collect_lot_id'].'">'.$query_lot['collect_lot_nom'].'</option>';	
								}
								$query->closeCursor();							
								?>
                              </select>
                            </div>
                          </div>                          
                        </div>
                        
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <div class="form-text text-muted">Ne pas renseigner les champs pour une recherche globale</div>
                            </div>
                          </div>                                                    
                        </div>
                                             
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer modal-footer--center">
                    <button class="btn btn-info" type="submit">Filtrer les données</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>   
            
            </div>
            
            <div class="col-lg-6" style="text-align:right">
            <a href="CollectRefresh-99" id="refreshhs" class="btn btn-warning icon-left btn-sm mr-3">Mise à jour totale de la base <span class="btn-icon iconfont iconfont-plus-v1"></span></a>
            <a class="btn btn-primary icon-right btn-sm mr-3"  href="#" id="refresh">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>
            </div>
            </div>
            <div class="container-block">
            <div class="row">      
            <div class="content table-responsive table-full-width">
            
            
            <table class="datatable table table-striped" id="table_collect">
            <thead>
            <tr>
            
            <th>LOT</th>
            <th>STATUT</th>                        
            <th>TOTAL</th>
            <th>TRAITÉ</th>
            <th>EN %</th> 
            <th>CC</th>
            <th>DÉBUT</th>
            <th>FIN</th>
            <th>TEMPS</th>
            <th>JH</th>
            <th>ACTION</th>
            <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
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
            	<label for="reporting">Objectif : <span class="required">*</span></label>
                <div class="field_container">
                    <input type="number" class="form-control" id="object" name="object" required>
                </div>
            </div>
            
            <div class="input_container">
            	<label for="reporting">Nomination : <span class="required">*</span></label>
                <div class="field_container">
                    <input type="text" class="form-control" id="nom" name="nom" required>
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
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="module/collectivite/table/js/webapp_collectivite_admin.js"></script>
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
<script src="vendor/select2/js/select2.full.min.js"></script>
<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>