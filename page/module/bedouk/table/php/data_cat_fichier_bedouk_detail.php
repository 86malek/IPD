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
  if ($job == 'get_cat_fichier_detail'){
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

if (isset($_GET['id_stat'])){
  $id_stat = $_GET['id_stat'];
  if (!is_numeric($id_stat)){
	$id_stat = '';
  }
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_cat_fichier_detail'){
    
    try 
	{
	$query_cumul = $bdd->prepare("SELECT data_siret.id_siret, data_siret.id_cat_siretisation, data_siret.reporting, data_siret.user_name, data_siret.user_id, data_siret.date_calcul, data_siret.temps_sec, data_siret.etat FROM data_siret INNER JOIN data_cat_siretisation ON data_cat_siretisation.id_cat_siretisation = data_siret.id_cat_siretisation WHERE data_siret.id_cat_siretisation = :id_cat_siretisation AND data_siret.etat = 1 GROUP BY data_siret.user_id");
	$query_cumul->bindParam(":id_cat_siretisation", $id_stat, PDO::PARAM_INT);
	$query_cumul->execute();
	while ($acide = $query_cumul->fetch()){
		
		
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = :id_cat_siretisation AND user_id = :user_id");
		$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		
		if(!empty($query_temps['datee'])){			
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		}else{$traitement = '';}
		
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND user_id = :user_id AND etat = 1");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND `user_id` = :user_id AND `reporting` = 1");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_nt = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_ntt = '<strong>'.$rowligne_nt.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND `user_id` = :user_id AND `reporting` = 2");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_stee = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_steee = '<strong>'.$rowligne_stee.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND `user_id` = :user_id AND `reporting` = 3");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_stef = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_steff = '<strong>'.$rowligne_stef.'</strong>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND `user_id` = :user_id AND `reporting` = 4");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_encours = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_encourss = '<strong>'.$rowligne_encours.'</strong>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation AND `user_id` = :user_id AND `reporting` = 5");
			$query->bindParam(":id_cat_siretisation", $acide['id_cat_siretisation'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ok = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_okk = '<strong>'.$rowligne_ok.'</strong>';
			
			 $mysql_data[] = array(
			  "collab"  => $acide['user_name'],
			  "temps"  => $traitement,
			  "ligne"     => $rowligne_style,
			  "nt"     => $rowligne_ntt,
			  "stee"     => $rowligne_steee,
			  "stef"     => $rowligne_steff,
			  "ok"     => $rowligne_okk,
			  "rowligne_encours"     => $rowligne_encourss
			  );	
		
	}
	$query_cumul->closeCursor();
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