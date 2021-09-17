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

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_collectivite_detail'){
		  
		if (isset($_GET['id'])){
		  $id = $_GET['id'];
		  if (!is_numeric($id)){
			$id = '';
		  }
		}
		
		if (isset($_GET['id_stat'])){
		  $id_stat = $_GET['id_stat'];
		  if (!is_numeric($id_stat)){
			$id_stat = '';
		  }
		}
		
		
  } else {
    $job = '';
  }
}

$mysql_data = array();
  
if($job == 'get_collectivite_detail'){
    
    try 
	{
		
		$query = $bdd->prepare("
		
		
		SELECT collectivite_lot_synthese.collect_lot_synthese_id, collectivite_lot_synthese.collect_lot_synthese_intervenant, collectivite_lot_synthese.collect_lot_synthese_statut, collectivite_lot.collect_lot_nom, collectivite_lot_synthese.collect_lot_id, collectivite_lot_synthese.date_debut_traitement, collectivite_lot_synthese.date_fin_traitement, collectivite_lot_synthese.collect_lot_synthese_id_intervenant FROM collectivite_lot_synthese INNER JOIN collectivite_lot ON collectivite_lot.collect_lot_id = collectivite_lot_synthese.collect_lot_id WHERE collectivite_lot_synthese.collect_lot_id = :collect_lot_id GROUP BY collectivite_lot_synthese.collect_lot_synthese_id_intervenant ORDER BY collect_lot_synthese_id DESC
		
		");
		$query->bindParam(":collect_lot_id", $id_stat, PDO::PARAM_INT);
		
		$query->execute();
		
		while ($doc = $query->fetch()){
		
		$query_verif_temp = $bdd->prepare("SELECT count(*) FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id");
		$query_verif_temp->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_verif_temp->execute();
		$verif_temps = $query_verif_temp->fetchColumn();
		$query_verif_temp->closeCursor();
		
		if($verif_temps >0){
		
		//Temporaire
		
		$query_time_n = $bdd->prepare("SELECT SUM(temps_sec) AS dateeN FROM collectivite_lot_synthese_details WHERE collect_lot_id = :collect_lot_id AND collect_lot_synthese_details_id_intervenant = :user_id");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_time_n->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_n = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		$query_time_n = $bdd->prepare("SELECT SUM(collect_fiche_traitement) AS dateeNN FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND etat = 1");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_time_n->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_nn = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
		if($query_temps_nn['dateeNN'] == NULL){$tempnn = 0;}else{$tempnn = $query_temps_nn['dateeNN'];}
		
		$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec) + ".$tempn." + ".$tempnn.") AS datee FROM collectivite_fiche_update WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id");
		$query_time->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);	
		$query_time->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_time->execute();	
		$query_temps = $query_time->fetch();
		$query_time->closeCursor();
		
		}else{
			
		//Temporaire
		
		$query_time_n = $bdd->prepare("SELECT SUM(temps_sec) AS dateeN FROM collectivite_lot_synthese_details WHERE collect_lot_id = :collect_lot_id AND collect_lot_synthese_details_id_intervenant = :user_id");
		$query_time_n->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_time_n->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);	
		$query_time_n->execute();	
		$query_temps_n = $query_time_n->fetch();
		$query_time_n->closeCursor();
		
		if($query_temps_n['dateeN'] == NULL){$tempn = 0;}else{$tempn = $query_temps_n['dateeN'];}
		
		$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(collect_fiche_traitement) + ".$tempn.") AS datee FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND etat = 1");
		$query_time->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_time->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);	
		$query_time->execute();	
		$query_temps = $query_time->fetch();
		$query_time->closeCursor();	
			
			}
		
		
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
	
		
		$query_calcul_ligne = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id");
		$query_calcul_ligne->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_calcul_ligne->execute();
		$rowligne = $query_calcul_ligne->fetchColumn();
		$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
		$query_calcul_ligne->closeCursor();
		
	
		if ($doc['collect_lot_synthese_statut'] == 1){
		$statut = '<span class="badge badge-success">CLOTURÉ</span>';}
		elseif($doc['collect_lot_synthese_statut'] == 2){$statut = '<span class="badge badge-info">EN PROGRESSION</span>';}
		else{$statut = '<span class="badge badge-warning">EN ATTENTE</span>';}
		
				
		
		$query_calcul_ligne_OK = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND 	user_id = :user_id AND collect_fiche_statut = 1");
		$query_calcul_ligne_OK->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_calcul_ligne_OK->execute();
		$rowligne_ok = $query_calcul_ligne_OK->fetchColumn();
		$query_calcul_ligne_OK->closeCursor();
		
		$query_calcul_ligne_KO = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND 	user_id = :user_id AND collect_fiche_statut = 2");
		$query_calcul_ligne_KO->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_KO->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_calcul_ligne_KO->execute();
		$rowligne_ko = $query_calcul_ligne_KO->fetchColumn();
		$query_calcul_ligne_KO->closeCursor();
		
		$query_calcul_ligne_OK_h = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 3");
		$query_calcul_ligne_OK_h->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_h->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_h->execute();
		$rowligne_ok_h = $query_calcul_ligne_OK_h->fetchColumn();
		$query_calcul_ligne_OK_h->closeCursor();
		
		$query_calcul_ligne_OK_s = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = :collect_lot_id AND user_id = :user_id AND collect_fiche_statut = 4");
		$query_calcul_ligne_OK_s->bindParam(":collect_lot_id", $doc['collect_lot_id'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_s->bindParam(":user_id", $doc['collect_lot_synthese_id_intervenant'], PDO::PARAM_INT);
		$query_calcul_ligne_OK_s->execute();
		$rowligne_ok_s = $query_calcul_ligne_OK_s->fetchColumn();
		$query_calcul_ligne_OK_s->closeCursor();	
			
		
		if (empty($doc['collect_lot_nom'])) {
		$fichier = '<span class="badge badge-outline-danger mb-3 mr-3">Aucun fichier</span>';
		}else{
		$fichier = '<span class="badge badge-sm badge-outline-primary mb-3 mr-3">'.$doc['collect_lot_nom'].'</span>';	
		}		
		
        $mysql_data[] = array(
          "nom" => $fichier,
		  "collab"  => $doc['collect_lot_synthese_intervenant'],
		  "statut"  => $statut,
		  "temps"  => $traitement,
          "ligne"     => $rowligne_style,
		  "ok"     => $rowligne_ok,
		  "ko"     => $rowligne_ko,
		  "okh"     => $rowligne_ok_h,
		  "kos"     => $rowligne_ok_s
		  );	
			
		}
		$query->closeCursor();
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
	$query = null;
	
}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>