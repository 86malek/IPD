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
	$query_cumul = $bdd->prepare("SELECT hb_acide.id_acide, hb_acide.id_cat_acide, hb_acide.reporting, hb_acide.operateur_acide, hb_acide.user_id, hb_acide.date_calcul, hb_acide.temps_sec, hb_acide.etat FROM hb_acide INNER JOIN hb_cat_acide ON hb_cat_acide.id_cat_acide = hb_acide.id_cat_acide WHERE hb_acide.id_cat_acide = :id_cat_acide AND hb_acide.etat = 1 GROUP BY hb_acide.user_id");
	$query_cumul->bindParam(":id_cat_acide", $id_stat, PDO::PARAM_INT);
	$query_cumul->execute();
	while ($acide = $query_cumul->fetch()){
		
		
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = :id_cat_acide AND user_id = :user_id");
		$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);	
		$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		
		if(!empty($query_temps['datee'])){			
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		}else{$traitement = '';}
		
			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND user_id = :user_id AND etat = 1");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 1");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ajout = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_ajoutt = '<strong>'.$rowligne_ajout.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 2");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_fermee = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_fermeee = '<strong>'.$rowligne_fermee.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 3");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_modif = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_modiff = '<strong>'.$rowligne_modif.'</strong>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 4");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ok = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_okk = '<strong>'.$rowligne_ok.'</strong>';


			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 5");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_supp = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_suppp = '<strong>'.$rowligne_supp.'</strong>';


			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 6");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_encours = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_encourss = '<strong>'.$rowligne_encours.'</strong>';


			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 7");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ko = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_koo = '<strong>'.$rowligne_ko.'</strong>';


			$query = $bdd->prepare("SELECT count(*) FROM hb_acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 8");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->execute();
			$rowligne_ajout_new = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_ajout_neww = '<strong>'.$rowligne_ajout_new.'</strong>';
			
			 $mysql_data[] = array(
			  "collab"  => $acide['operateur_acide'],
			  "temps"  => $traitement,
			  "ligne"     => $rowligne_style,
			  "ajout"     => $rowligne_ajoutt,
			  "ajoutnew"     => $rowligne_ajout_neww,
			  "fermee"     => $rowligne_fermeee,
			  "modif"     => $rowligne_modiff,
			  "ok"     => $rowligne_okk,
			  "supp"     => $rowligne_suppp,
			  "encours"     => $rowligne_encourss,
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