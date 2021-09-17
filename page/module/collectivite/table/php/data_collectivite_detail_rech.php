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

if(!checkAdmin()) {
die("Secured");
}

$job = '';
$id  = '';
$id_stat = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'table_collect_detail_rech'){
		  
		if (isset($_GET['date'])){
		  $date = $_GET['date'];
		}
		
		if (isset($_GET['lot'])){
		  $lot = $_GET['lot'];
		  if (!is_numeric($lot)){
			$lot = '';
		  }
		}
		
		if (isset($_GET['collab'])){
		  $collab = $_GET['collab'];
		  if (!is_numeric($collab)){
			$collab = '';
		  }
		}

  } else {
    $job = '';
  }
}



$mysql_data = array();

if($job == 'table_collect_detail_rech'){
	
	try 
	{
	$debut = substr($date, 0,10);
	$fin = substr($date, 13,22);
	
	if($collab == ''){$requete_collab = ':user_id = :user_id';}else{$requete_collab = 'collectivite_fiche.user_id = :user_id';}
	if($lot == ''){$requete_lot = ':collect_lot_id = :collect_lot_id AND';}else{$requete_lot = 'collectivite_fiche.collect_lot_id = :collect_lot_id AND';}
	$PDO_query_traitement = $bdd->prepare("SELECT collectivite_fiche.date_calcul, collectivite_fiche.collect_lot_id, collectivite_fiche.user_id, collectivite_fiche.user_name, collectivite_lot.collect_lot_nom, collectivite_lot.collect_lot_objectif FROM collectivite_fiche INNER JOIN collectivite_lot ON collectivite_lot.collect_lot_id = collectivite_fiche.collect_lot_id WHERE ".$requete_lot." ".$requete_collab." AND collectivite_fiche.date_calcul between :debut and :fin AND collectivite_fiche.date_calcul <> '00:00:00' GROUP BY collectivite_fiche.date_calcul, collectivite_fiche.user_id, collectivite_fiche.collect_lot_id");
	$PDO_query_traitement->bindParam(":collect_lot_id", $lot, PDO::PARAM_INT);
	$PDO_query_traitement->bindParam(":user_id", $collab, PDO::PARAM_INT);
	$PDO_query_traitement->bindParam(":debut", $debut, PDO::PARAM_STR);
	$PDO_query_traitement->bindParam(":fin", $fin, PDO::PARAM_STR);
	
	$PDO_query_traitement->execute();
	
	while ($doc = $PDO_query_traitement->fetch()){			
		  
		$query_calcul_ligne_OK = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND etat = 1 AND date_calcul = :date_calcul");
		$query_calcul_ligne_OK->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_calcul_ligne_OK->execute();
		$rowligne = $query_calcul_ligne_OK->fetchColumn();
		$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
		$query_calcul_ligne_OK->closeCursor();
		
		
		$query_verif_temp = $bdd->prepare("SELECT count(*) FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id AND date_calcul = :date_calcul");
		$query_verif_temp->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_verif_temp->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_verif_temp->execute();
		$verif_tempss = $query_verif_temp->fetchColumn();
		$query_verif_temp->closeCursor();
		
		if($verif_tempss > 0){
		
		// Solution Temporaire
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND date_calcul = :date_calcul");
		$query->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		
		}else{
			
		$query_time_n = $bdd->prepare("SELECT SUM(collectivite_lot_synthese_details.temps_sec) AS dateeN FROM collectivite_lot_synthese_details INNER JOIN collectivite_lot ON collectivite_lot.collect_lot_id = collectivite_lot_synthese_details.collect_lot_id WHERE collectivite_lot_synthese_details.collect_lot_id = :collect_lot_id AND collectivite_lot_synthese_details.collect_lot_synthese_details_id_intervenant = :user_id AND  collectivite_lot_synthese_details.actif = 1 AND collectivite_lot_synthese_details.date_calcul = :date_calcul");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_time_n->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_time_n->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);	
		$query_time_n->execute();	
		$query_temps_n = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
		
		// Solution Temporaire
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(collect_fiche_traitement) + ".$tempn.") AS datee FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND etat = 1 AND date_calcul = :date_calcul");
		$query->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		}
		
		
		
		$query_calcul_ligne_OK = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 1 AND etat = 1 AND date_calcul = :date_calcul");
		$query_calcul_ligne_OK->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_calcul_ligne_OK->execute();
		$rowligne_ok = $query_calcul_ligne_OK->fetchColumn();
		$query_calcul_ligne_OK->closeCursor();
		
		$query_calcul_ligne_KO = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 2 AND etat = 1 AND date_calcul = :date_calcul");
		$query_calcul_ligne_KO->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_KO->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_calcul_ligne_KO->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_calcul_ligne_KO->execute();
		$rowligne_ko = $query_calcul_ligne_KO->fetchColumn();
		$query_calcul_ligne_KO->closeCursor();
		
		$query_calcul_ligne_OK_h = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 3 AND etat = 1 AND date_calcul = :date_calcul");
		$query_calcul_ligne_OK_h->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_h->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_h->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_calcul_ligne_OK_h->execute();
		$rowligne_ok_h = $query_calcul_ligne_OK_h->fetchColumn();
		$query_calcul_ligne_OK_h->closeCursor();
		
		$query_calcul_ligne_OK_s = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 4 AND etat = 1 AND date_calcul = :date_calcul");
		$query_calcul_ligne_OK_s->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_s->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_s->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_calcul_ligne_OK_s->execute();
		$rowligne_ko_s = $query_calcul_ligne_OK_s->fetchColumn();
		$query_calcul_ligne_OK_s->closeCursor();
				
		if($rowligne > 0){
		
		$query_ligne_taiter_lot = $bdd->prepare("SELECT count(*) FROM `collectivite_fiche` WHERE collect_lot_id = :collect_lot_id AND collect_fiche_statut <> 0 AND user_id = :user_id AND date_calcul = :date_calcul");
		$query_ligne_taiter_lot->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_ligne_taiter_lot->bindParam(":user_id", $doc['user_id'], PDO::PARAM_INT);
		$query_ligne_taiter_lot->bindParam(":date_calcul", $doc['date_calcul'], PDO::PARAM_STR);
		$query_ligne_taiter_lot->execute();
		$ligne_taiter_lot = $query_ligne_taiter_lot->fetchColumn();
		$query_ligne_taiter_lot->closeCursor();
		
		$ecart_final = round($ligne_taiter_lot/$doc['collect_lot_objectif']*100,2).'%';
		
		}else{$ecart_final = '<center><strong>En cours de calcul</strong></center>';$ecart_final = '<center><strong>En attente de calcul</strong></center>';}
		
		
		if (empty($doc['collect_lot_nom'])) {
		$fichier = '<span class="badge badge-outline-danger mb-3 mr-3">Aucun fichier</span>';
		}else{
		$fichier = '<span class="badge badge-sm badge-outline-primary mb-3 mr-3">'.$doc['collect_lot_nom'].'</span>';	
		}	
		
        $mysql_data[] = array(
          "nom"          => $fichier,
		  "collab"  => $doc['user_name'],
		  "temps"  => $traitement,
          "ligne"     => $rowligne_style,
		  "ok"     => $rowligne_ok,
		  "ko"     => $rowligne_ko,
		  "kos"     => $rowligne_ko_s,
		  "okh"     => $rowligne_ok_h,
		  "ecart"     => $ecart_final,
		  "date"     => $doc['date_calcul']
        );
		}
	$PDO_query_traitement->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';
	
	}
    catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;
	$PDO_query_traitement = null;
    
} 



$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>