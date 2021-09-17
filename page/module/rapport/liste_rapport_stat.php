<?php 
$page = '';
$id = '';
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
  <title>RAPPORTS</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

<link rel="stylesheet" href="vendor/date-range-picker/daterangepicker.css">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
<link rel="stylesheet" href="vendor/jquery-confirm/jquery-confirm.min.css">   
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>


<link rel="stylesheet" href="module/rapport/table/css/layout_rapport.css">
  

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
  	<div class="col-lg-3">  
        <div class="widget widget-table">
          <div class="widget-controls__header"><b>Sociétés</b><div>
              
            </div>
          </div>
          <div class="widget-controls__content js-scrollable">
            <table class="table table-no-border table-striped">
              <thead>
                <tr>
                  <th>Janvier juin 2018</th>
                  <th>Total</th>
                  <th>Projection Annuelle</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="table__tag table__tag--green">Cessations</span></td>
                  <td>800</td>
                  <td>1500</td>
                </tr>
                <tr>
                  <td><span class="table__tag table__tag--green">Réactivations</span></td>
                  <td>15</td>
                  <td>24</td>
                </tr>
                <tr>
                  <td><span class="table__tag table__tag--green">Fusions Achats</span></td>
                  <td>52</td>
                  <td>76</td>
                </tr>
                <tr>
                  <td><span class="table__tag table__tag--green">Déménagement</span></td>
                  <td>132</td>
                  <td>234</td>
                </tr>
                <tr>
                  <td><span class="table__tag table__tag--green">Total</span></td>
                  <td>999</td>
                  <td>1834</td>
                </tr>
                

              </tbody>
            </table>
          </div>
        </div>
    </div>
    <!--<div class="col-xl-3 col-lg-3 col-md-6">
      <div class="widget widget-alpha widget-alpha--color-green-jungle">
        <div>
          	<div class="widget-alpha__amount">
			<?php 
		  
		  	/*$query = $bdd->prepare("SELECT count(*) FROM users WHERE banned = 0");
			$query->execute();
			$rowcountuser = $query->fetchColumn();
			$query->closeCursor();
		  	echo $rowcountuser;*/ 
		  	?></div>
          <div class="widget-alpha__description">Collaborateurs Actifs</div>
        </div>
        <span class="widget-alpha__icon iconfont iconfont-user-outline"></span>
      </div>
      <div class="widget widget-alpha widget-alpha--color-amaranth">
        <div>
          <div class="widget-alpha__amount">
			<?php 
		  
		  	/*$query = $bdd->prepare("SELECT count(*) FROM users WHERE banned = 1");
			$query->execute();
			$rowcountuser = $query->fetchColumn();
			$query->closeCursor();
		  	echo $rowcountuser; */
		  	?></div>
          <div class="widget-alpha__description">Collaborateurs Bloqués</div>
        </div>
        <span class="widget-alpha__icon iconfont iconfont-user-outline"></span>
      </div>
    </div>-->
  <!--<div class="col-lg-9">
      <div class="widget widget-sales-stats">
        <div class="widget-controls__header">
          <div>
            <b>Data Intelligence: IE& Collectivités</b>
          </div>
        </div>
        <div class="widget-controls__content">
          <table class="table table-no-border table-striped">
            <thead>
              <tr>
                <th></th>
                <th>ETP théorique</th>
                <th>ETP Réel</th>
                <th>Volume Objectif 2018</th>
                <th>Fiches traitées</th>
                <th>Avancement en %</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>
                <th>Objectif</th>
                <th>Ecart</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Etablissements</td>
                <td>2</td>
                <td>1.5</td>
                <td>18765</td>
                <td>10141</td>
                <td>54%</td>
                <td>164</td>
                <td>39</td>
                <td>40</td>
                <td><span class="table__cell-down">-2%</span></td>
              </tr>
              <tr>
                <td>Demandes Annexes</td>
                <td>1</td>
                <td>1</td>
                <td>**</td>
                <td>4161</td>
                <td>**</td>
                <td>28</td>
                <td>**</td>
                <td>**</td>
                <td>**</td>
              </tr>
              <tr>
                <td>Automobile</td>
                <td>0.25</td>
                <td>0</td>
                <td>2031</td>
                <td>327</td>
                <td>16%</td>
                <td>4</td>
                <td>80</td>
                <td>60</td>
                <td><span class="table__cell-up">33%</span></td>
              </tr>
              <tr>
                <td>Collectivité</td>
                <td>2</td>
                <td>2</td>
                <td>6800</td>
                <td>3021</td>
                <td>44%</td>
                <td>116.15</td>
                <td>26</td>
                <td>**</td>
                <td>**</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
        
    </div>-->
    
  </div>     
  <!--<div class="row">  	
  <div class="col-lg-12">
      <div class="widget">
        <div class="widget-controls__header ">
          <div>
            <b>Lead Gen</b>
          </div>
        </div>
        <div class="widget-controls__content">
          <table class="table table-no-border table-striped">
            <thead>
              <tr>
                <th></th>
                <th>ETP théorique</th>
                <th>ETP Réel</th>
                <th>Volume Objectif 2018</th>
                <th>Fiches</th>
                <th>Avancement en %</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>
                <th>Objectif</th>
                <th>Ecart</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Control Qualité Leads</td>
                <td>2</td>
                 <td>1.25</td>
                <td>15000</td>
                <td>6376</td>
                <td>43%</td>
                <td>134.4</td>
                <td>47.5</td>
                <td>50</td>
                <td><span class="table__cell-down">-5%</span></td>
              </tr>
              <tr>
                <td>Delivery Client (Bases Adhoc, ouvreurs)</td>
                <td>2</td>
                 <td>2</td>
                <td>54000</td>
                <td>33060</td>
                <td>61%</td>
                <td>435.5</td>
                <td>76</td>
                <td>80</td>
                <td><span class="table__cell-down">-5%</span></td>
              </tr>
              
              <tr>
                <td align="right">Base Adhoc Samsung</td>
                <td></td>
                 <td>4</td>
                <td></td>
                <td>752</td>
                <td>100%</td>
                <td>51</td>
                <td>15</td>
                <td>**</td>
                <td></td>
              </tr>
              
              
              <tr>
                <td align="right">Base Adhoc Toyota</td>
                <td></td>
                 <td>4</td>
                <td></td>
                <td>2728</td>
                <td>100%</td>
                <td>90</td>
                <td>30</td>
                <td>**</td>
                <td></td>
              </tr>
              
              
              <tr>
                <td align="right">Base Adhoc Agena 3000</td>
                <td></td>
                 <td>5</td>
                <td></td>
                <td>2233</td>
                <td>100%</td>
                <td>122</td>
                <td>18</td>
                <td>**</td>
                <td></td>
              </tr>
              
              <tr>
                <td align="right">Base Adhoc Infass</td>
                <td></td>
                 <td>4</td>
                <td></td>
                <td>3777</td>
                <td>100%</td>
                <td>63.5</td>
                <td>59</td>
                <td>**</td>
                <td></td>
              </tr>
              
              <tr>
                <td align="right">Piscinistes</td>
                <td></td>
                 <td>2</td>
                <td></td>
                <td>560</td>
                <td>100%</td>
                <td>24</td>
                <td>23</td>
                <td>**</td>
                <td></td>
              </tr>
              
              
              <tr>
                <td align="right">Norsilk</td>
                <td></td>
                 <td>4</td>
                <td></td>
                <td>1569</td>
                <td>100%</td>
                <td>20</td>
                <td>78</td>
                <td>**</td>
                <td></td>
              </tr>
              <tr>
                <td align="right">Autres…</td>
                <td></td>
                 <td>2</td>
                <td></td>
                <td>5100</td>
                <td>100%</td>
                <td>65</td>
                <td>78</td>
                <td>**</td>
                <td></td>
              </tr>
              
              <tr>
                <td>Acide Demandes Annexes + bdd</td>
                <td>2</td>
                <td>2</td>
                <td>**</td>
                <td>51001</td>
                <td>**</td>
                <td>243</td>
                <td>**</td>
                <td>**</td>
                <td></td>
              </tr>
              <tr>
                <td bgcolor="" style="" colspan="9">Acide :</td>
              </tr>
              <tr>
                <td>Nomination</td>
                <td>1</td>
                 <td>1</td>
                <td>12000</td>
                <td>6007</td>
                <td>50%</td>
                <td>89</td>
                <td>67</td>
                <td>60</td>
                <td><span class="table__cell-up">12%</span></td>
              </tr>
              <tr>
                <td>Linkedin</td>
                <td>2</td>
                 <td>1.25</td>
                <td>40000</td>
                <td>15754</td>
                <td>39%</td>
                <td>125</td>
                <td>165</td>
                <td>130</td>
                <td><span class="table__cell-up">27%</span></td>
              </tr>
              <tr>
                <td>HB</td>
                <td>2</td>
                 <td>1.5</td>
                <td>30000</td>
                <td>3930</td>
                <td>13%</td>
                <td>68.5</td>
                <td>57</td>
                <td>60</td>
                <td><span class="table__cell-down">-5%</span></td>
              </tr>
              <tr>
                <td>Doublons (stricts et proches)</td>
                <td>0,125</td>
                 <td>0</td>
                <td>2000</td>
                <td>728</td>
                <td>36%</td>
                <td>9</td>
                <td>83.5</td>
                <td>80</td>
                <td><span class="table__cell-up">17%</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
        
    </div>
   
  </div>-->
  <div class="row"> 
  <!--<div class="col-lg-6">
      <div class="widget widget-sales-stats">
        <div class="widget-controls__header">
          <div>
            <b>Qualité de données	</b>
          </div>
        </div>
        <div class="widget-controls__content">
          <table class="table table-no-border table-striped">
            <thead>
              <tr>
                <th></th>
                <th>ETP théorique</th>
                <th>ETP Réel</th>
                <th>Volume Objectif 2018</th>
                <th>Fiches traitées</th>
                <th>Avancement en %</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>
                <th>Objectif</th>
                <th>Ecart</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Qualification Bdd Groupe</td>
                <td>2</td>
                 <td>1</td>
                <td>30000</td>
                <td>7695</td>
                <td>26%</td>
                <td>80</td>
                <td>96</td>
                <td>**</td>
                <td>**</td>
              </tr>
              <tr>
                <td>Sourcing Tel Service Gisi</td>
                <td>1</td>
                 <td>1</td>
                <td>30000</td>
                <td>12130</td>
                <td>40%</td>
                <td>95</td>
                <td>106</td>
                <td>100</td>
                <td><span class="table__cell-up">6%</span></td>
              </tr>
              
            </tbody>
          </table>
        </div>
      </div>
      </div>-->
      <div class="col-lg-6">
        <div class="widget widget-sales-stats">
        <div class="widget-controls__header">
          <div>
           <b>Webmasters	</b>
          </div>
        </div>
        <div class="widget-controls__content">
          <table class="table table-no-border table-striped">
            <thead>
              <tr>
                <th></th>
                <th>ETP théorique</th>
                <th>ETP Réel</th>
                <th>Volume Objectif 2018</th>
                <th>Fiches traitées</th>
                <th>Avancement en %</th>
                <th>JH à Date</th>
                <th>Performance Moyenne</th>
                <th>Objectif</th>
                <th>Ecart</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Routage Emailing Client+Groupe</td>
                <td>5</td>
                <td>5</td>
                <td>4500</td>
                <td>1984</td>
                <td>44%</td>
                <td>428</td>
                <td>**</td>
                <td>**</td>
                <td>**</td>
              </tr>
              
            </tbody>
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
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>


<script charset="utf-8" src="module/rapport/table/js/webapp_equipe.js"></script>


<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
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
<script src="js/preview/datepicker.min.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<div class="sidebar-mobile-overlay"></div>   
</body>
</html>