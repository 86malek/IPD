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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Collectivité</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
  
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
<div class="container-fluid m-tasks">
  <div class="main-container m-tasks__container container-heading-bordered">
    <h2 class="container-heading">
      <span>Bibliothèque des Lots Collectivité</span>
      <a class="btn btn-primary icon-right btn-sm mr-3" href="javascript:window.location.reload()">Rafraîchissement des lots <span class="btn-icon iconfont iconfont-refresh"></span></a>
    </h2>
    <div class="container-body m-tasks__columns">
      
      
      <div class="m-tasks__column m-tasks__column--pending list-group">      
      
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">Lots EN ATTENTES</span>
        </div>
        <div class="m-tasks__items"> 
        <?php
				
			$attente = mysqli_query($db,"SELECT * FROM collectivite_lot WHERE collect_lot_id NOT IN (SELECT collect_lot_id FROM collectivite_lot_synthese) ORDER BY collect_lot_date_traitement ASC") or die(mysqli_connect_error());
			$count = mysqli_num_rows($attente);
			if($count == 0){			
				
			}else{
				while($donnees_attente = mysqli_fetch_array($attente)){				
					
					echo '<a href="CollectBiblio-'.$donnees_attente['collect_lot_id'].'">       
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-buttercup mb-3 mr-3">En attente de traitement</span><br>
					</div>
					<span class="m-tasks__item-date">Pour le  : <span class="badge badge-sm badge-outline-primary mb-3 mr-3">'.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</span></span>				
					</div>
					</a>';
					
				}
			}
		
		?>
        </div>
        
        
      </div>
      
      <div class="m-tasks__column m-tasks__column--in-progress list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">Lots EN COURS</span>
        </div>
        <div class="m-tasks__items">
          <?php
				
			$attente = mysqli_query($db,"SELECT collectivite_lot.collect_lot_id, collectivite_lot.collect_lot_nom, collectivite_lot.collect_lot_insert, collectivite_lot_synthese.collect_lot_synthese_intervenant, collectivite_lot_synthese.collect_lot_synthese_id_intervenant, collectivite_lot.collect_lot_date_traitement FROM collectivite_lot INNER JOIN collectivite_lot_synthese ON collectivite_lot_synthese.collect_lot_id = collectivite_lot.collect_lot_id WHERE collectivite_lot_synthese.niveau = 1") or die(mysqli_connect_error());
			$count = mysqli_num_rows($attente);
			if($count == 0){
				
			
					
			}else{
			while($donnees_attente = mysqli_fetch_array($attente)){
				
				
				if($donnees_attente['collect_lot_synthese_id_intervenant'] == $_SESSION['user_id']){
					
				echo '<a href="CollectBiblio-debut-'.$donnees_attente['collect_lot_id'].'">       
				<div class="m-tasks__item list-group-item">            
				<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
				<div class="m-tasks__item-desc">
				<span class="badge badge-lasur mb-3 mr-3">En cours</span><br>
				</div>
				<span class="m-tasks__item-date">Pour le : <b>'.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</b></span><br>
				<span class="m-tasks__item-date">Intervenant : <b>'.$donnees_attente['collect_lot_synthese_intervenant'].'</b></span>
				<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
				</div>
				</a>';
				
				}else{
					
					echo '      
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-lasur mb-3 mr-3">En cours</span><br>
					</div>
					<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</span><br>
					<span class="m-tasks__item-date">Intervenant : <b>'.$donnees_attente['collect_lot_synthese_intervenant'].'</b></span>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>';
				
				}
				
				
			}
			}
		
		?>         
          
        </div>
      </div>
      
      <div class="m-tasks__column m-tasks__column--completed list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">Lots EN PROGRESSION</span>
        </div>
        <div class="m-tasks__items">
          
          <?php
			
			$attente = mysqli_query($db,"SELECT collectivite_lot_synthese.niveau, collectivite_lot.collect_lot_statut, collectivite_lot.collect_lot_id, collectivite_lot.collect_lot_nom, collectivite_lot.collect_lot_insert, collectivite_lot_synthese.collect_lot_synthese_intervenant, collectivite_lot_synthese.collect_lot_synthese_id_intervenant, collectivite_lot.collect_lot_date_traitement FROM collectivite_lot INNER JOIN collectivite_lot_synthese ON collectivite_lot_synthese.collect_lot_id = collectivite_lot.collect_lot_id GROUP BY collectivite_lot_synthese.collect_lot_id") or die(mysqli_connect_error());
			while($donnees_attente = mysqli_fetch_array($attente)){	
			
			$verif_ligne = mysqli_query($db,"SELECT * FROM collectivite_fiche WHERE collect_fiche_statut=0 AND collect_lot_id =".$donnees_attente['collect_lot_id']."") or die(mysqli_connect_error());
			$verif_ligne = mysqli_num_rows($verif_ligne);
			
			$verif_etat = mysqli_query($db,"SELECT * FROM collectivite_lot_synthese WHERE niveau = 1 AND collect_lot_id =".$donnees_attente['collect_lot_id']."") or die(mysqli_connect_error());
			$verif_etat = mysqli_num_rows($verif_etat);
			
			
				if($verif_ligne > 0){
					
					/*if($verif_etat > 0){
					echo '      
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-bittersweet mb-3 mr-3">EN PROGRESSION</span><br>					
					</div>
					<span class="m-tasks__item-date">pour le : <b>'.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</b></span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					';
					}else{*/
					echo '<a href="CollectBiblio-'.$donnees_attente['collect_lot_id'].'">      
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-bittersweet mb-3 mr-3">EN PROGRESSION</span><br>					
					</div>
					<span class="m-tasks__item-date">Pour le : <b>'.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</b></span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					</a>';
					
					/*}*/
				
				}else{			
					
						
					
					
					
				}
				
			}
		
		?>
        
        </div>
        
      </div>
      
      <div class="m-tasks__column m-tasks__column--completed list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">Lots TERMINÉES</span>
        </div>
        <div class="m-tasks__items">
          
          <?php
			
			$attente = mysqli_query($db,"SELECT collectivite_lot.collect_lot_id, collectivite_lot.collect_lot_nom, collectivite_lot.collect_lot_insert, collectivite_lot_synthese.collect_lot_synthese_intervenant, collectivite_lot_synthese.collect_lot_synthese_id_intervenant, collectivite_lot.collect_lot_date_traitement FROM collectivite_lot INNER JOIN collectivite_lot_synthese ON collectivite_lot_synthese.collect_lot_id = collectivite_lot.collect_lot_id GROUP BY collectivite_lot.collect_lot_id") or die(mysqli_connect_error());
			while($donnees_attente = mysqli_fetch_array($attente)){	
			
			$verif_ligne = mysqli_query($db,"SELECT * FROM collectivite_fiche WHERE collect_fiche_statut=0 AND collect_lot_id =".$donnees_attente['collect_lot_id']."") or die(mysqli_connect_error());
			$verif_ligne = mysqli_num_rows($verif_ligne);
			
				if($verif_ligne > 0){
					
					
				
				}else{			
					
					
					echo '<a href="CollectBiblio-'.$donnees_attente['collect_lot_id'].'">       
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['collect_lot_nom'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-shamrock mb-3 mr-3">TERMINÉ</span><br>
					</div>
					<span class="m-tasks__item-date">Pour le : <b>'.date("d-M-Y", strtotime($donnees_attente['collect_lot_date_traitement'])).'</b></span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					</a>';
					
				}
				
			}
		
		?>
        </div>
        
      </div>
      
      
    </div>
</div>

  </div>
</div>   
</div>  
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="vendor/sortable/sortable.min.js"></script>
<script src="js/preview/tasks.min.js"></script>

<div class="sidebar-mobile-overlay"></div>  
</body>
</html>