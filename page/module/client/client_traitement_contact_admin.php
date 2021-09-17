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
if($_GET['idcat'] == ''){$idcat = "";}else{$idcat = $_GET['idcat'];}
if($_GET['idcatt'] == ''){$idcatt = "";}else{$idcatt = $_GET['idcatt'];}

$query = $bdd->prepare("SELECT * FROM client_traitement WHERE siret_client = :siret_client GROUP BY siret_client");
$query->bindParam(":siret_client", $idcat, PDO::PARAM_STR);
$query->execute();
$doc = $query->fetch();
$query->closeCursor();	
$document = $doc['raison_sociale_client'];

$datetime = date("Y-m-d");

$query_ligne_taiter = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE reporting_contact <> 0");
$query_ligne_taiter->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
$query_ligne_taiter->bindParam(":date_calcul", $datetime, PDO::PARAM_STR);
$query_ligne_taiter->execute();
$ligne_taiter = $query_ligne_taiter->fetchColumn();
$query_ligne_taiter->closeCursor();
		
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
          <h2 class="content-heading">Traitement client : <b><?php echo $document; ?></b></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-6">
            <a class="btn btn-success icon-left mr-3" href="ClientBiblioAdmin-<?php echo $id; ?>-2-<?php echo $idcatt; ?>">Retour aux dossiers <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
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
                echo '<table class="datatable table table-striped" id="table_traitement" data-id="'.$idcat.'"  data-mode="">';
                
                ?>
                <thead>
                	
                    <tr>
                        
                        <th>Admin</th>

                        <th>SOCIÉTÉ</th>
                        <th>CIVILITÉ</th> 
                        <th>PRÉNOM</th>
                        <th>NOM</th>
                        <th>FONCTION</th>
                        <th>EMAIL</th>
                        <th>STATUT</th>                        

                        <th>Collab</th>
                        <th>Temps</th>
                        <th>modif</th>
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
                    <label for="nature">CIVILITÉ :</label>
                    <div class="field_container">
                    
                        <input type="text" class="form-control old" id="civ_o" name="civ_o" disabled>
                        <select id="civ" name="civ" class="form-control">
                            <option value="Non Renseigné" selected>CHOISIR UNE CIVILITÉ</option>
                            <option value="MR">MR</option>
                            <option value="MME">MME</option>
                        </select>
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">PRÉNOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="prenom_o" name="prenom_o" disabled>
                        <input type="text" class="form-control new" id="prenom" name="prenom">
                        
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">NOM :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="nom_o" name="nom_o" disabled>
                        <input type="text" class="form-control new" id="nom" name="nom">
                    </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">FONCTION :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="fonction_o" name="fonction_o" disabled>
                        <input type="text" class="form-control new" id="fc" name="fc">
                    </div>
            </div>
            <div class="input_container">
            <label for="reporting">SERVICE : </label>
            <div class="field_container">
                <select id="service" name="service" class="form-control">
                    <option value="0" selected>CHOISIR UN SERVICE</option>
                    <option value="1">ACHATS</option>
                    <option value="2">INFORMATIQUE</option>
                </select>
            </div>
            </div>
            
            <div class="input_container">
                    <label for="nature">EMAIL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control old" id="email_o" name="email_o" disabled>
                        <input type="email" class="form-control new" id="email" name="email">
                    </div>
            </div>            
            <div class="input_container">
                <label for="nature">NEW LINKEDIN :</label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="lk" name="lk">
                    </div>
            </div>
            
            <div class="input_container">
            		<label for="nature">TEL :</label>
                    <div class="field_container">
                        <input type="text" class="form-control full" id="tel2" name="tel2">
                    </div>
            </div>
			<div class="input_container">
            <label for="reporting">TYPE : </label>
            <div class="field_container">
                <select id="type" name="type" class="form-control">
                    <option value="0" selected>CHOISIR TYPE DE RECHERCHE</option>
                    <option value="1">PAR TEL</option>
                    <option value="2">PAR RECHERCHE</option>
                </select>
            </div>
            </div>
            <div class="input_container">
            		<label for="nature">COMMENTAIRE COLLAB :</label>
                    <div class="field_container">
              <textarea rows="4" placeholder="Commentaire" class="form-control full" id="commentaire_collab" name="commentaire_collab" disabled></textarea>
            </div>
            </div>
           
            <hr>
            <center><p><b>Partie Administrateur :</b></p></center>
            <hr>
             <div class="input_container">
            <label for="reporting" style="color:#F00; font-weight:bolder">STATUT : </label>
            <div class="field_container">
                <select id="stat" name="stat" class="form-control" >
                    <option value="0" selected>CHOISIR UN STATUT</option>
                    
                    <option value="1">Non Vérifié</option>
                    <option value="2">A quitté</option>
                    <option value="3">OK</option>
                    <option value="4">OK avec modif</option>
                    <option value="5">Remplacé</option>
                    <option value="6">Hors Cible</option>
                    <option value="7">Ajout</option>
                    
                </select>
            </div>
            </div>
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

<script charset="utf-8" src="module/client/table/js/webapp_client_traitement_contact_admin.js"></script>

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