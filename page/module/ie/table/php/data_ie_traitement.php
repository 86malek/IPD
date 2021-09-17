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
	$query = $bdd->prepare("SELECT * FROM data_ie WHERE (id_cat_ie = :id_cat_ie AND user_id = 0) OR (id_cat_ie = :id_cat_ie AND user_id = :user_id) ORDER BY `id_cat_ie` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat_ie", $id_import, PDO::PARAM_INT);
	}else{
	$query = $bdd->prepare("SELECT * FROM data_ie WHERE (id_cat_ie = :id_cat_ie AND user_id = 0 AND reporting = :reporting) OR (id_cat_ie = :id_cat_ie AND user_id = :user_id AND reporting = :reporting) ORDER BY `id_cat_ie` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":reporting", $reporting, PDO::PARAM_INT);
	$query->bindParam(":id_cat_ie", $id_import, PDO::PARAM_INT);
	}
	
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$functions  = '';	



			if($traitement['reporting'] == 0){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-warning mb-3 mr-3">En Attente</span></a>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">KO</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">NRP</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">REFUS</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">RAPPEL</span></a>';	
			}elseif($traitement['reporting'] == 6){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">HS / FAX</span></a>';	
			}elseif($traitement['reporting'] == 7){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">FAUX NUMÉRO</span></a>';	
			}elseif($traitement['reporting'] == 8){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">CESSATION D\'ACTIVITÉ</span></a>';	
			}




			$functions .= '';
			if($traitement['reporting'] == 0){
			$etb = '...';
			$bre =  '...';
			$annee = '...';
			}else{
			$etb = $traitement['etb_ie'];
			$bre =  $traitement['ca_bre_ie'];
			$annee = $traitement['annee_ca_bre_ie'];
			}
			}else{
			
			$functions  = '<center>';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '</center>';
			$etb = '';
			$bre =  '';
			$annee = '';
			
			}	
		
		
		
		if($traitement['reporting'] <> 0){
		$url = '<span class="badge badge-sm badge-outline-success mb-3 mr-3">Fiche terminée</span>';
		}else{$url = '<span class="badge badge-sm badge-outline-default mb-3 mr-3">Fiche en attente de traitement</span>';}
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';
		}
		$alerte .= '';
		
        $mysql_data[] = array(
		  "etb"  => $etb,
		  "bre"  => $bre,
		  "annee"  => $annee,
		  "alerte"     => $alerte,
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
		
	$query = $bdd->prepare("SELECT * FROM data_ie WHERE id_cat_ie = :id_cat_ie");
	$query->bindParam(":id_cat_ie", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			$functions  = '';			
			if($traitement['reporting'] == 0){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-warning mb-3 mr-3">En Attente</span></a>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">KO</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">NRP</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">REFUS</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">RAPPEL</span></a>';	
			}elseif($traitement['reporting'] == 6){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">HS / FAX</span></a>';	
			}elseif($traitement['reporting'] == 7){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">FAUX NUMÉRO</span></a>';	
			}elseif($traitement['reporting'] == 8){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_ie'] . '" data-name="Numéro : ' . $traitement['id_ie'] . '"><span class="badge badge-danger mb-3 mr-3">CESSATION D\'ACTIVITÉ</span></a>';	
			}		
			$functions .= '';
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM data_cat_synthese_fiche_update_ie WHERE id_ie = :id_ie");
		$query_count->bindParam(":id_ie", $traitement['id_ie'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_ie'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_ie'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_ie'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';
				
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(data_cat_synthese_fiche_update_ie.temps_sec)) AS traitement FROM data_cat_synthese_fiche_update_ie INNER JOIN data_ie ON data_ie.id_ie = data_cat_synthese_fiche_update_ie.id_ie WHERE data_cat_synthese_fiche_update_ie.id_ie = :id");	
		$query_temps->bindParam(":id", $traitement['id_ie'], PDO::PARAM_INT);
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
		
		
					
        $mysql_data[] = array(          
		  "etb"  => $traitement['etb_ie'],
		  "bre"  => $traitement['ca_bre_ie'],
		  "annee"  => $traitement['annee_ca_bre_ie'],
          "functions" => $functions,
		  "collab" => $operateur,
		  "mood" => $mood,
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
			$query_select_add = $bdd->prepare("SELECT * FROM data_ie WHERE id_ie = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$newVariable = str_replace(',','.',$traitement_edit['ca_bre_ie']);
				$mysql_data[] = array(
				"etb"  => $traitement_edit['etb_ie'],
				"bre"  => $newVariable,
				"annee"  => $traitement_edit['annee_ca_bre_ie'],
				"commentaire"  => $traitement_edit['commentaire'],
				"reporting"  => $traitement_edit['reporting'],
				"commentaire_collab"  => $traitement_edit['commentaire_collab']
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
			$query_select_add = $bdd->prepare("SELECT * FROM data_ie WHERE id_ie = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"user"  => $traitement_edit['user_name'],
				"etb"  => $traitement_edit['etb_ie'],
				"bre"  => $traitement_edit['ca_bre_ie'],
				"annee"  => $traitement_edit['annee_ca_bre_ie'],
				"commentaire"  => $traitement_edit['commentaire'],
				"commentaire_collab"  => $traitement_edit['commentaire_collab'],
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
		
		if($_GET['reporting'] == 0){
			$result  = 'success';
			$message = 'Succès de requête';	
		}else{
		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `data_cat_synthese_fiche_update_ie` (`id_ie`, `date_debut_traitement`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `id_cat_ie`, `date_calcul`) VALUES (:id_ie, :fiche_debut, :fiche_fin, :go, :id_user, :user_name, :id_cat_ie, now())");	
		$query->bindParam(":id_ie", $id, PDO::PARAM_INT);
		$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":id_cat_ie", $_GET['lot'], PDO::PARAM_INT);
		
		$query->execute();
		$query->closeCursor();
		
		$query = $bdd->prepare("UPDATE data_ie SET user_name = :user , user_id = :id_user, reporting = :reporting, date_calcul = now(), etat = 1, commentaire_collab = :commentaire_collab WHERE id_ie = :id");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
		$query->bindParam(":commentaire_collab", $_GET['commentaire_collab'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
		}
	  
    }
    
  } elseif ($job == 'edit_traitement_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		$query = $bdd->prepare("UPDATE data_ie SET commentaire = :commentaire, commentaire_alerte = :commentaire_alerte WHERE id_ie = :id");			
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