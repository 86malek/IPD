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
$id_import = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if (	$job == 'get_webs_integration' ||
		$job == 'get_webs_integration_admin' ||
		$job == 'get_add_webs_integration'   ||
		$job == 'add_webs_integration'   ||
		$job == 'edit_webs_integration'  ||
		$job == 'delete_webs_integration'){
		  
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
		
		if (isset($_GET['mode'])){
		  $mode_import = $_GET['mode'];	  
		}
		$val_0 = 0;
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_webs_integration_admin'){
	 
	try 
	{ 
	if(empty($id_import)){$PDO_query_webs = $bdd->prepare("SELECT * FROM webmaster_integration ORDER BY id_rapport DESC");}
	else
	{$PDO_query_webs = $bdd->prepare("SELECT * FROM webmaster_integration WHERE user_id = :user_id ORDER BY id_rapport DESC");
	$PDO_query_webs->bindParam(":user_id", $id_import, PDO::PARAM_INT);}
	
		
	$PDO_query_webs->execute();
	while ($traitement = $PDO_query_webs->fetch()){
		
		$statut  = '';		
		if($traitement['type_rapport'] == 1){
        $statut .= 'Leads';
		}elseif($traitement['type_rapport'] == 2){
		$statut .= 'Personnalisé';	
		}elseif($traitement['type_rapport'] == 3){
		$statut .= 'Flash';	
		}elseif($traitement['type_rapport'] == 4){
		$statut .= 'Ré-inégration';	
		}elseif($traitement['type_rapport'] == 5){
		$statut .= 'Création';	
		}elseif($traitement['type_rapport'] == 6){
		$statut .= 'CRÉATION + LEADS';
		}elseif($traitement['type_rapport'] == 7){
		$statut .= 'INÉGRATION';
		}
        $statut .= '';
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM webmaster_integration WHERE id_rapport = :id_rapport");
		$query->bindParam(":id_rapport", $traitement['id_rapport'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['date_insertion'])).'</span>';
		
		
		
		
		$date_debut = ''.date("d/m/Y H:i:s", strtotime($traitement['date_debut_traitement'])).'';
		$date_fin = ''.date("d/m/Y H:i:s", strtotime($traitement['date_fin_traitement'])).'';
			
		$date_sort = ''.date("d/m/Y", strtotime($traitement['date_debut_traitement'])).'';
		 
		
        $mysql_data[] = array(
		"id"  => $date_sort,	
			"collab"  => $traitement['user_name'],
		"campagne"  => $traitement['campagne_rapport'],
		  "type"  => $statut,
		  "debut" => $date_debut,
		  "fin" => $date_fin,
		  "temps" => $query_temps['datee'],
		  "comm" => $traitement['commentaire_rapport']
        );
	}
	$PDO_query_webs->closeCursor();
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
	$PDO_query_webs = null;     
    
  }elseif ($job == 'get_add_webs_integration'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM webmaster_integration WHERE id_rapport = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"type"  => $traitement_edit['type_rapport'],
			"campagne"  => $traitement_edit['campagne_rapport'],
			"debut"  => $traitement_edit['date_debut_traitement'],
			"fin"  => $traitement_edit['date_fin_traitement'],
			"comm"  => $traitement_edit['commentaire_rapport']
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
  
  }elseif ($job == 'get_webs_integration'){
	 
	try 
	{ 
	
	$PDO_query_webs = $bdd->prepare("SELECT * FROM webmaster_integration WHERE user_id = :user_id ORDER BY id_rapport ASC");
	$PDO_query_webs->bindParam(":user_id", $id_import, PDO::PARAM_INT);	
	$PDO_query_webs->execute();
	while ($traitement = $PDO_query_webs->fetch()){
		
		$statut  = '';		
		if($traitement['type_rapport'] == 1){
        $statut .= 'Leads';
		}elseif($traitement['type_rapport'] == 2){
		$statut .= 'Personnalisé';	
		}elseif($traitement['type_rapport'] == 3){
		$statut .= 'Flash';	
		}elseif($traitement['type_rapport'] == 4){
		$statut .= 'Ré-inégration';	
		}elseif($traitement['type_rapport'] == 5){
		$statut .= 'Création';	
		}elseif($traitement['type_rapport'] == 6){
		$statut .= 'CRÉATION + LEADS';
		}elseif($traitement['type_rapport'] == 7){
		$statut .= 'INÉGRATION';
		}
        $statut .= '';
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM webmaster_integration WHERE id_rapport = :id_rapport");
		$query->bindParam(":id_rapport", $traitement['id_rapport'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['date_insertion'])).'</span>';
		
		$functions  = '';
		$functions .= '<a href="#" id="function_edit_web" data-id="'.$traitement['id_rapport'].'" data-name="'.$traitement['campagne_rapport'].'"><span class="badge badge-success mb-3 mr-3">Modifier</span></a>';
		$functions .= '<a href="#" id="del" data-id="' . $traitement['id_rapport'] . '" data-name="' . $traitement['campagne_rapport'] . '"><span  class="badge badge-danger mb-3 mr-3">supprimer</span></a>';		
		
		$functions .= '';
		
		
		$date_debut = ''.date("d/m/Y H:i:s", strtotime($traitement['date_debut_traitement'])).'';
		$date_fin = ''.date("d/m/Y H:i:s", strtotime($traitement['date_fin_traitement'])).'';
		$date_sort = ''.date("d/m/Y", strtotime($traitement['date_debut_traitement'])).'';	
		
		 
		
        $mysql_data[] = array(
		"id"  => $date_sort,
		"campagne"  => $traitement['campagne_rapport'],
		  "type"  => $statut,
		  "debut" => $date_debut,
		  "fin" => $date_fin,
		  "temps" => $query_temps['datee'],
		  "comm" => $traitement['commentaire_rapport'],
          "functions" => $functions
        );
	}
	$PDO_query_webs->closeCursor();
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
	$PDO_query_webs = null;      
    
  }elseif ($job == 'add_webs_integration'){
	  
   	try 
	{
	$query = $bdd->prepare("INSERT INTO `webmaster_integration` (user_id, user_name, type_rapport, campagne_rapport, date_debut_traitement, date_fin_traitement, commentaire_rapport, date_calcul) VALUES (:user_id, :user_name, :type_rapport, :campagne_rapport, :date_debut_traitement, :date_fin_traitement, :commentaire_rapport, now())");		
	$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);	
	$query->bindParam(":type_rapport", $_GET['type'], PDO::PARAM_INT);		
	$query->bindParam(":campagne_rapport", $_GET['campagne'], PDO::PARAM_STR);		
	$query->bindParam(":date_debut_traitement", $_GET['debut'], PDO::PARAM_STR);		
	$query->bindParam(":date_fin_traitement", $_GET['fin'], PDO::PARAM_STR);		
	$query->bindParam(":commentaire_rapport", $_GET['comm'], PDO::PARAM_STR);	
	$query->execute();
	$query->closeCursor();
	
	
	$query = $bdd->prepare("SELECT MAX(id_rapport) AS MAX FROM webmaster_integration WHERE user_id = :user_id");	
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
	$query->execute();
	$max_id = $query->fetch();
	$query->closeCursor();	
	
	$fin = $_GET['fin'];
	$debut = $_GET['debut'];
	$max = $max_id['MAX'];
	$go = get_working_hours_2($debut,$fin);
	
	$query = $bdd->prepare("UPDATE webmaster_integration SET temps_sec = :temps_sec WHERE id_rapport = :id");
	$query->bindParam(":temps_sec", $go, PDO::PARAM_INT);
	$query->bindParam(":id", $max, PDO::PARAM_INT);
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
	$query = null;
	$bdd = null;
	
	
  
  }elseif ($job == 'edit_webs_integration'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
			try 
			{
			$go = get_working_hours_2($_GET['debut'],$_GET['fin']);
			$query = $bdd->prepare("UPDATE webmaster_integration SET type_rapport = :type_rapport, campagne_rapport = :campagne_rapport, date_debut_traitement = :date_debut_traitement, date_fin_traitement = :date_fin_traitement, commentaire_rapport = :commentaire_rapport, temps_sec = :temps_sec WHERE id_rapport = :id");	
			$query->bindParam(":id", $id, PDO::PARAM_INT);			
			$query->bindParam(":type_rapport", $_GET['type'], PDO::PARAM_INT);
			$query->bindParam(":campagne_rapport", $_GET['campagne'], PDO::PARAM_STR);		
			$query->bindParam(":date_debut_traitement", $_GET['debut'], PDO::PARAM_STR);		
			$query->bindParam(":date_fin_traitement", $_GET['fin'], PDO::PARAM_STR);		
			$query->bindParam(":commentaire_rapport", $_GET['comm'], PDO::PARAM_STR);
			$query->bindParam(":temps_sec", $go, PDO::PARAM_INT);						
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
	$query = null;
	$bdd = null;
	}
    
  } elseif ($job == 'delete_webs_integration'){
  
    if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{		
		$query_del = $bdd->prepare("DELETE FROM webmaster_integration WHERE id_rapport = :id");	
		$query_del->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del->execute();
		$query_del->closeCursor();	
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