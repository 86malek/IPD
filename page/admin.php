<?php 
include '../config/dbc.php';
page_protect();

if(!checkAdmin()) {
header("Location: ../index.php");
exit();
}
$page_limit = 100; 


$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @preg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

foreach($_GET as $key => $value) {
	$get[$key] = filter($db,$value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($db,$value);
}

if(!empty($_POST['doBan']) && $post['doBan'] == 'Bannir') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($db,$uid);
		mysqli_query($db,"update users set banned='1' where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];
 
 header("Location: $ret");
 exit();
}

if(!empty($_POST['doUnban']) && $_POST['doUnban'] == 'Débannir') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($db,$uid);
		mysqli_query($db,"update users set banned='0' where id='$id'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if(!empty($_POST['doDelete']) && $_POST['doDelete'] == 'Effacer') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($db,$uid);
		mysqli_query($db,"delete from users where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];
 
 header("Location: $ret");
 exit();
}

if(!empty($_POST['doApprove']) && $_POST['doApprove'] == 'Approuver') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($db,$uid);
		mysqli_query($db,"update users set approved='1' where id='$id'");
		
	list($to_email) = mysqli_fetch_row(mysqli_query($db,"select user_email from users where id='$uid'"));	
 
$message = 
"Bonjour,\n
Merci pour votre enregistrement. Votre compte esr acctivé...\n

*****Lien de connexion*****\n
http://$host$path/login.php

Merci

Administrateur InfoPro
$host_upper
______________________________________________________
CECI EST UNE RÉPONSE AUTOMATISÉ.
*** NE PAS RÉPONDRE À CE COURRIEL ****
";

@mail($to_email, "Activation", $message,
    "From: \"Infoprodata\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion()); 
	 
	}
 }
 
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];	 
 header("Location: $ret");
 exit();
}

$rs_all = mysqli_query($db,"select count(*) as total_all from users") or die(mysqli_connect_error());
$rs_active = mysqli_query($db,"select count(*) as total_active from users where approved='1'") or die(mysqli_connect_error());
$rs_total_pending = mysqli_query($db,"select count(*) as tot from users where approved='0'");						   

