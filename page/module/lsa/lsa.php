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
  <title>DISTRIBUTION / IPD</title>
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
<body class="js-loading  sidebar-md">

<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="img/logo/LogoEnr.png" alt="" width="200"></div>
  </div>
</div>

<?php include("include/top.php");?>

<div class="page-wrap">
  
<?php include("include/sidebar.php");?>

<div class="page-content">

        <div class="container-fluid">
          <h2 class="content-heading">DISTRIBUTION</h2>
          
          <div class="main-container">
  
            <div class="container-block">
            <div class="row">
            <div class="col-lg-12">
            <button type="button" class="btn btn-info icon-right mr-3" id="add_lsa">Ajouter une nouvelle entrée <span class="btn-icon iconfont iconfont-circle-check"></span></button>
            
            <a class="btn btn-primary icon-right mr-3" href="lsa_import.php">Importer un fichier  <span class="btn-icon iconfont iconfont-upload"></span></a>
            </div>           
            </div>
            
            </div>
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">                                
            <table class="datatable table table-striped" id="table_lsa">
                <thead>
                    <tr>
                        <th>Opération</th>
                        <th>Participant</th>
                        <th>Réalisé</th>
                        <th>Intégré</th>
                        <th>Indépendant</th>
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
            
            <form class="form add" id="form_company" data-id="">
            
            
            <div class="input_container">
            <label for="operation">Opération : <span class="required">*</span></label>
            <div class="field_container">
            <input type="text" name="operation" id="operation" value=""  class="form-control" required>
            </div>
            </div>
            <div class="input_container">
            <label for="indep">Indépendant : <span class="required">*</span></label>
            <div class="field_container">
            <input type="text" name="indep" id="indep" value=""  class="form-control" required>
            </div>
            </div>
            
            <div class="input_container">
            <label for="nb">Nombre de participants : <span class="required">*</span></label>
            <div class="field_container">
            <input type="number" step="1" min="0" name="nb" id="nb" value=""  class="form-control" required>
            </div>
            </div>
            <div class="input_container">
            <label for="realiser">Réalisation : <span class="required">*</span></label>
            <div class="field_container">
            <input type="number" step="1" min="0" name="realiser" id="realiser" value=""  class="form-control" required>
            </div>
            </div>
            <div class="input_container">
            <label for="integre">Intégré : <span class="required">*</span></label>
            <div class="field_container">
            <input type="number" step="1" min="0" name="integre" id="integre" value=""  class="form-control" required>
            </div>
            </div>
            <div class="input_container">
            <label for="debut">Date de début : <span class="required">*</span></label>
            <div class="field_container">
            <input type="date" name="debut" id="debut" value=""  class="form-control" required>
            </div>
            </div>
            <div class="input_container">
            <label for="fin">Date de fin : <span class="required">*</span></label>
            <div class="field_container">
            <input type="date" name="fin" id="fin" value=""  class="form-control" required>
            </div>
            </div>
            
            
                    
            
            
            <div class="input_container" style="text-align:right; margin-left:20px">
            <button style="padding:7px" type="submit" class="btn btn-primary btn-sm mb-2 mr-3"></button>
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
<script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
<script charset="utf-8" src="table/webapp_lsa.js"></script>
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