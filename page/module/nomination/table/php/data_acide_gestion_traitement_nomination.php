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
  		$job == 'get_gestion_traitement_nomination' ||
		$job == 'get_gestion_traitement_nomination_enrechisement' ||
      	$job == 'get_gestion_traitement_add_nomination_enrechisement'   ||
		$job == 'get_gestion_traitement_add_nomination_obj'   ||
      	$job == 'add_gestion_traitement_nomination_enrechisement'   ||
		$job == 'add_gestion_traitement_nomination_obj'   ||
      	$job == 'edit_gestion_traitement_nomination_enrechisement'  ||
		$job == 'edit_gestion_traitement_nomination_obj'  ||
		$job == 'delete_gestion_traitement_nomination_enrechisement'  ||
		$job == 'get_gestion_traitement_nomination_obj'  ||
      	$job == 'delete_gestion_traitement_nomination_obj'){
		  
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
  
if ($job == 'get_gestion_traitement_nomination'){
	
	
	try
	{
	$PDO_query_traitement = $bdd->prepare("SELECT * FROM `nomination_acide` GROUP BY `acide_intervenant_id_nomination`");
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){	
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id = :user_id");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		if($query_temps['datee'] <> '00:00:00'){			
			
		$traitement_time = '<strong>'.$query_temps['datee'].'</strong>';

		$pieces = explode(":", $query_temps['datee']);
		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);				
		
		$jh = round($duree_decimal/8, 2);
		
		}else{$traitement_time = '<strong>X</strong>'; $jh = '<strong>X</strong>';}	
		
		$query = $bdd->prepare("SELECT count(*) FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->execute();
		$ligne_total = $query->fetchColumn();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id AND acide_nt_nomination = 1");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->execute();
		$ligne_total_nt = $query->fetchColumn();
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id AND acide_nt_nomination = 2");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->execute();
		$ligne_total_bo = $query->fetchColumn();
		$query->closeCursor();
		
		
		$functions  = '<center>';				
				
		$functions .= '<a href="NominationBiblioDetailsJour-' . $traitement['acide_intervenant_id_nomination'] . '"><span class="badge badge-primary mr-3">Affichage Journalier</span></a>';
		$functions .= '<a href="NominationBiblioCollab-'.$traitement['acide_intervenant_id_nomination'].'"><span class="badge badge-primary ">affichage des fiches traitées</span></a>';	

		$functions .= '</center>';
		
		$query = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id = :user_id");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->execute();
		$select_date = $query->fetch();
		$query->closeCursor();
			
			$date_fin = date("d-m-Y", strtotime($select_date['date_fin']));
			$date_debut = date("d-m-Y", strtotime($select_date['date_debut']));
			
		$mysql_data[] = array(				
		  "collab"  => $traitement['acide_intervenant_nomination'],
		  "count"          => $ligne_total,		  
		  "date_fin"          => $date_fin,
		  "date_debut"          => $date_debut,
		  "countbo"          => $ligne_total_bo,
		  "countnt"          => $ligne_total_nt,
		  "time"          => $traitement_time,
		  "jh"          => $jh,
		  "functions"     => $functions
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
    
}elseif ($job == 'get_gestion_traitement_nomination_enrechisement'){

	try
	{
	$PDO_query_traitement = $bdd->prepare("SELECT * FROM `nomination_acide_like` ORDER BY `nomination_acide_like_id` ASC");
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
			$code = $traitement['nomination_acide_like_mot'];
			$prefix = $traitement['nomination_acide_like_nomination'];
			if($traitement['actif'] == 0){$actif = '<span class="badge-circle badge-circle-danger mr-3">Non actif</span>';}else{$actif = '<span class="badge-circle badge-circle-success mr-3">Actif</span>';}
			$date = date_change_format($traitement['nomination_acide_like_date'],'Y-m-d','d/m/Y');
			$functions  = '';
			$functions .= '<a  href="#" id="function_edit_enrechisement" data-id="'   . $traitement['nomination_acide_like_id'] . '" data-name="' . $traitement['nomination_acide_like_nomination'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';			
			$functions .= '<a href="#" id="del_enrechisement" data-id="' . $traitement['nomination_acide_like_id'] . '" data-name="' . $traitement['nomination_acide_like_nomination'] . '"  data-doc="' . $traitement['nomination_acide_like_nomination'] . '"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
			$functions .= '';	
			  
			$mysql_data[] = array(		
			  "date" => $date,
			  "prefix" => $prefix,
			  "code" => $code,
			  "actif" => $actif,
			  "functions" => $functions
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
	    
  
}elseif ($job == 'get_gestion_traitement_nomination_obj'){

	try
	{
	$PDO_query_traitement = $bdd->prepare("SELECT * FROM `nomination_acide_obj` ORDER BY id_objectif ASC");
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
			$ligne = $traitement['nbligne_objectif'];
			$heure = $traitement['nbheure_objectif'];
			
			$date_debut = date_change_format($traitement['debut_objectf'],'Y-m-d','d/m/Y');
			$date_fin = date_change_format($traitement['fin_objectif'],'Y-m-d','d/m/Y');
			
			$datenow = date("Y-m-d");
			if($datenow <= $traitement['fin_objectif'] && $datenow >= $traitement['debut_objectf']){$actif = '<span class="badge-circle badge-circle-success mr-3">Actif</span>';}else{$actif = '<span class="badge-circle badge-circle-danger mr-3">Non actif</span>';}
			
			
			$functions  = '';
			$functions .= '<a  href="#" id="function_edit_obj" data-id="'   . $traitement['id_objectif'] . '" data-name="OBJECTIF"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';			
			$functions .= '<a href="#" id="del_obj" data-id="' . $traitement['id_objectif'] . '" data-name="OBJECTIF"  data-doc="OBJECTIF"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
			$functions .= '';	
			  
			$mysql_data[] = array(		
			  "ligne" => $ligne,
			  "heure" => $heure,
			  "date_debut" => $date_debut,
			  "date_fin" => $date_fin,
			  "actif" => $actif,
			  "functions" => $functions
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
	    
  
}elseif ($job == 'get_gestion_traitement_add_nomination_obj'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try
		{
		$query = $bdd->prepare("SELECT * FROM nomination_acide_obj WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		while ($traitement_edit = $query->fetch()){
			
			$intervalle = $traitement_edit['debut_objectf'].' - '.$traitement_edit['fin_objectif'];
			$mysql_data[] = array(
			"heure"  => $traitement_edit['nbheure_objectif'],
			"fiche"  => $traitement_edit['nbligne_objectif'],
			"intervalle"  => $intervalle
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
		$query = null;
      
    }
  
}elseif ($job == 'get_gestion_traitement_add_nomination_enrechisement'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try
		{
		$query = $bdd->prepare("SELECT * FROM nomination_acide_like WHERE nomination_acide_like_id = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		while ($traitement_edit = $query->fetch()){
			
			$mysql_data[] = array(
			"prefix"  => $traitement_edit['nomination_acide_like_nomination'],
			"mot"  => $traitement_edit['nomination_acide_like_mot']
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
		$query = null;
      
    }
  
} elseif ($job == 'add_gestion_traitement_nomination_enrechisement'){
    
		try
		{
		$query = $bdd->prepare("INSERT INTO nomination_acide_like (nomination_acide_like_date, nomination_acide_like_mot, nomination_acide_like_nomination, actif) VALUES (now(), :nomination_acide_like_mot, :nomination_acide_like_nomination, 1)");
		$query->bindParam(":nomination_acide_like_mot", $_GET['mot'], PDO::PARAM_STR);
		$query->bindParam(":nomination_acide_like_nomination", $_GET['prefix'], PDO::PARAM_STR);
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
		$bdd = null;
		$query = null;
  
  }elseif ($job == 'add_gestion_traitement_nomination_obj'){
    
		try
		{
		$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("INSERT INTO nomination_acide_obj (nbligne_objectif, nbheure_objectif, debut_objectf, fin_objectif) VALUES (:nbligne_objectif, :nbheure_objectif, :debut_objectf, :fin_objectif)");
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_INT);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_INT);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
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
		$bdd = null;
		$query = null;
  
  }elseif ($job == 'edit_gestion_traitement_nomination_obj'){
	  
	  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
   		try
		{
			$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("UPDATE nomination_acide_obj SET nbligne_objectif = :nbligne_objectif, nbheure_objectif = :nbheure_objectif, debut_objectf = :debut_objectf, fin_objectif = :fin_objectif WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		
		
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_INT);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_INT);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
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
		$bdd = null;
		$query = null;
	}
    
  } elseif ($job == 'edit_gestion_traitement_nomination_enrechisement'){
	  
	  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
   		try
		{
		
		$query = $bdd->prepare("UPDATE nomination_acide_like SET nomination_acide_like_mot = :nomination_acide_like_mot, nomination_acide_like_nomination = :nomination_acide_like_nomination WHERE nomination_acide_like_id = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":nomination_acide_like_mot", $_GET['mot'], PDO::PARAM_STR);
		$query->bindParam(":nomination_acide_like_nomination", $_GET['prefix'], PDO::PARAM_STR);
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
		$bdd = null;
		$query = null;
	}
    
  }elseif ($job == 'delete_gestion_traitement_nomination_obj'){
	  
  	if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
    	try
		{
		$query = $bdd->prepare("DELETE FROM nomination_acide_obj WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
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
		$bdd = null;
		$query = null;
	}
    
  
  }elseif ($job == 'delete_gestion_traitement_nomination_enrechisement'){
	  
  if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
    	try
		{
		$query = $bdd->prepare("DELETE FROM nomination_acide_like WHERE nomination_acide_like_id = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
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
		$bdd = null;
		$query = null;
	}
  
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