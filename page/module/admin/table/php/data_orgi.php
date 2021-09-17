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
  
  if ($job == 'get_orgi' ||
      $job == 'get_orgi_add'   ||
      $job == 'add_orgi'   ||
      $job == 'edit_orgi'  ||
      $job == 'delete_orgi'){
		  
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
  
  if ($job == 'get_orgi'){
    
    try 
	{ 
	$PDO_query_user = $bdd->prepare("SELECT * FROM user_equipe ORDER BY id_equipe ASC");		
	$PDO_query_user->execute();
	while ($team = $PDO_query_user->fetch()){	
		
		
		$functions  = '';
				
		$functions .= '<a href="#" id="function_edit_orgi" data-id="' . $team['id_equipe'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';
		
				$query = $bdd->prepare("select count(*) from users where equipe_id = :equipe_id");
				$query->bindParam(":equipe_id", $team['id_equipe'], PDO::PARAM_INT);
				$query->execute();
				$verif_existe = $query->fetchColumn();
				$query->closeCursor();
				
		if($verif_existe == 0){
		$functions .= '<a href="#" id="del" data-id="' . $team['id_equipe'] . '" data-name="' . $team['name_equipe'] . '"  data-doc="' . $team['name_equipe'] . '"><span  class="badge badge-secondary mb-3 mr-3">Effacer</span></a>';		
		}else{
		$functions .= '<a href="#"><span  class="badge badge-bittersweet mb-3 mr-3">équipe utlisée</span></a>';
		}
		$functions .= '';
		
        $mysql_data[] = array(
			"nom" => $team['name_equipe'],
			"functions" => $functions
        );
	}
	$PDO_query_user->closeCursor();
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
	$PDO_query_user = null; 
	
    
    
  } elseif ($job == 'get_orgi_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM user_equipe WHERE id_equipe = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"nom"  => $traitement_edit['name_equipe']
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
	
	
  
  } elseif ($job == 'add_orgi'){
	  
	  
    try 
	{
	$query = $bdd->prepare("INSERT INTO user_equipe (`name_equipe`) VALUES (:name_equipe)");		
	$query->bindParam(":name_equipe", $_GET['nom'], PDO::PARAM_STR);
	$query->execute();
	$query->closeCursor();
	
	$result  = 'success';
	$message = 'Équipe ajouteé avec succés';
  	}
	catch(PDOException $x) 
	{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête'; 	
	}	
	$query = null;
	$bdd = null;
	
	
	
  } elseif ($job == 'edit_orgi'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE user_equipe SET name_equipe = :name_equipe WHERE id_equipe = :id");		
		$query->bindParam(":name_equipe", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Équipe modifiée avec succés';
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
    
  } elseif ($job == 'delete_orgi'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM user_equipe WHERE id_equipe = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Équipe effacée avec succés';
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