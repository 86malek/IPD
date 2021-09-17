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
  if ($job == 'get_traitement' ||
      $job == 'get_traitement_add'   ||
      $job == 'add_traitement'   ||
      $job == 'edit_traitement'  ||
      $job == 'delete_traitement'){
		  
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
	
	if (isset($_GET['id_import'])){
      $id_import = $_GET['id_import'];
      if (!is_numeric($id_import)){
        $id_import = '';
      }
    }
	if (isset($_GET['name_user'])){
      $name_user = $_GET['name_user'];
      
    }
	if (isset($_GET['id_user'])){
      $id_user = $_GET['id_user'];
      if (!is_numeric($id_user)){
        $id_user = '';
      }
    }
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){
  
  
  
  if ($job == 'get_traitement'){
	
	try 
	{
		
			
		$PDO_query_traitement = $bdd->prepare("SELECT collectivite_fiche.collect_fiche_intervallemaj, collectivite_fiche.collect_fiche_rs1,collectivite_fiche.date_calcul, collectivite_fiche.collect_fiche_debut, collectivite_fiche.collect_fiche_fin, collectivite_fiche.collect_fiche_statut, collectivite_fiche.collect_fiche_id, collectivite_fiche.collect_fiche_idint, collectivite_lot.collect_lot_nom FROM collectivite_fiche INNER JOIN collectivite_lot ON collectivite_fiche.collect_lot_id = collectivite_lot.collect_lot_id WHERE (collectivite_fiche.collect_lot_id = :collect_lot_id AND collectivite_fiche.user_id = 0) OR (collectivite_fiche.collect_lot_id = :collect_lot_id AND collectivite_fiche.user_id = :user_id) ORDER BY collectivite_fiche.collect_fiche_intervallemaj DESC");
		$PDO_query_traitement->bindParam(":collect_lot_id", $id_import, PDO::PARAM_INT);
		$PDO_query_traitement->bindParam(":user_id", $id_user, PDO::PARAM_INT);
		
		$PDO_query_traitement->execute();
		while ($traitement = $PDO_query_traitement->fetch()){
		
			if (checkAdmin()) {
			
			$functions  = '';
			if($traitement['collect_fiche_statut'] == 2){
			$functions .= '<span class="badge badge-danger mb-3 mr-3">KO</span>';
			}elseif($traitement['collect_fiche_statut'] == 1){
			$functions .= '<span class="badge badge-success mb-3 mr-3">OK</span>';	
			}elseif($traitement['collect_fiche_statut'] == 0){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">EN ATTENTE</span>';	
			}elseif($traitement['collect_fiche_statut'] == 3){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">OK - Hors Lot</span>';	
			}elseif($traitement['collect_fiche_statut'] == 4){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">OK - SCE</span>';	
			}
			$functions .= '';
			
			}else{
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
			
			$functions  = '';			
			if($traitement['collect_fiche_statut'] == 0){
				//$fiche = '<h4>En Attente</h4>';
				$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '"><span class="badge badge-warning mb-3 mr-3">Traiter</span></a>';
			}elseif($traitement['collect_fiche_statut'] == 1){
				$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['collect_fiche_statut'] == 2){
				$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '"><span class="badge badge-danger mb-3 mr-3">KO</span></a>';	
			}elseif($traitement['collect_fiche_statut'] == 3){
				$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '"><span class="badge badge-success mb-3 mr-3">OK - Hors Lot</span></a>';	
			}elseif($traitement['collect_fiche_statut'] == 4){
				$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '"><span class="badge badge-success mb-3 mr-3">OK - SCE</span></a>';	
			}		
			$functions .= '';
			
			}else{
			$fiche = '';
			$functions  = '';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '';
			
			}
			}

				if($traitement['date_calcul'] == NULL || $traitement['date_calcul'] == 0){
					$date = '';
				}else{			
				$date = date("d-m-Y", strtotime($traitement['date_calcul']));
				}
			$mysql_data[] = array(
			  "identificateur" => $fiche,
			  "lot"  => $traitement['collect_lot_nom'],
			  "date"  => $date,
			  "maj"  => $traitement['collect_fiche_intervallemaj'],
			  "functions"     => $functions
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
    
    
  }elseif ($job == 'get_traitement_add'){
	  
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
	try 
	{		
		$query_select_add = $bdd->prepare("SELECT * FROM collectivite_fiche WHERE collect_fiche_id = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"reporting"  => $traitement_edit['collect_fiche_statut'],
			"fiche"  => $traitement_edit['collect_fiche_idint'],
			"lot"  => $traitement_edit['collect_lot_id'],
			"collect_fiche_debut"  => $traitement_edit['collect_fiche_debut']
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
	
  
  }elseif ($job == 'add_traitement'){
    


  		$query = $bdd->prepare("INSERT INTO `collectivite_fiche` (`user_name`, `user_id`, `collect_fiche_statut`, `date_calcul`, `etat`, `collect_lot_id`, `collect_fiche_idint`) VALUES (:user, :id_user, :reporting, now(), 1, :lot, :idint)");

  		$query->bindParam(":lot", $_GET['lot'], PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
		$query->bindParam(":idint", $_GET['fiche'], PDO::PARAM_INT);

		$query->execute();
		$query->closeCursor();

    	$query = $bdd->prepare("SELECT MAX(collect_fiche_id) AS MAX FROM collectivite_fiche WHERE user_id = :user_id");	
		$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
		$query->execute();
		$max_id = $query->fetch();
		$query->closeCursor();

		$fin = $_GET['fin'];
		$debut = $_GET['collect_fiche_debut'];

		$go = get_working_hours_2($debut,$fin);

		$query = $bdd->prepare("INSERT INTO `collectivite_fiche_update` (`collect_fiche_id`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `collect_lot_id`, `date_calcul`) VALUES (:fiche_id, :collect_fiche_debut, :collect_fiche_fin, :go, :id_user, :user, :collect_lot_id, now())");	

		$query->bindParam(":fiche_id", $max_id['MAX'], PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":collect_fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":collect_fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":collect_lot_id", $_GET['lot'], PDO::PARAM_INT);

		$query->execute();
		$query->closeCursor();

		$result  = 'success';
		$message = 'Succès de requête';
		
			
		$query = null;
		$bdd = null;

  
  }elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
	try 
	{	
		$fin = $_GET['collect_fiche_fin'];
		$debut = $_GET['collect_fiche_debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `collectivite_fiche_update` (`collect_fiche_id`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `collect_lot_id`, `date_calcul`) VALUES (:id, :collect_fiche_debut, :collect_fiche_fin, :go, :id_user, :user, :collect_lot_id, now())");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":collect_fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":collect_fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":collect_lot_id", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$query = $bdd->prepare("UPDATE collectivite_fiche SET user_name = :user , user_id = :id_user, collect_fiche_statut = :reporting, date_calcul = now(), etat = 1 WHERE collect_fiche_id = :id");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
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
	$query_del = null;
	$bdd = null;
	}
    
  }elseif ($job == 'delete_qld'){ 
  
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