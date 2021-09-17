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
  		$job == 'get_rapport_cnil'){
		  
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
  
	if ($job == 'get_rapport_cnil'){
	
	
	try
	{
	$PDO_query_rapport = $bdd->prepare("SELECT SUM(cnil_champ_1) AS recu, SUM(cnil_champ_2) AS supp, SUM(cnil_champ_3) AS desabo, cnil_com, date_calcul, user_id, user_name, id_cnil FROM `cnil_traitment` GROUP BY `user_id`");
	$PDO_query_rapport->execute();
	while ($traitement = $PDO_query_rapport->fetch()){	
		
		/*$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM webmaster_integration WHERE user_id = :user_id");
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
			$traitement_time = '<strong>X</strong>'; */
			$jh = '';
		/*}*/
		
		$recu = '<span class="badge badge-success ">'.$traitement['recu'].'</span>';
		$supp = '<span class="badge badge-warning ">'.$traitement['supp'].'</span>';
		$desabo = '<span class="badge badge-danger ">'.$traitement['desabo'].'</span>';
		
		
		
		
		
		$functions  = '<center>';				
				
		$functions .= '<a href="CnilRapportCollab-'.$traitement['user_id'].'"><span class="badge badge-primary ">Affichage Journalier</span></a>';	

		$functions .= '</center>';		
		
			
		$mysql_data[] = array(				
		  "collab"  => $traitement['user_name'],
		  "totalr" => $recu,		  
		  "totals" => $supp,
		  "totald" => $desabo,
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