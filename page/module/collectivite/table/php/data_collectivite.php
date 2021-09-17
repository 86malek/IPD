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
    if (file_exists("../../../../../config/".$page) && $page != 'index.php') {
       include("../../../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();

$job = '';
$id  = '';

if (isset($_GET['job'])){
	
  $job = $_GET['job'];
  if (	$job == 'get_collect_liste' ||
		$job == 'get_collect_liste_admin' ||
		$job == 'get_collect_liste_edit'   ||
		$job == 'add_doc_acide'   ||
		$job == 'edit_collect_liste'  ||
		$job == 'delete_collect_liste'){
			
		  	if (isset($_GET['cat'])){
			$cat = $_GET['cat'];}
			
    		if (isset($_GET['id'])){
      		$id = $_GET['id'];
      		if (!is_numeric($id)){
        	$id = '';}}
					
			if (isset($_GET['id_cat'])){
      		$id_cat = $_GET['id_cat'];
      		if (!is_numeric($id_cat)){
        	$id_cat = '';}}
		
    
  }else{$job = '';}
}

$mysql_data = array();

if ($job != ''){
	  
  if ($job == 'get_collect_liste_admin'){
    
	/*try 
	{*/
	$query = $bdd->prepare("SELECT * FROM `collectivite_lot` ORDER BY `collect_lot_id` DESC");
	$query->execute();
	
	while ($doc = $query->fetch()){	
		
		//****Solution temporaire le temps que tout traitement passe par la table Update*****//
		
		/*$query_verif_temp = $bdd->prepare("SELECT * FROM collectivite_lot_synthese WHERE collect_lot_id = :collect_lot_id AND etat = 1");
		$query_verif_temp->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_verif_temp->execute();
		$verif_tempss = $query_verif_temp->fetch();
		$query_verif_temp->closeCursor();
		
		if($verif_tempss['date_debut_traitement'] <> NULL || $verif_tempss['date_debut_traitement'] <> '0000-00-00 00:00:00'){
		
		$query_select_date = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_lot_synthese WHERE collect_lot_id = :collect_lot_id AND etat = 1");
		$query_select_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_select_date->execute();
		$select_date_max = $query_select_date->fetch();
		$query_select_date->closeCursor();
		
		$query_select_date = $bdd->prepare("SELECT MIN(date_debut_traitement) AS date_debut FROM collectivite_lot_synthese WHERE collect_lot_id = :collect_lot_id AND etat = 1");
		$query_select_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_select_date->execute();
		$select_date_min = $query_select_date->fetch();
		$query_select_date->closeCursor();
			
			
			$query_update_date = $bdd->prepare("UPDATE collectivite_lot SET date_debut_traitement = :date_debut_traitement, date_fin_traitement = :date_fin_traitement WHERE collect_lot_id = :collect_lot_id");
			$query_update_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$query_update_date->bindParam(":date_debut_traitement", $select_date_min['date_debut'], PDO::PARAM_STR);
			$query_update_date->bindParam(":date_fin_traitement", $select_date_max['date_fin'], PDO::PARAM_STR);
			$query_update_date->execute();
			$query_update_date->closeCursor();
			
		}else{
			
			$query_select_date = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id");
			$query_select_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$query_select_date->execute();
			$select_date = $query_select_date->fetch();
			$query_select_date->closeCursor();
				
				$verif_erreur = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche_update` WHERE collect_lot_id = :collect_lot_id");
				$verif_erreur->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
				$verif_erreur->execute();
				$evite_erreur = $verif_erreur->fetchColumn();
				$verif_erreur->closeCursor();
				
				if($evite_erreur > 0){
				$query_update_date = $bdd->prepare("UPDATE collectivite_lot SET date_debut_traitement = :date_debut_traitement, date_fin_traitement = :date_fin_traitement WHERE collect_lot_id = :collect_lot_id");
				$query_update_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
				$query_update_date->bindParam(":date_debut_traitement", $select_date['date_debut'], PDO::PARAM_STR);
				$query_update_date->bindParam(":date_fin_traitement", $select_date['date_fin'], PDO::PARAM_STR);
				$query_update_date->execute();
				$query_update_date->closeCursor();
				}
				
					
		
		}*/
		
		//****Solution temporaire le temps que tout traitement passe par la table Update*****//
		$query_select_date = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitment) AS date_deb FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id");
		$query_select_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_select_date->execute();
		$select_date = $query_select_date->fetch();
		$query_select_date->closeCursor();
				
		$verif_erreur = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche_update` WHERE collect_lot_id = :collect_lot_id");
		$verif_erreur->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$verif_erreur->execute();
		$evite_erreur = $verif_erreur->fetchColumn();
		$verif_erreur->closeCursor();
		
		if($evite_erreur > 0){
			
			
			$verif_table = $bdd->prepare("SELECT count(*) FROM `collectivite_lot` WHERE collect_lot_id = :collect_lot_id AND date_debut_traitement IS NOT NULL");
			$verif_table->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$verif_table->execute();
			$evite_choix_table = $verif_table->fetchColumn();
			$verif_table->closeCursor();
			if($evite_choix_table > 0){
			$query_update_date = $bdd->prepare("UPDATE collectivite_lot SET date_fin_traitement = :date_fin_traitement WHERE collect_lot_id = :collect_lot_id");
			$query_update_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$query_update_date->bindParam(":date_fin_traitement", $select_date['date_fin'], PDO::PARAM_STR);
			$query_update_date->execute();
			$query_update_date->closeCursor();
			}else{
			$query_update_date = $bdd->prepare("UPDATE collectivite_lot SET date_fin_traitement = :date_fin_traitement, date_debut_traitement = :date_debut_traitement WHERE collect_lot_id = :collect_lot_id");
			$query_update_date->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$query_update_date->bindParam(":date_fin_traitement", $select_date['date_fin'], PDO::PARAM_STR);
			$query_update_date->bindParam(":date_debut_traitement", $select_date['date_deb'], PDO::PARAM_STR);
			$query_update_date->execute();
			$query_update_date->closeCursor();	
			
			}
		
		
		
		
		}
		//******//		
		
		$query_select_date_fin_deb = $bdd->prepare("SELECT date_debut_traitement, date_fin_traitement FROM collectivite_lot WHERE collect_lot_id = :collect_lot_id");
		$query_select_date_fin_deb->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_select_date_fin_deb->execute();
		$select_date_fin_deb = $query_select_date_fin_deb->fetch();
		$query_select_date_fin_deb->closeCursor();		
		if($select_date_fin_deb['date_debut_traitement'] <> '0000-00-00 00:00:00'){if($select_date_fin_deb['date_debut_traitement'] <> NULL){$debut = date("d/m/Y", strtotime($select_date_fin_deb['date_debut_traitement']));}else{$debut = '';}}else{$debut = 'x';}		
		if($select_date_fin_deb['date_fin_traitement'] <> '0000-00-00 00:00:00'){if($select_date_fin_deb['date_fin_traitement'] <> NULL){$fin = date("d/m/Y", strtotime($select_date_fin_deb['date_fin_traitement']));}else{$fin = '';}}else{$fin = 'x';}	
		
		
		$query_calcul_update = $bdd->prepare("SELECT count(*) FROM collectivite_lot_synthese WHERE collect_lot_id IN (SELECT collect_lot_id FROM collectivite_lot_synthese WHERE collect_lot_id = :collect_lot_id)");
		$query_calcul_update->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_update->execute();
		$rowcountupdate = $query_calcul_update->fetchColumn();
		$query_calcul_update->closeCursor();
	
		if ($rowcountupdate > 0){
			
			$query_calcul_statut = $bdd->prepare("SELECT count(*) FROM collectivite_lot_synthese WHERE collect_lot_id = :collect_lot_id  AND `niveau` <> 2");
			$query_calcul_statut->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
			$query_calcul_statut->execute();
			$rowcountst = $query_calcul_statut->fetchColumn();
			$query_calcul_statut->closeCursor();
			
			if ($rowcountst > 0){
				$query_update_statut = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_statut = 2 WHERE collect_lot_id = :collect_lot_id");
				$query_update_statut->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
				$query_update_statut->execute();
				$query_update_statut->closeCursor();
			}elseif ($rowcountst == 0){
				$query_verif_ligne = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut = 0 AND collect_lot_id = :collect_lot_id");
				$query_verif_ligne->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
				$query_verif_ligne->execute();
				$verif_ligne = $query_verif_ligne->fetchColumn();
				$query_verif_ligne->closeCursor();
				if($verif_ligne > 0){
					$query_update_statut = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_statut = 2 WHERE collect_lot_id = :collect_lot_id");
					$query_update_statut->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}else{
					$query_update_statut = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_statut = 1 WHERE collect_lot_id = :collect_lot_id");
					$query_update_statut->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}
			}
			
		}else{
			$query_update_statut = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_statut = 3 WHERE collect_lot_id = :collect_lot_id");
			$query_update_statut->bindParam(":collect_lot_id", $acide['collect_lot_id'], PDO::PARAM_INT);
			$query_update_statut->execute();
			$query_update_statut->closeCursor();		
		}
		
		$query_select_date_max_modif = $bdd->prepare("SELECT MAX(date_calcul) AS maxdate FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id");
		$query_select_date_max_modif->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_select_date_max_modif->execute();
		$select_date_max_modif = $query_select_date_max_modif->fetch();
		$query_select_date_max_modif->closeCursor();
		if($select_date_max_modif['maxdate'] == '0000-00-00'){$date_lot = ''.date("d-m-Y", strtotime($doc['collect_lot_date_traitement'])).'';}else{$date_lot = ''.date("d/m/Y", strtotime($select_date_max_modif['maxdate'])).'';}
		
		
		$nom_lot = ''.$doc['collect_lot_nom'].'';
		
		if ($doc['collect_lot_statut'] == 1) {
		$statut = '<span class="badge badge-shamrock">CLOTURÉ</span>';	
		}elseif ($doc['collect_lot_statut'] == 3){
		$statut = '<span class="badge badge-info">EN ATTENTE</span>';	
		}elseif ($doc['collect_lot_statut'] == 2){
		$statut = '<span class="badge badge-warning">EN PROGRESSION</span>';
		}
		
		$objectif_lot = ''.$doc['collect_lot_objectif'].' Fiches';
		
		
		$query_somme_participant = $bdd->prepare("SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = :collect_lot_id AND user_id <> 0");
		$query_somme_participant->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_somme_participant->execute();
		$participant = $query_somme_participant->fetchColumn();
		$query_somme_participant->closeCursor();				
		$query_save = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_participant = :collect_lot_participant WHERE collect_lot_id = :collect_lot_id");
		$query_save->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_save->bindParam(":collect_lot_participant", $participant, PDO::PARAM_INT);
		$query_save->execute();
		$query_save->closeCursor();
		
		
		$query_verif_temp = $bdd->prepare("SELECT count(*) FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id");
		$query_verif_temp->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_verif_temp->execute();
		$verif_temps = $query_verif_temp->fetchColumn();
		$query_verif_temp->closeCursor();
		
		if($verif_temps >0){
			
		// Solution Temporaire
		$query_time_n = $bdd->prepare("SELECT SUM(temps_sec) AS dateeN FROM collectivite_lot_synthese_details WHERE collect_lot_id = :collect_lot_id");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_n = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		$query_time_n = $bdd->prepare("SELECT SUM(collect_fiche_traitement) AS dateeNN FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND etat = 1");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_nn = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
		if($query_temps_nn['dateeNN'] == NULL){$tempnn = 0;}else{$tempnn = $query_temps_nn['dateeNN'];}
		// Temporaire
		$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec) + ".$tempn." + ".$tempnn.") AS datee FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id");
		$query_time->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		//$query_time->bindParam(":tempn", $tempn, PDO::PARAM_INT);	
		$query_time->execute();	
		$query_temps = $query_time->fetch();
		$query_time->closeCursor();
		
		}else{
			
		// Solution Temporaire
		$query_time_n = $bdd->prepare("SELECT SUM(temps_sec) AS dateeN FROM collectivite_lot_synthese_details WHERE collect_lot_id = :collect_lot_id");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_n = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
		// Temporaire
		$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(collect_fiche_traitement) + ".$tempn.") AS datee FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND etat = 1");
		$query_time->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		//$query_time->bindParam(":tempn", $tempn, PDO::PARAM_INT);	
		$query_time->execute();	
		$query_temps = $query_time->fetch();
		$query_time->closeCursor();	
			
		
		
		}
		
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		
		
		$query_ligne_lot = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche` WHERE collect_lot_id = :collect_lot_id");
		$query_ligne_lot->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_ligne_lot->execute();
		$ligne_lot = $query_ligne_lot->fetchColumn();
		$query_ligne_lot->closeCursor();
		$query_save = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_total_l = :collect_lot_total_l WHERE collect_lot_id = :collect_lot_id");
		$query_save->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_save->bindParam(":collect_lot_total_l", $ligne_lot, PDO::PARAM_INT);
		$query_save->execute();
		$query_save->closeCursor();		
		
		$query_ligne_taiter_lot = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche` WHERE collect_lot_id = :collect_lot_id AND collect_fiche_statut <> 0");
		$query_ligne_taiter_lot->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_ligne_taiter_lot->execute();
		$ligne_taiter_lot = $query_ligne_taiter_lot->fetchColumn();
		$query_ligne_taiter_lot->closeCursor();	
		$query_save = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_traite_l = :collect_lot_traite_l WHERE collect_lot_id = :collect_lot_id");
		$query_save->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_save->bindParam(":collect_lot_traite_l", $ligne_taiter_lot, PDO::PARAM_INT);
		$query_save->execute();
		$query_save->closeCursor();	
		
		if($query_temps['datee'] == '00:00:00' || $query_temps['datee'] == NULL){$jh_lot = '<strong>X</strong>';}else{
		$pieces = explode(":", $query_temps['datee']);		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);			
		$jh_lot = round($duree_decimal/8, 2);
		$query_save = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_jh = :collect_lot_jh WHERE collect_lot_id = :collect_lot_id");
		$query_save->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_save->bindParam(":collect_lot_jh", $jh_lot, PDO::PARAM_STR);
		$query_save->execute();
		$query_save->closeCursor();
		}	
				
		$functions  = '';		
		if ($doc['collect_lot_statut'] == 3) {			
        $functions .= '<a href="CollectAjout-update-' . $doc['collect_lot_id'] . '.html"><span class="badge badge-shamrock mb-3 mr-3">Modifier Fichier</span></a>';
		
		$functions .= '<a href="#" id="del" data-id="' . $doc['collect_lot_id'] . '" data-name="' . $doc['collect_lot_nom'] . '"  data-doc="' . $doc['collect_lot_doc'] . '"><span  class="badge badge-danger mb-3 mr-3">Effacer</span></a>';
		$functions .= '<a href="CollectBiblio-' . $doc['collect_lot_id'] . '"><span class="badge badge-primary mb-3 mr-3">données</span></a>';	
		$functions .= '<a href="#" id="function_edit_web" data-id="'   . $doc['collect_lot_id'] . '" data-name="Lot : ' . $doc['collect_lot_doc'] . '"><span class="badge badge-lasur mb-3 mr-3"><span class="icon iconfont iconfont-pencil"></span></span></a>';
		
		}else{
			$functions .= '<a href="CollectDetails-' . $doc['collect_lot_id'] . '"><span class="badge badge-primary mb-3 mr-3">Cumul</span></a>';
		 	$functions .= '<a href="CollectDetailsJour-' . $doc['collect_lot_id'] . '"><span class="badge badge-primary mb-3 mr-3">Journalier</span></a>';
			$functions .= '<a href="CollectBiblio-' . $doc['collect_lot_id'] . '"><span class="badge badge-primary mb-3 mr-3">données</span></a>';
			$functions .= '<a href="module/collectivite/upload/' . $doc['collect_lot_doc'] . '"><span class="badge badge-success mb-3 mr-3"><span class="icon iconfont iconfont-file-excel"></span></span></a>';
			
			if($doc['update_lot'] == 1){
			$functions .= '<a href="CollectRefresh-' . $doc['collect_lot_id'] . '" id="refreshhs"><span class="badge badge-warning">alimenter le lot</span></a>';
			}else{
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $doc['collect_lot_id'] . '" data-name="Lot : ' . $doc['collect_lot_doc'] . '"><span class="badge badge-lasur mb-3 mr-3"><span class="icon iconfont iconfont-pencil"></span></span></a>';	
			}
		}
		
		
		$query_alerte = $bdd->prepare("SELECT COUNT(collect_lot_id) AS modif FROM `collectivite_fiche_update` WHERE collect_lot_id = :collect_lot_id GROUP BY collect_fiche_id ORDER by COUNT(collect_lot_id) DESC LIMIT 0,1");
		$query_alerte->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_alerte->execute();
		$alerte = $query_alerte->fetch();
		$query_alerte->closeCursor();
			
		$functions .= '';
		
		$modif  = '';
		
		if ($alerte['modif'] < 3) {			
        	$modif .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}else{
			$modif .= '<span class="badge badge-danger mb-3 mr-3"><span class="icon iconfont iconfont-exclamation"></span></span>';
		}
		
			
		$modif .= '';
		if($ligne_taiter_lot == 0){$avancement = '<span class="badge badge-info badge-rounded mb-3 mr-3">0%</span>';}else{
		$avancementt = round(($ligne_taiter_lot/$ligne_lot)*100, 1);
		if($avancementt > 60){
		$avancement = '<span class="badge badge-shamrock badge-rounded mb-3 mr-3">'.round(($ligne_taiter_lot/$ligne_lot)*100, 1).'%</span>'; 
		}elseif($avancementt >= 20 && $avancementt <= 60){
		$avancement = '<span class="badge badge-buttercup badge-rounded mb-3 mr-3">'.round(($ligne_taiter_lot/$ligne_lot)*100, 1).'%</span>'; 
		}elseif($avancementt < 20 ){
		$avancement = '<span class="badge badge-danger badge-rounded mb-3 mr-3">'.round(($ligne_taiter_lot/$ligne_lot)*100, 1).'%</span>'; 
		}
		}
        $mysql_data[] = array(
          "nom_lot" => $nom_lot,
          "statut" => $statut,
		  "total_ligne_lot" => $ligne_lot,
		  "total_ligne_taiter_lot" => $ligne_taiter_lot,
		  "participant_lot" => $participant,
		  "debut" => $debut,
		  "fin" => $fin,
		  "somme_traitement" => $traitement,		  
		  "jh_lot" => $jh_lot,
		  "avancement" => $avancement,
		  "functions" => $functions,
		  "modif" => $modif
        );
    }
	  
    $query->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	/*}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;*/
    
} elseif ($job == 'get_doc_admin_acide'){   
} elseif ($job == 'get_collect_liste_edit'){
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
	try 
	{		
		$query_select_add = $bdd->prepare("SELECT * FROM collectivite_lot WHERE collect_lot_id = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"object"  => $traitement_edit['collect_lot_objectif'],
			"nom"  => $traitement_edit['collect_lot_nom']
			);		
		}
		
		$query_select_add->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
	}
	catch(PDOException $x) 
	{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête'; 	
	}	
	$query_del = null;
	$bdd = null;
	}
	 
} elseif ($job == 'add_doc'){ 
} elseif ($job == 'edit_collect_liste'){
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{		
			$query_select_update = $bdd->prepare("UPDATE collectivite_lot SET collect_lot_objectif = :objectif, collect_lot_nom = :nom WHERE collect_lot_id = :id");	
			$query_select_update->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_update->bindParam(":objectif", $_GET['object'], PDO::PARAM_INT);
			$query_select_update->bindParam(":nom", $_GET['nom'], PDO::PARAM_STR);
			$query_select_update->execute();
			$query_select_update->closeCursor();
			
			$result  = 'success';
			$message = 'Succès de requête';
		
		}
		catch(PDOException $x) 
		{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
		}	
		$query_del = null;
		$bdd = null;
	}
	
	
	
	
	
	
	 
} elseif ($job == 'delete_collect_liste'){  
	  
	if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{		
		$query_del = $bdd->prepare("DELETE FROM collectivite_fiche WHERE collect_lot_id = :id");	
		$query_del->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del->execute();
		$query_del->closeCursor();	
		$result  = 'success';
		$message = 'Succès de requête';
		$query_del_niveau1 = $bdd->prepare("DELETE FROM collectivite_lot WHERE collect_lot_id = :id");	
		$query_del_niveau1->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del_niveau1->execute();
		$query_del_niveau1->closeCursor();
		unlink("../../upload/".$cat);	
		}
		catch(PDOException $x) 
		{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête'; 	
		}	
		$query_del = null;
		$bdd = null;
		
	  
    }
	
}     

}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;


?>