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
}
$job = '';
$id  = '';

if (isset($_GET['job'])){
	
  $job = $_GET['job'];
  
  if ($job == 'get_doc' ||
      $job == 'get_doc_add' ||
      $job == 'edit_doc' ||
      $job == 'delete_doc'){
		  
		if (isset($_GET['id'])){
			$id = $_GET['id'];
			if (!is_numeric($id)){
			$id = '';
			}
    	}
		
		if (isset($_GET['cat'])){
			$cat = $_GET['cat'];}
			
		if (isset($_GET['intervalle'])){
		  $date = $_GET['intervalle'];
		}
		
  }else{$job = '';}
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_doc'){
	  
    /*try 
	{*/
	$query_global = $bdd->prepare("SELECT * FROM `webmaster_doc` ORDER BY `doc_id` DESC");
	$query_global->execute();
	
	while ($doc = $query_global->fetch()){
		
		
		
		$functions  = '';				
		
		$functions .= '<a href="WebsAjout-update-' . $doc['doc_id'] . '.html"><span class="badge badge-shamrock mb-3 mr-3">Modifier fichier</span></a>';
		$functions .= '<a href="#" id="del" data-id="' . $doc['doc_id'] . '" data-name="' . $doc['doc_nom'] . '"  data-doc="' . $doc['doc'] . '"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';		
		$functions .= '<a href="#" id="function_edit" data-id="'   . $doc['doc_id'] . '" data-name="Lot : ' . $doc['doc_nom'] . '"><span class="badge badge-lasur"><span class="icon iconfont iconfont-pencil"></span></span></a>';			
		$functions .= '';		
		
		$taille = round($doc['doc_taille']/1024);
		$taillefinal = $taille.' Ko';
		
		$fichier = ''.$doc['doc_nom'].'';
		$ajout = date("d/m/Y", strtotime($doc['doc_date_ajout']));
		if($doc['doc_date_modification'] == NULL){$modif = '';}else{$modif = date("d/m/Y", strtotime($doc['doc_date_modification']));}
		$down = '<a target="_blank" href="module/webmaster/upload/' . $doc['doc'] . '"><span class="badge badge-primary mb-3 mr-3">Téléchargement</span></a>';
		if($doc['doc_actif'] == 1){$actif = '<span class="badge-circle badge-circle-success mr-3"></span>';}else{$actif = '<span class="badge-circle badge-circle-danger mr-3"></span>';}
		$mysql_data[] = array(
			
		"id"  => $doc['doc_id'],
		  "nom"  => $fichier,
		  "type"  => $doc['doc_type'],
		  "taille"  => $taillefinal,
		  "actif"  => $actif,
		  "ajout"  => $ajout,
		  "modif" => $modif,
		  "down" => $down,
          "functions"     => $functions
        );
		
	}
	  
    $query_global->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	/*}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;   */
    
  } elseif ($job == 'get_doc_add'){
    
   	if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
		$query = $bdd->prepare("SELECT * FROM webmaster_doc WHERE doc_id = :doc_id");
		$query->bindParam(":doc_id", $id, PDO::PARAM_INT);
		$query->execute();
		$doc = $query->fetch();		
		$query->closeCursor();
		$mysql_data[] = array("nom"  => $doc['doc_nom']);
      	$result  = 'success';
		$message = 'Succès de requête';
    }
  
  }elseif ($job == 'edit_doc'){    
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {		
		$query = $bdd->prepare("UPDATE webmaster_doc SET doc_nom = :doc_nom WHERE doc_id = :doc_id");
		$query->bindParam(":doc_id", $id, PDO::PARAM_INT);
		$query->bindParam(":doc_nom", $_GET['nom'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';
    }    
  }elseif ($job == 'delete_doc'){
  	
	if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{		
		
		$query_del = $bdd->prepare("DELETE FROM webmaster_doc WHERE doc_id = :id");	
		$query_del->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del->execute();
		$query_del->closeCursor();
		unlink("../../upload/".$cat);
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