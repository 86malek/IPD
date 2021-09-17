<?php 
include '../config/dbc.php';
page_protect();
							
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Gestion Automobile / IPD</title>
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



<?php include("include/top.php");?>

<div class="page-wrap">
  
<?php include("include/sidebar.php");?>

<div class="page-content">

        <div class="container-fluid">
          <h2 class="content-heading">Gestion : <b>Automobile</b></h2>
              <div class="row">
                <div class="col-lg-3">
                  <div class="widget widget-welcome">
                    <div class="widget-welcome__message">
                      <h4 class="widget-welcome__message-l1">FICHES TRAITEES</h4>
                      <h6 class="widget-welcome__message-l2"></h6>
                    </div>
                    <div class="widget-welcome__stats">
                      <div class="widget-welcome__stats-item monthly-growth">
                        <span class="widget-welcome__stats-item-value"><center>
						<?php $query_count_total = "SELECT * FROM `auto_traitement`";
								$query_count_total = mysqli_query($db, $query_count_total);
								if (!$query_count_total){
								  $result  = 'error';
								  $message = 'Échec de requête';
								} else {
								  $result  = 'success';
								  $message = 'Succès de requête';
								}
								$rowcount = mysqli_num_rows($query_count_total);
								echo $rowcount; ?>
                        </center></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="widget widget-welcome">
                    <div class="widget-welcome__message">
                      <h4 class="widget-welcome__message-l1">Total des OK</h4>
                      <h6 class="widget-welcome__message-l2"></h6>
                    </div>
                    <div class="widget-welcome__stats">
                      <div class="widget-welcome__stats-item daily-growth">
                        <span class="widget-welcome__stats-item-value"><center>
						<?php $query_count_total = "SELECT * FROM `auto_traitement` WHERE statut_auto = 'OK'";
								$query_count_total = mysqli_query($db, $query_count_total);
								if (!$query_count_total){
								  $result  = 'error';
								  $message = 'Échec de requête';
								} else {
								  $result  = 'success';
								  $message = 'Succès de requête';
								}
								$rowcount = mysqli_num_rows($query_count_total);
								echo $rowcount; ?>
                        </center></span>
                      </div>
                    </div>
                    </div></div>
                    <div class="col-lg-3">
                  <div class="widget widget-welcome">
                  <div class="widget-welcome__message">
                      <h4 class="widget-welcome__message-l1">Total des KO</h4>
                      <h6 class="widget-welcome__message-l2"></h6>
                    </div>
                    <div class="widget-welcome__stats">
                      <div class="widget-welcome__stats-item daily-growth">
                        <span class="widget-welcome__stats-item-value"><center>
						<?php $query_count_total = "SELECT * FROM `auto_traitement` WHERE statut_auto = 'KO'";
								$query_count_total = mysqli_query($db, $query_count_total);
								if (!$query_count_total){
								  $result  = 'error';
								  $message = 'Échec de requête';
								} else {
								  $result  = 'success';
								  $message = 'Succès de requête';
								}
								$rowcount = mysqli_num_rows($query_count_total);
								echo $rowcount; ?>
                        </center></span>
                      </div>
                    </div>                  
                    
                    
                  </div>
                </div>
              </div>
          
          <div class="main-container">
  
            
            <div class="container-block">
            <div class="row">            
            <div class="content table-responsive table-full-width">
                                          
            <table class="datatable table table-striped" id="table_gestion_traitement_auto">
                <thead>
                    <tr>
                    	
                    	<th>Collaborateur</th>
                    	<th>Date début</th>
                        <th>Date de fin</th>
                        <th>JH</th>
                        <th>Temps</th>
                        <th>Total ligne</th>
                        <th>OK</th>
                        <th>KO</th>
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
    
<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
<script charset="utf-8" src="table/webapp_gestion_traitement_auto.js"></script>
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