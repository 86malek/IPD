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
	
	
	try 
	{
		
	
	$query = $bdd->prepare("SELECT * FROM acide WHERE (id_cat_acide = :id_cat_acide AND user_id = 0) OR (id_cat_acide = :id_cat_acide AND user_id = :user_id) ORDER BY `id_cat_acide` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat_acide", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$functions  = '<center>';			
			if($traitement['reporting'] == 0){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-warning mb-3 mr-3">En Attente</span></a>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">Modif OK</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-bittersweet mb-3 mr-3">Sup</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-info mb-3 mr-3">Ajout</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">Doublon</span></a>';	
			}		
			$functions .= '</center>';
			
			}else{
			
			$functions  = '<center>';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '</center>';
			
			}
			
				if($traitement['nt_acide'] == 1){
				$type = '<span class="badge badge-sm badge-shamrock mb-3 mr-3">NT</span>';        
				}elseif($traitement['nt_acide'] == 0){ $type = '';}
				
				if($traitement['siret_acide'] == NULL){
				$siret = '';        
				}else{ $siret = $traitement['siret_acide'];}
		
		$linkedin = '<a class="iconfont iconfont-social-linkedin-sm container-heading-control" target="_blank" href="'.$traitement['url_linkedin_acide'].'"></a>';
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';
		}
		$alerte .= '';
        $mysql_data[] = array(
          "raison"          => $traitement['raison_sociale_acide'],
          "codep"  => $traitement['code_postal_acide'],
		  "ville"  => $traitement['ville_acide'],
		  "idcontact"  => $traitement['id_contact_acide'],
		  "civilite"  => $traitement['civilite_acide'],
		  "nom"  => $traitement['nom_acide'],
		  "prenom"  => $traitement['prenom_acide'],
		  "idsociete"  => $traitement['id_societe_acide'],
		  "fonction"  => $traitement['fonction_acide'],
		  "urllinkedin"  => $linkedin,
		  "newposte"  => $traitement['new_poste_acide'],
		  "oldposte"  => $traitement['old_poste_acide'],
		  "newentreprise"  => $traitement['new_entreprise_acide'],
		  "oldentreprise"  => $traitement['old_entreprise_acide'],
		  "nt"  => $type,
		  "siret"  => $siret,
		  "alerte"     => $alerte,
          "functions"     => $functions
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
    
  }elseif ($job == 'get_traitement_admin'){
	
	
	try 
	{
		
	$query = $bdd->prepare("SELECT * FROM acide WHERE id_cat_acide = :id_cat_acide");
	$query->bindParam(":id_cat_acide", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
		$functions  = '<center>';			
			if($traitement['reporting'] == 0){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">En Attente</span>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">Modif OK</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-bittersweet mb-3 mr-3">Sup</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-info mb-3 mr-3">Ajout</span></a>';	
			}elseif($traitement['reporting'] == 5){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">Doublon</span></a>';	
			}		
			$functions .= '</center>';
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM cat_synthese_fiche_update WHERE linkedin_fiche_id = :linkedin_fiche_id");
		$query_count->bindParam(":linkedin_fiche_id", $traitement['id_acide'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';	
		
		if($traitement['nt_acide'] == 1){
		$type = '<span class="badge badge-sm badge-shamrock mb-3 mr-3">NT</span>';        
		}elseif($traitement['nt_acide'] == 0){ $type = '';}
		
		$linkedin = '<a class="iconfont iconfont-social-linkedin-sm container-heading-control" target="_blank" href="'.$traitement['url_linkedin_acide'].'"></a>';
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(cat_synthese_fiche_update.temps_sec)) AS traitement FROM cat_synthese_fiche_update INNER JOIN acide ON acide.id_acide = cat_synthese_fiche_update.linkedin_fiche_id WHERE cat_synthese_fiche_update.linkedin_fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_acide'], PDO::PARAM_INT);
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
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur_acide'].'</p>';
		if($traitement['siret_acide'] == NULL){
				$siret = '';        
				}else{ $siret = $traitement['siret_acide'];}
		
		
					
        $mysql_data[] = array(
          "raison"          => $traitement['raison_sociale_acide'],
          "codep"  => $traitement['code_postal_acide'],
		  "ville"  => $traitement['ville_acide'],
		  "idcontact"  => $traitement['id_contact_acide'],
		  "civilite"  => $traitement['civilite_acide'],
		  "nom"  => $traitement['nom_acide'],
		  "prenom"  => $traitement['prenom_acide'],
		  "idsociete"  => $traitement['id_societe_acide'],
		  "fonction"  => $traitement['fonction_acide'],
		  "urllinkedin"  => $linkedin,
		  "newposte"  => $traitement['new_poste_acide'],
		  "oldposte"  => $traitement['old_poste_acide'],
		  "newentreprise"  => $traitement['new_entreprise_acide'],
		  "oldentreprise"  => $traitement['old_entreprise_acide'],
          "functions"     => $functions,
		  "collab" => $operateur,
		  "siret"  => $siret,
		  "nt"  => $type,
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
			$query_select_add = $bdd->prepare("SELECT * FROM acide WHERE id_acide = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"raison"  => $traitement_edit['raison_sociale_acide'],
				"prenom"  => $traitement_edit['prenom_acide'],
				"nom"  => $traitement_edit['nom_acide'],
				"title"  => $traitement_edit['civilite_acide'],
				"newe"  => $traitement_edit['new_entreprise_acide'],
				"url"  => $traitement_edit['url_linkedin_acide'],
				"siret"  => $traitement_edit['siret_acide'],
				"nt"  => $traitement_edit['nt_acide'],
				"nfonction"  => $traitement_edit['fonction_new_acide'],
				"ids"  => $traitement_edit['id_societe_acide'],
				"cp"  => $traitement_edit['code_postal_acide'],
				"ville"  => $traitement_edit['ville_acide'],
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
			$query_select_add = $bdd->prepare("SELECT * FROM acide WHERE id_acide = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"raison"  => $traitement_edit['raison_sociale_acide'],
				"prenom"  => $traitement_edit['prenom_acide'],
				"nom"  => $traitement_edit['nom_acide'],
				"title"  => $traitement_edit['civilite_acide'],
				"newe"  => $traitement_edit['new_entreprise_acide'],
				"url"  => $traitement_edit['url_linkedin_acide'],
				"siret"  => $traitement_edit['siret_acide'],
				"nt"  => $traitement_edit['nt_acide'],
				"nfonction"  => $traitement_edit['fonction_new_acide'],
				"ids"  => $traitement_edit['id_societe_acide'],
				"cp"  => $traitement_edit['code_postal_acide'],
				"ville"  => $traitement_edit['ville_acide'],
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
		$query = $bdd->prepare("INSERT INTO `cat_synthese_fiche_update` (`linkedin_fiche_id`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `linkedin_lot_id`, `date_calcul`) VALUES (:linkedin_fiche_id, :fiche_debut, :fiche_fin, :go, :id_user, :user, :linkedin_lot_id, now())");	
		$query->bindParam(":linkedin_fiche_id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":linkedin_lot_id", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$query = $bdd->prepare("UPDATE acide SET operateur_acide = :user , user_id = :id_user, reporting = :reporting, civilite_acide = :civilite_acide, nom_acide = :nom_acide, prenom_acide = :prenom_acide, new_entreprise_acide = :new_entreprise_acide, url_linkedin_acide = :url_linkedin_acide, date_calcul = now(), etat = 1, siret_acide = :siret_acide, nt_acide = :nt_acide, fonction_new_acide = :fonction_new_acide WHERE id_acide = :id");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
		$query->bindParam(":civilite_acide", $_GET['title'], PDO::PARAM_STR);
		$query->bindParam(":nom_acide", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":prenom_acide", $_GET['prenom'], PDO::PARAM_STR);
		$query->bindParam(":new_entreprise_acide", $_GET['newe'], PDO::PARAM_STR);
		$query->bindParam(":url_linkedin_acide", $_GET['url'], PDO::PARAM_STR);
		if(empty($_GET['siret'])){$siret = '';}else{$siret = $_GET['siret'];}
		$query->bindParam(":siret_acide", $siret , PDO::PARAM_STR);
		if(empty($_GET['nt'])){$nt = 0;}else{$nt = $_GET['nt'];}
		$query->bindParam(":nt_acide", $nt, PDO::PARAM_INT);
		if(empty($_GET['nfonction'])){$nfonction = '';}else{$nfonction = $_GET['nfonction'];}
		$query->bindParam(":fonction_new_acide", $nfonction , PDO::PARAM_STR);
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
		
		
		$query = $bdd->prepare("UPDATE acide SET commentaire = :commentaire, commentaire_alerte = :commentaire_alerte WHERE id_acide = :id");			
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