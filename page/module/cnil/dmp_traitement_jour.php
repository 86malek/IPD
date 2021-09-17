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
  <title>DMP</title>
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

<link rel="stylesheet" href="module/cnil/table/css/layout_cnil.css">

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
          <h2 class="content-heading">Recherche : <b>DMP</b></h2>
          <div class="content-description">
          
          	Intervalle : <span class="badge badge-bittersweet badge-rounded"><?php if(empty($_POST['intervalle'])){echo 'Non défini';}else{echo $_POST['intervalle'];}?></span> |     	    
          
          	Intervenant : <span class="badge badge-bittersweet badge-rounded">
		  	<?php if(empty($_POST['collab'])){echo 'Non défini';}else{
            $query = $bdd->prepare("SELECT full_name FROM `users` WHERE id = :user_id");
            $query->bindParam(":user_id", $_POST['collab'], PDO::PARAM_INT);							
            $query->execute();	
            $query_user = $query->fetch();							
            $query->closeCursor();
            echo ''.$query_user['full_name'].'';
            }?>
		  	</span>
                  
          </div>	
          	<div class="main-container">  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="DmpRapport">Retour aux statistiques <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
            <button type="button" class="btn btn-secondary mr-3" data-toggle="modal" data-target="#modal-settings">RECHERCHE</button>
        <div id="modal-settings" class="modal fade custom-modal-tabs">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header has-border">
                    <h5 class="modal-title">Recherche :</h5>
                    <div class="btn-group btn-collection nav custom-modal__header-tabs" role="group" aria-label="Basic example">
                      <button class="btn btn-secondary nav-item nav-link active" type="button" data-toggle="tab" data-target="#modal-settings-notifications">Configuration</button>
                    </div>
                    <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="iconfont iconfont-modal-close"></span>
                    </button>
                  </div>
                  <form action="DmpDetailsJour" method="post" name="filtre">
                  <div class="modal-body">
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="modal-settings-notifications">
                      
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="input-device">Intervenant</label>
                              <select class="form-control" data-placeholder="Tous les intervenants disponibles" name="collab">
                                <option></option>
                                <?php
								$query = $bdd->prepare("SELECT user_name, user_id FROM `dmp_traitment` WHERE user_id <> 0 GROUP BY user_id");
								$query->execute();	
								while($query_collab = $query->fetch()){
								 echo '<option value="'.$query_collab['user_id'].'">'.$query_collab['user_name'].'</option>';	
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
                              <label for="input-type">Intervalle</label>
                              <input id="custom-ranges" type="text" placeholder="Choisir un intervalle" class="js-date-custom-ranges form-control" name="intervalle">
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
                    <button class="btn btn-info" type="submit">Lancer la recherche</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            </div>
			<div class="col-lg-6" style="text-align:right">
            <a class="btn btn-primary icon-left mr-3" href="javascript:window.location.reload()">Rafraîchissement <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>                  
            </div>            
            </div>
            
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">
            
			<table class="datatable table table-striped" id="table_traitement_rapport_rech"  data-date="<?php echo $_POST['intervalle'];?>" data-collab="<?php echo $_POST['collab'];?>">
			<thead>
            		
                    <tr>
                    	<th>COLLAB</th>
                    	<th>DATE</th>
                        <th>SEMAINE</th>
                        <th>NOMBRE D'EMAILS</th>
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

<script charset="utf-8" src="module/cnil/table/js/webapp_dmp_admin_rech.js"></script>

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