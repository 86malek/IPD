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
  if (	$job == 'get_cnil' ||
		$job == 'get_cnil_admin' ||
		$job == 'get_add_cnil'   ||
		$job == 'add_cnil'   ||
		$job == 'edit_cnil'  ||
		$job == 'rech_cnil' ||
		$job == 'delete_cnil'){
		  
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
		
		if (isset($_GET['date'])){
		  $date = $_GET['date'];
		}
		
		if (isset($_GET['collab'])){
		  $collab = $_GET['collab'];
		  if (!is_numeric($collab)){
			$collab = '';
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
  
  if ($job == 'rech_cnil'){
	 
	try 
	{ 
	
	$debut = substr($date, 0,10);
	$fin = substr($date, 13,22);
	
	if($collab == ''){$requete_collab = ':user_id = :user_id';}else{$requete_collab = 'user_id = :user_id';}
	
	$PDO_query = $bdd->prepare("SELECT SUM(cnil_champ_1) AS recu, SUM(cnil_champ_2) AS supp, SUM(cnil_champ_3) AS desabo, cnil_com, date_calcul, user_id, user_name, id_cnil FROM `cnil_traitment` WHERE ".$requete_collab." AND date_calcul between :debut and :fin GROUP BY `date_calcul`, `user_id`");
	
	
	$PDO_query->bindParam(":user_id", $collab, PDO::PARAM_INT);
	$PDO_query->bindParam(":debut", $debut, PDO::PARAM_STR);
	$PDO_query->bindParam(":fin", $fin, PDO::PARAM_STR);
	
	
	
	
		
	$PDO_query->execute();
	while ($traitement = $PDO_query->fetch()){
		
		$date1 = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['date_calcul'])).'</span>';
		
		$recu = '<span class="badge badge-success ">'.$traitement['recu'].'</span>';
		$supp = '<span class="badge badge-warning ">'.$traitement['supp'].'</span>';
		$desabo = '<span class="badge badge-danger ">'.$traitement['desabo'].'</span>';	
		
		 
		
        $mysql_data[] = array(
		"collab"  => $traitement['user_name'],
		"date"  => $date1,
		  "ch1"  => $recu,
		  "ch2" => $supp,
		  "ch3" => $desabo,
		  "comm" => $traitement['cnil_com']
        );
	}
	$PDO_query->closeCursor();
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
	$PDO_query = null;     
    
  }elseif ($job == 'get_cnil_admin'){
	 
	try 
	{ 
	if(empty($id_import)){$PDO_query_webs = $bdd->prepare("SELECT SUM(cnil_champ_1) AS recu, SUM(cnil_champ_2) AS supp, SUM(cnil_champ_3) AS desabo, cnil_com, date_calcul, user_id, user_name, id_cnil FROM `cnil_traitment` GROUP BY `date_calcul`");}
	else
	{$PDO_query_webs = $bdd->prepare("SELECT SUM(cnil_champ_1) AS recu, SUM(cnil_champ_2) AS supp, SUM(cnil_champ_3) AS desabo, cnil_com, date_calcul, user_id, user_name, id_cnil FROM `cnil_traitment` WHERE user_id = :user_id GROUP BY `date_calcul`");
	$PDO_query_webs->bindParam(":user_id", $id_import, PDO::PARAM_INT);}
	
		
	$PDO_query_webs->execute();
	while ($traitement = $PDO_query_webs->fetch()){
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['date_calcul'])).'</span>';
		
		$functions  = '';
		$functions .= '<a href="#" id="function_edit_web" data-id="'.$traitement['id_cnil'].'" data-name="CNIL"><span class="badge badge-success mb-3 mr-3">Modifier</span></a>';
		$functions .= '<a href="#" id="del" data-id="' . $traitement['id_cnil'] . '" data-name="CNIL"><span  class="badge badge-danger mb-3 mr-3">supprimer</span></a>';		
		
		$functions .= '';
		
		$recu = '<span class="badge badge-success ">'.$traitement['recu'].'</span>';
		$supp = '<span class="badge badge-warning ">'.$traitement['supp'].'</span>';
		$desabo = '<span class="badge badge-danger ">'.$traitement['desabo'].'</span>';	
		
		 
		
        $mysql_data[] = array(
		"collab"  => $traitement['user_name'],
		"date"  => $date,
		  "ch1"  => $recu,
		  "ch2" => $supp,
		  "ch3" => $desabo,
		  "comm" => $traitement['cnil_com']
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
    
  }elseif ($job == 'get_add_cnil'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM cnil_traitment WHERE id_cnil = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"ch1"  => $traitement_edit['cnil_champ_1'],
			"ch2"  => $traitement_edit['cnil_champ_2'],
			"ch3"  => $traitement_edit['cnil_champ_3'],
			"comm"  => $traitement_edit['cnil_com']
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
  
  }elseif ($job == 'get_cnil'){
	 
	try 
	{ 
	
	$PDO_query_webs = $bdd->prepare("SELECT * FROM cnil_traitment WHERE user_id = :user_id ORDER BY id_cnil ASC");
	$PDO_query_webs->bindParam(":user_id", $id_import, PDO::PARAM_INT);	
	$PDO_query_webs->execute();
	while ($traitement = $PDO_query_webs->fetch()){		
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['date_calcul'])).'</span>';
		
		$functions  = '';
		$functions .= '<a href="#" id="function_edit_web" data-id="'.$traitement['id_cnil'].'" data-name="CNIL"><span class="badge badge-success mb-3 mr-3">Modifier</span></a>';
		$functions .= '<a href="#" id="del" data-id="' . $traitement['id_cnil'] . '" data-name="CNIL"><span  class="badge badge-danger mb-3 mr-3">supprimer</span></a>';		
		
		$functions .= '';
		
			
		
		 
		
        $mysql_data[] = array(
		"date"  => $date,
		  "ch1"  => $traitement['cnil_champ_1'],
		  "ch2" => $traitement['cnil_champ_2'],
		  "ch3" => $traitement['cnil_champ_3'],
		  "comm" => $traitement['cnil_com'],
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
    
  }elseif ($job == 'add_cnil'){
	  
   	try 
	{
		
	$query = $bdd->prepare("INSERT INTO `cnil_traitment` (user_id, user_name, cnil_champ_1, cnil_champ_2, cnil_champ_3, cnil_com, date_calcul) VALUES (:user_id, :user_name, :cnil_champ_1, :cnil_champ_2, :cnil_champ_3, :cnil_com, now())");		
	$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);	
	$query->bindParam(":cnil_champ_1", $_GET['ch1'], PDO::PARAM_STR);		
	$query->bindParam(":cnil_champ_2", $_GET['ch2'], PDO::PARAM_STR);
	$query->bindParam(":cnil_champ_3", $_GET['ch3'], PDO::PARAM_STR);		
	$query->bindParam(":cnil_com", $_GET['comm'], PDO::PARAM_STR);	
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
	
	
  
  }elseif ($job == 'edit_cnil'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
			try 
			{
			$query = $bdd->prepare("UPDATE cnil_traitment SET cnil_champ_1 = :cnil_champ_1, cnil_champ_2 = :cnil_champ_2, cnil_champ_3 = :cnil_champ_3, cnil_com = :cnil_com WHERE id_cnil = :id");	
			$query->bindParam(":id", $id, PDO::PARAM_INT);			
			$query->bindParam(":cnil_champ_1", $_GET['ch1'], PDO::PARAM_STR);
			$query->bindParam(":cnil_champ_2", $_GET['ch2'], PDO::PARAM_STR);
			$query->bindParam(":cnil_champ_3", $_GET['ch3'], PDO::PARAM_STR);		
			$query->bindParam(":cnil_com", $_GET['comm'], PDO::PARAM_STR);		
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
    
  } elseif ($job == 'delete_cnil'){
  
    if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{		
		$query_del = $bdd->prepare("DELETE FROM cnil_traitment WHERE id_cnil = :id");	
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