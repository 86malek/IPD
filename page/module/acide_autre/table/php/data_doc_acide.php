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
  if ($job == 'get_doc_acide' ||
		$job == 'get_doc_admin_acide' ||
		$job == 'get_doc_form_acide'   ||
		$job == 'add_doc_acide'   ||
		$job == 'edit_doc_acide'  ||
		$job == 'delete_doc_acide'){
		  if (isset($_GET['cat'])){
		$cat = $_GET['cat'];}
    	if (isset($_GET['id'])){
      	$id = $_GET['id'];
      	if (!is_numeric($id)){
        $id = '';}}
		
		if (isset($_GET['id_cat'])){
      	$id_cat = $_GET['id_cat'];
      	if (!is_numeric($id_cat)){
        $id_cat = '';}}
		
    
  }else{$job = '';}
}

$mysql_data = array();

if ($job != ''){
	  
  if ($job == 'get_doc_acide'){
    
	try 
	{
	$query = $bdd->prepare("SELECT autre_acide_fichier.id_autre_acide_fichier, autre_acide_fichier.autre_acide_fichier_nom, autre_acide_fichier.autre_acide_fichier_doc, autre_acide_fichier.autre_acide_fichier_doc_traiter, autre_acide_fichier.user_id, autre_acide_fichier.user_name, autre_acide_fichier.autre_acide_fichier_date_insertion, autre_acide_fichier.autre_acide_fichier_statut, autre_acide_fichier.id_autre_acide_fichier_categorie, autre_acide_fichier_categorie.autre_acide_fichier_categorie_nom, autre_acide_fichier.autre_acide_fichier_date_down, autre_acide_fichier.autre_acide_fichier_date_upload FROM autre_acide_fichier_categorie, autre_acide_fichier WHERE autre_acide_fichier_categorie.id_autre_acide_fichier_categorie = autre_acide_fichier.id_autre_acide_fichier_categorie AND autre_acide_fichier.id_autre_acide_fichier_categorie = :id_cat_fichiers");
	$query->bindParam(":id_cat_fichiers", $id_cat, PDO::PARAM_INT);
	$query->execute();
	
	while ($doc = $query->fetch()){
		
		 
		  
		
		$download = '';   
		
		
		if (checkAdmin()) {
			
			
			if ($doc['autre_acide_fichier_statut'] == 'Cloturer') {
			$download .= '<a href="ftp/server/php/files/'.$doc['autre_acide_fichier_doc'].'" target="_blank" class="badge badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			$download .= '<a href="ftp/server/php/files/traiter/'.$doc['autre_acide_fichier_doc_traiter'].'" target=_blank class="badge badge-primary badge-rounded mb-3 mr-3" title="Ancien"><span class="iconfont iconfont-download"></span></a>';
			}else{
			$download .= '<a href="#" href="datacenter_acide_ajout.php?mode=update&id='.$doc['id_autre_acide_fichier'].'" class="btn btn-success table__cell-actions-item"><span class="iconfont iconfont-pencil"></span></a>';		
			$download .= '<a href="#" id="del" class="btn btn-danger table__cell-actions-item" data-id="' . $doc['id_autre_acide_fichier'] . '" data-name="' . $doc['autre_acide_fichier_nom'] . '" data-doc="' . $doc['autre_acide_fichier_doc'] . '"><span class="iconfont iconfont-remove"></span></a>';	
			}
			
		}else{
			
			if ($doc['autre_acide_fichier_date_down'] == '0000-00-00 00:00:00') {               
			$download .= '<a href="DataAcideDownload-'.$doc['id_autre_acide_fichier'].'.html" class="badge badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			}elseif($doc['user_id'] == $_SESSION['user_id']){
			$download .= '<a href="DataAcideUpload-'.$doc['id_autre_acide_fichier'].'-'.$doc['id_autre_acide_fichier_categorie'].'.html" class="badge badge-success badge-rounded mb-3 mr-3"><span class="iconfont iconfont-upload"></span></a>';	
			}
			
		}
		
		$download .= '';
		
		
		if ($doc['autre_acide_fichier_statut'] == 1) {
		$statut = '<span class="badge badge-primary">CLOTURÉ</span>';
		}elseif ($doc['autre_acide_fichier_statut'] == 2){
		$statut = '<span class="badge badge-warning">EN COURS</span>';	
		}elseif ($doc['autre_acide_fichier_statut'] == 3){
		$statut = '<span class="badge badge-info">EN ATTENTE</span>';	
		}elseif ($doc['autre_acide_fichier_statut'] == 4){
		$statut = '<span class="badge badge-warning">EN PROGRESSION</span>';	
		}
		
		$date = '<div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y", strtotime($doc['autre_acide_fichier_date_insertion'])).'</span></div>';		
		
        $mysql_data[] = array(
          "nom_fichier" => $doc['autre_acide_fichier_nom'],
          "user_fichier"    => $doc['user_name'],
          "insertion_fichier"  => $date,
		  "statut_fichier"  => $statut,
		  "cat_fichier" => $doc['autre_acide_fichier_categorie_nom'],
		  "download"     => $download
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
    
  } elseif($job == 'get_doc_admin_acide'){
    
	try 
	{
	$query = $bdd->prepare("SELECT autre_acide_fichier.id_autre_acide_fichier, autre_acide_fichier.autre_acide_fichier_nb_ligne, autre_acide_fichier.autre_acide_fichier_nom, autre_acide_fichier.autre_acide_fichier_doc, autre_acide_fichier.autre_acide_fichier_doc_traiter, autre_acide_fichier.user_id, autre_acide_fichier.user_name, autre_acide_fichier.autre_acide_fichier_date_insertion, autre_acide_fichier.autre_acide_fichier_statut, autre_acide_fichier.id_autre_acide_fichier_categorie, autre_acide_fichier_categorie.autre_acide_fichier_categorie_nom, autre_acide_fichier.autre_acide_fichier_date_down, autre_acide_fichier.autre_acide_fichier_date_upload, organigramme.nomination_organigramme FROM autre_acide_fichier_categorie, autre_acide_fichier, organigramme WHERE autre_acide_fichier_categorie.id_autre_acide_fichier_categorie = autre_acide_fichier.id_autre_acide_fichier_categorie AND autre_acide_fichier.id_organigramme = organigramme.id_organigramme");
	$query->execute();
	
	while ($doc = $query->fetch()){       
		
		
		if (checkAdmin()) {
			
		$download = '';		
			if ($doc['autre_acide_fichier_statut'] == 1) {
			$download .= '<a href="module/acide_autre/upload/'.$doc['autre_acide_fichier_doc'].'" target="_blank" class="badge badge-primary mb-3 mr-3">Source</a>';
			$download .= '<a href="module/acide_autre/upload/traiter/'.$doc['autre_acide_fichier_doc_traiter'].'" target=_blank class="badge badge-success mb-3 mr-3" title="Ancien">Traité</a>';
			}elseif ($doc['autre_acide_fichier_statut'] == 4) {
			$download .= '<a href="module/acide_autre/upload/'.$doc['autre_acide_fichier_doc'].'" target="_blank" class="badge badge-primary mb-3 mr-3">Source</a>';
			$download .= '<a href="module/acide_autre/upload/traiter/'.$doc['autre_acide_fichier_doc_traiter'].'" target=_blank class="badge badge-success mb-3 mr-3" title="Ancien">Traité</a>';
			}elseif ($doc['autre_acide_fichier_statut'] == 2) {
			$download .= '<a href="#" class="badge badge-sm badge-danger mb-3 mr-3">IMPOSSIBLE LORS DU TRAITEMENT</a>';
			}else{
			$download .= '<a href="DataAcideAjout-update-'.$doc['id_autre_acide_fichier'].'.html" class="badge badge-sm badge-success mb-3 mr-3"><span class="iconfont iconfont-pencil"></span></a>';		
			$download .= '<a href="#" id="del" class="badge badge-sm badge-danger mb-3 mr-3" data-id="' . $doc['id_autre_acide_fichier'] . '" data-name="' . $doc['autre_acide_fichier_nom'] . '" data-doc="' . $doc['autre_acide_fichier_doc'] . '"><span class="iconfont iconfont-remove"></span></a>';	
			}
			
		}else{
			
			if ($doc['autre_acide_fichier_date_down'] == '0000-00-00 00:00:00') {               
			$download .= '<a href="datacenter_acide_download.php?id='.$doc['id_autre_acide_fichier'].'" class="badge badge-sm badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			}elseif($doc['user_name'] == $_SESSION['user_name']){
			$download .= '<a href="datacenter_acide_upload.php?id='.$doc['id_autre_acide_fichier'].'" class="badge badge-sm badge-scondary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-upload"></span></a>';	
			}
			
		}
		
		$download .= '';
		
		if ($doc['autre_acide_fichier_statut'] == 1) {
		$statut = '<span class="badge badge-primary">CLOTURÉ</span>';
		}elseif ($doc['autre_acide_fichier_statut'] == 2){
		$statut = '<span class="badge badge-warning">EN COURS</span>';	
		}elseif ($doc['autre_acide_fichier_statut'] == 3){
		$statut = '<span class="badge badge-info">EN ATTENTE</span>';	
		}elseif ($doc['autre_acide_fichier_statut'] == 4){
		$statut = '<span class="badge badge-warning">EN PROGRESSION</span>';	
		}
		
		$date = '<div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y", strtotime($doc['autre_acide_fichier_date_insertion'])).'</span></div>';
		
		if ($doc['autre_acide_fichier_date_upload'] == '0000-00-00 00:00:00') {
		
		$date_up = '<div class="table__cell-widget"><span class="table__cell-widget-name"></span></div>';
		}else{
		
		$date_up = '<div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i:s", strtotime($doc['autre_acide_fichier_date_upload'])).'</span></div>';	
		}
		
		
		if ($doc['autre_acide_fichier_date_down'] == '0000-00-00 00:00:00') {
		$date_down = '<div class="table__cell-widget"><span class="table__cell-widget-name"></span></div>';
		
		}else{
		$date_down = '<div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i:s", strtotime($doc['autre_acide_fichier_date_down'])).'</span></div>';
		
		}
		
		
		
		
		if($doc['autre_acide_fichier_date_down'] == '0000-00-00 00:00:00' || $doc['autre_acide_fichier_date_upload'] == '0000-00-00 00:00:00'){
		$traitement ='<strong>X</strong>';
		$jh ='<strong>X</strong>';
		}else{				
		$go = get_working_hours($doc['autre_acide_fichier_date_down'],$doc['autre_acide_fichier_date_upload']);
		$traitement = '<strong>'.$go.'</strong>';
		$PDO_query_jh = $bdd->prepare("SELECT * FROM autre_acide_fichier_heure ORDER BY id_autre_acide_fichier_heure DESC LIMIT 0, 1");
		$PDO_query_jh->execute();
		$jhh = $PDO_query_jh->fetch();			
		$jh_heure = $jhh['objectif_autre_acide_fichier_heure'];
		$pieces = explode(":", $go);		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);				
		$jh = $duree_decimal/$jh_heure;				
		$jh = round($jh, 1);		
		$PDO_query_jh->closeCursor();			
		} 
		
		
		 
		
		$ligne = '<strong>'.$doc['autre_acide_fichier_nb_ligne'].'</strong>';
		
        $mysql_data[] = array(
          "nom_fichier" => $doc['autre_acide_fichier_nom'],
		  "nb_ligne" => $ligne,
          "user_fichier"    => $doc['user_name'],
		  "statut_fichier"  => $statut,
		  "down"  => $date_down,
		  "up"  => $date_up,
		  "cat_fichier" => $doc['autre_acide_fichier_categorie_nom'],
		  "equipe" => $doc['nomination_organigramme'],
		  "traitement" => $traitement,
		  "jh" => $jh,
		  "download"     => $download
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
    
} elseif ($job == 'get_doc_form'){  
} elseif ($job == 'add_doc'){ 
} elseif ($job == 'edit_doc'){   
} elseif ($job == 'delete_doc_acide'){  
	  
	if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{
		$query_del_niveau1 = $bdd->prepare("DELETE FROM autre_acide_fichier WHERE id_autre_acide_fichier = :id");	
		$query_del_niveau1->bindParam(":id", $id, PDO::PARAM_INT);
		$query_del_niveau1->execute();
		$query_del_niveau1->closeCursor();
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