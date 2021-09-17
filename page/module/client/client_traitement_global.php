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
		
$query = $bdd->prepare("SELECT * FROM client_cat_oraga WHERE id_client_cat_oraga = :id_client_cat_oraga");
$query->bindParam(":id_client_cat_oraga", $_GET['idcatt'], PDO::PARAM_INT);
$query->execute();
$donnees_dossier = $query->fetch();
$titre_dossier = $donnees_dossier['nom_client_cat_oraga'];
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
  <title>TRAITEMENT CLIENT GLOBAL</title>
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
<link rel="stylesheet" href="css/layout_global.css">
  

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
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="Client-<?php echo $_GET['idcatt']?>">Retour à la Bibliothèque <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>           
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
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$_GET['idcatt'].'"  data-mode="">';                
                ?>
                <thead>
                	
                    <tr>

                         <th>DATE</th>
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
                        
                        <th>NEW RS</th>
                        <th>NEW AD1</th>
                        <th>NEW AD2</th>
                        <th>NEW AD3</th>
                        <th>NEW CP</th>
                        <th>NEW VILLE</th>
                        <th>NEW TEL</th>
                        <th>NEW FAX</th>
                        <th>NEW SIRET</th>
                        <th>NEW EFFECTIF SITE</th>  
                        <th>TRANCHE EFFECTIF SITE</th>    
                        <th>NEW EFFECTIF GROUPE</th> 
                        <th>TRANCHE EFFECTIF GROUPE</th> 
                        <th>NEW EFFECTIF NATIONAL</th> 
                        <th>TRANCHE EFFECTIF NATIONAL</th> 
                        <th>NEW CA</th>
						<th>NEW CA TRANCHE</th>
                        
                        <th>COLLAB</th>
                        <th>TEMPS</th>
                        <th>NB MODIF</th>
                        
                        <th>TITLE</th>
                        <th>NOM</th>
                        <th>PRENOM</th>
                        <th>FONCTION</th>
                        <th>EMAIL</th>
                        
                        <th>NEW TITLE</th>
                        <th>NEW PRENOM</th>  
                        <th>NEW NOM</th>
                        <th>NEW FONCTION</th>  
                        <th>NEW SERVICE</th>
                        <th>NEW EMAIL</th> 
                        <th>LINKEDIN</th> 
                        <th>TELEPHONE</th> 
                        <th>COMMENTAIRE</th> 
                        <th>STATUT</th>
                        
                        <th>COLLAB</th>
                        <th>TEMPS</th>
                        <th>NB MODIF</th>
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
<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script charset="utf-8" src="js/jquery.validate.min.js"></script>

<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_admin_global.js"></script>

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