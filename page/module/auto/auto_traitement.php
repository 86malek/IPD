<?php 
include '../config/dbc.php';
page_protect();


if(!empty($_POST['debut']) && $_POST['debut'] == 'debut'){
$query = "INSERT INTO auto_synthese SET auto_intervenant_synthese = '".$_SESSION['user_name']."', auto_orga = now(), auto_debut_synthese = now()";
$query = mysqli_query($db, $query);

}elseif(!empty($_GET['mode']) && $_GET['mode'] == 'fin'){
$query = "SELECT MAX(auto_id_synthese) AS max FROM auto_synthese WHERE auto_intervenant_synthese = '".$_SESSION['user_name']."'";
$query = mysqli_query($db, $query);
$max = mysqli_fetch_array($query);
$max = $max['max'];

	if(!$max){

      echo 'Échec de requête';
    } else {
      mysqli_query($db,"UPDATE auto_synthese SET auto_fin_synthese = now() WHERE auto_intervenant_synthese = '".$_SESSION['user_name']."' AND auto_id_synthese = ".$max."") or die(mysqli_connect_error());
    }

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
  <title>Traitement Automobile / IPD</title>
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

<?php include("include/top.php");?>

<div class="page-wrap">
  
<?php include("include/sidebar.php");?>


	<div class="page-content">
    	
        <div class="container-fluid">     
          <h2 class="content-heading">Traitement : <b>Automobile</b></h2>
          <div class="main-container">
  			<div class="container-block">
            <div class="row">
            <?php if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){?>
            <div class="col-lg-6">
            <a href="auto_traitement.php?mode=fin" class="btn btn-warning icon-left btn-sm mr-3">Finir le traitement<span class="btn-icon iconfont iconfont-info"></span></a>
              </div>          
            <div class="col-lg-12">
            <div style="text-align:right">
            <button type="button" class="btn btn-info icon-left btn-sm mr-3" id="add_auto_fiche">Ajouter une fiche automobile <span class="btn-icon iconfont iconfont-circle-check"></span></button>
            </div>
            </div>
            <?php }else{?>
            <div class="col-lg-6">
            <form action="auto_traitement.php?mode=debut" title="debut" id="debut" method="post">            
            <input type="hidden" name="debut" value="debut">
            <button type="submit" class="btn btn-info icon-left btn-sm mr-3">Débuter le traitement <span class="btn-icon iconfont iconfont-circle-check"></span></button>
            </form>
             </div>
            
            <?php }?>
            
            </div>
            
            </div>          
  			
            <div class="container-block">
            <div class="row">
            
            <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_auto_fiche">
            <thead>
            <tr>
            <th>Collaborateur</th>
            <th>ID Fiche</th>
            <th>Statut</th>
            <th>Date de traitement</th>
            <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
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
    
    <form class="form add" id="form_auto_fiche" data-id="" novalidate>
    <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_name'];?>" readonly>
      
      		<div class="input_container">
            	<label for="Identificateur">Identificateur de fiche : <span class="required">*</span></label>
                <div class="field_container">
                    <input type="number" class="form-control" id="fiche" name="fiche" required>
                </div>
            </div>
      		
            <div class="input_container">
            <label for="STAT">Statut : <span class="required">*</span></label>
            <div class="field_container">
                <select id="statut" name="statut" class="form-control" required>
                    <option value="" selected>Choisir un statut</option>
                    <option value="OK">OK</option>
                    <option value="KO">KO</option>
                </select>
            </div>
            </div>
            
      
      <div class="form-group" style="text-align:right">
        <button type="submit" class="btn btn-info icon-right btn-sm mr-3"></button>
      </div>
    </form>
    
  </div>
</div>

<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
<script src="table/webapp_traitement_auto.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="vendor/sparkline/jquery.sparkline.min.js"></script>
<script src="js/preview/default-dashboard.min.js"></script>


<div class="sidebar-mobile-overlay"></div>  
</body>
</html>