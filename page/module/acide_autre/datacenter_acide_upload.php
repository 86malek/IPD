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


if (!is_numeric($_GET['id']) || $_GET['id'] == ''){die("Stop");}else {$id = $_GET['id'];}
if (!is_numeric($_GET['id_cat']) || $_GET['id_cat'] == ''){die("Stop");}else {$id_cat = $_GET['id_cat'];}
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



?>
<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>DATA Acide - Upload</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

 <link rel="stylesheet" href="css/layout_global.css"> 
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

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
            <div class="row">
            <div class="col-lg-12">
              <div class="widget widget-welcome">
                <div class="widget-welcome__message">
                
                <h4 class="widget-welcome__message-l1">Formulaire retour fichier</h4>
                
                  
                </div>
                
              </div>
            </div>
            </div>        
          	<div class="main-container">            
            <div class="container-block">
            <div class="row">
            
            <div class="col-12">            
            <?php
			if(!empty($_POST['doSubmitdatamodif']) && $_POST['doSubmitdatamodif'] == 'uploadmodif')
			{	
				
				if(isset($_FILES['files']))
				{ 
					$dossier = 'upload/traiter/';
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
					
					if(!isset($erreur))
					{
							if(!empty($donnees_update['autre_acide_fichier_doc_traiter']))
							{							
							$chemin = "upload/traiter/".$donnees_update['autre_acide_fichier_doc_traiter'];						
							
							unlink($chemin);
							}
						
							if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
							{
							
							
							try 
							{
															
								if(file_exists($dossier . $donnees_update['autre_acide_fichier_doc']))
								{
								$chemin = "upload/".$donnees_update['autre_acide_fichier_doc'];						
								
								unlink($chemin);
								
								}
								
								$query_update_doc = $bdd->prepare("UPDATE autre_acide_fichier SET autre_acide_fichier_doc_traiter = :autre_acide_fichier_doc_traiter, autre_acide_fichier_date_upload = now(), user_name = :user_name, user_id = :user_id, autre_acide_fichier_statut = :autre_acide_fichier_statut WHERE id_autre_acide_fichier = :id_autre_acide_fichier");								
								$query_update_doc->bindParam(":autre_acide_fichier_doc_traiter", $fichier, PDO::PARAM_STR);								
								$query_update_doc->bindParam(":user_name", $_POST['affectation'], PDO::PARAM_STR);
								$query_update_doc->bindParam(":user_id", $_POST['affectation_id'], PDO::PARAM_INT);															
								$query_update_doc->bindParam(":autre_acide_fichier_statut", $_POST['statut'], PDO::PARAM_INT);															
								$query_update_doc->bindParam(":id_autre_acide_fichier", $id, PDO::PARAM_INT);
								$query_update_doc->execute();
								$query_update_doc->closeCursor();      
				
								echo "<script type='text/javascript'>document.location.replace('DataAcide-".$id_cat.".html');</script>";	
							}
							catch(PDOException $x) 
							{ 	
							die("Échec de requête 2");	
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
            <form method="post" action="DataAcideUpload-<?php echo $id; ?>-<?php echo $id_cat; ?>.html" enctype="multipart/form-data" onsubmit="afficheLoader('load');">
			<div class="form-group">
          	<label for="read-only">Afféctation</label>
			<input type="text" class="form-control" name="affectation" id="affectation" value="<?php echo $_SESSION['user_name'];?>" readonly>
            <input type="hidden" class="form-control" name="affectation_id" id="affectation_id" value="<?php echo $_SESSION['user_id'];?>" readonly>
			</div>
            
            <div class="form-group">  
            <label for="read-only">Statut</label>
			<select class="form-control" data-placeholder=""  name="statut" id="statut" required>
				<option value="" selected>Selectionner un statut</option>
                <option value="1">Cloturé</option>
                <option value="4">En progression</option>               
			</select>
			</div>
            
            <div class="form-group">     
			
            	<label for="read-only">Fichier du type <b>CSV et XLSX</b> uniquement :</label>		
                <input name="files" type="file" required />
			</div>
			<div class="form-group">
            <button class="btn btn-info icon-right mr-3" name="doSubmitdatamodif" type="submit" id="doSubmitdatamodif" value="uploadmodif">Envoyer les informations</button>
            <a class="btn btn-danger icon-right mr-3" href="DataAcide-<?php echo $id_cat; ?>.html">Annuler et retour à la liste</a>
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
<script charset="utf-8" src="js/jquery.validate.min.js"></script>
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