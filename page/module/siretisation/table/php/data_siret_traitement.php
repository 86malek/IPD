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
  
  if ($job == 'get_traitement' ||
  		$job == 'get_traitement_admin' ||
      	$job == 'get_traitement_add'   ||
		$job == 'get_traitement_add_admin' ||
      	$job == 'add_traitement'   ||
      	$job == 'edit_traitement'  ||
		$job == 'edit_traitement_admin'  ||
      	$job == 'delete_traitement'){
			
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
	
	if (isset($_GET['reporting'])){
      $reporting = $_GET['reporting'];
      if (!is_numeric($reporting)){
        $reporting = '';
      }
    }
	
	if (isset($_GET['id_user'])){
      $id_user = $_GET['id_user'];
      
    }
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){ 
   
  if ($job == 'get_traitement'){
	
	
	/*try 
	{*/
		
	if($reporting == ''){
	$query = $bdd->prepare("SELECT * FROM data_siret WHERE (id_cat_siretisation = :id_cat_siretisation AND user_id = 0) OR (id_cat_siretisation = :id_cat_siretisation AND user_id = :user_id) ORDER BY `id_cat_siretisation` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat_siretisation", $id_import, PDO::PARAM_INT);
	}else{
	$query = $bdd->prepare("SELECT * FROM data_siret WHERE (id_cat_siretisation = :id_cat_siretisation AND user_id = 0 AND reporting = :reporting) OR (id_cat_siretisation = :id_cat_siretisation AND user_id = :user_id AND reporting = :reporting) ORDER BY `id_cat_siretisation` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":reporting", $reporting, PDO::PARAM_INT);
	$query->bindParam(":id_cat_siretisation", $id_import, PDO::PARAM_INT);
	}
	
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$functions  = '';			
			if($traitement['reporting'] == 0){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-rose mb-3 mr-3">En Attente</span></a>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-danger mb-3 mr-3">NT (non trouvé)</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-info mb-3 mr-3">Ste Etrangère</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-danger mb-3 mr-3">Ste Fermée</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-danger mb-3 mr-3">En cours de liquidation</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}		
			$functions .= '';
			
			if($traitement['siretisation'] == NULL){
			$siret = '';        
			}else{ $siret = $traitement['siretisation'];}
			
			}else{
			
			$functions  = '<center>';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '</center>';
			$siret = '';
			
			}	
		
		
		
		if($traitement['reporting'] <> 0){
		$url = '<span class="badge badge-sm badge-outline-success mb-3 mr-3">Lien traité avec succès</span>';
		}else{$url = '<span class="badge badge-sm badge-outline-default mb-3 mr-3">En attente de traitement</span>';}
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';
		}
		$alerte .= '';
		
		if($traitement['date_calcul'] == NULL){
		$jour = '';
		}else{
		$jour = date("d-m-Y", strtotime($traitement['date_calcul']));
		}
		
		$url = ''.$traitement['url_siretisation'].'';

        $mysql_data[] = array(
		  "url"  => $url,
		  "jour"  => $jour,
		  "siret"  => $siret,
		  "alerte"     => $alerte,
		  "url"     => $url,
          "functions"     => $functions
        );
      
	
	
	}	  
    $query->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	/*}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;*/
    
  }elseif ($job == 'get_traitement_admin'){
	
	
	try 
	{
		
	$query = $bdd->prepare("SELECT * FROM data_siret WHERE id_cat_siretisation = :id_cat_siretisation");
	$query->bindParam(":id_cat_siretisation", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			$functions  = '';			
			if($traitement['reporting'] == 0){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">En Attente</span>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-info mb-3 mr-3">NT (non trouvé)</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-success mb-3 mr-3">Ste Etrangère</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-danger mb-3 mr-3">Ste Fermée</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-danger mb-3 mr-3">En cours de liquidation</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_siret'] . '" data-name="Numéro : ' . $traitement['id_siret'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}		
			$functions .= '';
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM data_cat_synthese_fiche_update_siretisation WHERE id_siret = :id_siret");
		$query_count->bindParam(":id_siret", $traitement['id_siret'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_siret'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_siret'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_siret'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';
				
		$url = '<a class="font-semibold color-info" target="_blank" href="'.$traitement['url_siretisation'].'">'.$traitement['url_siretisation'].'</a>';
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(data_cat_synthese_fiche_update_siretisation.temps_sec)) AS traitement FROM data_cat_synthese_fiche_update_siretisation INNER JOIN data_siret ON data_siret.id_siret = data_cat_synthese_fiche_update_siretisation.id_siret WHERE data_cat_synthese_fiche_update_siretisation.id_siret = :id");	
		$query_temps->bindParam(":id", $traitement['id_siret'], PDO::PARAM_INT);
		$query_temps->execute();			
		$query_sum = $query_temps->fetch();						
		$query_temps->closeCursor();
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à vérifier</span>';
		}
		$alerte .= '';
		
		$temps = '<p class="custom-line-height font-light">'.$query_sum['traitement'].'</p>';
		$operateur = '<p class="custom-line-height font-light">'.$traitement['user_name'].'</p>';
		if($traitement['siretisation'] == NULL){
				$siret = '';        
				}else{ $siret = $traitement['siretisation'];}
		
		
					
        $mysql_data[] = array(          
		  "url"  => $url,
          "functions"     => $functions,
		  "collab" => $operateur,
		  "siret"  => $siret,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "alerte"     => $alerte
        );
      
	
	
	}	  
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
	$bdd = null;
    
  } elseif ($job == 'get_traitement_add'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{		
			$query_select_add = $bdd->prepare("SELECT * FROM data_siret WHERE id_siret = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				
				$mysql_data[] = array(
				"url"  => $traitement_edit['url_siretisation'],
				"siret"  => $traitement_edit['siretisation'],
				"commentaire"  => $traitement_edit['commentaire'],
				"reporting"  => $traitement_edit['reporting']
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
  
  } elseif ($job == 'get_traitement_add_admin'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{		
			$query_select_add = $bdd->prepare("SELECT * FROM data_siret WHERE id_siret = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"user"  => $traitement_edit['user_name'],
				"url"  => $traitement_edit['url_siretisation'],
				"siret"  => $traitement_edit['siretisation'],
				"commentaire"  => $traitement_edit['commentaire'],
				"reporting"  => $traitement_edit['reporting']
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
  
  } elseif ($job == 'add_traitement'){
    
    
  
  } elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
				
		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `data_cat_synthese_fiche_update_siretisation` (`id_siret`, `date_debut_traitement`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `id_cat_siretisation`, `date_calcul`) VALUES (:id_siret, :fiche_debut, :fiche_fin, :go, :id_user, :user_name, :id_cat_siretisation, now())");	
		$query->bindParam(":id_siret", $id, PDO::PARAM_INT);
		$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":id_cat_siretisation", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$query = $bdd->prepare("UPDATE data_siret SET user_name = :user , user_id = :id_user, reporting = :reporting, date_calcul = now(), etat = 1, siretisation = :siretisation WHERE id_siret = :id");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
		$query->bindParam(":siretisation", $_GET['siret'] , PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  } elseif ($job == 'edit_traitement_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		$query = $bdd->prepare("UPDATE data_siret SET commentaire = :commentaire, commentaire_alerte = :commentaire_alerte WHERE id_siret = :id");			
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		if(empty($_GET['commentaire'])){$push = 0;$query->bindParam(":commentaire_alerte", $push, PDO::PARAM_INT);}else{$push = 1;$query->bindParam(":commentaire_alerte", $push, PDO::PARAM_INT);}
		$query->bindParam(":commentaire", $_GET['commentaire'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  } elseif ($job == 'delete_traitement'){} 

}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>