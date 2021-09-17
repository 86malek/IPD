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
          <h2 class="content-heading">Tableaux des données : <b>DMP</b></h2>
          	
          	<div class="main-container">  
            <div class="container-block">
            <div class="row">
            <?php if(!checkAdmin()) { ?>
            <div class="col-lg-6">
            <button type="button" class="btn btn-info icon-left mr-3" id="add_rapport">Ajouter une nouvelle semaine <span class="btn-icon iconfont iconfont-plus-v1"></span></button>
            </div>
			<div class="col-lg-6" style="text-align:right">
            <a class="btn btn-primary icon-left mr-3" href="javascript:window.location.reload()">Rafraîchissement <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>
            <?php }else{ ?>
           	<div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="DmpRapport">Retour aux statistiques <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>              
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
			<table class="datatable table table-striped" id="table_traitement_rapport" data-id="<?php echo $id;?>">
			<thead>
                    <tr>
                    	<th>COLLABORATEUR</th>
                        <th>DATE</th>
                    	<th>SEMAINE</th>
                        <th>NOMBRE D'EMAILS</th>
                        
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
			<?php }else{ ?>
            <table class="datatable table table-striped" id="table_traitement_rapport" data-id="<?php echo $_SESSION['user_id'];?>">
            <thead>            		
                    <tr>
                    	<th>SEMAINE</th>
                        <th>NOMBRE D'EMAILS</th>
                        <th>DATE</th>
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
			
            
            <form class="form add" id="form_company" data-id="">
            <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id'];?>" >
            <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_name'];?>" >
            
            <div class="input_container">
            <label for="nature">SEMAINE : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="text" class="form-control" id="ch1" name="ch1" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nature">NOMBRE D'EMAILS : <span class="required">*</span></label>
            <div class="field_container">
                    <input type="number" class="form-control" id="ch2" name="ch2" required>
            </div>
            </div>
            
            <!--<div class="input_container">
            <label for="nature">LISTE D'EMAILS : </label>
      <div class="field_container">
                    <textarea rows="10" placeholder="..." class="form-control" id="lemail" name="lemail"></textarea>   
            </div>         
            </div>-->
            <HR>
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
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>

<?php if (checkAdmin()) { ?>   
<script charset="utf-8" src="module/cnil/table/js/webapp_dmp_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/cnil/table/js/webapp_dmp.js"></script>
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
<script src="js/preview/datepicker.min.js"></script>
<!--<script src="vendor/select2/js/select2.full.min.js"></script>-->
<div class="sidebar-mobile-overlay"></div> 
</body>
</html>