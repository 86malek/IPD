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
  
  if ($job == 'get_activite' ||
      $job == 'get_activite_add'   ||
      $job == 'add_activite'   ||
      $job == 'edit_activite'  ||
      $job == 'delete_activite'){
		  
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
  
  if ($job == 'get_activite'){
    
    try 
	{ 
	$PDO_query_timesheet = $bdd->prepare("SELECT * FROM user_activite_timesheet ORDER BY id_activite_timesheet ASC");		
	$PDO_query_timesheet->execute();
	while ($timesheet = $PDO_query_timesheet->fetch()){	
		
		
		$functions  = '';
				
		$functions .= '<a href="#" id="function_edit_activite" data-id="' . $timesheet['id_activite_timesheet'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';

				$query = $bdd->prepare("select * from users where id = :id_user");
				$query->bindParam(":id_user", $timesheet['id_user'], PDO::PARAM_INT);
				$query->execute();
				$user_name = $query->fetch();
				$query->closeCursor();


				$query = $bdd->prepare("select * from user_tache_timesheet where id_tache_timesheet = :id_tache_timesheet");
				$query->bindParam(":id_tache_timesheet", $timesheet['id_tache_timesheet'], PDO::PARAM_INT);
				$query->execute();
				$tache_name = $query->fetch();
				$query->closeCursor();


				$query = $bdd->prepare("select * from user_service_timesheet where id_service_timesheet = :id_service_timesheet");
				$query->bindParam(":id_service_timesheet", $timesheet['id_service_timesheet'], PDO::PARAM_INT);
				$query->execute();
				$categorie_name = $query->fetch();
				$query->closeCursor();



				
		$functions .= '<a href="#" id="del" data-id="' . $timesheet['id_activite_timesheet'] . '" data-name="Activite - ' . $timesheet['id_activite_timesheet'] . '"  data-doc="Activite - ' . $timesheet['id_activite_timesheet'] . '"><span  class="badge badge-danger mb-3 mr-3">Effacer</span></a>';		
		
		$functions .= '';
		

		$myDateTime = DateTime::createFromFormat('Y-m-d', $timesheet['date_activite_timesheet']);
		$formatteddate = $myDateTime->format('d-m-Y');


		$temps = $timesheet['temps_activite_timesheet'].'min';

        $mysql_data[] = array(
			"date" => $formatteddate,
			"tache" => $tache_name['name_tache_timesheet'],
			"categorie" => $categorie_name['name_service_timesheet'],
			"temps" => $temps,
			"user" => $user_name['full_name'],
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
	
    
    
  } elseif ($job == 'get_activite_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM user_activite_timesheet WHERE id_activite_timesheet = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"date"  => $traitement_edit['date_activite_timesheet'],
			"tache"  => $traitement_edit['id_tache_timesheet'],
			"cat"  => $traitement_edit['id_service_timesheet'],
			"temps"  => $traitement_edit['temps_activite_timesheet']
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
	
	
  
  } elseif ($job == 'add_activite'){
	  
	  
    try 
	{
	$query = $bdd->prepare("INSERT INTO user_activite_timesheet (`id_tache_timesheet`, `id_service_timesheet` , `id_user`, `date_activite_timesheet`, `temps_activite_timesheet`) VALUES (:id_tache_timesheet, :id_service_timesheet, :id_user, :date_activite_timesheet, :temps_activite_timesheet)");		
	$query->bindParam(":id_tache_timesheet", $_GET['tache'], PDO::PARAM_INT);
	$query->bindParam(":id_service_timesheet", $_GET['cat'], PDO::PARAM_INT);
	$query->bindParam(":id_user", $_GET['id_user'], PDO::PARAM_INT);
	$query->bindParam(":date_activite_timesheet", $_GET['date'], PDO::PARAM_STR);
	$query->bindParam(":temps_activite_timesheet", $_GET['temps'], PDO::PARAM_INT);
	$query->execute();
	$query->closeCursor();
	
	$result  = 'success';
	$message = 'Activite ajouteé avec succés';
  	}
	catch(PDOException $x) 
	{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête'; 	
	}	
	$query = null;
	$bdd = null;
	
	
	
  } elseif ($job == 'edit_activite'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE user_activite_timesheet SET id_tache_timesheet = :id_tache_timesheet, id_service_timesheet = :id_service_timesheet, date_activite_timesheet = :date_activite_timesheet, temps_activite_timesheet = :temps_activite_timesheet  WHERE id_activite_timesheet = :id");		
		$query->bindParam(":id_tache_timesheet", $_GET['tache'], PDO::PARAM_INT);
		$query->bindParam(":id_service_timesheet", $_GET['cat'], PDO::PARAM_INT);
		$query->bindParam(":date_activite_timesheet", $_GET['date'], PDO::PARAM_STR);
		$query->bindParam(":temps_activite_timesheet", $_GET['temps'], PDO::PARAM_INT);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Activité modifiée avec succés';
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
    
  } elseif ($job == 'delete_activite'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM user_activite_timesheet WHERE id_activite_timesheet = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Activité effacée avec succés';
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