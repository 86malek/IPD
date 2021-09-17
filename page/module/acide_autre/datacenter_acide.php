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
if(checkAdmin()) {
try 
{		
$query = $bdd->prepare("SELECT * FROM autre_acide_fichier_heure ORDER BY id_autre_acide_fichier_heure DESC LIMIT 0, 1");
$query->execute();
$donnees = $query->fetch();
$heure = securite_bdd($db,$donnees['objectif_autre_acide_fichier_heure']);	
$query->closeCursor();				
}
catch(PDOException $x) 
{ 	
die("Secured");	
$message = 'Échec de la requête pour les objectifs'; 	
}	
$query = null;
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
  <title>DATA Acide</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

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
          	<h2 class="content-heading">DATA Acide</h2>
            <?php if (checkAdmin()) { ?>  
            <div class="content-description">
            Heures ouvrables : <span class="badge badge-bittersweet mb-3 mr-3"><?php if(isset($heure)){echo $heure.'H';}else{echo 'En attente';}?></span>
            </div>
             <?php } ?>           
          	<div class="main-container"> 
            
           
                   
            <div class="container-block">
            <div class="row">
            
            <div class="col-lg-6">
            <?php if (checkAdmin()) { ?> 
            <a href="DataAcideAjout" class="btn btn-success icon-left btn-sm mr-3">Ajouter un fichier pour traitement <span class="btn-icon iconfont iconfont-plus-v1"></span></a>
            <a class="btn btn-info icon-left btn-sm mr-3" href="#" id="objectif">Ajouter un objectif en heures<span class="btn-icon iconfont iconfont-plus-square"></span></a>
            <a class="btn btn-secondary icon-left btn-sm mr-3" href="DataAcideCat">Ajouter une catégorie <span class="btn-icon iconfont iconfont-plus-v1"></span></a>
            
            <?php } ?>  
            </div>
             
            <div class="col-lg-3">
            
            </div>
            <div class="col-lg-3" style="text-align:right">
            <a class="btn btn-primary icon-right btn-sm mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>
            </div>
            </div>
            <div class="container-block">
            <div class="row">      
            <div class="content table-responsive table-full-width">
            <?php if (checkAdmin()) { ?>                    
            
            <table class="datatable table table-striped" id="table_doc_acide">
            <thead>
            <tr>
            <th>FICHIER</th>
            <th>TYPE</th>
            <th>EQUIPE</th>
            <th>AFFECTATION</th>
            <th>STATUT</th>
            <th>DEBUT</th>
            <th>FIN</th>
            <th>TOTAL</th>
            <th>JH</th>
            <th>LIGNES</th>          
            <th>ACTION</th>            
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
            
            <?php }else{ ?>
            
            <table class="datatable table table-striped" id="table_doc_acide" data-id="<?php echo $_GET['id_cat'];?>">
            <thead>
            <tr>
            <th>FICHIER</th>
            <th>TYPE</th>
            <th>AFFÉCTATION</th>
            <th>DATE D'AJOUT</th>
            <th>STATUT</th>  
            <th>ACTION</th>            
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
            
            <?php } ?> 
                                             
            </div>
            </div>                                
            </div>
            </div>
                                    
                                    
        </div>
  	</div>
</div>    

<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
<?php if (checkAdmin()) { ?> 
<script charset="utf-8" src="module/acide_autre/table/js/webapp_doc_acide_admin.js"></script>
<?php }else{ ?>
<script charset="utf-8" src="module/acide_autre/table/js/webapp_doc_acide.js"></script>
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
<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>