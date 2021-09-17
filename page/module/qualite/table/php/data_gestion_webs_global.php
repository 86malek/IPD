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
  if (
  		$job == 'get_rapport_integration'){
		  
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
		
		if (isset($_GET['intervalle'])){
		  $date = $_GET['intervalle'];
		}
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){
  
	if ($job == 'get_rapport_integration'){
	
	
	try
	{
	$PDO_query_rapport = $bdd->prepare("SELECT * FROM `webmaster_integration` GROUP BY `user_id`");
	$PDO_query_rapport->execute();
	while ($traitement = $PDO_query_rapport->fetch()){	
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM webmaster_integration WHERE user_id = :user_id");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		if($query_temps['datee'] <> '00:00:00'){			
			
		$traitement_time = '<strong>'.$query_temps['datee'].'</strong>';

		$pieces = explode(":", $query_temps['datee']);
		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);				
		
		$jh = round($duree_decimal/8, 2);
		
		}else{
			$traitement_time = '<strong>X</strong>'; 
			$jh = '<strong>X</strong>';
		}	
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$totall = $query->fetchColumn();
		$query->closeCursor();
		
		$total = '<span class="badge badge-info ">'.$totall.'</span>';
		
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 1");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$leads = $query->fetchColumn();
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 2");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$perso = $query->fetchColumn();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 3");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$flash = $query->fetchColumn();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 4");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$reintegration = $query->fetchColumn();
		$query->closeCursor();		
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 5");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$crea = $query->fetchColumn();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 6");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$crealead = $query->fetchColumn();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM `webmaster_integration` WHERE user_id = :user_id AND type_rapport = 7");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$integ = $query->fetchColumn();
		$query->closeCursor();
		
		
		$functions  = '<center>';				
				
		$functions .= '<a href="WebsRapportCollab-'.$traitement['user_id'].'"><span class="badge badge-primary ">Détails</span></a>';	

		$functions .= '</center>';
		
		$query = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitement) AS date_debut FROM webmaster_integration WHERE user_id = :user_id");
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->execute();
		$select_date = $query->fetch();
		$query->closeCursor();
			
			$date_fin = date("d-m-Y", strtotime($select_date['date_fin']));
			$date_debut = date("d-m-Y", strtotime($select_date['date_debut']));
			
		$mysql_data[] = array(	
		"id"  => $traitement['id_rapport'],			
		  "collab"  => $traitement['user_name'],
		  "total" => $total,		  
		  "date_fin" => $date_fin,
		  "date_debut" => $date_debut,
		  "leads" => $leads,
		  "flash" => $flash,
		  "perso" => $perso,
		  "integ" => $integ,
		  "crealead" => $crealead,
		  "reintegration" => $reintegration,
		  "crea" => $crea,
		  "temps" => $traitement_time,
		  "jh" => $jh,
		  "functions"     => $functions
		);
				
				
		
		
	}
	$PDO_query_rapport->closeCursor();
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
	$PDO_query_rapport = null;	
    
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