<?php 
include '../config/dbc.php';
page_protect();
if(!checkAdmin()) {
header("Location: ../index.php");
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
  <title>Automobile - import / IPD</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
  

<script src="js/ie.assign.fix.min.js"></script>
  
</head>
<body class="js-loading sidebar-md">

<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="img/logo/logo.png" alt="" width="200"></div>
  </div>
</div>
<?php include("include/top.php");?>

<div class="page-wrap">
  
<?php include("include/sidebar.php");?>


	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-12">
              <div class="widget widget-welcome">
                <div class="widget-welcome__message">
                <h4 class="widget-welcome__message-l1">Importation BD Automobile</h4>                  
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
					$dossier = 'ftp/server/php/files/';
					$fichier = basename($_FILES['files']['name']);
					$fichier = strtr($fichier, 
					'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
					'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
					$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
					$taille_maxi = 100000000;
					$taille = filesize($_FILES['files']['tmp_name']);
					$extensions = array('.csv', '.xlsx');
					$extension = strrchr($_FILES['files']['name'], '.'); 
					//Début des vérifications de sécurité...
					if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
					{
						$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
						<div class="custom-alert__top-side">
						<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
						<div class="custom-alert__body">
						<h6 class="custom-alert__heading">
						Information :
						</h6>
						<div class="custom-alert__content">
						<p>Vous devez uploader un fichier de type xlsx, csv</p>
						</div>
						</div>
						</div>
						</div>';
								
					
					}
					if($taille>$taille_maxi)
					{
					$erreur = '<div class="alert custom-alert custom-alert--danger" role="alert">
						<div class="custom-alert__top-side">
						<span class="alert-icon iconfont iconfont-alert-warning custom-alert__icon"></span>
						<div class="custom-alert__body">
						<h6 class="custom-alert__heading">
						Information :
						</h6>
						<div class="custom-alert__content">
						<p>Le fichier est trop gros</p>
						</div>
						</div>
						</div>
						</div>';
					}
					
					if(!isset($erreur))
					{
						
						if(move_uploaded_file($_FILES['files']['tmp_name'], $dossier . $fichier))
						{						
							 				
								$fichier = fopen($dossier.$fichier, "r");
									 		
								$cpt = 1;
								
								$db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
								  mysqli_set_charset($db_connection, "utf8");
								  if (mysqli_connect_errno()){
									$result  = 'error';
									$message = 'Connexion à la base de données impossible: ' . mysqli_connect_error();
								  }
								  
								  $query_efaacer = "TRUNCATE TABLE industrie";
								  $query_last = mysqli_query($db_connection, $query_efaacer);
								  if (!$query_efaacer){
									$result  = 'error';
									$message = 'query error';
								  } else {
									$result  = 'success';
									$message = 'query success';
								  }
								
									if ($fichier !== FALSE) {
										
									   while (($data = fgetcsv($fichier, 4096, ";"))) {
										$cpt++;  
										$num = count($data);
										for ($c=0; $c < $num; $c++) {
										  $col[$c] = $data[$c];
										}
										
										$champ1 = utf8_encode(mysqli_escape_string($db,$col[0]));
										$champ2 = utf8_encode(mysqli_escape_string($db,$col[1]));
										$champ3 = utf8_encode(mysqli_escape_string($db,$col[2]));										
										$champ4 = utf8_encode(mysqli_escape_string($db,$col[3]));										
										$champ5 = utf8_encode(mysqli_escape_string($db,$col[4]));										
										$champ7 = date('Y-m-d', strtotime(str_replace('/', '-', $col[8])));
										$champ8 = date('Y-m-d', strtotime(str_replace('/', '-', $col[9])));
										
										if(empty($champ3) && empty($champ4)){
										$champ3 = 1;
										$champ4 = 1;
										}
												
									 	if($champ1 !=''){  
									 
										$query = "INSERT INTO industrie(id, operation_qld, nb_participant_qld, objectif_qld, realiser_qld, nature_objet_qld, date_debut_qld, date_fin_qld) VALUES('','$champ1','$champ2', '$champ3', '$champ4', '$champ5', '$champ7', '$champ8')";
										$query = mysqli_query($db_connection, $query);
									 }
									
									}
									fclose($fichier);
									}       
									
									echo "<script type='text/javascript'>document.location.replace('industrie.php');</script>";
									
								 
							  
						 } else {
							 
							  echo '<div class="alert alert-danger"><span><b>Alerte - </b> Échec de l\'upload !</span></div>';
							  
						 }
						 
					}else{
						
						 echo $erreur;
						 
					}
				
				}			 
				
			}
			?>
            
            <form method="post" action="industrie_import.php"  enctype="multipart/form-data">           
      
            <div class="main-container file-upload">
            <div class="file-upload__dropzone">
              <span class="iconfont iconfont-upload file-upload__icon"></span>
              <span class="file-upload__separator"><span class="file-upload__separator-text"></span></span>
              <div class="file-upload__browse-files">
                <button class="btn btn-outline-info btn-lg btn-block btn-rounded file-upload__browse-btn">Ajouter un fichier</button>
                <input id="upload-files-default" name="files" type="file"  onchange='document.getElementById("phrase").innerHTML="Vous allez uploader : "+this.value;'>
              </div>
            </div>
            <div class="file-upload__files">
              <div class="file-upload__file">
                <div class="file-upload__file-bg-progress"></div>
                <div class="file-upload__file-preview">
                </div>
                <div class="file-upload__file-info">
                  <span class="file-upload__file-name" id='phrase'>Ficiher en attente</span>
                </div>
              </div>
              
            </div>
            </div>    	
      
			<div class="form-group">            
            <button class="btn btn-info btn-lg mb-2 mr-3 icon-right" name="doSubmitdata" type="submit" id="doSubmitdata" value="download">Alimenter la base Industrie</button>
            <a class="btn btn-danger btn-lg mb-2 mr-3 icon-right" name="doSubmitdatamodif" href="industrie.php">Annuler et retour à la liste</a>
			</div>
			
			</form>
			</div>
            
            <div class="col-6">
            <div class="m-task__desc">
              <div class="m-task__desc-header">
                <h3 class="m-task__desc-heading">Texte d'aide</h3>
              </div>
              <div class="m-task__desc-text">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.
              </div>
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
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>

<script src="vendor/fileapi/FileAPI.html5.min.js"></script>
<script src="js/preview/file-upload.min.js"></script>

<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>