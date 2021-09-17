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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Webmasters / IPD</title>
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
          <h2 class="content-heading"><b>Webmasters</b></h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-9">
            <button type="button" class="btn btn-info icon-right btn-sm mr-3" id="add_webmaster">Ajouter une nouvelle opération webmaster <span class="btn-icon iconfont iconfont-circle-check"></span></button>
            
            </div> 
            <div class="col-lg-3" style="text-align:right">
             
            <a class="btn btn-primary icon-right btn-sm mb-3 mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
            </div>          
            </div>
            
            </div>
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">                                
            <table class="datatable table table-striped" id="table_webmaster">
                <thead>
                    <tr>
                        <th>Opération</th>
                        <th>Participant</th>
                        <th>Campagne</th>
                        <th>JH</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Action</th>
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
			<form class="form add" data-id="" id="form_company">
				<div class="input_container">
					<label for="operation">Opération : <span class="required">*</span></label>
					<div class="field_container">
						<input id="operation" name="operation" type="text" value=""  class="form-control" required/>
					</div>
				</div>
				<div class="input_container">
					<label for="nb">Nombre participants : <span class="required">*</span></label>
					<div class="field_container">
						<input id="nb" min="0" name="nb" step="1" type="number" value="" class="form-control" required/>
					</div>
				</div>
				<div class="input_container">
					<label for="compagne">Nombre de campagne : <span class="required">*</span></label>
					<div class="field_container">
						<input id="compagne" min="0" name="compagne" step="1" type="number" value="" class="form-control" required/>
					</div>
				</div>
				<div class="input_container">
					<label for="debut">Date début : <span class="required">*</span></label>
					<div class="field_container">
						<input id="debut" name="debut" type="date" value="" class="form-control" required/>
					</div>
				</div>
				<div class="input_container">
					<label for="fin">Date fin : <span class="required">*</span></label>
					<div class="field_container">
						<input id="fin" name="fin" type="date" value="" class="form-control" required/>
					</div>
				</div>
				<div class="form-group" style="text-align:right">
					<button type="submit" class="btn btn-info btn-sm mb-2 mr-3"></button>
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
<script charset="utf-8" src="module/webmaster/table/js/webapp_webmaster.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>

<div class="sidebar-mobile-overlay"></div>  
</body>
</html>