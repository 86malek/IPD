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
    if (file_exists("../config/".$page) && $page != 'index.php') {
       include("../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();
include("stat/fusioncharts.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Tableau de bord</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
  

<script src="js/ie.assign.fix.min.js"></script>
<script type="text/javascript" src="stat/js/fusioncharts.js"></script>
<script type="text/javascript" src="stat/js/themes/fusioncharts.theme.ocean.js"></script>
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
    if (file_exists("include/".$page) && $page != 'index.php') {
       include("include/".$page); 
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
    if (file_exists("include/".$page) && $page != 'index.php') {
       include("include/".$page); 
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
      <div class="widget widget-remaining-time">
        <h3 class="widget-remaining-time__heading">BIENVENUE SUR ENERGISDATA <sup class="widget-remaining-time__heading-sup">IPD</sup></h3>
        <div class="widget-remaining-time__block">
          <a class="btn btn-primary icon-right mr-3" href="javascript:window.location.reload()">Rafraîchir les données <span class="btn-icon iconfont iconfont-refresh"></span></a>
          
        </div>
      </div>
    </div>
  </div>
  
  <?php if (checkAdmin()) { ?>
  <div class="row">
    <div class="col-lg-12">
    	 <div class="widget widget-controls widget-sales-stats">
        <div class="widget-tabs__header widget-controls__header--bordered">
          <div>
            <img src="https://www.comm-back.fr/wp-content/themes/commback/landing/img/logo-acide.png" alt="acidE" width="150" />
          </div>
          <ul class="nav nav-tabs">
          	<li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#widget-db">Données Brutes</a>
            </li>
          	<li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#widget-lk">Carte Visite (LK)</a>
            </li>
            <li class="nav-item">
              <a class="nav-link " data-toggle="tab" href="#widget-nn">Nomination</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#widget-hb">Hard Bounce</a>
            </li>            
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#widget-st">Doublons stricts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#widget-stt">Doublons proches</a>
            </li>
          </ul>
        </div>
    	<div class="widget-tabs__content">
          <div class="tab-content">
          	<div class="tab-pane show active" id="widget-db">
           
           <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th>Section</th>
                  <th>OK</th>
                  <th>Ajout</th>
                  <th>Modif</th>
                  <th>Supp</th>
                  <th>Total</th>
                  <th>Objectif Annuel</th>
                  <th>Avancement</th>
                  <th>JH</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="table__tag table__tag--green">Nomination</span></td>
                  <td><span class="badge badge-lg badge-default badge-rounded mb-3 mr-3">X</span></td>
                  <?php
				  
				  	$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 1");
					$query->execute();
					$rowcountajout = $query->fetchColumn();
					$query->closeCursor();
					$nrowcountajout = $rowcountajout+3540;
					echo '<td><span class="">'.$nrowcountajout.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 2");
					$query->execute();
					$rowcountmodif = $query->fetchColumn();
					$nrowcountmodif = $rowcountmodif+1475;
					$query->closeCursor();
					echo '<td><span class="">'.$nrowcountmodif.'</span></td>';
				
				
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 3");
					$query->execute();
					$rowcountsupp = $query->fetchColumn();
					$nrowcountsupp = $rowcountsupp+304;
					$query->closeCursor();
					echo '<td><span class="">'.$nrowcountsupp.'</span></td>';	
					
					
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide");
					$query->execute();
					$rowcounttotal = $query->fetchColumn();
					$nrowcounttotal = $rowcounttotal+3540+1475+304;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcounttotal.'</span></td>';
				  
				  ?>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">12K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round(($nrowcounttotal/12000)*100).'%';?></span></td>
                  <?php

                  	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update");
					$query->execute();
					$query_temps = $query->fetch();
					$query->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
					$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
					$jh = round($duree_decimal/8, 2) + 87.1;
				  	echo '<td><span class="">'.$jh.'</span></td>';

                  ?>
                </tr>

                <tr>
                  <td><span class="table__tag table__tag--green">Hard Bounce</span></td>
                  <td><span class="">473</span></td>
                  <td><span class="">732</span></td>
                  <td><span class="">1121</span></td>
                  <td><span class="">1604</span></td>
                  <td><span class="">3930</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">30K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((3930/30000)*100).'%';?></span></td>
                  <td><span class="">68,5</span></td>
                </tr>
				<tr>
                  <td><span class="table__tag table__tag--green">Carte Visite (LK)</span></td>
                  <?php 
				  
				  	
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 1");
					$query->execute();
					$rowcountOK = $query->fetchColumn();
					$nrowcountOK = $rowcountOK+3554;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountOK.'</span></td>'; 
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 4");
					$query->execute();
					$rowcountajout = $query->fetchColumn();
					$nrowcountajout = $rowcountajout+118;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountajout.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 2");
					$query->execute();
					$rowcountmodif = $query->fetchColumn();
					$nrowcountmodif = $rowcountmodif+4350;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountmodif.'</span></td>';
					
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 3");
					$query->execute();
					$rowcountsupp = $query->fetchColumn();
					$nrowcountsupp = $rowcountsupp+4149;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountsupp.'</span></td>';
					
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting <> 0");
					$query->execute();
					$rowcounttotal = $query->fetchColumn();
					$nrowcounttotal = $rowcounttotal+4149+118+4350+3554;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcounttotal.'</span></td>';
					
					?>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">40K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round(($nrowcounttotal/40000)*100).'%';?></span></td>

                  <?php

                  	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update");
					$query->execute();
					$query_temps = $query->fetch();
					$query->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
					$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
					$jh = round($duree_decimal/8, 2) + 103.8;
				  	echo '<td><span class="">'.$jh.'</span></td>';

                  ?>


                </tr>
                <thead>
                <tr>
                  <th>Section</th>
                  <th>OK</th>
                  <th>Supp</th>
                  <th>Remontees</th>
                  <th>nt</th>
                  <th>Total</th>
                  <th>Objectif Annuel</th>
                  <th>Avancement</th>
                  <th>JH</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="table__tag table__tag--green">Doublons stricts</span></td>
                  <td><span class="">429</span></td>
                  <td><span class="">602</span></td>
                  <td><span class="">0</span></td>
                  <td><span class="">2</span></td>
                  <td><span class="">1033</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">1K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((1033/2000)*100).'%';?></span></td> 
                  <td>3</td>
                </tr>
                <tr>
                  <td><span class="table__tag table__tag--green">Doublons proches</span></td>
                  <td><span class="">888</span></td>
                  <td><span class="">871</span></td>
                  <td><span class="">1</span></td>
                  <td><span class="">2</span></td>
                  <td><span class="">1868</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">1K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((1868/2000)*100).'%';?></span></td>
                  <td>6</td>
                </tr>
              </tbody>
            </table>
          </div>
           
           
                 
            </div>
            <div class="tab-pane" id="widget-nn">
            
            <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>AJOUT</th>
                  <th>MODIF</th>
                  <th>SUPP</th>
                  <th>NT</th>
                  <th>TOTAL</th>
                  <th>OBJECTIF</th>
                  <th>A%</th>
                  <th>JH</th>
                  <th>P/Moy</th>
                </tr>
              </thead>
              <tbody>                
				<tr>
                  <td><span class="table__tag table__tag--green">Nomination</span></td>
                  
                  	<?php 
				  	
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 1");
					$query->execute();
					$rowcountajout = $query->fetchColumn();
					$query->closeCursor();
					$nrowcountajout = $rowcountajout+3540;
					echo '<td><span class="">'.$nrowcountajout.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 2");
					$query->execute();
					$rowcountmodif = $query->fetchColumn();
					$nrowcountmodif = $rowcountmodif+1475;
					$query->closeCursor();
					echo '<td><span class="">'.$nrowcountmodif.'</span></td>';
				
				
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 3");
					$query->execute();
					$rowcountsupp = $query->fetchColumn();
					$nrowcountsupp = $rowcountsupp+304;
					$query->closeCursor();
					echo '<td><span class="">'.$nrowcountsupp.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 1");
					$query->execute();
					$rowcountnt = $query->fetchColumn();
					$query->closeCursor();
					echo '<td><span class="">'.$rowcountnt.'</span></td>';	
					
					
					$query = $bdd->prepare("SELECT count(*) FROM nomination_acide");
					$query->execute();
					$rowcounttotal = $query->fetchColumn();
					$nrowcounttotal = $rowcounttotal+3540+1475+304;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcounttotal.'</span></td>';
					
					?>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">12K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round(($nrowcounttotal/12000)*100).'%';?></span></td>
                  <?php

                  	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update");
					$query->execute();
					$query_temps = $query->fetch();
					$query->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
					$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
					$jh = round($duree_decimal/8, 2) + 87.1;
				  	echo '<td><span class="">'.$jh.'</span></td>';

                  ?>


                  <td>190.2</td>
                </tr>
                
                
              </tbody>
            </table>
            
            <?php
 			
		
			
			
			$dataPoints = array(
			array("y" => $nrowcountajout, "legendText" => "Nomination Ajout", "label" => "Nomination Ajout"),
			array("y" => $nrowcountsupp, "legendText" => "Nomination SUPPRESSION", "label" => "Nomination SUPPRESSION"),
			array("y" => $nrowcountmodif, "legendText" => "Nomination Modification", "label" => "Nomination Modification"),
			array("y" => $rowcountnt, "legendText" => "Nomination nt", "label" => "Nomination nt")
			);
			// Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
			$pie3dChart = new FusionCharts("pie3d", "ex2", "50%", 400, "chart-1", "json", '{   "chart": {
					"caption": "Total Nomination",
					"subcaption": "",
					"startingangle": "120",
					"showlabels": "1",
					"showlegend": "1",
					"enablemultislicing": "0",
					"exportEnabled ":"1",
					"slicingdistance": "15",
					"showpercentvalues": "1",
					"showpercentintooltip": "1",
					"plottooltext": "NB : $datavalue",
					"theme": "ocean"
				},
				"data": [
					{
						"label": "Nomination Ajout",
						"value": "'.$nrowcountajout.'"
					},
					{
						"label": "Nomination SUPPRESSION",
						"value": "'.$nrowcountsupp.'"
					},
					{
						"label": "Nomination Modification",
						"value": "'.$nrowcountmodif.'"
					},
					{
						"label": "Nomination NT",
						"value": "'.$rowcountnt.'"
					}
				]
			}');
			// Render the chart
			$pie3dChart->render();
			?>
			<div id="chart-1"></div>
            </div>
            </div>
            <div class="tab-pane" id="widget-lk">
            <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>NT</th>
                  <th>OK</th>
                  <th>AJOUT</th>
                  <th>MODIF</th>
                  <th>SUPP</th>
                  <th>DOUB</th>
                  <th>TOTAL</th>
                  <th>OBJ</th>
                  <th>A%</th>
                  <th>JH</th>
                  <th>P/Moy</th>
                </tr>
              </thead>
              <tbody>                
				<tr>
                  <td><span class="table__tag table__tag--green">Carte Visite (LK)</span></td>
                  
                  	<?php 
				  
				  	$query = $bdd->prepare("SELECT count(*) FROM acide WHERE nt_acide = 1");
					$query->execute();
					$rowcountnt = $query->fetchColumn();
					$query->closeCursor();
				  	echo '<td><span class="">'.$rowcountnt.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 1");
					$query->execute();
					$rowcountOK = $query->fetchColumn();
					$nrowcountOK = $rowcountOK+3554;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountOK.'</span></td>'; 
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 4");
					$query->execute();
					$rowcountajout = $query->fetchColumn();
					$nrowcountajout = $rowcountajout+118;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountajout.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 2");
					$query->execute();
					$rowcountmodif = $query->fetchColumn();
					$nrowcountmodif = $rowcountmodif+4350;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountmodif.'</span></td>';
					
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 3");
					$query->execute();
					$rowcountsupp = $query->fetchColumn();
					$nrowcountsupp = $rowcountsupp+4149;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcountsupp.'</span></td>';
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting = 5");
					$query->execute();
					$rowcountdoub = $query->fetchColumn();
					$query->closeCursor();
				  	echo '<td><span class="">'.$rowcountdoub.'</span></td>';
					
					
					$query = $bdd->prepare("SELECT count(*) FROM acide WHERE reporting <> 0");
					$query->execute();
					$rowcounttotal = $query->fetchColumn();
					$nrowcounttotal = $rowcounttotal+4149+118+4350+3554;
					$query->closeCursor();
				  	echo '<td><span class="">'.$nrowcounttotal.'</span></td>';
					
					?>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">40K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round(($nrowcounttotal/40000)*100).'%';?></span></td>

                  <?php

                  	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update");
					$query->execute();
					$query_temps = $query->fetch();
					$query->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
					$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
					$jh = round($duree_decimal/8, 2) + 103.8;
				  	echo '<td><span class="">'.$jh.'</span></td>';

                  ?>


                  <td>165.2</td>
                </tr>
                
                
              </tbody>
            </table> 


            <?php		
			
			$dataPoints = array(
			array("y" => $rowcountnt, "legendText" => "NT", "label" => "NT"),
			array("y" => $nrowcountOK, "legendText" => "OK", "label" => "OK"),
			array("y" => $nrowcountajout, "legendText" => "AJOUT", "label" => "AJOUT"),
			array("y" => $nrowcountmodif, "legendText" => "MODIFICATION", "label" => "MODIFICATION"),
			array("y" => $nrowcountsupp, "legendText" => "SUPPRESSION", "label" => "SUPPRESSION"),
			array("y" => $rowcountdoub, "legendText" => "DOUBLON", "label" => "DOUBLON")
			);
			// Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
			$pie3dChart = new FusionCharts("pie3d", "ex1", "50%", 400, "chart-2", "json", '{   "chart": {
					"caption": "Total DES FICHES LINKEDIN",
					"subcaption": "",
					"startingangle": "120",
					"showlabels": "1",
					"showlegend": "1",
					"enablemultislicing": "0",
					"exportEnabled ":"1",
					"slicingdistance": "15",
					"showpercentvalues": "1",
					"showpercentintooltip": "1",
					"plottooltext": "NB : $datavalue",
					"theme": "ocean"
				},
				"data": [
					{
						"label": "NT",
						"value": "'.$rowcountnt.'"
					},
					{
						"label": "OK",
						"value": "'.$nrowcountOK.'"
					},
					{
						"label": "AJOUT",
						"value": "'.$nrowcountajout.'"
					},
					{
						"label": "MODIFICATION",
						"value": "'.$nrowcountmodif.'"
					},
					{
						"label": "SUPPRESSION",
						"value": "'.$nrowcountsupp.'"
					},
					{
						"label": "DOUBLON",
						"value": "'.$rowcountdoub.'"
					}
				]
			}');
			// Render the chart
			$pie3dChart->render();
			?>  
            
            <div id="chart-2"></div>
            </div>        
            </div>
            <div class="tab-pane" id="widget-hb">
            <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>OK</th>
                  <th>AJOUT</th>
                  <th>MODIF</th>
                  <th>SUPP</th>
                  <th>TOTAL</th>
                  <th>OBJ</th>
                  <th>A%</th>
                  <th>JH</th>
                  <th>P/Moy</th>
                </tr>
              </thead>
              <tbody>                
				<tr>
                  <td><span class="table__tag table__tag--green">Hard Bounce (HB)</span></td>
                  
                  <td><span class="">473</span></td>
                  <td><span class="">732</span></td>
                  <td><span class="">1121</span></td>
                  <td><span class="">1604</span></td>
                  <td><span class="">3930</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">30K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((3930/30000)*100).'%';?></span></td>

                  <?php

                  	/*$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update");
					$query->execute();
					$query_temps = $query->fetch();
					$query->closeCursor();
					$pieces = explode(":", $query_temps['datee']);		
					$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
					$jh = round($duree_decimal/8, 2) + 103.8;*/
				  	echo '<td><span class="">68,5</span></td>';

                  ?>


                  <td>57,37</td>
                </tr>
                
                
              </tbody>
            </table> 


            <?php		
			
			$dataPoints = array(
			array("y" => "473", "legendText" => "OK", "label" => "OK"),
			array("y" => "732", "legendText" => "AJOUT", "label" => "AJOUT"),
			array("y" => "1121", "legendText" => "MODIFICATION", "label" => "MODIFICATION"),
			array("y" => "1604", "legendText" => "SUPPRESSION", "label" => "SUPPRESSION")
			);
			// Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
			$pie3dChart = new FusionCharts("pie3d", "ex3", "50%", 400, "chart-3", "json", '{   "chart": {
					"caption": "Total DES FICHES Hard Bounce",
					"subcaption": "",
					"startingangle": "120",
					"showlabels": "1",
					"showlegend": "1",
					"enablemultislicing": "0",
					"exportEnabled ":"1",
					"slicingdistance": "15",
					"showpercentvalues": "1",
					"showpercentintooltip": "1",
					"plottooltext": "NB : $datavalue",
					"theme": "ocean"
				},
				"data": [
					,
					{
						"label": "OK",
						"value": "473"
					},
					{
						"label": "AJOUT",
						"value": "732"
					},
					{
						"label": "MODIFICATION",
						"value": "1121"
					},
					{
						"label": "SUPPRESSION",
						"value": "1604"
					}
				]
			}');
			// Render the chart
			$pie3dChart->render();
			?>  
            
            <div id="chart-3"></div>
            </div>        
            </div>
            <div class="tab-pane" id="widget-st">
            <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>OK</th>
                  <th>SUPP</th>
                  <th>REMONTEES</th>
                  <th>NT</th>
                  <th>TOTAL</th>
                  <th>OBJ</th>
                  <th>A%</th>
                  <th>JH</th>
                  <th>P/Moy</th>
                </tr>
              </thead>
              <tbody>                
				<tr>
                  <td><span class="table__tag table__tag--green">Doublons Stricts</span></td>
                  
                  <td><span class="">429</span></td>
                  <td><span class="">602</span></td>
                  <td><span class="">0</span></td>
                  <td><span class="">2</span></td>
                  <td><span class="">1033</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">1K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((1033/1000)*100).'%';?></span></td> 
                  <td><?php echo round((70/8));?></td>
                  <td>..</td>             


                </tr>
                
                
              </tbody>
            </table> 


            <?php		
			
			$dataPoints = array(
			array("y" => $nrowcountOK, "legendText" => "OK", "label" => "OK"),
			array("y" => $rowcountajout, "legendText" => "SUPPRESSION", "label" => "SUPPRESSION"),
			array("y" => $nrowcountmodif, "legendText" => "REMONTEES", "label" => "REMONTEES"),
			array("y" => $nrowcountsupp, "legendText" => "NT", "label" => "NT")
			);
			// Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
			$pie3dChart = new FusionCharts("pie3d", "ex4", "50%", 400, "chart-4", "json", '{   "chart": {
					"caption": "Total Doublons Stricts",
					"subcaption": "",
					"startingangle": "120",
					"showlabels": "1",
					"showlegend": "1",
					"enablemultislicing": "0",
					"exportEnabled ":"1",
					"slicingdistance": "15",
					"showpercentvalues": "1",
					"showpercentintooltip": "1",
					"plottooltext": "NB : $datavalue",
					"theme": "ocean"
				},
				"data": [
					,
					{
						"label": "OK",
						"value": "429"
					},
					{
						"label": "SUPPRESSION",
						"value": "602"
					},
					{
						"label": "REMONTEES",
						"value": "0"
					},
					{
						"label": "NT",
						"value": "2"
					}
				]
			}');
			// Render the chart
			$pie3dChart->render();
			?>  
            
            <div id="chart-4"></div>
            </div>        
            </div>
            <div class="tab-pane" id="widget-stt">
            <div class="widget-controls__content widget-sales-stats__content">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th></th>
                  <th>OK</th>
                  <th>SUPP</th>
                  <th>NT</th>
                  <th>FERMEES</th>
                  <th>TOTAL</th>
                  <th>OBJ</th>
                  <th>A%</th>
                  <th>JH</th>
                  <th>P/Moy</th>
                </tr>
              </thead>
              <tbody>                
				<tr>
                  <td><span class="table__tag table__tag--green">Doublons proches</span></td>
                  
                  <td><span class="">888</span></td>
                  <td><span class="">871</span></td>
                  <td><span class="">1</span></td>
                  <td><span class="">2</span></td>
                  <td><span class="">1868</span></td>
                  <td><span class="badge badge-lg badge-primary badge-rounded mb-3 mr-3">1K</span></td>
                  <td><span class="badge badge-lg badge-success badge-rounded mb-3 mr-3"><?php echo round((1868/1000)*100).'%';?></span></td>
                  <td><?php echo round((121.5/8));?></td>
                  <td>..</td>

                </tr>
                
                
              </tbody>
            </table> 


            <?php		
			
			$dataPoints = array(
			array("y" => $nrowcountOK, "legendText" => "OK", "label" => "OK"),
			array("y" => $rowcountajout, "legendText" => "SUPPRESSION", "label" => "SUPPRESSION"),
			array("y" => $nrowcountmodif, "legendText" => "NT", "label" => "NT"),
			array("y" => $nrowcountsupp, "legendText" => "FERMEES", "label" => "FERMEES")
			);
			// Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
			$pie3dChart = new FusionCharts("pie3d", "ex5", "50%", 400, "chart-5", "json", '{   "chart": {
					"caption": "Total Doublons proches",
					"subcaption": "",
					"startingangle": "120",
					"showlabels": "1",
					"showlegend": "1",
					"enablemultislicing": "0",
					"exportEnabled ":"1",
					"slicingdistance": "15",
					"showpercentvalues": "1",
					"showpercentintooltip": "1",
					"plottooltext": "NB : $datavalue",
					"theme": "ocean"
				},
				"data": [
					,
					{
						"label": "OK",
						"value": "888"
					},
					{
						"label": "SUPPRESSION",
						"value": "871"
					},
					{
						"label": "NT",
						"value": "1"
					},
					{
						"label": "FERMEES",
						"value": "2"
					}
				]
			}');
			// Render the chart
			$pie3dChart->render();
			?>  
            
            <div id="chart-5"></div>
            </div>        
            </div>
          </div>
          	
        </div>
      </div>   
        
        
    </div>
    
    
  </div>



  <div class="row">
  	<div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>GAZETTE : Collectivité</b><div>
              
            </div>
          </div>
          <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_collect_detail_rech" data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
            <thead>
            <tr>
            <th>DATE</th>
            <th>LOT</th>
            <th>INTERVENANT</th> 
            <th>DURÉE</th>           
            <th>OK</th>
            <th>KO</th>
            <th>OK - HORS LOT</th>
            <th>OK - SCE</th>
            <th>LIGNES</th>
            <th>TAUX RESOLUTION</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>            
        
          </div>
        </div>
    </div>   
  </div>     
  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>Acide : Hard Bounce</b><div>
              
            </div>
          </div>
          <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_cat_fichier_acide_rech" data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
            <thead>
            <tr>
            <th>JOUR</th>
            <th>INTERVENANT</th> 
            <th>DURÉE</th>           
            
            <th>AJOUT/SUPPRESSION</th>
            <th>AJOUT</th>
            <th>FERMÉE</th>
            <th>MODIF</th>
            <th>OK</th>
            <th>SUPP</th>
            <th>EN COURS</th>
            <th>KO</th>

            <th>LIGNES</th>
            <th>ECART/OBJ</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>            
        
            </div>
        </div>
    </div>   
  </div> 
  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>INDUSTRIE EXPLORER</b><div>
              
            </div>
          </div>
    <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_cat_fichier_acide_rech_tb" data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
            <thead>
            <tr>
            <th>JOUR</th>
            <th>INTERVENANT</th> 
            <th>DURÉE</th>
            <th>OK</th>           
            <th>KO</th>
            <th>LIGNES</th>
            <th>ECART/OBJ</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>            
        
            </div>
        </div>
    </div>   
  </div>

  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>SIRETISATION</b><div>
              
            </div>
          </div>
    <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_cat_fichier_acide_rech_tb_siret" data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
            <thead>
            <tr>
            <th>JOUR</th>
            <th>INTERVENANT</th> 
            <th>DURÉE</th>
            <th>OK</th>           
            <th>NT</th>
            <th>Ste Etrangère</th>
            <th>Ste Fermée</th>
            <th>En cours de liquidation</th>
            <th>LIGNES</th>
            <th>ECART/OBJ</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>          
        
            </div>
        </div>
    </div>   
  </div>


  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>CNIL</b><div>
              
            </div>
          </div>
    <div class="content table-responsive table-full-width">
            
            <table class="datatable table table-striped" id="table_traitement_rapport_rech"  data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
      <thead>
                <tr>
                        <th colspan="2"></th>
                        <th colspan="3" bgcolor="#efefef" style="border-right:#e9ecef 1px solid;border-left:#e9ecef 1px solid" align="center"><CENTER>FEEDBACK</CENTER></th>
                        <th></th>
                    </tr>
                    <tr>
                      <th>COLLAB</th>
                      <th>DATE</th>
                        <th>RECUS</th>
                        <th>A SUPPRIMER</th>
                        <th>DÉSABO</th>
                        <th>COMMENTAIRE</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>          
        
            </div>
        </div>
    </div>   
  </div>


  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>DMP</b><div>
              
            </div>
          </div>
    <div class="content table-responsive table-full-width">
            
      <table class="datatable table table-striped" id="table_traitement_rapport_rech_dmp" data-date="<?php echo date("Y-m-d").' - '.date("Y-m-d");?>">
            <thead>
                
                    <tr>
                      <th>COLLAB</th>
                      <th>DATE</th>
                        <th>SEMAINE</th>
                        <th>NOMBRE D'EMAILS</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        
            </div>
        </div>
    </div>   
  </div>

  <div class="row">
    <div class="col-lg-12">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>WEBMASTERS : Créations et Intégrations</b><div>
              
            </div>
          </div>
    <div class="content table-responsive table-full-width">
            
        <table class="datatable table table-striped" id="table_gestion_rapport_webs">
            <thead>
                <tr>                    
                    <th>WEBMASTER</th> 
                    <th>TOTAL INTEGRATIONS</th>
                    <th>LEADS</th>
                    <th>PERSONNALISÉ</th>
                    <th>FLASH</th> 
                    <th>RÉ-INTEGRATION</th>
                    <th>INTÉGRATION</th> 
                    <th>CRÉATION</th> 
                    <th>CRÉATION + LEADS</th>                                
                    <th>TRAITEMENT</th>
                    <th>JH</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        
            </div>
        </div>
    </div>   
  </div>

  


  <?php }else{ ?>
  <div class="row">
  
  	<?php
	if (checkWeb()) {
	$page = '';
	if (empty($page)) {
	 $page = "home";
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
		if (file_exists("module/webmaster/include/".$page) && $page != 'index.php') {
		   include("module/webmaster/include/".$page); 
		}
	
		else {
			echo "Page inexistante !";
		}
	}
	}else{
		
		/*$page = '';
  	if (empty($page)) {
  	 $page = "home";
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
  		if (file_exists("module/doc/include/".$page) && $page != 'index.php') {
  		   include("module/doc/include/".$page); 
  		}
  	
  		else {
  			echo "Page inexistante !";
  		}
  	}*/
	?> 
       
    <?php } ?> 
  </div>
  <?php } ?> 
