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
exit();
}

$job = '';
$id  = '';

if (isset($_GET['job'])){
	
  $job = $_GET['job'];
  
  if ($job == 'get_service' ||
      $job == 'get_service_add'   ||
      $job == 'add_service'   ||
      $job == 'edit_service'  ||
      $job == 'delete_service'){
		  
		if (isset($_GET['id'])){
		$id = $_GET['id'];
		if (!is_numeric($id)){
		$id = '';
		}
		}
		
  } else {
	  
    $job = '';
	
  }
  
}

$mysql_data = array();

if ($job != ''){ 
  
  if ($job == 'get_service'){
    
    try 
	{ 
	$PDO_query_timesheet = $bdd->prepare("SELECT * FROM user_service_timesheet ORDER BY id_service_timesheet ASC");		
	$PDO_query_timesheet->execute();
	while ($timesheet = $PDO_query_timesheet->fetch()){	
		
		
		$functions  = '';
				
		$functions .= '<a href="#" id="function_edit_service" data-id="' . $timesheet['id_service_timesheet'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';

				$query = $bdd->prepare("select count(*) from user_activite_timesheet where id_service_timesheet = :id_service_timesheet");
				$query->bindParam(":id_service_timesheet", $timesheet['id_service_timesheet'], PDO::PARAM_INT);
				$query->execute();
				$verif_existe = $query->fetchColumn();
				$query->closeCursor();
				
		if($verif_existe == 0){
		$functions .= '<a href="#" id="del" data-id="' . $timesheet['id_service_timesheet'] . '" data-name="' . $timesheet['name_service_timesheet'] . '"  data-doc="' . $timesheet['name_service_timesheet'] . '"><span  class="badge badge-secondary mb-3 mr-3">Effacer</span></a>';		
		}else{
		$functions .= '<a href="#"><span  class="badge badge-bittersweet mb-3 mr-3">Service utlisé</span></a>';
		}
		$functions .= '';
		
        $mysql_data[] = array(
			"nom" => $timesheet['name_service_timesheet'],
			"functions" => $functions
        );
	}
	$PDO_query_timesheet->closeCursor();
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
	$PDO_query_timesheet = null; 
	
    
    
  } elseif ($job == 'get_service_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM user_service_timesheet WHERE id_service_timesheet = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"nom"  => $traitement_edit['name_service_timesheet']
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
	
	
  
  } elseif ($job == 'add_service'){
	  
	  
    try 
	{
	$query = $bdd->prepare("INSERT INTO user_service_timesheet (`name_service_timesheet`) VALUES (:name_service_timesheet)");		
	$query->bindParam(":name_service_timesheet", $_GET['nom'], PDO::PARAM_STR);
	$query->execute();
	$query->closeCursor();
	
	$result  = 'success';
	$message = 'Service ajouteé avec succés';
  	}
	catch(PDOException $x) 
	{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête'; 	
	}	
	$query = null;
	$bdd = null;
	
	
	
  } elseif ($job == 'edit_service'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE user_service_timesheet SET name_service_timesheet = :name_service_timesheet WHERE id_service_timesheet = :id");		
		$query->bindParam(":name_service_timesheet", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Service modifiée avec succés';
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
    
  } elseif ($job == 'delete_service'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM user_service_timesheet WHERE id_service_timesheet = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Service effacée avec succés';
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