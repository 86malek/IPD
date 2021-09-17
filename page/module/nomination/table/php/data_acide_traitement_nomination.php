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
  if ($job == 'get_traitement_nomination' ||
  		$job == 'get_traitement_nomination_admin' ||
      $job == 'get_traitement_add_nomination'   ||
	  $job == 'get_traitement_add_nomination_admin'   ||
      $job == 'add_traitement_nomination'   ||
	  $job == 'add_traitement_nomination_admin'   ||
      $job == 'edit_traitement_nomination'  ||
	  $job == 'edit_traitement_nomination_admin'  ||
      $job == 'delete_traitement_nomination'){
		  
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
  
  if ($job == 'get_traitement_nomination_admin'){
	 
	try 
	{ 
	if($mode_import == 'total'){
		$PDO_query_traitement = $bdd->prepare("SELECT * FROM nomination_acide ORDER BY acide_id_nomination ASC");
	}elseif($mode_import == 'detail'){
		$PDO_query_traitement = $bdd->prepare("SELECT * FROM nomination_acide WHERE acide_intervenant_id_nomination = :user_id ORDER BY acide_id_nomination ASC");
		$PDO_query_traitement->bindParam(":user_id", $id_import, PDO::PARAM_INT);	
	} 	
	
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
		$mood  = '';
		$query = $bdd->prepare("SELECT COUNT(*) FROM nomination_acide_update WHERE acide_id_nomination = :acide_id_nomination");
		$query->bindParam(":acide_id_nomination", $traitement['acide_id_nomination'], PDO::PARAM_INT);
		$query->execute();
		$alerte_modif = $query->fetchColumn();
		$query->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<a id="mood_affichage" data-id="'.$traitement['acide_id_nomination'].'"><span class="badge badge-info mb-3 mr-3">1</span></a>';
		}elseif($alerte_modif == 2){
		$mood .= '<a id="mood_affichage" data-id="'.$traitement['acide_id_nomination'].'"><span class="badge badge-buttercup mb-3 mr-3">2</span></a>';
		}elseif($alerte_modif > 2){
		$mood .= '<a id="mood_affichage" data-id="'.$traitement['acide_id_nomination'].'"><span class="badge badge-danger mb-3 mr-3"><b>> 3</b></span></a>';
		}
		$mood .= '';
		
		$alerte  = '';
		
		if($traitement['acide_comm_alerte_nomination'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['acide_comm_alerte_nomination'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">URGENT</span>';
		}
		$alerte .= '';
			
		$statut  = '';		
		if($traitement['acide_statut_nomination'] == 0){
        $statut .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3"></span>';
		}elseif($traitement['acide_statut_nomination'] == 1){
		$statut .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">Ajout</span>';	
		}elseif($traitement['acide_statut_nomination'] == 2){
		$statut .= '<span class="badge badge-sm badge-default mb-3 mr-3">Modif</span>';	
		}elseif($traitement['acide_statut_nomination'] == 3){
		$statut .= '<span class="badge badge-sm badge-info mb-3 mr-3">Supp</span>';	
		}		
        $statut .= '';
		
		$title  = '';		
		if($traitement['acide_civilite_nomination'] == NULL){
        $title .= 'Non renseignée';
		}elseif($traitement['acide_civilite_nomination'] == 'Mme'){
		$title .= 'Mme';	
		}elseif($traitement['acide_civilite_nomination'] == 'M'){
		$title .= 'M';	
		}		
        $title .= '';
		
		$type  = '';
		
		if($traitement['acide_nt_nomination'] == 2){
				$type .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">BO-Acide</span>';	
        
		}elseif($traitement['acide_nt_nomination'] == 1){
				$type .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">NT</span>';
		}
		
        $type .= '';
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['acide_date_nomination'])).'</span>';
		
		$functions  = '<center>';
		$functions .= '';
		$functions .= '<a href="#" id="function_edit_web" data-id="'.$traitement['acide_id_nomination'].'" data-name="'.$traitement['acide_rs_nomination'].'"><span class="badge badge-success mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
		$functions .= '</center>';	
		
		
		if($traitement['acide_publication_nomination'] == 1){
			$publication = 'ETAT MAJORS';	
			}elseif($traitement['acide_publication_nomination'] == 2){		
			$publication = 'LE FAC';
			}elseif($traitement['acide_publication_nomination'] == 3){		
			$publication = 'DECIDEURS MAGAZINE';
			}elseif($traitement['acide_publication_nomination'] == 4){		
			$publication = 'CADREO';
			}elseif($traitement['acide_publication_nomination'] == 5){		
			$publication = 'JDN IT';
			}elseif($traitement['acide_publication_nomination'] == 6){		
			$publication = 'LE MONDE INFO';
			}elseif($traitement['acide_publication_nomination'] == 7){		
			$publication = 'LES ECHOS';
			}elseif($traitement['acide_publication_nomination'] == 8){		
			$publication = 'GOOGLE ALERTE';
			}elseif($traitement['acide_publication_nomination'] == 9){		
			$publication = 'Alerte LINKEDIN';
			}elseif($traitement['acide_publication_nomination'] == 10){		
			$publication = 'Alerte Nomination';
			}elseif($traitement['acide_publication_nomination'] == 11){		
			$publication = 'Alerte KBC';
			}elseif($traitement['acide_publication_nomination'] == 12){		
			$publication = 'LSA';
			}elseif($traitement['acide_publication_nomination'] == 13){		
			$publication = 'AGEFI';
			}else{$publication = 'Problème dans la colonne publication';}
			
			$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS traitement FROM nomination_acide_update INNER JOIN nomination_acide ON nomination_acide.acide_id_nomination = nomination_acide_update.acide_id_nomination WHERE nomination_acide_update.acide_id_nomination = :id");	
			$query->bindParam(":id", $traitement['acide_id_nomination'], PDO::PARAM_INT);
			$query->execute();			
			$query_sum = $query->fetch();						
			$query->closeCursor();
		 	$temps = '<h4>'.$query_sum['traitement'].'</h4>';
		
        $mysql_data[] = array(
		  "collab"  => $traitement['acide_intervenant_nomination'],
          "date"          => $date,
		  "publication"  => $publication,
          "rs"  => $traitement['acide_rs_nomination'],
		  "siret"  => $traitement['acide_siret_nomination'],
		  "title"  => $title,
		  "nom"  => $traitement['acide_nom_nomination'],
		  "prenom"  => $traitement['acide_prenom_nomination'],
		  "fe"  => $traitement['acide_fe_nomination'],
		  "statut"  => $statut,
		  "ancienne"  => $traitement['acide_acienne_nomination'],
		  "type"  => $type,
          "functions"     => $functions,
		  "mood"     => $mood,
		  "alerte"     => $alerte,
		  "temps"     => $temps
        );
	}
	$PDO_query_traitement->closeCursor();
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
	$PDO_query_traitement = null;      
    
  } elseif ($job == 'get_traitement_add_nomination_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM nomination_acide WHERE acide_id_nomination = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"publication"  => $traitement_edit['acide_publication_nomination'],
			"rs"  => $traitement_edit['acide_rs_nomination'],
			"siret"  => $traitement_edit['acide_siret_nomination'],
			"title"  => $traitement_edit['acide_civilite_nomination'],
			"nom"  => $traitement_edit['acide_nom_nomination'],
			"prenom"  => $traitement_edit['acide_prenom_nomination'],
			"fonction"  => $traitement_edit['acide_fe_nomination'],
			"statut"  => $traitement_edit['acide_statut_nomination'],
			"ancienne"  => $traitement_edit['acide_acienne_nomination'],
			"user_id"  => $traitement_edit['acide_intervenant_id_nomination'],
			"etat"  => $traitement_edit['acide_nt_nomination'],
			"comm"  => $traitement_edit['acide_comm_nomination']
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
  
  }elseif ($job == 'get_traitement_add_nomination'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM nomination_acide WHERE acide_id_nomination = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"publication"  => $traitement_edit['acide_publication_nomination'],
			"rs"  => $traitement_edit['acide_rs_nomination'],
			"siret"  => $traitement_edit['acide_siret_nomination'],
			"title"  => $traitement_edit['acide_civilite_nomination'],
			"nom"  => $traitement_edit['acide_nom_nomination'],
			"prenom"  => $traitement_edit['acide_prenom_nomination'],
			"fonction"  => $traitement_edit['acide_fe_nomination'],
			"statut"  => $traitement_edit['acide_statut_nomination'],
			"ancienne"  => $traitement_edit['acide_acienne_nomination'],
			"user_id"  => $traitement_edit['acide_intervenant_id_nomination'],
			"etat"  => $traitement_edit['acide_nt_nomination'],
			"comm"  => $traitement_edit['acide_comm_nomination']
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
  
  }elseif ($job == 'get_traitement_nomination'){
	 
	try 
	{ 
	if($mode_import == 'absolute'){
		$PDO_query_traitement = $bdd->prepare("SELECT * FROM nomination_acide ORDER BY acide_id_nomination ASC");
	}else{
		$PDO_query_traitement = $bdd->prepare("SELECT * FROM nomination_acide WHERE acide_intervenant_id_nomination = :user_id ORDER BY acide_id_nomination ASC");
		$PDO_query_traitement->bindParam(":user_id", $id_import, PDO::PARAM_INT);	
	}
  	
	
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
		$statut  = '';		
		if($traitement['acide_statut_nomination'] == 0){
        $statut .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3"></span>';
		}elseif($traitement['acide_statut_nomination'] == 1){
		$statut .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">Ajout</span>';	
		}elseif($traitement['acide_statut_nomination'] == 2){
		$statut .= '<span class="badge badge-sm badge-default mb-3 mr-3">Modif</span>';	
		}elseif($traitement['acide_statut_nomination'] == 3){
		$statut .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">Supp</span>';	
		}		
        $statut .= '';
		
		$title  = '';		
		if($traitement['acide_civilite_nomination'] == NULL){
        $title .= 'Non renseignée';
		}elseif($traitement['acide_civilite_nomination'] == 'Mme'){
		$title .= 'Mme';	
		}elseif($traitement['acide_civilite_nomination'] == 'M'){
		$title .= 'M';	
		}		
        $title .= '';
		$alerte  = '';
		
		if($traitement['acide_comm_alerte_nomination'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['acide_comm_alerte_nomination'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">URGENT</span>';
		}
		$alerte .= '';
		$type  = '';
		
		if($traitement['acide_nt_nomination'] == 2){
				$type .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">BO-Acide</span>';	
        
		}elseif($traitement['acide_nt_nomination'] == 1){
				$type .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">NT</span>';
		}
		
        $type .= '';
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date("d/m/Y", strtotime($traitement['acide_date_nomination'])).'</span>';
		
		$functions  = '<center>';
		if($mode_import == 'absolute'){
		$functions .= '';
		}else{
		if (checkAdmin()) {
		$functions .= '<a href="#" id="del" data-id="' . $traitement['acide_id_nomination'] . '" data-name="' . $traitement['acide_rs_nomination'] . '"><span  class="badge badge-danger mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
		}else{
		
		
		$functions .= '<a href="#" id="function_edit_web" data-id="'.$traitement['acide_id_nomination'].'" data-name="'.$traitement['acide_rs_nomination'].'"><span class="badge badge-success mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
		
		}
		}
		$functions .= '</center>';
		if($traitement['acide_publication_nomination'] == 1){
			$publication = 'ETAT MAJORS';	
			}elseif($traitement['acide_publication_nomination'] == 2){		
			$publication = 'LE FAC';
			}elseif($traitement['acide_publication_nomination'] == 3){		
			$publication = 'DECIDEURS MAGAZINE';
			}elseif($traitement['acide_publication_nomination'] == 4){		
			$publication = 'CADREO';
			}elseif($traitement['acide_publication_nomination'] == 5){		
			$publication = 'JDN IT';
			}elseif($traitement['acide_publication_nomination'] == 6){		
			$publication = 'LE MONDE INFO';
			}elseif($traitement['acide_publication_nomination'] == 7){		
			$publication = 'LES ECHOS';
			}elseif($traitement['acide_publication_nomination'] == 8){		
			$publication = 'GOOGLE ALERTE';
			}elseif($traitement['acide_publication_nomination'] == 9){		
			$publication = 'Alerte LINKEDIN';
			}elseif($traitement['acide_publication_nomination'] == 10){		
			$publication = 'Alerte Nomination';
			}elseif($traitement['acide_publication_nomination'] == 11){		
			$publication = 'Alerte KBC';
			}elseif($traitement['acide_publication_nomination'] == 12){		
			$publication = 'LSA';
			}elseif($traitement['acide_publication_nomination'] == 13){		
			$publication = 'AGEFI';
			}else{$publication = 'Problème dans la colonne publication';}
			
		
		 
		 
        $mysql_data[] = array(
		  "collab"  => $traitement['acide_intervenant_nomination'],
          "date"          => $date,
		  "publication"  => $publication,
          "rs"  => $traitement['acide_rs_nomination'],
		  "siret"  => $traitement['acide_siret_nomination'],
		  "title"  => $title,
		  "nom"  => $traitement['acide_nom_nomination'],
		  "prenom"  => $traitement['acide_prenom_nomination'],
		  "fe"  => $traitement['acide_fe_nomination'],
		  "statut"  => $statut,
		  "ancienne"  => $traitement['acide_acienne_nomination'],
		  "type"  => $type,
          "functions"     => $functions,
		  "alerte"     => $alerte
        );
	}
	$PDO_query_traitement->closeCursor();
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
	$PDO_query_traitement = null;      
    
  } elseif ($job == 'add_traitement_nomination_admin'){
	  
    try 
	{	
	$query = $bdd->prepare("INSERT INTO `nomination_acide` (acide_date_nomination, acide_intervenant_nomination, acide_intervenant_id_nomination, acide_publication_nomination, acide_rs_nomination, acide_siret_nomination, acide_civilite_nomination, acide_nom_nomination, acide_prenom_nomination, acide_fe_nomination, acide_statut_nomination, acide_acienne_nomination, date_calcul, acide_nt_nomination, acide_comm_nomination, acide_comm_alerte_nomination) VALUES (now(), :user_name, :user_id, :acide_publication_nomination, :acide_rs_nomination, :acide_siret_nomination, :acide_civilite_nomination, :acide_nom_nomination, :acide_prenom_nomination, :acide_fe_nomination, :acide_statut_nomination, :acide_acienne_nomination, now(), :acide_nt_nomination, :acide_comm_nomination, :acide_comm_alerte_nomination)");
		
	$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
	
	$query->bindParam(":acide_publication_nomination", $_GET['publication'], PDO::PARAM_INT);
	$query->bindParam(":acide_rs_nomination", $_GET['rs'], PDO::PARAM_STR);		
	$query->bindParam(":acide_siret_nomination", $_GET['siret'], PDO::PARAM_INT);		
	$query->bindParam(":acide_civilite_nomination", $_GET['title'], PDO::PARAM_STR);		
	$query->bindParam(":acide_nom_nomination", $_GET['nom'], PDO::PARAM_STR);		
	$query->bindParam(":acide_prenom_nomination", $_GET['prenom'], PDO::PARAM_STR);		
	$query->bindParam(":acide_fe_nomination", $_GET['fonction'], PDO::PARAM_STR);		
	$query->bindParam(":acide_statut_nomination", $_GET['statut'], PDO::PARAM_INT);		
	$query->bindParam(":acide_acienne_nomination", $_GET['ancienne'], PDO::PARAM_STR);
	
	if(empty($_GET['comm'])){$push = 0;$query->bindParam(":acide_comm_alerte_nomination", $push, PDO::PARAM_INT);}else{$push = 1;$query->bindParam(":acide_comm_alerte_nomination", $push, PDO::PARAM_INT);}
	$query->bindParam(":acide_comm_nomination", $_GET['comm'], PDO::PARAM_STR);
	
	
	$query->bindParam(":acide_nt_nomination", $_GET['etat'], PDO::PARAM_INT);
		
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
	
	
  
  } elseif ($job == 'add_traitement_nomination'){
	  
    try 
	{	
	$query = $bdd->prepare("INSERT INTO `nomination_acide` (acide_date_nomination, acide_intervenant_nomination, acide_intervenant_id_nomination, acide_publication_nomination, acide_rs_nomination, acide_siret_nomination, acide_civilite_nomination, acide_nom_nomination, acide_prenom_nomination, acide_fe_nomination, acide_statut_nomination, acide_acienne_nomination, date_calcul, acide_nt_nomination) VALUES (now(), :user_name, :user_id, :acide_publication_nomination, :acide_rs_nomination, :acide_siret_nomination, :acide_civilite_nomination, :acide_nom_nomination, :acide_prenom_nomination, :acide_fe_nomination, :acide_statut_nomination, :acide_acienne_nomination, now(), :acide_nt_nomination)");
		
	$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
	
	$query->bindParam(":acide_publication_nomination", $_GET['publication'], PDO::PARAM_INT);
	$query->bindParam(":acide_rs_nomination", $_GET['rs'], PDO::PARAM_STR);		
	$query->bindParam(":acide_siret_nomination", $_GET['siret'], PDO::PARAM_INT);		
	$query->bindParam(":acide_civilite_nomination", $_GET['title'], PDO::PARAM_STR);		
	$query->bindParam(":acide_nom_nomination", $_GET['nom'], PDO::PARAM_STR);		
	$query->bindParam(":acide_prenom_nomination", $_GET['prenom'], PDO::PARAM_STR);		
	$query->bindParam(":acide_fe_nomination", $_GET['fonction'], PDO::PARAM_STR);		
	if(!empty($_GET['statut'])){		
	$query->bindParam(":acide_statut_nomination", $_GET['statut'], PDO::PARAM_INT);
	}else{	
	$query->bindParam(":acide_statut_nomination", $val_0, PDO::PARAM_INT);
	}		
	$query->bindParam(":acide_acienne_nomination", $_GET['ancienne'], PDO::PARAM_STR);
	$query->bindParam(":acide_nt_nomination", $_GET['etat'], PDO::PARAM_INT);
		
	$query->execute();
	$query->closeCursor();
	
	
	$query = $bdd->prepare("SELECT MAX(acide_id_nomination) AS MAX FROM nomination_acide WHERE acide_intervenant_id_nomination = :user_id");	
	$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
	$query->execute();
	$max_id = $query->fetch();
	$query->closeCursor();	
	
	$fin = $_GET['fin'];
	$debut = $_GET['debut'];
	$go = get_working_hours_2($debut,$fin);
	
	$query = $bdd->prepare("INSERT INTO `nomination_acide_update` (`acide_id_nomination`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `date_calcul`) VALUES (:id, :fiche_debut, :fiche_fin, :go, :id_user, :user, now())");	
	$query->bindParam(":id", $max_id['MAX'], PDO::PARAM_INT);
	$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
	$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
	$query->bindParam(":go", $go, PDO::PARAM_INT);
	$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
	$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
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
	
	
  
  } elseif ($job == 'edit_traitement_nomination_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
			try 
			{
			$query = $bdd->prepare("UPDATE nomination_acide SET acide_publication_nomination = :acide_publication_nomination, acide_rs_nomination = :acide_rs_nomination, acide_siret_nomination = :acide_siret_nomination, acide_civilite_nomination = :acide_civilite_nomination, acide_nom_nomination = :acide_nom_nomination, acide_prenom_nomination = :acide_prenom_nomination, acide_fe_nomination = :acide_fe_nomination, acide_statut_nomination = :acide_statut_nomination, acide_acienne_nomination = :acide_acienne_nomination, acide_comm_nomination = :acide_comm_nomination, acide_nt_nomination = :acide_nt_nomination, acide_comm_alerte_nomination = :acide_comm_alerte_nomination WHERE acide_id_nomination = :id");	
			$query->bindParam(":id", $id, PDO::PARAM_INT);			
			$query->bindParam(":acide_publication_nomination", $_GET['publication'], PDO::PARAM_INT);
			$query->bindParam(":acide_rs_nomination", $_GET['rs'], PDO::PARAM_STR);		
			$query->bindParam(":acide_siret_nomination", $_GET['siret'], PDO::PARAM_INT);		
			$query->bindParam(":acide_civilite_nomination", $_GET['title'], PDO::PARAM_STR);		
			$query->bindParam(":acide_nom_nomination", $_GET['nom'], PDO::PARAM_STR);		
			$query->bindParam(":acide_prenom_nomination", $_GET['prenom'], PDO::PARAM_STR);		
			$query->bindParam(":acide_fe_nomination", $_GET['fonction'], PDO::PARAM_STR);
			$query->bindParam(":acide_statut_nomination", $_GET['statut'], PDO::PARAM_INT);		
			$query->bindParam(":acide_acienne_nomination", $_GET['ancienne'], PDO::PARAM_STR);
			if(empty($_GET['comm'])){$push = 0;$query->bindParam(":acide_comm_alerte_nomination", $push, PDO::PARAM_INT);}else{$push = 1;$query->bindParam(":acide_comm_alerte_nomination", $push, PDO::PARAM_INT);}
			$query->bindParam(":acide_comm_nomination", $_GET['comm'], PDO::PARAM_STR);
			$query->bindParam(":acide_nt_nomination", $_GET['etat'], PDO::PARAM_STR);
						
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
    
  }elseif ($job == 'edit_traitement_nomination'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
			
			$query = $bdd->prepare("UPDATE nomination_acide SET acide_publication_nomination = :acide_publication_nomination, acide_rs_nomination = :acide_rs_nomination, acide_siret_nomination = :acide_siret_nomination, acide_civilite_nomination = :acide_civilite_nomination, acide_nom_nomination = :acide_nom_nomination, acide_prenom_nomination = :acide_prenom_nomination, acide_fe_nomination = :acide_fe_nomination, acide_statut_nomination = :acide_statut_nomination, acide_acienne_nomination = :acide_acienne_nomination, acide_nt_nomination = :acide_nt_nomination WHERE acide_id_nomination = :id");	
			$query->bindParam(":id", $id, PDO::PARAM_INT);			
			$query->bindParam(":acide_publication_nomination", $_GET['publication'], PDO::PARAM_INT);
			$query->bindParam(":acide_rs_nomination", $_GET['rs'], PDO::PARAM_STR);		
			$query->bindParam(":acide_siret_nomination", $_GET['siret'], PDO::PARAM_INT);		
			$query->bindParam(":acide_civilite_nomination", $_GET['title'], PDO::PARAM_STR);		
			$query->bindParam(":acide_nom_nomination", $_GET['nom'], PDO::PARAM_STR);		
			$query->bindParam(":acide_prenom_nomination", $_GET['prenom'], PDO::PARAM_STR);		
			$query->bindParam(":acide_fe_nomination", $_GET['fonction'], PDO::PARAM_STR);
			if($_GET['etat'] == 1){$st = 0;}else{$st = $_GET['statut'];}
			$query->bindParam(":acide_statut_nomination", $st, PDO::PARAM_INT);		
			$query->bindParam(":acide_acienne_nomination", $_GET['ancienne'], PDO::PARAM_STR);
			
			$query->bindParam(":acide_nt_nomination", $_GET['etat'], PDO::PARAM_STR);
						
			$query->execute();
			$query->closeCursor();
			
			$fin = $_GET['fin'];
			$debut = $_GET['debut'];
			$go = get_working_hours_2($debut,$fin);
			
			$query = $bdd->prepare("INSERT INTO `nomination_acide_update` (`acide_id_nomination`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `date_calcul`) VALUES (:id, :fiche_debut, :fiche_fin, :go, :id_user, :user, now())");	
			$query->bindParam(":id", $id, PDO::PARAM_INT);
			$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
			$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
			$query->bindParam(":go", $go, PDO::PARAM_INT);
			$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
			$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
			$query->execute();
			$query->closeCursor();
			
			
			$result  = 'success';
	$message = 'Succès de requête';
	
	
			
	$query = null;
	$bdd = null;
	}
    
  } elseif ($job == 'delete_traitement_nomination'){
  
    /*if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM nomination_acide WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
      }
    }*/
  
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