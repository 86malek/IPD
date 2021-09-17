<div class="col-xl-6 col-lg-12">
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
			$home_webs_down_count = $bdd->prepare("SELECT COUNT(*) FROM `energis_doc`");
			$home_webs_down_count->execute();
			$count = $home_webs_down_count->fetchColumn();
			$home_webs_down_count->closeCursor();
			
			if($count == 0){
				echo'<tr>';
				echo'<td colspan="7">Aucun fichier disponible !</td>';
				echo'<tr>';
			}else{
				$home_webs_down = $bdd->prepare("SELECT * FROM `energis_doc`");
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
