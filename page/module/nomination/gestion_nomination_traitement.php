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
header("Location: ../index.php");
exit();
}
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
    
      	<h2 class="content-heading"><b>NOMINATION</b></h2>
        <div class="content-description">
        Heures : <span class="badge badge-bittersweet badge-rounded"><?php echo $heure;?> H</span> - Lignes : <span class="badge badge-bittersweet badge-rounded"><?php echo $ligne;?> Lignes</span>
        </div>      	
        
        <div class="main-container">
        
        <div class="container-block">            
        <div class="row">
        <div class="col-lg-6">
        <a class="btn btn-success icon-right btn-sm mr-3" href="NominationBiblioCollab">Afficher la totalité des données</a>
        <a class="btn btn-secondary icon-left btn-sm mr-3" href="Nominationobjectif">Gestion des objectifs</span></a>
        <button type="button" class="btn btn-info icon-left btn-sm mr-3" data-toggle="modal" data-target="#modal-settings">Recherche avancée</button>
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
                  <form action="NominationBiblioDetailsJour" method="post" name="filtre">
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
                								$query = $bdd->prepare("SELECT acide_intervenant_nomination, acide_intervenant_id_nomination FROM `nomination_acide` WHERE acide_intervenant_id_nomination <> 0 GROUP BY acide_intervenant_id_nomination");
                								$query->execute();	
                								while($query_collab = $query->fetch()){
                								 echo '<option value="'.$query_collab['acide_intervenant_id_nomination'].'">'.$query_collab['acide_intervenant_nomination'].'</option>';	
                								}
                								$query->closeCursor();						
                								?>
                                <option value="">Tout</option>
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
                    <button class="btn btn-info" type="submit">Filtrer les données</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
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
                                      
        <table class="datatable table table-striped" id="table_gestion_traitement_nomination">
            <thead>
                <tr>                    
                    <th>COLLAB</th> 
                    <th>DEBUT</th> 
                    <th>FIN</th>                    
                    <th>TRAITEMENT</th>
                    <th>JH</th>
                    <th>TOTAL</th>                        
                    <th>BO-ACIDE</th>
                    <th>SOCIÉTÉ NT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
                                         
        </div>
        </div>                                
        </div>
        </div>
                                
        <div class="main-container">
        <div class="container-block">            
        <div class="row">
        <div class="col-lg-6">
        <a class="btn btn-secondary icon-left btn-sm mr-3" href="NominationEnrichissement">Ajouter un Enrichissement métier <span class="btn-icon iconfont iconfont-plus-square"></span></a>
        </div>
        <div class="col-lg-3">       
        </div>
        <div class="col-lg-3">        
        </div>
        </div>       
        </div>
        <div class="container-block tabs-alpha">
            <ul class="nav nav-tabs tabs-alpha__nav-tabs">
              <li class="nav-item tabs-alpha__item">
                <a class="nav-link tabs-alpha__link active" data-toggle="tab" href="#tabs-alpha-home">
                  Sources de données à Date <span class="iconfont iconfont-circle-close tabs-alpha__tab-close-icon"></span>
                </a>
              </li>
              <li class="nav-item tabs-alpha__item">
                <a class="nav-link tabs-alpha__link" data-toggle="tab" href="#tabs-alpha-profile">
                  Enrichissement métier <span class="iconfont iconfont-circle-close tabs-alpha__tab-close-icon"></span>
                </a>
              </li>
            </ul>
            <div class="tab-content tabs-alpha__tab-content">
              <div class="tab-pane active" id="tabs-alpha-home" role="tabpanel" aria-expanded="true">
                <table class="table table-no-border">
                
                <thead>
                <tr>
                <?php
        				$query = $bdd->prepare("SELECT * FROM nomination_acide GROUP BY acide_publication_nomination DESC");
        				$query->execute();
        				while ($publication = $query->fetch()){
        				if(securite_bdd($db,$publication['acide_publication_nomination']) == 1){
        					echo '<th>ETAT MAJORS</th>';	
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 2){		
        					echo '<th>LE FAC</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 3){		
        					echo '<th>DECIDEURS MAGAZINE</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 4){		
        					echo '<th>CADREO</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 5){		
        					echo '<th>JDN IT</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 6){		
        					echo '<th>LE MONDE INFO</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 7){		
        					echo '<th>LES ECHOS</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 8){		
        					echo '<th>GOOGLE ALERTE</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 9){		
        					echo '<th>Alerte LINKEDIN</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 10){		
        					echo '<th>Alerte Nomination</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 11){		
        					echo '<th>Alerte KBC</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 12){		
        					echo '<th>LSA</th>';
        					}elseif(securite_bdd($db,$publication['acide_publication_nomination']) == 13){		
        					echo '<th>AGEFI</th>';
        					}else{$publication = 'Problème dans la colonne publication';}
        				}
        				$query->closeCursor(); 
        				
        				?>
                </tr>
                </thead>
                
                <tbody>
                <tr>
                <?php 
				
        				$query = $bdd->prepare("SELECT count(acide_publication_nomination) AS stat FROM nomination_acide GROUP BY acide_publication_nomination DESC");
        				$query->execute();
        				while ($publication = $query->fetch()){
        					echo '<td>'.$publication['stat'].'</td>';
        				}
        				$query->closeCursor();
        				?>
                </tr>               
                </tbody>
                
                </table>
              </div>
              <div class="tab-pane" id="tabs-alpha-profile" role="tabpanel" aria-expanded="false">
              <table class="table table-no-border">
                
                <thead>
                <tr>
                <?php
        				$query = $bdd->prepare("SELECT * FROM nomination_acide_like ORDER BY nomination_acide_like_id");
        				$query->execute();
        				while ($publication = $query->fetch()){
        					echo '<th>'.$publication['nomination_acide_like_nomination'].'</th>';
        				}
        				$query->closeCursor();
                ?> 
                </tr>
                </thead>
                
                <tbody>
                <tr>
                <?php
                
				
        				$query = $bdd->prepare("SELECT * FROM nomination_acide_like ORDER BY nomination_acide_like_id");
        				$query->execute();
        				while ($enrichissement = $query->fetch()){
        					$query_1 = $bdd->prepare("SELECT count(*) AS stat FROM nomination_acide WHERE acide_fe_nomination LIKE '%".$enrichissement['nomination_acide_like_mot']."%'");
        					$query_1->execute();
        					$publication = $query_1->fetchColumn();
        						echo '<td>'.$publication.'</td>';					
        					$query_1->closeCursor();
        				}
        				$query->closeCursor();
        				?>               
                
                </tr>              
                </tbody>
                
                </table>
              
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
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="module/nomination/table/js/webapp_acide_gestion_traitement_nomination.js"></script>
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