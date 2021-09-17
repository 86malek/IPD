<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_doc' ||
  		$job == 'get_doc_admin' ||
      $job == 'get_doc_form'   ||
      $job == 'add_doc'   ||
      $job == 'edit_doc'  ||
      $job == 'delete_doc'){
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
if (isset($_GET['id_team'])){$id_cat = $_GET['id_team'];}
$mysql_data = array();

if ($job != ''){
  
  $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'Erreur';
    $message = 'Connexion à la base de données impossible ' . mysqli_connect_Échec();
    $job     = '';
  }
  
  if ($job == 'get_doc'){
if(($id_cat == 2) OR ($id_cat == 8)){    
    $query = "SELECT fichiers.id_fichiers, fichiers.name_fichiers, fichiers.doc_fichiers, fichiers.doc_fichiers_traiter, fichiers.user_id, fichiers.user_name, fichiers.inser, fichiers.statut, fichiers.id_cat_fichiers, fichiers_cat.nom_cat_fichiers, fichiers.down, fichiers.up, IF(Weekday(fichiers.up) >= Weekday(fichiers.down),ROUND(CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16, 2),ROUND(CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16 -2, 2)) AS datee FROM fichiers_cat, fichiers WHERE fichiers_cat.id_cat_fichiers = fichiers.id_cat_fichiers AND fichiers.id_orga = '".$id_cat."'";
}elseif(empty($id_cat)){
	$query = "SELECT fichiers.id_fichiers, fichiers.name_fichiers, fichiers.doc_fichiers, fichiers.doc_fichiers_traiter, fichiers.user_id, fichiers.user_name, fichiers.inser, fichiers.statut, fichiers.id_cat_fichiers, fichiers_cat.nom_cat_fichiers, fichiers.down, fichiers.up, IF(Weekday(fichiers.up) >= Weekday(fichiers.down),ROUND(CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16, 2),ROUND(CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16 -2, 2)) AS datee FROM fichiers_cat, fichiers WHERE fichiers_cat.id_cat_fichiers = fichiers.id_cat_fichiers AND fichiers.id_orga <> '2' AND fichiers.id_orga <> '8'";
	
	}
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'Erreur';
      $message = 'Requete SQL en Erreur';
    } else {
      $result  = 'Ok';
      $message = 'Requete SQL OK';
      while ($doc = mysqli_fetch_array($query)){
		  
		
		$download = '<center>';   
		
		
		if (checkAdmin()) {
			
			
			if ($doc['statut'] == 'Cloturer') {
			$download .= '<a href="ftp/server/php/files/'.$doc['doc_fichiers'].'" target="_blank" class="badge badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			$download .= '<a href="ftp/server/php/files/traiter/'.$doc['doc_fichiers_traiter'].'" target=_blank class="badge badge-primary badge-rounded mb-3 mr-3" title="Ancien"><span class="iconfont iconfont-download"></span></a>';
			}else{
			$download .= '<a href="#" href="datacenter_ajout.php?mode=update&id='.$doc['id_fichiers'].'" class="btn btn-success table__cell-actions-item"><span class="iconfont iconfont-pencil"></span></a>';		
			$download .= '<a href="#" id="del" class="btn btn-danger table__cell-actions-item" data-id="' . $doc['id_fichiers'] . '" data-name="' . $doc['name_fichiers'] . '" data-doc="' . $doc['doc_fichiers'] . '"><span class="iconfont iconfont-remove"></span></a>';	
			}
			
		}else{
			
			if ($doc['down'] == '0000-00-00 00:00:00') {               
			$download .= '<a href="datacenter_download.php?id='.$doc['id_fichiers'].'" class="badge badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			}elseif($doc['user_name'] == $_SESSION['user_name']){
			$download .= '<a href="datacenter_upload.php?id='.$doc['id_fichiers'].'" class="badge badge-success badge-rounded mb-3 mr-3"><span class="iconfont iconfont-upload"></span></a>';	
			}
			
		}
		
		$download .= '</center>';
		
		if ($doc['statut'] == 'Cloturer') {
		$statut = '<center><span class="badge badge-primary">'.$doc['statut'].'</span></center>';
		}elseif ($doc['statut'] == 'En cours'){
		$statut = '<center><span class="badge badge-warning">'.$doc['statut'].'</span></center>';	
		}elseif ($doc['statut'] == 'En attente'){
		$statut = '<center><span class="badge badge-info">'.$doc['statut'].'</span></center>';	
		}elseif ($doc['statut'] == 'progression'){
		$statut = '<center><span class="badge badge-danger">En '.$doc['statut'].'</span></center>';	
		}
		
		$date = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y", strtotime($doc['inser'])).'</span></div></center>';
		
		
		$traitement = '<center><strong>'.str_replace('.', 'h:', $doc['datee'].'m').'</strong></center>';
		
        $mysql_data[] = array(
          "nom_fichier" => $doc['name_fichiers'],
          "user_fichier"    => $doc['user_name'],
          "insertion_fichier"  => $date,
		  "statut_fichier"  => $statut,
		  "cat_fichier" => $doc['nom_cat_fichiers'],
		  "download"     => $download
        );
      }
    }
    
  } elseif($job == 'get_doc_admin'){
    
    $query = "SELECT fichiers.id_fichiers, fichiers.nb_ligne, fichiers.name_fichiers, fichiers.doc_fichiers, fichiers.doc_fichiers_traiter, fichiers.user_id, fichiers.user_name, fichiers.inser, fichiers.statut, fichiers.id_cat_fichiers, fichiers_cat.nom_cat_fichiers, fichiers.id_demandeur_fichiers, fichiers_demandeur.nom_demandeur_fichiers, fichiers.down, fichiers.up, IF(Weekday(fichiers.up) >= Weekday(fichiers.down),ROUND(CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16, 2),CONCAT(hour(TIMEDIFF(fichiers.up,fichiers.down)),'.',MINUTE(TIMEDIFF(fichiers.up,fichiers.down))) - DATEDIFF(fichiers.up,fichiers.down)*16 -2) AS datee, organigramme.nomination_organigramme FROM fichiers_demandeur, fichiers_cat, fichiers, organigramme WHERE fichiers_demandeur.id_demandeur_fichiers = fichiers.id_demandeur_fichiers AND fichiers_cat.id_cat_fichiers = fichiers.id_cat_fichiers AND fichiers.id_orga = organigramme.id_organigramme";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'Erreur';
      $message = 'Requete SQL en Erreur';
    } else {
      $result  = 'Ok';
      $message = 'Requete SQL OK';
      while ($doc = mysqli_fetch_array($query)){
		  
        
		
		$download = '<center>';   
		
		
		if (checkAdmin()) {
			
			
			if ($doc['statut'] == 'Cloturer') {
			$download .= '<a href="ftp/server/php/files/'.$doc['doc_fichiers'].'" target="_blank" class="badge badge-primary badge-rounded mb-3 mr-3">Source</a>';
			$download .= '<a href="ftp/server/php/files/traiter/'.$doc['doc_fichiers_traiter'].'" target=_blank class="badge badge-success badge-rounded mb-3 mr-3" title="Ancien">Traité</a>';
			}elseif ($doc['statut'] == 'progression') {
			$download .= '<a href="ftp/server/php/files/'.$doc['doc_fichiers'].'" target="_blank" class="badge badge-primary badge-rounded mb-3 mr-3">Source</a>';
			$download .= '<a href="ftp/server/php/files/traiter/'.$doc['doc_fichiers_traiter'].'" target=_blank class="badge badge-success badge-rounded mb-3 mr-3" title="Ancien">Traité</a>';
			}else{
			$download .= '<a href="datacenter_ajout.php?mode=update&id='.$doc['id_fichiers'].'" class="badge badge-sm badge-success badge-rounded mb-3 mr-3"><span class="iconfont iconfont-pencil"></span></a>';		
			$download .= '<a href="#" id="del" class="badge badge-sm badge-danger badge-rounded mb-3 mr-3" data-id="' . $doc['id_fichiers'] . '" data-name="' . $doc['name_fichiers'] . '" data-doc="' . $doc['doc_fichiers'] . '"><span class="iconfont iconfont-remove"></span></a>';	
			}
			
		}else{
			
			if ($doc['down'] == '0000-00-00 00:00:00') {               
			$download .= '<a href="datacenter_download.php?id='.$doc['id_fichiers'].'" class="badge badge-sm badge-primary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-download"></span></a>';
			}elseif($doc['user_name'] == $_SESSION['user_name']){
			$download .= '<a href="datacenter_upload.php?id='.$doc['id_fichiers'].'" class="badge badge-sm badge-scondary badge-rounded mb-3 mr-3"><span class="iconfont iconfont-upload"></span></a>';	
			}
			
		}
		
		$download .= '</center>';
		
		if ($doc['statut'] == 'Cloturer') {
		$statut = '<center><span class="badge badge-success">'.$doc['statut'].'</span></center>';
		}elseif ($doc['statut'] == 'En cours'){
		$statut = '<center><span class="badge badge-warning">'.$doc['statut'].'</span></center>';	
		}elseif ($doc['statut'] == 'En attente'){
		$statut = '<center><span class="badge badge-info">'.$doc['statut'].'</span></center>';	
		}elseif ($doc['statut'] == 'progression'){
		$statut = '<center><span class="badge badge-info">En '.$doc['statut'].'</span></center>';	
		}
		
		$date = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y", strtotime($doc['inser'])).'</span></div></center>';
		
		if ($doc['up'] == '0000-00-00 00:00:00') {
		
		$date_up = '<center><div class="table__cell-widget"><span class="table__cell-widget-name"></span></div></center>';
		}else{
		
		$date_up = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i:s", strtotime($doc['up'])).'</span></div></center>';	
		}
		
		
		if ($doc['down'] == '0000-00-00 00:00:00') {
		$date_down = '<center><div class="table__cell-widget"><span class="table__cell-widget-name"></span></div></center>';
		
		}else{
		$date_down = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i:s", strtotime($doc['down'])).'</span></div></center>';
		
		}
		
		if ($doc['datee'] == NULL) {
		$traitement = '<center><strong></strong></center>';
		}else{
		$traitement = '<center><strong>'.str_replace('.', 'h:', $doc['datee'].'m').'</strong></center>';
		
		}
		
		$ligne = '<center><strong>'.$doc['nb_ligne'].'</strong></center>';
		
        $mysql_data[] = array(
          "nom_fichier" => $doc['name_fichiers'],
		  "nb_ligne" => $ligne,
          "user_fichier"    => $doc['user_name'],
		  "statut_fichier"  => $statut,
		  "down"  => $date_down,
		  "up"  => $date_up,
		  "cat_fichier" => $doc['nom_cat_fichiers'],
		  "demandeur_fichier" => $doc['nom_demandeur_fichiers'],
		  "equipe" => $doc['nomination_organigramme'],
		  "traitement" => $traitement,
		  "download"     => $download
        );
      }
    }
    
  }elseif ($job == 'get_doc_form'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "SELECT collaborateurs.id_collab, collaborateurs.matricule_collaborateurs, collaborateurs.nom_collaborateurs, collaborateurs.prenom_collaborateurs, collaborateurs.anciente_collaborateurs, collaborateurs.email_collaborateurs, collaborateurs.coordinateur, organigramme.id_organigramme, collaborateurs.ip_collaborateurs, collaborateurs.somme_abs_collaborateurs  FROM collaborateurs, organigramme WHERE collaborateurs.id_organi = organigramme.id_organigramme AND collaborateurs.id_collab = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "matricule"          => $company['matricule_collaborateurs'],
            "nom"  => $company['nom_collaborateurs'],
            "prenom"    => $company['prenom_collaborateurs'],
            "date"       => $company['anciente_collaborateurs'],
			"coordinateur"       => $company['coordinateur'],
			"email"       => $company['email_collaborateurs'],
			"ip"       => $company['ip_collaborateurs'],
			"somme"       => $company['somme_abs_collaborateurs'],
			"Poste"       => $company['id_organigramme']
          );
        }
      }
    }
  
  } elseif ($job == 'add_doc'){
    
    $query = "INSERT INTO collaborateurs SET ";
    if (isset($_GET['matricule']))         { $query .= "matricule_collaborateurs         = '" . mysqli_real_escape_string($db_connection, $_GET['matricule'])         . "', "; }
    if (isset($_GET['nom'])) { $query .= "nom_collaborateurs = '" . mysqli_real_escape_string($db_connection, $_GET['nom']) . "', "; }
    if (isset($_GET['prenom']))   { $query .= "prenom_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
	if (isset($_GET['date']))   { $query .= "anciente_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['date'])   . "', "; }
	if (isset($_GET['email']))   { $query .= "email_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['email'])   . "', "; }
	if (isset($_GET['ip']))   { $query .= "ip_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['ip'])   . "', "; }
	if (isset($_GET['coordinateur']))   { $query .= "coordinateur   = '" . mysqli_real_escape_string($db_connection, $_GET['coordinateur'])   . "', "; }else{$query .= "coordinateur   = '0',";}
	if (isset($_GET['somme']))   { $query .= "somme_abs_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['somme'])   . "', "; }else{$query .= "somme_abs_collaborateurs   = '0',";}
	if (isset($_GET['Poste']))   { $query .= "id_organi   = '" . mysqli_real_escape_string($db_connection, $_GET['Poste'])   . "' "; }else{$query .= "id_organi   = '0'";}
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_doc'){
    
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
      $query = "UPDATE collaborateurs SET ";
      if (isset($_GET['matricule']))         { $query .= "matricule_collaborateurs         = '" . mysqli_real_escape_string($db_connection, $_GET['matricule'])         . "', "; }
      if (isset($_GET['nom'])) { $query .= "nom_collaborateurs = '" . mysqli_real_escape_string($db_connection, $_GET['nom']) . "', "; }
      if (isset($_GET['prenom']))   { $query .= "prenom_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
	  if (isset($_GET['email']))   { $query .= "email_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['email'])   . "', "; }
	  if (isset($_GET['ip']))   { $query .= "ip_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['ip'])   . "', "; }
      if (isset($_GET['date']))      { $query .= "anciente_collaborateurs      = '" . mysqli_real_escape_string($db_connection, $_GET['date'])      . "', "; }
	  if (isset($_GET['coordinateur']))   { $query .= "coordinateur   = '" . mysqli_real_escape_string($db_connection, $_GET['coordinateur'])   . "', "; }else{$query .= "coordinateur   = '0',";}
	  if (isset($_GET['somme']))   { $query .= "somme_abs_collaborateurs   = '" . mysqli_real_escape_string($db_connection, $_GET['somme'])   . "', "; }else{$query .= "somme_abs_collaborateurs   = '0',";}
	  if (isset($_GET['Poste']))   { $query .= "id_organi   = '" . mysqli_real_escape_string($db_connection, $_GET['Poste'])   . "' "; }else{$query .= "id_organi   = '0'";}
      $query .= "WHERE id_collab = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_doc'){
  
  		
		
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
      $query = "DELETE FROM fichiers WHERE 	id_fichiers = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query); 			
							
      if (!$query){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
		  
		
		unlink("../ftp/server/php/files/".$_GET['cat']);
		
        $result  = 'success';
        $message = 'Succès de requête';
      }
    }
  
  }
  
  mysqli_close($db_connection);

}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;


?>