list($total_pending) = mysqli_fetch_row($rs_total_pending);
list($all) = mysqli_fetch_row($rs_all);
list($active) = mysqli_fetch_row($rs_active);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>Administration / ENERGIS</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">

  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

  

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
  <h2 class="content-heading">Administration</h2>
  <div class="row">
    <div class="col-lg-12">
      <div class="widget widget-welcome">
        <div class="widget-welcome__message">
          <h4 class="widget-welcome__message-l1">Base de données des utilisateurs</h4>
          <h6 class="widget-welcome__message-l2"><img src="img/logo/logo.png" alt="" width="110"></h6>
        </div>
        <div class="widget-welcome__stats">
          <div class="widget-welcome__stats-item early-growth">
            <span class="widget-welcome__stats-item-value"><center><?php echo $all;?></center></span>
            <span class="widget-welcome__stats-item-desc">Total</span>
          </div>
          <div class="widget-welcome__stats-item monthly-growth">
            <span class="widget-welcome__stats-item-value"><center><?php echo $active; ?></center></span>
            <span class="widget-welcome__stats-item-desc">Actifs</span>
          </div>
          <div class="widget-welcome__stats-item daily-growth">
            <span class="widget-welcome__stats-item-value"><center><?php echo $total_pending; ?></center></span>
            <span class="widget-welcome__stats-item-desc">En attente</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main-container">
  
  <div class="container-block">
  
  	<div class="row">
        <div class="col-6">
        <h3>Création d'un nouveau compte utilisateur</h3>
        
        
		<?php
        if(!empty($_POST['doSubmit']) && $_POST['doSubmit'] == 'Création')
        {
            
            
                $rs_dup = mysqli_query($db,"select count(*) as total from users where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(mysqli_connect_error());
                list($dups) = mysqli_fetch_row($rs_dup);
            
                if($dups > 0) {
                echo '<div class="alert alert-message alert-message-red" role="alert"><div class="alert-message-heading">Information</div><p>Le nom ou l\'adresse mail existe déjà dans la base</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>';
                }else{
            
                    if(!empty($_POST['pwd'])) {
                      if(passComplex($_POST['pwd']) == true){
                      $pwd = $post['pwd'];	
                      $hash = PwdHash($post['pwd']);
                      mysqli_query($db,"INSERT INTO users (`full_name`,`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`)
                         VALUES ('$post[user_name]','$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]')
                         ") or die(mysqli_connect_error());        
            
                      echo '<div class="alert alert-message alert-message-green" role="alert"><div class="alert-message-heading">Information</div><p>Utilisateur ajouté avec succés et approuvé automatiquement</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>'; 
                      }else{echo '<div class="alert alert-message alert-message-red" role="alert"><div class="alert-message-heading">Information</div><p>Mot de passe non conforme</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>';}
                     }  
                     else
                     {
                      $pwd = GenPwd();
                      $hash = PwdHash($pwd);
                      mysqli_query($db,"INSERT INTO users (`full_name`,`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`)
                         VALUES ('$post[user_name]','$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]')
                         ") or die(mysqli_connect_error());        
            
                      echo '<div class="alert alert-message alert-message-green" role="alert"><div class="alert-message-heading">Information</div><p>Utilisateur ajouté avec succés et approuvé automatiquement</p><span class="close iconfont iconfont-alert-close" data-dismiss="alert"></span></div>'; 
                      
                     }
             
                      
                }
            
        }
        ?>
        <form name="form1" method="post" action="admin.php" id="adminForm" autocomplete="off">
        <input type="hidden" name="actif" value="outil">
        
        <div class="input-group">
        <span class="input-group-addon">...</span>
        <input type="text" placeholder="Nom et prénom" class="form-control" name="user_name" id="user_name" autocomplete="off" required>
        </div>
        
        <div class="input-group">
        <span class="input-group-addon">@</span>
        <input type="email" placeholder="Courrier éléctronique" class="form-control" name="user_email" id="user_email" autocomplete="off" required>
        </div>
        
        
        
        <div class="input-group">
        <span class="input-group-addon">MDP</span>
        <input type="password" minlength="6" pattern=".{6,}" placeholder=".6 caracteres minimum" class="form-control" name="pwd" id="pwd" autocomplete="off" required>
        </div>
        
        <div class="form-group">        
        <select class="form-control" data-placeholder="Niveau de permission"  name="user_level" id="user_level" required>
        <option></option>            
        <optgroup label="Grades">
        <option value="0">Collaborateur</option>
        <option value="1">Coordinateur</option>
        <option value="5">Super Admin</option>
        </optgroup>
        </select>
        </div>
        
        
        <h4>Envoyé un Email</h4>
        <div class="input-group">
          <label class="switch">
            <input name="send" type="checkbox" id="send" value="1" checked>
              <span class="switch-slider">
                <span class="switch-slider__on"></span>
                <span class="switch-slider__off"></span>
              </span>
          </label>
        </div>        
        
        
        <div class="form-group">
        <button class="btn btn-info btn-sm mb-2 mr-3 icon-right" name="doSubmit" type="submit" id="doSubmit" value="Création">Enregistrement du compte</button>
        </div>
        
        </form>
        </div>
        <div class="col-6">
        	<h3>Recherche des utilisateurs dans la base de donnée</h3>
            <?php 
      			if(!empty($msg)) {
      			echo $msg[0];
      			}
      			?>
            
          <form name="form1" method="get" action="admin.php" id="sform">
            <input type="hidden" name="actif" value="outil">
            
            <div class="input-group">
            <span class="input-group-addon">RECH</span>
            <input name="q" type="text" id="q" class="form-control"> 
            </div>
            
            <!--<h4>Statu</h4>
            <div class="input-group">
               	
                <label class="color-radio">
                  <input type="radio" name="qoption" value="pending">
                  <span class="color-radio__color color-radio__color--success"></span>
                  <span class="color-radio__text">Enregistrés</span>
                </label>
                <label class="color-radio">
                  <input type="radio" name="qoption" value="recent">
                  <span class="color-radio__color color-radio__color--warning"></span>
                  <span class="color-radio__text">En attente</span>
                </label>
                <label class="color-radio">
                  <input type="radio" name="qoption" value="banned">
                  <span class="color-radio__color color-radio__color--danger"></span>
                  <span class="color-radio__text">Bannis</span>
                </label>
            </div> -->
            <div class="form-group">
            
            <button class="btn btn-info btn-lg mb-2 mr-3 icon-right" name="doSearch" type="submit" id="doSearch2" value="Recherche">Lancer la recherche</button>
            </div>
          </form>
			<?php if (!empty($get['doSearch']) && $get['doSearch'] == 'Recherche') {
            $cond = '';
            if(!empty($get['qoption']) && $get['qoption'] == 'pending') {
            $cond = "where `approved`='0' order by date desc";
            }
            if(!empty($get['qoption']) && $get['qoption'] == 'recent') {
            $cond = "order by date desc";
            }
            if(!empty($get['qoption']) && $get['qoption'] == 'banned') {
            $cond = "where `banned`='1' order by date desc";
            }
            
            if($get['q'] == '') { 
            $sql = "select * from users $cond";
            } 
            else { 
            $sql = "select * from users where `user_email` = '$_REQUEST[q]' or `user_name`='$_REQUEST[q]' ";
            }
            
            
            $rs_total = mysqli_query($db,$sql) or die(mysqli_connect_error());
            $total = mysqli_num_rows($rs_total);
            
            if (!isset($_GET['page']) )
            { $start=0; } else
            { $start = ($_GET['page'] - 1) * $page_limit; }
            
            $rs_results = mysqli_query($db,$sql . " limit $start,$page_limit") or die(mysqli_connect_error());
            $total_pages = ceil($total/$page_limit);
            
            
            
            if ($total > $page_limit)
            {
            echo "<div><strong>Pages:</strong> ";
            $i = 0;
            while ($i < $page_limit)
            {
            
            
            $page_no = $i+1;
            $qstr = ereg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
            echo "<a href=\"admin.php?actif=outil&$qstr&page=$page_no\">$page_no</a> ";
            $i++;
            }
            echo "</div>";
            }  
            ?>
          
    	</div>
        
	</div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
      <div class="main-container table-container">
      	<form name="searchform" action="admin.php" method="post" id="serachform">
        <table class="table table__actions">
          <thead>
            <tr>
                <th></th>
                <th>Date d'ajout</th>
                <th>Login</th>
                <th>Email</th>
              	<th>statut</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($rrows = mysqli_fetch_array($rs_results)) {?>
                <tr>
                 	<td class="table__switch">
                      <label class="switch">
                        <input name="u[]" type="checkbox" value="<?php echo $rrows['id']; ?>" id="u[]">
                          <span class="switch-slider">
                            <span class="switch-slider__on"></span>
                            <span class="switch-slider__off"></span>
                          </span>
                      </label>
                    </td>
          			 <td><?php echo $rrows['date']; ?></td>
                      <td><?php echo $rrows['user_name'];?></td>
                      <td><?php echo $rrows['user_email']; ?></td>
                      <td class="table__label"><?php if(!$rrows['banned']) { echo '<span class="badge badge-success">Actif<span>'; } else {echo '<span class="badge badge-danger">désactivé</span>'; }?></td>
                      <td class="table__cell-actions">
                        <div class="table__cell-actions-wrap">
                          
                               
                              <a class="btn btn-sm btn-info table__cell-actions-item" href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");'>Modifier</a>
                      
                      
                          </div>
                      </td>
                      </tr>
                      <tr> 
									<td colspan="6">
									
									<div style="display:none;" class="col-lg-6"" id="edit<?php echo $rrows['id']; ?>">
									
                    <input type="hidden" name="actif" value="outil">
                    <input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
                    <div class="form-group">
                    <input type="text" placeholder="Login" class="form-control" name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" value="<?php echo $rrows['user_name']; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" placeholder="Email" class="form-control" id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" value="<?php echo $rrows['user_email']; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" placeholder="Permission" class="form-control" id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" value="<?php echo $rrows['user_level']; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" placeholder="Mot de passe" class="form-control" id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>">
                    </div>
                    <div class="form-group">
                    <button class="btn btn-info btn-sm mb-2 mr-3 icon-right" name="doSave" type="submit" id="doSave" value="Sauvgrader" onclick='$.get("do.php",{ cmd: "edit", pass:$("input#pass<?php echo $rrows['id']; ?>").val(),user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val() } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });'>Enregistrement</button><a onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);" class="btn btn-sm btn-info table__cell-actions-item">Fermer</a>
                    </div>
                    <div id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
									
                                    
									
									
								  </div>
								  
								  <?php } ?>
                                    </td></tr></tbody></table> 
                                    <div class="form-group">
                                
                                    <button class="btn btn-secondary btn-sm mb-2 mr-3" name="doApprove" type="submit" id="doApprove" value="Approuver">Approuver</button>
                                    <button class="btn btn-secondary btn-sm mb-2 mr-3" name="doBan" type="submit" id="doBan" value="Bannir">Bannir</button>
                                    <button class="btn btn-secondary btn-sm mb-2 mr-3" name="doUnban" type="submit" id="doUnban" value="Débannir">Débannir</button>
                                    <button class="btn btn-secondary btn-sm mb-2 mr-3" name="doDelete" type="submit" id="doDelete" value="Effacer">Effacer</button>
                                    <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>"></div>

                                    </form>
							  
							     <?php }?>
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
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<div class="sidebar-mobile-overlay"></div>  
</body>
</html>