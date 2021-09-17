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
  
  if ($job == 'get_tache' ||
      $job == 'get_tache_add'   ||
      $job == 'add_tache'   ||
      $job == 'edit_tache'  ||
      $job == 'delete_tache'){
		  
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
  
  if ($job == 'get_tache'){
    
    try 
	{ 
	$PDO_query_timesheet = $bdd->prepare("SELECT * FROM user_tache_timesheet ORDER BY id_tache_timesheet ASC");		
	$PDO_query_timesheet->execute();
	while ($timesheet = $PDO_query_timesheet->fetch()){	
		
		
		$functions  = '';
				
		$functions .= '<a href="#" id="function_edit_tache" data-id="' . $timesheet['id_tache_timesheet'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';

				$query = $bdd->prepare("select count(*) from user_activite_timesheet where id_tache_timesheet = :id_tache_timesheet");
				$query->bindParam(":id_tache_timesheet", $timesheet['id_tache_timesheet'], PDO::PARAM_INT);
				$query->execute();
				$verif_existe = $query->fetchColumn();
				$query->closeCursor();
				
		if($verif_existe == 0){
		$functions .= '<a href="#" id="del" data-id="' . $timesheet['id_tache_timesheet'] . '" data-name="' . $timesheet['name_tache_timesheet'] . '"  data-doc="' . $timesheet['name_tache_timesheet'] . '"><span  class="badge badge-secondary mb-3 mr-3">Effacer</span></a>';		
		}else{
		$functions .= '<a href="#"><span  class="badge badge-bittersweet mb-3 mr-3">Tâche utlisé</span></a>';
		}
		$functions .= '';
		
        $mysql_data[] = array(
			"nom" => $timesheet['name_tache_timesheet'],
			"code" => $timesheet['code_tache_timesheet'],
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
	
    
    
  } elseif ($job == 'get_tache_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM user_tache_timesheet WHERE id_tache_timesheet = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"nom"  => $traitement_edit['name_tache_timesheet'],
			"code"  => $traitement_edit['code_tache_timesheet']
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
	
	
  
  } elseif ($job == 'add_tache'){
	  
	  
    try 
	{
	$query = $bdd->prepare("INSERT INTO user_tache_timesheet (`name_tache_timesheet`, `code_tache_timesheet`) VALUES (:name_tache_timesheet, :code_tache_timesheet)");		
	$query->bindParam(":name_tache_timesheet", $_GET['nom'], PDO::PARAM_STR);
	$query->bindParam(":code_tache_timesheet", $_GET['code'], PDO::PARAM_STR);
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
	
	
	
  } elseif ($job == 'edit_tache'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE user_tache_timesheet SET name_tache_timesheet = :name_tache_timesheet, code_tache_timesheet = :code_tache_timesheet WHERE id_tache_timesheet = :id");		
		$query->bindParam(":name_tache_timesheet", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":code_tache_timesheet", $_GET['code'], PDO::PARAM_INT);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Tache modifiée avec succés';
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
    
  } elseif ($job == 'delete_tache'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM user_tache_timesheet WHERE id_tache_timesheet = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Tache effacée avec succés';
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