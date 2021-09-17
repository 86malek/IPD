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
		
				
		$PDO_query_traitement = $bdd->prepare("SELECT collectivite_fiche.collect_fiche_rs1, collectivite_fiche.collect_fiche_intervallemaj, collectivite_fiche.collect_fiche_statut, collectivite_fiche.collect_fiche_id, collectivite_fiche.collect_fiche_idint, collectivite_lot.collect_lot_nom, collectivite_fiche.user_name, collectivite_fiche.user_id FROM collectivite_fiche INNER JOIN collectivite_lot ON collectivite_fiche.collect_lot_id = collectivite_lot.collect_lot_id WHERE collectivite_fiche.collect_lot_id = :collect_lot_id");
		$PDO_query_traitement->bindParam(":collect_lot_id", $id_import, PDO::PARAM_INT);		
		
		
		$PDO_query_traitement->execute();
		while ($traitement = $PDO_query_traitement->fetch()){
		
			
			$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(collectivite_fiche_update.temps_sec)) AS traitement FROM collectivite_fiche_update INNER JOIN collectivite_fiche ON collectivite_fiche.collect_fiche_id = collectivite_fiche_update.collect_fiche_id WHERE collectivite_fiche_update.collect_fiche_id = :id");	
			$query->bindParam(":id", $traitement['collect_fiche_id'], PDO::PARAM_INT);
			$query->execute();			
			$query_sum = $query->fetch();						
			$query->closeCursor();
			
			
			//<a href="#" id="function_edit_web" data-id="'   . $traitement['collect_fiche_id'] . '" data-name="Numéro : ' . $traitement['collect_fiche_id'] . '">
			
			$functions  = '';			
			if($traitement['collect_fiche_statut'] == 0){
			$functions .= '<span class="badge badge-warning">à Traiter</span>';
			}elseif($traitement['collect_fiche_statut'] == 1){
			$functions .= '<span class="badge badge-success">OK</span>';	
			}elseif($traitement['collect_fiche_statut'] == 2){
			$functions .= '<span class="badge badge-bittersweet">KO</span>';	
			}elseif($traitement['collect_fiche_statut'] == 3){
			$functions .= '<span class="badge badge-shamrock">OK - HORS LOT</span>';	
			}elseif($traitement['collect_fiche_statut'] == 4){
			$functions .= '<span class="badge badge-success">OK - SCE</span>';	
			}
			
			$functions .= '';
			
			$mood  = '';
			$query = $bdd->prepare("SELECT COUNT(*) FROM collectivite_fiche_update WHERE collect_fiche_id = :collect_fiche_id");
			$query->bindParam(":collect_fiche_id", $traitement['collect_fiche_id'], PDO::PARAM_INT);
			$query->execute();
			$alerte_modif = $query->fetchColumn();
			$query->closeCursor();
			
			if($alerte_modif == 1){
			$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['collect_fiche_id'].'">Une seule Modification</a></span>';
			}elseif($alerte_modif == 2){
			$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['collect_fiche_id'].'">Deux Modifications</a></span>';
			}elseif($alerte_modif > 2){
			$mood .= '<span class="btn btn-danger"><span class="iconfont iconfont-bell"></span>&nbsp;&nbsp;<a id="mood_affichage" data-id="'.$traitement['collect_fiche_id'].'"><b>Alerte modification - ('.$alerte_modif.')</b></a>&nbsp;&nbsp;<span class="iconfont iconfont-bell"></span></span>';
			}
			$mood .= '';
			
			$fiche = '<h4>'.$traitement['collect_fiche_idint'].'</h4>';
			$temps = '<h4>'.$query_sum['traitement'].'</h4>';
			
			$mysql_data[] = array(
				"collab" => $traitement['user_name'],
				"maj" => $traitement['collect_fiche_intervallemaj'],
				"temps" => $temps,
			  	"identificateur" => $fiche,
			  	"lot"  => $traitement['collect_lot_nom'],
			  	"functions"     => $functions,
				"mood"     => $mood
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
			"reporting"  => $traitement_edit['collect_fiche_statut']
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
    
    
  
  }elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
	try 
	{		
		$query_select_update = $bdd->prepare("UPDATE collectivite_fiche SET collect_fiche_statut = :reporting, collect_fiche_fin = now(), etat = 1 WHERE collect_fiche_id = :id");	
		$query_select_update->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_update->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
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