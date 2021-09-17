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
if(!empty($_GET['mode']) && $_GET['mode'] == 'update')
{		
if (!is_numeric($_GET['id'])){die("Stop");}else {$id = $_GET['id'];}
try 
{	
$query = $bdd->prepare("SELECT * FROM autre_acide_fichier WHERE id_autre_acide_fichier = :id");
$query->bindParam(":id", $id, PDO::PARAM_INT);
$query->execute();
$donnees_update = $query->fetch();
$query->closeCursor();
}
catch(PDOException $x) 
{ 	
die("Échec de requête 1");	
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
  <title>DATA Acide - Ajout</title>
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
                <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){
                  	echo '<h4 class="widget-welcome__message-l1">Modification DATA Acide</h4>';
                }else{
                	echo '<h4 class="widget-welcome__message-l1">Ajout DATA Acide</h4>';
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
					$taille_maxi = TAILLE_MAX_UP;
					$taille = filesize($_FILES['files']['tmp_name']);
					$extensions = array('.csv', '.xlsx');
					$extension = strrchr($_FILES['files']['name'], '.');						  
					if(!in_array($extension, $extensions))
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Vous devez uploader un fichier de type xlsx, csv
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
					Taille fichier incorrecte
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
						 
						 
						
							$contenu_fichier = file_get_contents($dossier . $fichier);
							$nombre_ligne_fichier = substr_count($contenu_fichier, "\n");
						 
							try 
							{										
							$query_insert = $bdd->prepare("INSERT INTO autre_acide_fichier (id_autre_acide_fichier_categorie, id_organigramme, autre_acide_fichier_nom, autre_acide_fichier_doc, autre_acide_fichier_date_insertion, autre_acide_fichier_nb_ligne, autre_acide_fichier_statut) VALUES (:id_autre_acide_fichier_categorie, :id_organigramme, :autre_acide_fichier_nom, :fichier, now(), :autre_acide_fichier_nb_ligne, 3)");	
							$query_insert->bindParam(":id_autre_acide_fichier_categorie", $_POST['doc_cat'], PDO::PARAM_INT);
							$query_insert->bindParam(":id_organigramme", $_POST['doc_equipe'], PDO::PARAM_INT);
							$query_insert->bindParam(":autre_acide_fichier_nom", $_POST['doc_name'], PDO::PARAM_STR);
							$query_insert->bindParam(":fichier", $fichier, PDO::PARAM_STR);
							$query_insert->bindParam(":autre_acide_fichier_nb_ligne", $nombre_ligne_fichier, PDO::PARAM_INT);
							$query_insert->execute();
							$query_insert->closeCursor();
							}
							catch(PDOException $x) 
							{ 	
							die("Échec de requête 2");	
							}	
							$query_insert = null;
							$bdd = null;			
							echo "<script type='text/javascript'>document.location.replace('DataAcide');</script>";
							
														  
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
			}elseif(!empty($_POST['doSubmitdatamodif']) && $_POST['doSubmitdatamodif'] == 'downloadmodif'){	
				
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
					$extensions = array('.csv', '.xlsx');
					$extension = strrchr($_FILES['files']['name'], '.'); 
					//Début des vérifications de sécurité...
					if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Vous devez uploader un fichier de type xlsx, csv
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
					Taille fichier incorrecte
					</h6>
					</div>
					</div>          
					</div>';
					}
					
					if(empty($fichier))
					// verify if the file already exists
					{
						
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
					<!--<span class="close iconfont iconfont-alert-close custom-alert__close" data-dismiss="alert"></span>-->
					<div class="custom-alert__top-side">
					<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
					<div class="custom-alert__body">
					<h6 class="custom-alert__heading">
					Aucun fichier selectionner
					</h6>
					</div>
					</div>          
					</div>';
					
					}else{
						
						if(file_exists($dossier . $fichier))
						// verify if the file already exists
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
						
					}
					
					
					if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
					{
						//On formate le nom du fichier ici...
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
						{
							
							
							
							try 
							{
								$contenu_fichier = file_get_contents($dossier . $fichier);
								$nombre_ligne_fichier = substr_count($contenu_fichier, "\n");
															
								if(file_exists($dossier . $donnees_update['autre_acide_fichier_doc']))
								// verify if the file already exists
								{
								$chemin = "upload/".$donnees_update['autre_acide_fichier_doc'];						
								
								unlink($chemin);
								
								}
								
								$query_update_doc = $bdd->prepare("UPDATE autre_acide_fichier SET id_autre_acide_fichier_categorie = :id_autre_acide_fichier_categorie, id_organigramme = :id_organigramme, autre_acide_fichier_nom = :autre_acide_fichier_nom, autre_acide_fichier_doc = :autre_acide_fichier_doc, autre_acide_fichier_date_insertion = now(), autre_acide_fichier_statut = 3, autre_acide_fichier_nb_ligne = :autre_acide_fichier_nb_ligne WHERE id_autre_acide_fichier = :id_autre_acide_fichier");								
								$query_update_doc->bindParam(":id_autre_acide_fichier_categorie", $_POST['doc_cat'], PDO::PARAM_INT);
								$query_update_doc->bindParam(":id_organigramme", $_POST['doc_equipe'], PDO::PARAM_INT);							
								$query_update_doc->bindParam(":autre_acide_fichier_nom", $_POST['doc_name'], PDO::PARAM_STR);							
								$query_update_doc->bindParam(":autre_acide_fichier_doc", $fichier, PDO::PARAM_STR);						
								$query_update_doc->bindParam(":autre_acide_fichier_nb_ligne", $nombre_ligne_fichier, PDO::PARAM_INT);							
								$query_update_doc->bindParam(":id_autre_acide_fichier", $id, PDO::PARAM_INT);
								$query_update_doc->execute();
								$query_update_doc->closeCursor();      
				
								echo "<script type='text/javascript'>document.location.replace('DataAcide');</script>";	
							}
							catch(PDOException $x) 
							{ 	
							die("Échec de requête 3");	
							}	
							$query_update_doc = null;
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
			<form method="post" action="DataAcideAjout-update-<?php echo $id; ?>.html"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
			<?php }else{ ?>
            <form method="post" action="DataAcideAjout"  enctype="multipart/form-data" onsubmit="afficheLoader('load');">
            <?php } ?>
            
            
			<div class="form-group">
          	<label for="read-only">Nom du fichier</label>
			<input type="text" placeholder="" class="form-control" name="doc_name" id="doc_name" value="<?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){ echo $donnees_update['autre_acide_fichier_nom'];}?>" required>
			</div>
            
            <div class="form-group">  
            <label for="read-only">Catégorie du fichier</label>
			<select class="form-control" data-placeholder=""  name="doc_cat" id="doc_cat" required>
				<option selected value=""> Selectionner une catégorie</option>
                <?php
					
					try 
					{	
					$donnees = $bdd->prepare("SELECT * FROM autre_acide_fichier_categorie");
					$donnees->execute();
					while($donnees_cat = $donnees->fetch()){
					
						if($donnees_cat['id_autre_acide_fichier_categorie'] == $donnees_update['id_autre_acide_fichier_categorie']){
						echo '<option value="'.$donnees_cat['id_autre_acide_fichier_categorie'].'" selected>'.$donnees_cat['autre_acide_fichier_categorie_nom'].'</option>';
						}else{echo '<option value="'.$donnees_cat['id_autre_acide_fichier_categorie'].'">'.$donnees_cat['autre_acide_fichier_categorie_nom'].'</option>';}
						
					}
					$donnees->closeCursor();
					$donnees = null;
					}
					catch(PDOException $x) 
					{ 	
					die("Échec de requête 4");	
					}
					
					
				
				?>
				
                
			</select>
			</div>
            
            <div class="form-group">  
            <label for="read-only">Équipe</label>
			<select class="form-control" data-placeholder="Équipe de traitement"  name="doc_equipe" id="doc_equipe" required>
				<option selected value=""> Selectionner une équipe</option> 
                <?php
					
					try 
					{	
					$donnees = $bdd->prepare("SELECT * FROM organigramme");
					$donnees->execute();
					while($donnees_orga = $donnees->fetch()){
					
						if($donnees_orga['id_organigramme'] == $donnees_update['id_organigramme']){
						echo '<option value="'.$donnees_orga['id_organigramme'].'" selected>'.$donnees_orga['nomination_organigramme'].'</option>';
						}else{echo '<option value="'.$donnees_orga['id_organigramme'].'">'.$donnees_orga['nomination_organigramme'].'</option>';}
						
					}
					$donnees->closeCursor();
					$donnees = null;
					}
					catch(PDOException $x) 
					{ 	
					die("Échec de requête 4");	
					}
				
				?>
				
                
			</select>
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
						
						try 
						{	
						$donnees = $bdd->prepare("SELECT autre_acide_fichier_doc FROM autre_acide_fichier WHERE id_autre_acide_fichier = :id");
						$donnees->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
						$donnees->execute();
						$donnees_fichier = $donnees->fetch();
						
							echo '<b><a href="module/acide_autre/upload/'.$donnees_fichier['autre_acide_fichier_doc'].'">'.$donnees_fichier['autre_acide_fichier_doc'].'</a></b>';
							
						
						$donnees->closeCursor();
						$donnees = null;
						}
						catch(PDOException $x) 
						{ 	
						die("Échec de requête 4");	
						}			
					
					?>
                  </div>
                </div>
              </div>
            </div>
            <?php }?>  
            <div class="form-group">	
            	<label for="read-only">Fichier du type <b>CSV et XLSX</b> uniquement :</label>		
                <input name="files" type="file" required />
			</div>
			<div class="form-group">
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'update'){?>
            <button class="btn btn-info icon-right mr-3" name="doSubmitdatamodif" type="submit" id="doSubmitdatamodif" value="downloadmodif">Modifier les informations</button>	
            <a class="btn btn-danger icon-right mr-3" name="doSubmitdatamodif" href="DataAcide">Annuler et retour à la liste</a>		
            <?php }else{ ?>
            <button class="btn btn-info icon-right mr-3" name="doSubmitdata" type="submit" id="doSubmitdata" value="download">Envoyer les informations</button>
            <a class="btn btn-danger  mr-3 icon-right" name="doSubmitdatamodif" href="DataAcide">Annuler et retour à la liste</a>
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
<script src="js/main.js"></script>
<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>