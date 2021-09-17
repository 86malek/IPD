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
  
  if ($job == 'get_cat_fichier' ||
      $job == 'get_cat_fichier_add' ||
      $job == 'add_cat_fichier' ||
      $job == 'edit_cat_fichier' ||
      $job == 'delete_cat_fichier' ||
	  $job == 'get_gestion_traitement_lk_obj' ||
      $job == 'delete_gestion_traitement_lk_obj' ||
	  $job == 'edit_gestion_traitement_lk_obj' ||
	  $job == 'add_gestion_traitement_lk_obj'   ||
	  $job == 'get_gestion_traitement_add_lk_obj'){
		  
		if (isset($_GET['id'])){
			$id = $_GET['id'];
			if (!is_numeric($id)){
			$id = '';
			}
    	}
		
		if (isset($_GET['cat'])){
			$cat = $_GET['cat'];}
			
		if (isset($_GET['intervalle'])){
		  $date = $_GET['intervalle'];
		}
		
  }else{$job = '';}
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_cat_fichier'){
	  
    /*try 
	{*/
	$query_global = $bdd->prepare("SELECT * FROM `data_cat_ie` ORDER BY `id_cat_ie` DESC");
	$query_global->execute();
	
	while ($doc = $query_global->fetch()){
		
		
			
		$query = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = :id_cat_ie");
		$query->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query->execute();
		$select_date = $query->fetch();
		$query->closeCursor();
					
			if($select_date['date_fin'] <> NULL){$fin = date("d-m-Y", strtotime($select_date['date_fin']));}else{$fin = '';}
			if($select_date['date_debut'] <> NULL){$debut = date("d-m-Y", strtotime($select_date['date_debut']));}else{$debut = '';}	
		
		
		$query_somme_total_ligne = $bdd->prepare("SELECT count(*) FROM `data_ie` WHERE id_cat_ie = :id_cat_ie");
		$query_somme_total_ligne->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query_somme_total_ligne->execute();
		$somme_total = $query_somme_total_ligne->fetchColumn();
		$query_somme_total_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT count(*) FROM `data_ie` WHERE id_cat_ie = :id_cat_ie AND reporting <> 0");
		$query_somme_traite_ligne->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_participant = $bdd->prepare("SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = :id_cat_ie AND user_id <> 0");
		$query_somme_participant->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query_somme_participant->execute();
		$somme_collab = $query_somme_participant->fetchColumn();
		$query_somme_participant->closeCursor();
						
		$query_save = $bdd->prepare("UPDATE data_cat_ie SET intervenant_cat_ie = :intervenant_cat_ie WHERE id_cat_ie = :id_cat_ie");
		$query_save->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query_save->bindParam(":intervenant_cat_ie", $somme_collab, PDO::PARAM_INT);
		$query_save->execute();
		$query_save->closeCursor();
		
		
			
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = :id_cat_ie");
		$query->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		if($query_temps['datee'] <> NULL){	
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		$pieces = explode(":", $query_temps['datee']);		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);		
		$jh = round($duree_decimal/8, 2);
		
		$somme_traite_pourcentage_couleur = round(($somme_traite/$somme_total)*100);
		
			if($somme_traite_pourcentage_couleur > 50 && $somme_traite_pourcentage_couleur < 100){
			$somme_traite_pourcentage = '<span class="badge badge-info badge-rounded mb-3 mr-3">'.round(($somme_traite/$somme_total)*100).'%</span>';
			}elseif($somme_traite_pourcentage_couleur < 50 && $somme_traite_pourcentage_couleur > 0){
			$somme_traite_pourcentage = '<span class="badge badge-buttercup badge-rounded mb-3 mr-3">'.round(($somme_traite/$somme_total)*100).'%</span>';
			}elseif($somme_traite_pourcentage_couleur == 100){
			$somme_traite_pourcentage = '<span class="badge badge-success badge-rounded mb-3 mr-3">'.round(($somme_traite/$somme_total)*100).'%</span>';
			}
		}else{$traitement = '';		
		$jh = '';
		$somme_traite_pourcentage_couleur = '<span class="badge badge-danger badge-rounded mb-3 mr-3">0%</span>';}		
		
		
		
		
		$query_calcul_update = $bdd->prepare("SELECT count(*) FROM data_cat_synthese_ie WHERE id_cat_ie IN (SELECT id_cat_ie FROM data_cat_synthese_ie WHERE id_cat_ie = :id_cat_ie)");
		$query_calcul_update->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
		$query_calcul_update->execute();
		$rowcountupdate = $query_calcul_update->fetchColumn();
		$query_calcul_update->closeCursor();
	
		if ($rowcountupdate > 0){
			
			$query_calcul_statut = $bdd->prepare("SELECT count(*) FROM data_cat_synthese_ie WHERE id_cat_ie = :id_cat_ie  AND niveau <> 2");
			$query_calcul_statut->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
			$query_calcul_statut->execute();
			$rowcountst = $query_calcul_statut->fetchColumn();
			$query_calcul_statut->closeCursor();
			
			if ($rowcountst > 0){
				$query_update_statut = $bdd->prepare("UPDATE data_cat_ie SET statut_fichier_cat_ie = 2 WHERE id_cat_ie = :id_cat_ie");
				$query_update_statut->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
				$query_update_statut->execute();
				$query_update_statut->closeCursor();
			}elseif ($rowcountst == 0){
				$query_verif_ligne = $bdd->prepare("SELECT count(*) FROM data_ie WHERE reporting = 0 AND id_cat_ie = :id_cat_ie");
				$query_verif_ligne->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
				$query_verif_ligne->execute();
				$verif_ligne = $query_verif_ligne->fetchColumn();
				$query_verif_ligne->closeCursor();
				if($verif_ligne > 0){
					$query_update_statut = $bdd->prepare("UPDATE data_cat_ie SET statut_fichier_cat_ie = 2 WHERE id_cat_ie = :id_cat_ie");
					$query_update_statut->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}else{
					$query_update_statut = $bdd->prepare("UPDATE data_cat_ie SET statut_fichier_cat_ie = 1 WHERE id_cat_ie = :id_cat_ie");
					$query_update_statut->bindParam(":id_cat_ie", $doc['id_cat_ie'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}
			}
			
		}else{
			$query_update_statut = $bdd->prepare("UPDATE data_cat_ie SET statut_fichier_cat_ie = 3 WHERE id_cat_ie = :id_cat_ie");
			$query_update_statut->bindParam(":id_cat_ie", $acide['id_cat_ie'], PDO::PARAM_INT);
			$query_update_statut->execute();
			$query_update_statut->closeCursor();		
		}
		
		if ($doc['statut_fichier_cat_ie'] == 1) {
		$statut = '<span class="badge badge-success">CLOTURÉ</span>';	
		}elseif ($doc['statut_fichier_cat_ie'] == 3){
		$statut = '<span class="badge badge-danger">EN ATTENTE</span>';	
		}elseif ($doc['statut_fichier_cat_ie'] == 2){
		$statut = '<span class="badge badge-warning">EN PROGRESSION</span>';
		}
		
		$functions  = '';				
		if ($doc['statut_fichier_cat_ie'] == 3) {	
		$functions .= '<a href="IEAjout-update-' . $doc['id_cat_ie'] . '.html"><span class="badge badge-shamrock mb-3 mr-3">Modifier fichier</span></a>';
		$functions .= '<a href="#" id="del" data-id="' . $doc['id_cat_ie'] . '" data-name="' . $doc['nom_cat_ie'] . '"  data-doc="' . $doc['fichier_cat_ie'] . '"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
		$functions .= '<a target="_blank" href="module/ie/upload/' . $doc['fichier_cat_ie'] . '"><span class="badge badge-success mb-3 mr-3"><span class="icon iconfont iconfont-file-excel"></span></span></a>';
		$functions .= '<a href="IEBiblio-' . $doc['id_cat_ie'] . '"><span class="badge badge-primary mb-3 mr-3">Données</span></a>';
		$functions .= '<a href="#" id="function_edit_cat_fichier" data-id="'   . $doc['id_cat_ie'] . '" data-name="Lot : ' . $doc['nom_cat_ie'] . '"><span class="badge badge-lasur"><span class="icon iconfont iconfont-pencil"></span></span></a>';	
		}else{
			$functions .= '<a href="IEBiblioDetails-' . $doc['id_cat_ie'] . '"><span class="badge badge-primary mb-3 mr-3">Cumul</span></a>';
			$functions .= '<a href="IEBiblioDetailsJour-' . $doc['id_cat_ie'] . '"><span class="badge badge-primary mb-3 mr-3">Journalier</span></a>';
			$functions .= '<a href="IEBiblio-' . $doc['id_cat_ie'] . '"><span class="badge badge-primary mb-3 mr-3">Données</span></a>';
			$functions .= '<a target="_blank" href="module/ie/upload/' . $doc['fichier_cat_ie'] . '"><span class="badge badge-success mb-3 mr-3"><span class="icon iconfont iconfont-file-excel"></span></span></a>';
			$functions .= '<a href="#" id="function_edit_cat_fichier" data-id="'   . $doc['id_cat_ie'] . '" data-name="Lot : ' . $doc['nom_cat_ie'] . '"><span class="badge badge-lasur"><span class="icon iconfont iconfont-pencil"></span></span></a>';
		}		
		$functions .= '';		
		
		$somme_non_traite = $somme_total - $somme_traite;		
		
		$fichier = ''.$doc['nom_cat_ie'].'';	
		
		$mysql_data[] = array(
		
		  "statut"  => $statut,
		  "total"  => $somme_total,
		  "traite"  => $somme_traite,
		  "pourcent"  => $somme_traite_pourcentage_couleur,
		  "fichier"  => $fichier,
		  "collab" => $somme_collab,
		  "fin"  => $fin,
		  "debut"  => $debut,
		  "temps"  => $traitement,
		  "jh"  => $jh,
          "functions"     => $functions
        );
		
	}
	  
    $query_global->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	/*}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;   */
    
  } elseif ($job == 'get_cat_fichier_add'){
    
   	if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
		$query = $bdd->prepare("SELECT * FROM data_cat_ie WHERE id_cat_ie = :id_cat_ie");
		$query->bindParam(":id_cat_ie", $id, PDO::PARAM_INT);
		$query->execute();
		$doc = $query->fetch();		
		$query->closeCursor();
		$mysql_data[] = array("nom"  => $doc['nom_cat_ie']);
      	$result  = 'success';
		$message = 'Succès de requête';
    }
  
  }elseif ($job == 'add_cat_fichier'){ 
  
  }elseif ($job == 'edit_cat_fichier'){    
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {		
		$query = $bdd->prepare("UPDATE data_cat_ie SET nom_cat_ie = :nom_cat_ie WHERE id_cat_ie = :id_cat_ie");
		$query->bindParam(":id_cat_ie", $id, PDO::PARAM_INT);
		$query->bindParam(":nom_cat_ie", $_GET['nom'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';
    }    
  }elseif ($job == 'get_gestion_traitement_lk_obj'){

	try
	{
	$PDO_query_traitement = $bdd->prepare("SELECT * FROM `data_cat_synthese_fiche_obj_ie` ORDER BY id_objectif ASC");
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
			$ligne = $traitement['nbligne_objectif'];
			$heure = $traitement['nbheure_objectif'];
			
			$date_debut = date_change_format($traitement['debut_objectf'],'Y-m-d','d/m/Y');
			$date_fin = date_change_format($traitement['fin_objectif'],'Y-m-d','d/m/Y');
			
			$datenow = date("Y-m-d");
			if($datenow <= $traitement['fin_objectif'] && $datenow >= $traitement['debut_objectf']){$actif = '<span class="badge-circle badge-circle-success mr-3">Actif</span>';}else{$actif = '<span class="badge-circle badge-circle-danger mr-3">Non actif</span>';}
			
			
			$functions  = '';
			$functions .= '<a  href="#" id="function_edit_obj" data-id="'   . $traitement['id_objectif'] . '" data-name="OBJECTIF"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';			
			$functions .= '<a href="#" id="del_obj" data-id="' . $traitement['id_objectif'] . '" data-name="OBJECTIF"  data-doc="OBJECTIF"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
			$functions .= '';	
			  
			$mysql_data[] = array(		
			  "ligne" => $ligne,
			  "heure" => $heure,
			  "date_debut" => $date_debut,
			  "date_fin" => $date_fin,
			  "actif" => $actif,
			  "functions" => $functions
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
	    
  
  }elseif ($job == 'get_gestion_traitement_add_lk_obj'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try
		{
		$query = $bdd->prepare("SELECT * FROM data_cat_synthese_fiche_obj_ie WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		while ($traitement_edit = $query->fetch()){
			
			$intervalle = $traitement_edit['debut_objectf'].' - '.$traitement_edit['fin_objectif'];
			$mysql_data[] = array(
			"heure"  => $traitement_edit['nbheure_objectif'],
			"fiche"  => $traitement_edit['nbligne_objectif'],
			"intervalle"  => $intervalle
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
  
}elseif ($job == 'add_gestion_traitement_lk_obj'){
    
		try
		{
		$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("INSERT INTO data_cat_synthese_fiche_obj_ie (nbligne_objectif, nbheure_objectif, debut_objectf, fin_objectif) VALUES (:nbligne_objectif, :nbheure_objectif, :debut_objectf, :fin_objectif)");
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_INT);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_INT);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
		$query->execute();
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
  
  }elseif ($job == 'edit_gestion_traitement_lk_obj'){
	  
	  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
   		try
		{
			$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("UPDATE data_cat_synthese_fiche_obj_ie SET nbligne_objectif = :nbligne_objectif, nbheure_objectif = :nbheure_objectif, debut_objectf = :debut_objectf, fin_objectif = :fin_objectif WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		
		
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_STR);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_STR);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
		$query->execute();
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
    
  }elseif ($job == 'delete_gestion_traitement_lk_obj'){
	  
  	if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
    	try
		{
		$query = $bdd->prepare("DELETE FROM data_cat_synthese_fiche_obj_ie WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
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
    
  
  }elseif ($job == 'delete_cat_fichier'){
  	
	if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{		
		$query_del = $bdd->prepare("DELETE FROM data_ie WHERE id_cat_ie = :id");	
		$query_del->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del->execute();
		$query_del->closeCursor();	
		$result  = 'success';
		$message = 'Succès de requête';
		$query_del_niveau1 = $bdd->prepare("DELETE FROM data_cat_ie WHERE id_cat_ie = :id");	
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