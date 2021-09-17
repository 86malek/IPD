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
	$query_cumul = $bdd->prepare("SELECT data_ie.id_ie, data_ie.id_cat_ie, data_ie.reporting, data_ie.user_name, data_ie.user_id, data_ie.date_calcul, data_ie.temps_sec, data_ie.etat FROM data_ie INNER JOIN data_cat_ie ON data_cat_ie.id_cat_ie = data_ie.id_cat_ie WHERE data_ie.id_cat_ie = :id_cat_ie AND data_ie.etat = 1 GROUP BY data_ie.user_id");
	$query_cumul->bindParam(":id_cat_ie", $id_stat, PDO::PARAM_INT);
	$query_cumul->execute();
	while ($acide = $query_cumul->fetch()){
		
		
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = :id_cat_ie AND user_id = :user_id");
		$query->bindParam(":id_cat_ie", $acide['id_cat_ie'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		
		if(!empty($query_temps['datee'])){			
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		}else{$traitement = '';}
		
			$query = $bdd->prepare("SELECT count(*) FROM data_ie WHERE id_cat_ie = :id_cat_ie AND user_id = :user_id AND etat = 1");
			$query->bindParam(":id_cat_ie", $acide['id_cat_ie'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM data_ie WHERE id_cat_ie = :id_cat_ie AND `user_id` = :user_id AND `reporting` = 1");
			$query->bindParam(":id_cat_ie", $acide['id_cat_ie'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ok = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_okk = '<strong>'.$rowligne_ok.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM data_ie WHERE id_cat_ie = :id_cat_ie AND `user_id` = :user_id AND `reporting` = 2");
			$query->bindParam(":id_cat_ie", $acide['id_cat_ie'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ko = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_koo = '<strong>'.$rowligne_ko.'</strong>';
			
			
			
			 $mysql_data[] = array(
			  "collab"  => $acide['user_name'],
			  "temps"  => $traitement,
			  "ligne"     => $rowligne_style,
			  "ok"     => $rowligne_okk,
			  "ko"     => $rowligne_koo
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