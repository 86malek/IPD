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
	$query_cumul = $bdd->prepare("SELECT acide.id_acide, acide.id_cat_acide, acide.reporting, acide.operateur_acide, acide.user_id, acide.date_calcul, acide.temps_sec, acide.etat FROM acide INNER JOIN cat_acide ON cat_acide.id_cat_acide = acide.id_cat_acide WHERE acide.id_cat_acide = :id_cat_acide AND acide.etat = 1 GROUP BY acide.user_id");
	$query_cumul->bindParam(":id_cat_acide", $id_stat, PDO::PARAM_INT);
	$query_cumul->execute();
	while ($acide = $query_cumul->fetch()){
		
		if($id_stat == 2){
		$query_time = $bdd->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(traitement_detail))) AS datee FROM cat_synthese_acide_details WHERE id_cat_acide = 2 AND id_intervenant_cat_acide = 65");
		$query_time->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
		$query_time->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
		$query_time->execute();	
		$query_temps = $query_time->fetch();
		$query_time->closeCursor();
		}else{
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = :linkedin_lot_id AND user_id = :user_id");
		$query->bindParam(":linkedin_lot_id", $acide['id_cat_acide'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		}
		
		if(!empty($query_temps['datee'])){			
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		}else{$traitement = '';}
		
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND user_id = :user_id AND etat = 1");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 1");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_okk = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_ok = '<strong>'.$rowligne_okk.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 2");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_MODIFF = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_MODIF = '<strong>'.$rowligne_MODIFF.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 3");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_SUPPP = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_SUPP = '<strong>'.$rowligne_SUPPP.'</strong>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 4");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_AJOUTT = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_AJOUT = '<strong>'.$rowligne_AJOUTT.'</strong>';
			
			 $mysql_data[] = array(
			  "collab"  => $acide['operateur_acide'],
			  "temps"  => $traitement,
			  "ligne"     => $rowligne_style,
			  "ok"     => $rowligne_ok,
			  "modif"     => $rowligne_MODIF,
			  "supp"     => $rowligne_SUPP,
			  "ajout"     => $rowligne_AJOUT
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