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
if(!checkAdmin()) {
die("Secured");
exit();
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
  <title>RAPPORTS</title>
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


<link rel="stylesheet" href="module/rapport/table/css/layout_rapport.css">
  

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
          
            <div class="row">
              <div class="col-lg-12">
                <div class="widget widget-welcome">
                  <div class="widget-welcome__message">
                    <h4 class="widget-welcome__message-l1">RAPPORT SERVICE IPD</h4>
                    <h6 class="widget-welcome__message-l2">Intervalle : <?php if(empty($_POST['intervalle'])){echo '<span class="badge badge-bittersweet badge-rounded">Non défini</span>';}else{echo '<span class="badge badge-success badge-rounded">'.$_POST['intervalle'].'</span>';}?> | 
                    
                          Équipes : 
                          <?php if(empty($_POST['equipe_id'])){echo '<span class="badge badge-bittersweet badge-rounded">Non défini</span>';}else{
                          $query = $bdd->prepare("SELECT name_equipe FROM user_equipe WHERE id_equipe = :id_equipe");
                          $query->bindParam(":id_equipe", $_POST['equipe_id'], PDO::PARAM_INT);              
                          $query->execute();  
                          $query_user = $query->fetch();              
                          $query->closeCursor();
                          echo '<span class="badge badge-success badge-rounded">'.$query_user['name_equipe'].'</span>';
                          }?>
                      </h6>
                  </div>
                  <div class="widget-welcome__stats">
                    <div class="widget-welcome__stats-item early-growth">
                      <?php
if (isset($_POST['intervalle'])){
$date = $_POST['intervalle'];
}else{ $date = '2005-01-01 - 2100-01-01';}

if($date == ''){

$debut = '2005-01-01';
$fin = '2100-01-01';


}else{
$debut = substr($date, 0,10);
$fin = substr($date, 13,22);
}
$query_rapport = $bdd->prepare("SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS date_debut FROM data_ie AS a  WHERE a.date_calcul between :debut and :fin
UNION
SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND  id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin
UNION
SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND  id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin
UNION
SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide) FROM acide AS x WHERE x.date_calcul between :debut and :fin
UNION
SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin
UNION
SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide), (SELECT count(*) FROM nomination_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin
UNION

        SELECT op_ext, nb_cc_ext, obj_ext, rea_ext, '', fin, debut FROM externe AS b WHERE b.debut between :debut and :fin
        UNION
SELECT DISTINCT(SELECT nom_cat FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin
        UNION
        SELECT DISTINCT(SELECT nom_cat FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin




");
$query_rapport->bindParam(":debut", $debut, PDO::PARAM_STR);
  $query_rapport->bindParam(":fin", $fin, PDO::PARAM_STR);
$query_rapport->execute();
$jh_somme = 0;
while ($rapport = $query_rapport->fetch()){

if($rapport['datee'] == NULL){$datee = '00:49:18';}else{$datee = $rapport['datee'];}
$traitement = '<strong>'.$datee.'</strong>';
$pieces = explode(":", $datee);   
$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);    
$jh = round($duree_decimal/8, 2);

$jh_somme = $jh_somme + $jh;

$ajout_35 = ($jh_somme*35)/100;
}
$query_rapport->closeCursor();
if($date == '2005-01-01 - 2100-01-01'){
                      ?>
                      <span class="widget-welcome__stats-item-value"><?php echo round($jh_somme + $ajout_35 + 1244,1); ?></span>
                      <?php }else{?>
                      <span class="widget-welcome__stats-item-value"><?php echo round($jh_somme + $ajout_35 + 1244,1); ?></span>
                      <?php }?>
                      <span class="widget-welcome__stats-item-desc">JH GLOBAL</span>
                    </div>
                    <div class="widget-welcome__stats-item monthly-growth">
                      <span class="widget-welcome__stats-item-value">**</span>
                      <span class="widget-welcome__stats-item-desc">JH MOYEN</span>
                    </div>
                  </div>
                </div>
              </div> 
            </div>

                <div class="row">


                  <div class="col-md-6 col-lg-3">
                    <button class="btn btn-success btn-block mb-4" id="team">ÉQUIPES IPD</button>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <button type="button" class="btn btn-info btn-block mb-4" data-toggle="modal" data-target="#modal-settings">INTERVALLE DE RECHERCHE</button>        
              
                      <div id="modal-settings" class="modal fade custom-modal-tabs">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header has-border">
                              <h5 class="modal-title">Filtre de recherche :</h5>
                              <div class="btn-group btn-collection nav custom-modal__header-tabs" role="group" aria-label="Basic example">
                                <!--<button class="btn btn-secondary nav-item nav-link" type="button" data-toggle="tab" data-target="#modal-settings-account">Account</button>
                                <button class="btn btn-secondary nav-item nav-link" type="button" data-toggle="tab" data-target="#modal-settings-voice">Voice</button>-->
                                <button class="btn btn-secondary nav-item nav-link active" type="button" data-toggle="tab" data-target="#modal-settings-notifications">Configuration</button>
                              </div>
                              <button type="button" class="close custom-modal__close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="iconfont iconfont-modal-close"></span>
                              </button>
                            </div>
                            <form action="StatHebdo" method="post" name="filtre">
                            <div class="modal-body">
                              <div class="tab-content">
                                <!--<div class="tab-pane fade" id="modal-settings-account">Account</div>
                                <div class="tab-pane fade" id="modal-settings-voice">Voice</div>-->
                                <div class="tab-pane fade show active" id="modal-settings-notifications">
                                
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="input-device">Équipe</label>
                                        <select class="form-control" data-placeholder="Toutes les équipe du service" name="equipe_id">
                                          <option></option>
                                          <?php
                                          $query = $bdd->prepare("SELECT DISTINCT user_equipe.name_equipe,  user_equipe.id_equipe FROM user_equipe INNER JOIN users WHERE users.equipe_id = user_equipe.id_equipe AND user_equipe.admin_equipe = 0");
                                          $query->execute();  
                                          while($query_collab = $query->fetch()){
                                           echo '<option value="'.$query_collab['id_equipe'].'">'.$query_collab['name_equipe'].'</option>'; 
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
                    <div class="col-md-6 col-lg-3">
                      <a class="btn btn-danger btn-block mb-4" href="StatHebdo" >ANNULER L'INTERVALLE</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                    <a class="btn btn-primary btn-block mb-4" href="javascript:window.location.reload()">Rafraîchissement des données</a>
                    </div>


                </div>
           
           
           
           
           <?php if(empty($_POST['equipe_id'])){ ?>
           <div class="main-container">
              <div class="container-block">
              <div class="row">

                
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">LEAD GENERATION :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe" data-equipe="8" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>    
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
           <?php }else{if($_POST['equipe_id'] == 8){ ?>
           <div class="main-container">
              <div class="container-block">
              <div class="row">

                
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">LEAD GENERATION :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe" data-equipe="8" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>    
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
           <?php }}?> 
           
           
           
           
           <?php if(empty($_POST['equipe_id'])){ ?>
           <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">COLLECTIVITÉ :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_collect" data-equipe="16" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
           <?php }else{if($_POST['equipe_id'] == 16){ ?>
           <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">COLLECTIVITÉ :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_collect" data-equipe="16" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
           <?php }}?> 
           
           
           
            

            <?php if(empty($_POST['equipe_id'])){ ?>
            <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">QUALITÉ DE DONNEES :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_qd" data-equipe="2" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
           <?php }else{if($_POST['equipe_id'] == 2){ ?>
           <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">QUALITÉ DE DONNEES :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_qd" data-equipe="2" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
           <?php }}?> 
           
           
            
           
           

             <?php if(empty($_POST['equipe_id'])){ ?>
                <div class="main-container">
                <div class="container-block">
                <div class="row">
                <div class="col-lg-12">
                <h4 class="widget-welcome__message-l1">INDUSTRIE :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_indus" data-equipe="6" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
            <?php }else{if($_POST['equipe_id'] == 6){ ?>
              <div class="main-container">
                <div class="container-block">
                <div class="row">
                <div class="col-lg-12">
                <h4 class="widget-welcome__message-l1">INDUSTRIE :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_indus" data-equipe="6" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th> 
                <th>Performance Moyenne</th>     
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
      <?php }}?> 

             <?php if(empty($_POST['equipe_id'])){ ?>
      <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">RENFORT :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_rf" data-equipe="21" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th>  
                <th>Performance Moyenne</th>    
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
      
      <?php }else{if($_POST['equipe_id'] == 21){ ?> 
            <div class="main-container">
            <div class="container-block">
              <div class="row">
                <div class="col-lg-12">
                  <h4 class="widget-welcome__message-l1">RENFORT :</h4>
                <div class="content table-responsive table-full-width">
                <table class="datatable table table-striped" id="table_rapport_equipe_rf" data-equipe="21" data-date="<?php if(isset($_POST['intervalle'])){echo $_POST['intervalle'];}else{}?>">
                <thead>
                <tr>
                <th>Mission</th>
                <th>Participants</th>
                <th>Volume Global</th>
                <th>Cumul Traité</th>
                <th>Cumul temps</th>
                <th>JH à Date</th>  
                <th>Performance Moyenne</th>    
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
          <?php }}?>        
        </div>
    </div>
</div>

<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>


<script charset="utf-8" src="module/rapport/table/js/webapp_equipe.js"></script>


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
<script src="vendor/select2/js/select2.full.min.js"></script>
<div class="sidebar-mobile-overlay"></div>   
</body>
</html>