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
  
  if ($job == 'get_notif' ||
      $job == 'get_notif_add'   ||
      $job == 'edit_notif'  ||
      $job == 'delete_notif'){
		  
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
  
  if ($job == 'get_notif'){
    
    try 
	{ 
	$PDO_query_notif = $bdd->prepare("SELECT * FROM contact ORDER BY id_message ASC");		
	$PDO_query_notif->execute();
	while ($team = $PDO_query_notif->fetch()){	
		
		
		$functions  = '';
		
		if($team['stat_message'] == 1){		
			$functions .= '<a href="#" id="function_edit_notif" data-id="' . $team['id_message'] . '"><span class="badge badge-success mb-3 mr-3">Notification traitée</span></a>';
		}else{
			$functions .= '<a href="#" id="function_edit_notif" data-id="' . $team['id_message'] . '"><span class="badge badge-warning mb-3 mr-3">Traiter</span></a>';
			$functions .= '<a href="#" id="del" data-id="' . $team['id_message'] . '" data-name="' . $team['np_message'] . '"  data-doc="' . $team['np_message'] . '"><span  class="badge badge-danger mb-3 mr-3">Effacer</span></a>';	

		}
		
			
		
		$functions .= '';
		
        $mysql_data[] = array(
			"nomprenom" => $team['np_message'],
			"date" => $team['date_message'],
			"sujet" => $team['sujet_message'],
			"email" => $team['email_message'],
			"functions" => $functions
        );
	}
	$PDO_query_notif->closeCursor();
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
	$PDO_query_notif = null; 
	
    
    
  } elseif ($job == 'get_notif_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM contact WHERE id_message = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"nomprenom"  => $traitement_edit['np_message'],
			"obj"  => $traitement_edit['sujet_message'],
			"mail"  => $traitement_edit['email_message'],
			"message"  => $traitement_edit['message']
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
	  
	  
    
	
	
	
  } elseif ($job == 'edit_notif'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE contact SET stat_message = 1 WHERE id_message = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Modifiée avec succés';
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
    
  } elseif ($job == 'delete_notif'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM contact WHERE id_message = :id");		
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'notif effacée avec succés';
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