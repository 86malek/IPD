
    
  
<?php
 
try
{
$home_webs = $bdd->prepare("SELECT * FROM `webmaster_integration` GROUP BY `user_id`");
$home_webs->execute();
while ($traitement = $home_webs->fetch()){
	
$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$totall = $query->fetchColumn();
$query->closeCursor();


$query = $bdd->prepare("SELECT * FROM `users` WHERE id = :user_id");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$user = $query->fetch();
$query->closeCursor();

		
		
$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 1");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$leads = $query->fetchColumn();
$query->closeCursor();


$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 2");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$perso = $query->fetchColumn();
$query->closeCursor();

$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 3");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$flash = $query->fetchColumn();
$query->closeCursor();

$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 4");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$reintegration = $query->fetchColumn();
$query->closeCursor();		

$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 5");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$crea = $query->fetchColumn();
$query->closeCursor();

$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 6");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$crealead = $query->fetchColumn();
$query->closeCursor();

$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 7");
$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
$query->execute();
$integ = $query->fetchColumn();
$query->closeCursor();
?>

<div class="col-xl-3 col-lg-12">
  <div class="widget widget-user-card" >
    <div class="widget-user-card__bg">
    
    </div>
    <div class="widget-user-card__content">
      <img src="module/webmaster/include/profil/<?php echo $user['user_img']; ?>" alt="" width="120" height="120" class="widget-user-card__avatar">

      <div class="widget-user-card__info">
        <div class="widget-user-card__name"><?php echo $traitement['user_name']; ?></div>
        <div class="widget-user-card__occupation"><?php echo $user['user_spec']; ?></div>
      </div>
    </div>
    <div class="widget-user-card__statistics">
      <div class="widget-user-card__statistics-item">
        <span class="widget-user-card__statistics-amount"><?php echo $crea; ?></span>
        <span class="widget-user-card__statistics-type">Créa</span>
      </div>
      <div class="widget-user-card__statistics-item">
        <span class="widget-user-card__statistics-amount"><?php echo $integ; ?></span>
        <span class="widget-user-card__statistics-type">Intég</span>
      </div>
      <div class="widget-user-card__statistics-item">
        <span class="widget-user-card__statistics-amount"><?php echo $leads; ?></span>
        <span class="widget-user-card__statistics-type">Leads</span>
      </div>
      <div class="widget-user-card__statistics-item">
        <span class="widget-user-card__statistics-amount"><?php echo $crealead; ?></span>
        <span class="widget-user-card__statistics-type">Créa-Leads</span>
      </div>
      <div class="widget-user-card__statistics-item">
        <span class="widget-user-card__statistics-amount"><?php echo $perso; ?></span>
        <span class="widget-user-card__statistics-type">Perso</span>
      </div>
    </div>
  </div>
</div>
<?php 

}
$home_webs->closeCursor();				
}
catch(PDOException $x) 
{ 	
die("Secured");		
}
//$bdd = null;
$home_webs = null;	

?>
<div class="col-xl-6 col-lg-6">
      <div class="widget widget-controls widget-payouts widget-controls--dark">
        <div class="widget-controls__header">
          <div>
            <span class="widget-controls__header-icon iconfont iconfont-download"></span> DOCUMENTS / RESSOURCES
          </div>
        </div>
        <div class="widget-controls__content js-scrollable">
          <table class="table table-no-border table-striped">
            <thead>
            <tr>
              <th>DOCUMENT</th>
              <th>TAILLE</th>
              <th>TYPE</th>
              <th>STATUT</th>
              <th>DATE D'AJOUT</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
 
			try
			{
			$home_webs_down_count = $bdd->prepare("SELECT COUNT(*) FROM `webmaster_doc`");
			$home_webs_down_count->execute();
			$count = $home_webs_down_count->fetchColumn();
			$home_webs_down_count->closeCursor();
			
			if($count == 0){
				echo'<tr>';
				echo'<td colspan="7">Aucun fichier disponible !</td>';
				echo'<tr>';
			}else{
				$home_webs_down = $bdd->prepare("SELECT * FROM `webmaster_doc`");
				$home_webs_down->execute();
				while ($traitement = $home_webs_down->fetch()){
					if($traitement['doc_actif'] == 1){$actif = '<span class="badge-circle badge-circle-success mr-3"></span>';}else{$actif = '<span class="badge-circle badge-circle-danger mr-3"></span>';}
					$ajout = date("d-m-Y", strtotime($traitement['doc_date_ajout']));
					$taille = round($traitement['doc_taille']/1024);
					$taillefinal = $taille.' Ko';
					
					$fichier = ''.$traitement['doc_nom'].'';
					
					echo'<tr>';
					echo'<td>'.$traitement['doc_nom'].'</td>';
					echo'<td>'.$taillefinal.'</td>';
					echo'<td>'.$traitement['doc_type'].'</td>';
					echo'<td>'.$actif.'</td>';
					echo'<td>'.$ajout.'</td>';
					echo'<td><a target="_blank" href="module/doc/upload/' . $traitement['doc'] . '" class="btn btn-sm btn-info">Télécharger</a></td>';
					echo'</tr>';
				}
				$home_webs_down->closeCursor();
			}
			}
			catch(PDOException $x) 
			{ 	
			die("Secured");		
			}
			//$bdd = null;
			$home_webs_down_count = null;	
			
			?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>