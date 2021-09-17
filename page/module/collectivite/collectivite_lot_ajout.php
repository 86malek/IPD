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
header("Location: ../../../index.php");
exit();
}
if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){
if($_GET['id'] == ''){$id = "";}else{$id = $_GET['id'];}
$update = $bdd->prepare("SELECT * FROM collectivite_lot WHERE collect_lot_id = :id");	
$update->bindParam(":id", $id, PDO::PARAM_INT);
$update->execute();
$donnees_update = $update->fetch();
$update->closeCursor();
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
  <title>Ajout de fichier Collectivité</title>
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
                
                <?php 
				if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){
                  	echo '<h4 class="widget-welcome__message-l1">Modification du fichier Collectivité</h4>';
                }else{
                	echo '<h4 class="widget-welcome__message-l1">Chargement d\'un fichier Collectivité</h4>';
                }
				?>
                  
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
					$taille_maxi = TAILLE_MAX_UP;
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
							 
								try 
								{
								
								
											
								$query_insert = $bdd->prepare("INSERT INTO collectivite_lot (collect_lot_nom, collect_lot_date_traitement, collect_lot_objectif, collect_lot_doc, collect_lot_insert, collect_lot_statut) VALUES (:doc_name, :doc_date, :doc_objectif, :fichier, now(), 3)");	
								$query_insert->bindParam(":doc_name", $_POST['doc_name'], PDO::PARAM_STR);
								$query_insert->bindParam(":doc_date", $_POST['doc_date'], PDO::PARAM_STR);
								$query_insert->bindParam(":doc_objectif", $_POST['doc_objectif'], PDO::PARAM_INT);
								$query_insert->bindParam(":fichier", $fichier, PDO::PARAM_STR);
								$query_insert->execute();
								$query_insert->closeCursor();
								
								$max = $bdd->prepare("SELECT MAX(collect_lot_id)as max FROM collectivite_lot");	
								$max->execute();
								$donnees_max = $max->fetch();
								$max->closeCursor();									
								$message = 'Succès de requête';
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
								
									$verif = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_idint = :collect_fiche_idint");	
									$verif->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
									$verif->execute();
									$verif_import = $verif->fetchColumn();
									$verif->closeCursor();
									if($verif_import == 0){
									$query_insert_doc = $bdd->prepare("INSERT INTO collectivite_fiche (collect_lot_id, collect_fiche_idint, collect_fiche_import,collect_fiche_intervallemaj, collect_fiche_rs1) VALUES (:collect_lot_id, :collect_fiche_idint, now(), :collect_fiche_intervallemaj, :collect_fiche_rs1)");										
									$query_insert_doc->bindParam(":collect_lot_id", $donnees_max['max'], PDO::PARAM_INT);
									$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
									$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[1], PDO::PARAM_INT);
									$query_insert_doc->bindParam(":collect_fiche_rs1", $col[2], PDO::PARAM_INT);
									$query_insert_doc->execute();
									$query_insert_doc->closeCursor();
									}
								}
								
								}
								fclose($fichier);
								}      
				
								echo "<script type='text/javascript'>document.location.replace('Collect');</script>";	
								}
								catch(PDOException $x) 
								{ 	
								die("Secured");	
								$message = 'Échec de requête';
								}	
								$query_insert = null;
								$bdd = null;
								
								
								
							  
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
						try 
						{
							if(file_exists($dossier . $donnees_update['collect_lot_doc']))
							{
							$chemin = "upload/".$donnees_update['collect_lot_doc'];						
							
							unlink($chemin);
							$query_del = $bdd->prepare("DELETE FROM collectivite_fiche WHERE collect_lot_id = :id");	
							$query_del->bindParam(":id", $id, PDO::PARAM_INT);
							$query_del->execute();
							$query_del->closeCursor();
							
							
							$query_up = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_nom = :doc_name, collect_lot_date_traitement = :doc_date, collect_lot_objectif = :doc_objectif, collect_lot_doc = :fichier, collect_lot_modif = now(), collect_lot_statut = 3 WHERE collect_lot_id = :id");	
							$query_up->bindParam(":id", $id, PDO::PARAM_INT);
							$query_up->bindParam(":doc_name", $_POST['doc_name'], PDO::PARAM_STR);
							$query_up->bindParam(":doc_date", $_POST['doc_date'], PDO::PARAM_STR);
							$query_up->bindParam(":doc_objectif", $_POST['doc_objectif'], PDO::PARAM_INT);
							$query_up->bindParam(":fichier", $fichier, PDO::PARAM_STR);
								
							$query_up->execute();
							$query_up->closeCursor();							
							}
							
							
							$message = 'Succès de requête';
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
							$verif = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_idint = :collect_fiche_idint");	
									$verif->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
									$verif->execute();
									$verif_import = $verif->fetchColumn();
									$verif->closeCursor();
									if($verif_import == 0){
							$query_insert_doc = $bdd->prepare("INSERT INTO collectivite_fiche (collect_lot_id, collect_fiche_idint, collect_fiche_import, collect_fiche_intervallemaj, collect_fiche_rs1) VALUES (:collect_lot_id, :collect_fiche_idint, now(), :collect_fiche_intervallemaj, :collect_fiche_rs1)");
										
							$query_insert_doc->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
							$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
							$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[1], PDO::PARAM_INT);
							$query_insert_doc->bindParam(":collect_fiche_rs1", $col[2], PDO::PARAM_INT);
							$query_insert_doc->execute();
							$query_insert_doc->closeCursor();
									}
							}
						
							}
							fclose($fichier);
							}      
			
							echo "<script type='text/javascript'>document.location.replace('Collect');</script>";	
						}
						catch(PDOException $x) 
						{ 	
						die("Secured");	
						$message = 'Échec de requête';
						}	
						$query_insert = null;
						$bdd = null;
				echo "<script type='text/javascript'>document.location.replace('Collect');</script>";
							  
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
			<form method="post" action="CollectAjout-update-<?php echo $id; ?>.html"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
			<?php }else{ ?>
            <form method="post" action="CollectAjout"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
            <?php } ?>
			<div class="form-group">
          	<label for="read-only">Nom du (fichier / Opération) :</label>
			<input type="text" placeholder="..." class="form-control" name="doc_name" id="doc_name" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['collect_lot_nom'];}?>" required>
			</div>
            <div class="form-group">
          	<label for="read-only">Date du lot :</label>
			<input type="text" class="form-control flatpickr" data-min-date="today" data-alt-format="Y-m-d" data-week-numbers="true" placeholder="Selection de date" data-default-date="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['collect_lot_date_traitement'];}?>" name="doc_date" id="doc_date" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['collect_lot_date_traitement'];}?>" required>
			</div>
            <div class="form-group">
          	<label for="read-only">Objectif du fichier :</label>
			<input type="number" placeholder="champ numérique" class="form-control" name="doc_objectif" id="doc_objectif" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['collect_lot_objectif'];}?>" required>
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
						$query_chemin = $bdd->prepare("SELECT * FROM collectivite_lot WHERE collect_lot_id = :id");	
						$query_chemin->bindParam(":id", $id, PDO::PARAM_INT);
						$query_chemin->execute();
						$donnees = $query_chemin->fetch();
						$query_chemin->closeCursor();
						echo '<b><a href="module/collectivite/upload/'.$donnees['collect_lot_doc'].'">'.$donnees['collect_lot_doc'].'</a></b>';				
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
            <a class="btn btn-danger  mr-3 icon-right" href="Collect">Annuler et retour à la bibliothèque</a>	
            <?php }else{ ?>
            <button class="btn btn-info icon-right mr-3" name="doSubmitdata" type="submit" id="doSubmitdata" value="download" >Charger le fichier sur le serveur</button>
            <a class="btn btn-danger  mr-3 icon-right" href="Collect">Annuler et retour à la bibliothèque</a>
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
<script src="js/jquery.validate.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/preview/datepicker.min.js"></script>
<script src="js/main.js"></script>
<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>