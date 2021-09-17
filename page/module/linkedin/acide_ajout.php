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

if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){
	
if($_GET['id'] == ''){$id = "";}else{$id = $_GET['id'];}

$query = $bdd->prepare("SELECT * FROM cat_acide WHERE id_cat_acide = :id");
$query->bindParam(":id", $id, PDO::PARAM_INT);
$query->execute();
$donnees_update = $query->fetch();
$query->closeCursor();	

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
  <title>TÉLÉCHARGEMENT - CARTES VISITES (LK)</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">
<link rel="stylesheet" href="module/linkedin/table/css/layout_lk.css">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script type="text/javascript">
function afficheLoader(action)
{
    if(action == "load")
    {  
        document.getElementById('loading_container').style.display = 'block';
        document.getElementById('formulaire').style.display = 'none';
    }
    else
    {
        document.getElementById('loading_container').style.display = 'none';
        document.getElementById('formulaire').style.display = 'block';
    }
     
}
 
<?php
if (isset($a)) {
    echo 'afficheLoader();';
}
?>
</script>

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
                <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){
                  	echo '<h4 class="widget-welcome__message-l1">Modification du fichier Cartes Visites (LK)</h4>';
                }else{
                	echo '<h4 class="widget-welcome__message-l1">Chargement d\'un fichier Cartes Visites (LK)</h4>';
                }?>
                  
                </div>
                
              </div>
            </div>
            </div>        
          	<div class="main-container">            
            <div class="container-block">
            <div class="row">
            
            <div class="col-6">            
            <?php
			if(!empty($_POST['doSubmitdata']) && $_POST['doSubmitdata'] == 'download')
			{	
				
				if(isset($_FILES['files']))
				{ 
					$dossier = 'upload/';
					$fichier = basename($_FILES['files']['name']);
					$fichier = strtr($fichier, 
					'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
					'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
					$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
					$taille_maxi = 100000000;
					$taille = filesize($_FILES['files']['tmp_name']);
					$extensions = array('.csv');
					$extension = strrchr($_FILES['files']['name'], '.'); 
					if(!in_array($extension, $extensions))
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Merci de charger un fichier de type <b>CSV</b>
					</h6>
					</div>
					</div>          
					</div>';
					}
					if($taille>$taille_maxi)
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Taille maximale dépasée !
					</h6>
					</div>
					</div>          
					</div>';
					}
					if(file_exists($dossier . $fichier))
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Le fichier existe déja sous cette nomination !
					</h6>
					</div>
					</div>          
					</div>';
					}
					if(!isset($erreur))
					{
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
						{
							 	
								$query = $bdd->prepare("INSERT INTO cat_acide (`nom_cat_acide`, `fichier_cat_acide`, `date_ajout_cat_acide`) VALUES (:nom_cat_acide, :fichier, now())");
								$query->bindParam(":nom_cat_acide", $_POST['doc_name'], PDO::PARAM_STR);
								$query->bindParam(":fichier", $fichier, PDO::PARAM_STR);
								$query->execute();
								$query->closeCursor();
								
								$query = $bdd->prepare("SELECT MAX(id_cat_acide)as max FROM cat_acide");
								$query->execute();
								$donnees_max = $query->fetch();
								$query->closeCursor();
								
								$fichier = fopen($dossier.$fichier, "r");					
																
								$cpt = 1;
								
								if ($fichier !== FALSE) {
									
								while (($data = fgetcsv($fichier, 4096, ";"))) {
									
								$num = count($data);
								
								$cpt++;
								
								for ($c=0; $c < $num; $c++) {
								$col[$c] = $data[$c];
								}						
								
								
								if($col[0] !=''){  
								
								
								$verif = $bdd->prepare("SELECT count(*) FROM acide WHERE id_contact_acide = :id_contact_acide");	
									$verif->bindParam(":id_contact_acide", $col[3], PDO::PARAM_INT);
									//$verif->bindParam(":id_cat_acide", $donnees_max['max'], PDO::PARAM_INT);
									$verif->execute();
									$verif_import = $verif->fetchColumn();
									$verif->closeCursor();
									if($verif_import == 0){

											$query = $bdd->prepare("INSERT INTO acide (id_cat_acide, `raison_sociale_acide`,`code_postal_acide`,`ville_acide`,`id_contact_acide`,`statut_contact_acide`,`civilite_acide`,`nom_acide`,`prenom_acide`,`id_societe_acide`,`fonction_acide`,`url_linkedin_acide`,`new_nom_prenom_acide`,`old_nom_prenom_acide`,`statut_nom_prenom_acide`,`new_poste_acide`,`old_poste_acide`,`statut_poste_acide`,`new_entreprise_acide`,`old_entreprise_acide`,`statut_entreprise_acide`,`new_date_entree_entreprise_acide`,`old_date_entree_entreprise_acide`,`statut_date_entree_entreprise_acide`) VALUES (:id_cat_acide,:raison_sociale_acide,:code_postal_acide,:ville_acide,:id_contact_acide,:statut_contact_acide,:civilite_acide,:nom_acide,:prenom_acide,:id_societe_acide,:fonction_acide,:url_linkedin_acide,:new_nom_prenom_acide,:old_nom_prenom_acide,:statut_nom_prenom_acide,:new_poste_acide,:old_poste_acide,:statut_poste_acide,:new_entreprise_acide,:old_entreprise_acide,:statut_entreprise_acide,:new_date_entree_entreprise_acide,:old_date_entree_entreprise_acide,:statut_date_entree_entreprise_acide)");
											
											$query->bindParam(":id_cat_acide", $donnees_max['max'], PDO::PARAM_INT);
											$query->bindParam(":raison_sociale_acide", utf8_encode($col[0]), PDO::PARAM_STR);
											$query->bindParam(":code_postal_acide", utf8_encode($col[1]), PDO::PARAM_INT);
											$query->bindParam(":ville_acide", utf8_encode($col[2]), PDO::PARAM_STR);
											$query->bindParam(":id_contact_acide", utf8_encode($col[3]), PDO::PARAM_INT);
											$query->bindParam(":statut_contact_acide", utf8_encode($col[4]), PDO::PARAM_STR);
											$query->bindParam(":civilite_acide", utf8_encode($col[5]), PDO::PARAM_STR);
											$query->bindParam(":nom_acide", utf8_encode($col[6]), PDO::PARAM_STR);
											$query->bindParam(":prenom_acide", utf8_encode($col[7]), PDO::PARAM_STR);
											$query->bindParam(":id_societe_acide", utf8_encode($col[8]), PDO::PARAM_INT);
											$query->bindParam(":fonction_acide", utf8_encode($col[9]), PDO::PARAM_STR);
											$query->bindParam(":url_linkedin_acide", utf8_encode($col[10]), PDO::PARAM_STR);
											$query->bindParam(":new_nom_prenom_acide", utf8_encode($col[11]), PDO::PARAM_STR);
											$query->bindParam(":old_nom_prenom_acide", utf8_encode($col[12]), PDO::PARAM_STR);
											$query->bindParam(":statut_nom_prenom_acide", utf8_encode($col[13]), PDO::PARAM_STR);
											$query->bindParam(":new_poste_acide", utf8_encode($col[14]), PDO::PARAM_STR);
											$query->bindParam(":old_poste_acide", utf8_encode($col[15]), PDO::PARAM_STR);
											$query->bindParam(":statut_poste_acide", utf8_encode($col[16]), PDO::PARAM_STR);
											$query->bindParam(":new_entreprise_acide", utf8_encode($col[17]), PDO::PARAM_STR);
											$query->bindParam(":old_entreprise_acide", utf8_encode($col[18]), PDO::PARAM_STR);
											$query->bindParam(":statut_entreprise_acide", utf8_encode($col[19]), PDO::PARAM_STR);
											$query->bindParam(":new_date_entree_entreprise_acide", utf8_encode($col[20]), PDO::PARAM_STR);
											$query->bindParam(":old_date_entree_entreprise_acide", utf8_encode($col[21]), PDO::PARAM_STR);
											$query->bindParam(":statut_date_entree_entreprise_acide", utf8_encode($col[22]), PDO::PARAM_STR);
											$query->execute();
											$query->closeCursor();

									}
								
								}
								
								}
								fclose($fichier);
								}      
				
								echo "<script type='text/javascript'>document.location.replace('Linkedin');</script>";
								
							  
						 } else {
							 
							  echo '<div class="alert custom-alert custom-alert--danger" role="alert">
									<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
									<div class="custom-alert__top-side">
									<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
									<div class="custom-alert__body">
									<h6 class="custom-alert__heading">
									<b> Alerte - </b> Echec de l\'upload !
									</h6>
									</div>
									</div>          
									</div>';
							  
						 }
						 
					}else{
						
						 echo $erreur;
						 
					}
				
				}
			 
			$a = true;	
			}elseif(!empty($_POST['doSubmitdatamodif']) && $_POST['doSubmitdatamodif'] == 'downloadmodif')
			{	
				
				if(isset($_FILES['files']))
				{ 
					$dossier = 'upload/';
					$fichier = basename($_FILES['files']['name']);
					$fichier = strtr($fichier, 
					'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
					'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
					$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
					$taille_maxi = 100000000;
					$taille = filesize($_FILES['files']['tmp_name']);
					$extensions = array('.csv');
					$extension = strrchr($_FILES['files']['name'], '.');
					if(!in_array($extension, $extensions))
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Merci de charger un fichier de type <b>CSV</b>
					</h6>
					</div>
					</div>          
					</div>';
					}
					if($taille>$taille_maxi)
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Taille maximale dépasée !
					</h6>
					</div>
					</div>          
					</div>';
					}
					if(file_exists($dossier . $fichier))
					{
						$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
						<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
						<div class="custom-alert__top-side">
						<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
						<div class="custom-alert__body">
						<h6 class="custom-alert__heading">
						Le fichier existe déja sous cette nomination !
						</h6>
						</div>
						</div>          
						</div>';
					}
					if(!isset($erreur))
					{
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
						{
														
							if(file_exists($dossier . $donnees_update['fichier_cat_acide']))
							{
							$chemin = "upload/".$donnees_update['fichier_cat_acide'];						
							
							unlink($chemin);
							
							}
							
								$query = $bdd->prepare("DELETE FROM acide WHERE id_cat_acide = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->execute();
								$query->closeCursor();
								
								$query = $bdd->prepare("UPDATE cat_acide SET `nom_cat_acide` = :nom_cat_acide,`fichier_cat_acide` = :fichier_cat_acide,`date_modification_cat_acide` = now() WHERE id_cat_acide = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->bindParam(":nom_cat_acide", $_POST['doc_name'], PDO::PARAM_STR);
								$query->bindParam(":fichier_cat_acide", $fichier, PDO::PARAM_STR);
								$query->execute();
								$query->closeCursor();							
								
								$fichier = fopen($dossier.$fichier, "r");	
															
								$cpt = 1;							

											if ($fichier !== FALSE) {										
											 
											while (($data = fgetcsv($fichier, 4096, ";"))) {
												
											$num = count($data);
											$cpt++;
											
											for ($c=0; $c < $num; $c++) {
											$col[$c] = $data[$c];
											}
											
											
											
											if($col[0] !=''){ 
											$verif = $bdd->prepare("SELECT count(*) FROM acide WHERE id_contact_acide = :id_contact_acide");	
											$verif->bindParam(":id_contact_acide", $col[3], PDO::PARAM_INT);
											//$verif->bindParam(":id_cat_acide", $donnees_max['max'], PDO::PARAM_INT);
											$verif->execute();
											$verif_import = $verif->fetchColumn();
											$verif->closeCursor();
											if($verif_import == 0){
											
											$query = $bdd->prepare("INSERT INTO acide (id_cat_acide, `raison_sociale_acide`,`code_postal_acide`,`ville_acide`,`id_contact_acide`,`statut_contact_acide`,`civilite_acide`,`nom_acide`,`prenom_acide`,`id_societe_acide`,`fonction_acide`,`url_linkedin_acide`,`new_nom_prenom_acide`,`old_nom_prenom_acide`,`statut_nom_prenom_acide`,`new_poste_acide`,`old_poste_acide`,`statut_poste_acide`,`new_entreprise_acide`,`old_entreprise_acide`,`statut_entreprise_acide`,`new_date_entree_entreprise_acide`,`old_date_entree_entreprise_acide`,`statut_date_entree_entreprise_acide`) VALUES (:id_cat_acide,:raison_sociale_acide,:code_postal_acide,:ville_acide,:id_contact_acide,:statut_contact_acide,:civilite_acide,:nom_acide,:prenom_acide,:id_societe_acide,:fonction_acide,:url_linkedin_acide,:new_nom_prenom_acide,:old_nom_prenom_acide,:statut_nom_prenom_acide,:new_poste_acide,:old_poste_acide,:statut_poste_acide,:new_entreprise_acide,:old_entreprise_acide,:statut_entreprise_acide,:new_date_entree_entreprise_acide,:old_date_entree_entreprise_acide,:statut_date_entree_entreprise_acide)");
								
											$query->bindParam(":id_cat_acide", $id, PDO::PARAM_INT);
											$query->bindParam(":raison_sociale_acide", utf8_encode($col[0]), PDO::PARAM_STR);
											$query->bindParam(":code_postal_acide", utf8_encode($col[1]), PDO::PARAM_INT);
											$query->bindParam(":ville_acide", utf8_encode($col[2]), PDO::PARAM_STR);
											$query->bindParam(":id_contact_acide", utf8_encode($col[3]), PDO::PARAM_INT);
											$query->bindParam(":statut_contact_acide", utf8_encode($col[4]), PDO::PARAM_STR);
											$query->bindParam(":civilite_acide", utf8_encode($col[5]), PDO::PARAM_STR);
											$query->bindParam(":nom_acide", utf8_encode($col[6]), PDO::PARAM_STR);
											$query->bindParam(":prenom_acide", utf8_encode($col[7]), PDO::PARAM_STR);
											$query->bindParam(":id_societe_acide", utf8_encode($col[8]), PDO::PARAM_INT);
											$query->bindParam(":fonction_acide", utf8_encode($col[9]), PDO::PARAM_STR);
											$query->bindParam(":url_linkedin_acide", utf8_encode($col[10]), PDO::PARAM_STR);
											$query->bindParam(":new_nom_prenom_acide", utf8_encode($col[11]), PDO::PARAM_STR);
											$query->bindParam(":old_nom_prenom_acide", utf8_encode($col[12]), PDO::PARAM_STR);
											$query->bindParam(":statut_nom_prenom_acide", utf8_encode($col[13]), PDO::PARAM_STR);
											$query->bindParam(":new_poste_acide", utf8_encode($col[14]), PDO::PARAM_STR);
											$query->bindParam(":old_poste_acide", utf8_encode($col[15]), PDO::PARAM_STR);
											$query->bindParam(":statut_poste_acide", utf8_encode($col[16]), PDO::PARAM_STR);
											$query->bindParam(":new_entreprise_acide", utf8_encode($col[17]), PDO::PARAM_STR);
											$query->bindParam(":old_entreprise_acide", utf8_encode($col[18]), PDO::PARAM_STR);
											$query->bindParam(":statut_entreprise_acide", utf8_encode($col[19]), PDO::PARAM_STR);
											$query->bindParam(":new_date_entree_entreprise_acide", utf8_encode($col[20]), PDO::PARAM_STR);
											$query->bindParam(":old_date_entree_entreprise_acide", utf8_encode($col[21]), PDO::PARAM_STR);
											$query->bindParam(":statut_date_entree_entreprise_acide", utf8_encode($col[22]), PDO::PARAM_STR);
											$query->execute();
											$query->closeCursor();
											}
											
											}
											
											}
											fclose($fichier);
											} 
							        echo "<script type='text/javascript'>document.location.replace('Linkedin');</script>";
							
							  
						 } else {
							 
							  	echo '<div class="alert custom-alert custom-alert--danger" role="alert">
								<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
								<div class="custom-alert__top-side">
								<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
								<div class="custom-alert__body">
								<h6 class="custom-alert__heading">
								<b> Alerte - </b> Echec de l\'upload !
								</h6>
								</div>
								</div>          
								</div>';
							  
						 }
						 
					}else{
						
						 echo $erreur;
						 
					}
				
				}
			 
			$a = true;	
			}
			?>
            <div id="loading_container" style="display : none;">
                <div id="loading_container2">
                    <div id="loading_container3">
                        <div id="loading_container4">
                            Chargement du fichier en cours
                        </div>
                    </div>
                </div>
            </div>
            <div id="formulaire">
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){?>
			<form method="post" action="LinkedinAjout-update-<?php echo $id; ?>.html"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
			<?php }else{ ?>
            <form method="post" action="LinkedinAjout"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
            <?php } ?>
			<div class="form-group">
          	<label for="read-only">Nom l'Opération : <span class="required">*</span></label>
			<input type="text" placeholder="Aucune restriction de taille ..." class="form-control" name="doc_name" id="doc_name" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['nom_cat_acide'];}?>" required>
			</div>
            
            
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){?>
            <div class="alert custom-alert custom-alert--info" role="alert">
              <div class="custom-alert__top-side">
                <span class="alert-icon iconfont iconfont-alert-info custom-alert__icon"></span>
                <div class="custom-alert__body">
                  <h6 class="custom-alert__heading">
                    Fichier en cours :
                  </h6>
                  <div class="custom-alert__content">
                     <?php
						
								$query = $bdd->prepare("SELECT fichier_cat_acide FROM cat_acide WHERE id_cat_acide = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->execute();
								$donnees = $query->fetch();								
								$query->closeCursor();
								echo '<b><a target="_blank" href="module/linkedin/upload/'.$donnees['fichier_cat_acide'].'">'.$donnees['fichier_cat_acide'].'</a></b>';
						
					
					?>
                  </div>
                </div>
              </div>
            </div>
            
            <?php }?>  
            <div class="form-group">     
				<label for="read-only">Fichier du type <b>CSV</b> uniquement :</label>
                <input name="files" type="file" required>
			</div>
			<div class="form-group">
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){?>
            <button class="btn btn-info btn-sm icon-right mr-3" name="doSubmitdatamodif" type="submit" id="doSubmitdatamodif" value="downloadmodif" >Modifier le fichier en cours</button>
            <a class="btn btn-danger btn-sm mr-3 icon-right" href="Linkedin">Annuler et retour à la bibliothèque</a>	
            <?php }else{ ?>
            <button class="btn btn-info btn-sm icon-right mr-3" name="doSubmitdata" type="submit" id="doSubmitdata" value="download" >Charger le fichier sur le serveur</button>
            <a class="btn btn-danger btn-sm mr-3 icon-right" href="Linkedin">Annuler et retour à la bibliothèque</a>
            <?php } ?>
			</div>	
			
            </form>
            </div>
			</div>
            <div class="col-3">
            </div>
            <div class="col-3">
            	<img src="img/down/download.png" alt="" class="embed-responsive" />
            </div>
            </div>                                
            </div>
            </div>
                                    
                                    
        </div>
  	</div>
</div>    

<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>