</div>

  </div>
</div>


<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script charset="utf-8" src="js/jquery.validate.min.js"></script>
<script charset="utf-8" src="module/collectivite/table/js/webapp_collectivite_detail_rech.js"></script>
<script charset="utf-8" src="module/hb/table/js/webapp_cat_fichier_acide_detail_rech.js"></script>
<script charset="utf-8" src="module/ie/table/js/webapp_cat_fichier_ie_detail_rech_tb.js"></script>
<script charset="utf-8" src="module/siretisation/table/js/webapp_cat_fichier_siret_detail_rech_tb.js"></script>
<script charset="utf-8" src="module/cnil/table/js/webapp_cnil_admin_rech_tb.js"></script>
<script charset="utf-8" src="module/cnil/table/js/webapp_dmp_admin_rech_tb.js"></script>
<script src="module/webmaster/table/js/webapp_gestion_webs_integration_tb.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="js/growl-notification/growl-notification.js"></script>
<script src="js/preview/growl-notifications.min.js"></script>
<script src="vendor/momentjs/moment-with-locales.min.js"></script>
<script src="vendor/date-range-picker/daterangepicker.js"></script>
<script src="js/preview/date-range-picker.js"></script>
<script src="vendor/nouislider/nouislider.min.js"></script>
<script src="vendor/tagify/tagify.min.js"></script>
<script src="js/preview/modal.min.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<div class="sidebar-mobile-overlay"></div> 
</body>
</html>