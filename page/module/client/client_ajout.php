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

$query = $bdd->prepare("SELECT * FROM client_cat WHERE id_cat = :id");
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
  <title>Client / Ajout</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">
<link rel="stylesheet" href="css/layout_global.css">

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
                  	echo '<h4 class="widget-welcome__message-l1">Modification du fichier Client</h4>';
                }else{
                	echo '<h4 class="widget-welcome__message-l1">Chargement d\'un fichier Client</h4>';
                }?>                  
                </div>
                
              </div>
            </div>
            </div>        
          	<div class="main-container">            
            <div class="container-block">
            <div class="row">
            
            <div class="col-12">            
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
					Vous devez uploader un fichier du type csv
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
					Le fichier est trop gros
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
					Le fichier existe déja sous cette nomination
					</h6>
					</div>
					</div>          
					</div>';
					}
					if(!isset($erreur))
					{
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
						{
							 	
								$query = $bdd->prepare("INSERT INTO client_cat (`nom_cat`, `fichier_cat`, `date_ajout_cat`) VALUES (:nom_cat, :fichier, now())");
								$query->bindParam(":nom_cat", $_POST['doc_name'], PDO::PARAM_STR);
								$query->bindParam(":fichier", $fichier, PDO::PARAM_STR);
								$query->execute();
								$query->closeCursor();
								
								$query = $bdd->prepare("SELECT MAX(id_cat)as max FROM client_cat");
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
								
								

								$query = $bdd->prepare("INSERT INTO client_traitement (id_cat, `raison_sociale_client`,`adresse1_client`,`adresse2_client`,`adresse3_client`,`code_postal_client`,`ville_client`,`tel_client`,`fax_client`,`siret_client`,`effectif_site_client`,`effectif_groupe_site`,`ca_client`,`title_client`,`nom_client`,`prenom_client`,`fonction_client`,`email_client`,`email_s_client`) VALUES (:id_cat, :raison_sociale_client,:adresse1_client,:adresse2_client,:adresse3_client,:code_postal_client,:ville_client,:tel_client,:fax_client,:siret_client,:effectif_site_client,:effectif_groupe_site,:ca_client,:title_client,:nom_client,:prenom_client,:fonction_client,:email_client,:email_s_client)");
								
								$query->bindParam(":id_cat", $donnees_max['max'], PDO::PARAM_INT);
								$query->bindParam(":raison_sociale_client", utf8_encode($col[0]), PDO::PARAM_STR);
								$query->bindParam(":adresse1_client", utf8_encode($col[1]), PDO::PARAM_STR);
								$query->bindParam(":adresse2_client", utf8_encode($col[2]), PDO::PARAM_STR);
								$query->bindParam(":adresse3_client", utf8_encode($col[3]), PDO::PARAM_STR);
								$query->bindParam(":code_postal_client", utf8_encode($col[4]), PDO::PARAM_INT);
								$query->bindParam(":ville_client", utf8_encode($col[5]), PDO::PARAM_STR);
								$query->bindParam(":tel_client", utf8_encode($col[6]), PDO::PARAM_INT);
								$query->bindParam(":fax_client", utf8_encode($col[7]), PDO::PARAM_INT);
								$query->bindParam(":siret_client", utf8_encode($col[8]), PDO::PARAM_STR);
								$query->bindParam(":effectif_site_client", utf8_encode($col[9]), PDO::PARAM_STR);
								$query->bindParam(":effectif_groupe_site", utf8_encode($col[10]), PDO::PARAM_STR);
								$query->bindParam(":ca_client", utf8_encode($col[11]), PDO::PARAM_STR);
								$query->bindParam(":title_client", utf8_encode($col[12]), PDO::PARAM_STR);
								$query->bindParam(":nom_client", utf8_encode($col[13]), PDO::PARAM_STR);
								$query->bindParam(":prenom_client", utf8_encode($col[14]), PDO::PARAM_STR);
								$query->bindParam(":fonction_client", utf8_encode($col[15]), PDO::PARAM_STR);
								$query->bindParam(":email_client", utf8_encode($col[16]), PDO::PARAM_STR);
								$query->bindParam(":email_s_client", utf8_encode($col[17]), PDO::PARAM_STR);
								$query->execute();
								$query->closeCursor();
								
								}
								
								}
								fclose($fichier);
								}      
				
								echo "<script type='text/javascript'>document.location.replace('Client-".$_GET['idcatt']."');</script>";
								
							  
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
					Vous devez uploader un fichier du type csv
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
					Le fichier est trop gros
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
						Le fichier existe déja sous cette nomination
						</h6>
						</div>
						</div>          
						</div>';
					}
					if(!isset($erreur))
					{
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
						{
														
							if(file_exists($dossier . $donnees_update['fichier_cat']))
							{
							$chemin = "upload/".$donnees_update['fichier_cat'];						
							
							unlink($chemin);
							
							}
							
								$query = $bdd->prepare("DELETE FROM client_traitement WHERE id_cat = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->execute();
								$query->closeCursor();
								
								$query = $bdd->prepare("UPDATE client_cat SET `nom_cat` = :nom_cat,`fichier_cat` = :fichier_cat,`date_modification_cat` = now() WHERE id_cat = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->bindParam(":nom_cat", $_POST['doc_name'], PDO::PARAM_STR);
								$query->bindParam(":fichier_cat", $fichier, PDO::PARAM_STR);
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
											$query = $bdd->prepare("INSERT INTO client_traitement (id_cat, `raison_sociale_client`,`adresse1_client`,`adresse2_client`,`adresse3_client`,`code_postal_client`,`ville_client`,`tel_client`,`fax_client`,`siret_client`,`effectif_site_client`,`effectif_groupe_site`,`ca_client`,`title_client`,`nom_client`,`prenom_client`,`fonction_client`,`email_client`,`email_s_client`) VALUES (:id_cat, :raison_sociale_client,:adresse1_client,:adresse2_client,:adresse3_client,:code_postal_client,:ville_client,:tel_client,:fax_client,:siret_client,:effectif_site_client,:effectif_groupe_site,:ca_client,:title_client,:nom_client,:prenom_client,:fonction_client,:email_client,:email_s_client)");
								
											$query->bindParam(":id_cat", $id, PDO::PARAM_INT);
											$query->bindParam(":raison_sociale_client", utf8_encode($col[0]), PDO::PARAM_STR);
											$query->bindParam(":adresse1_client", utf8_encode($col[1]), PDO::PARAM_STR);
											$query->bindParam(":adresse2_client", utf8_encode($col[2]), PDO::PARAM_STR);
											$query->bindParam(":adresse3_client", utf8_encode($col[3]), PDO::PARAM_STR);
											$query->bindParam(":code_postal_client", utf8_encode($col[4]), PDO::PARAM_INT);
											$query->bindParam(":ville_client", utf8_encode($col[5]), PDO::PARAM_STR);
											$query->bindParam(":tel_client", utf8_encode($col[6]), PDO::PARAM_INT);
											$query->bindParam(":fax_client", utf8_encode($col[7]), PDO::PARAM_INT);
											$query->bindParam(":siret_client", utf8_encode($col[8]), PDO::PARAM_STR);
											$query->bindParam(":effectif_site_client", utf8_encode($col[9]), PDO::PARAM_STR);
											$query->bindParam(":effectif_groupe_site", utf8_encode($col[10]), PDO::PARAM_STR);
											$query->bindParam(":ca_client", utf8_encode($col[11]), PDO::PARAM_STR);
											$query->bindParam(":title_client", utf8_encode($col[12]), PDO::PARAM_STR);
											$query->bindParam(":nom_client", utf8_encode($col[13]), PDO::PARAM_STR);
											$query->bindParam(":prenom_client", utf8_encode($col[14]), PDO::PARAM_STR);
											$query->bindParam(":fonction_client", utf8_encode($col[15]), PDO::PARAM_STR);
											$query->bindParam(":email_client", utf8_encode($col[16]), PDO::PARAM_STR);
											$query->bindParam(":email_s_client", utf8_encode($col[17]), PDO::PARAM_STR);
											$query->execute();
											$query->closeCursor();
											
											}
											
											}
											fclose($fichier);
											} 
							        echo "<script type='text/javascript'>document.location.replace('Client');</script>";
							
							  
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
                            Chargement du fichier
                        </div>
                    </div>
                </div>
            </div>
            <div id="formulaire">
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){?>
			<form method="post" action="ClientAjout-update-<?php echo $id; ?>.html"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
			<?php }else{ ?>
            <form method="post" action="ClientAjout-<?php echo $_GET['idcatt'];?>"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
            <?php } ?>
			<div class="form-group">
          	<label for="read-only">Nom du (fichier / Opération) :</label>
			<input type="text" placeholder="Aucune restriction de taille." class="form-control" name="doc_name" id="doc_name" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['nom_cat'];}?>" required>
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
						
								$query = $bdd->prepare("SELECT fichier_cat FROM client_cat WHERE id_cat = :id");
								$query->bindParam(":id", $id, PDO::PARAM_INT);
								$query->execute();
								$donnees = $query->fetch();								
								$query->closeCursor();
								echo '<b><a target="_blank" href="module/client/upload/'.$donnees['fichier_cat'].'">'.$donnees['fichier_cat'].'</a></b>';
						
					
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
            <button class="btn btn-info icon-right mr-3" name="doSubmitdatamodif" type="submit" id="doSubmitdatamodif" value="downloadmodif" >Modifier le fichier en cours</button>
            <a class="btn btn-danger  mr-3 icon-right" href="Client-<?php echo $_GET['idcatt'];?>">Annuler et retour à la bibliothèque</a>	
            <?php }else{ ?>
            <button class="btn btn-info icon-right mr-3" name="doSubmitdata" type="submit" id="doSubmitdata" value="download" >Charger le fichier sur le serveur</button>
            <a class="btn btn-danger  mr-3 icon-right" href="Client-<?php echo $_GET['idcatt'];?>">Annuler et retour à la bibliothèque</a>
            <?php } ?>
			</div>	
			
            </form>
            </div>
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