<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_gestion_traitement_hb' ||
      $job == 'get_gestion_traitement_add_hb'   ||
      $job == 'add_gestion_traitement_hb'   ||
      $job == 'edit_gestion_traitement_hb'  ||
      $job == 'delete_gestion_traitement_hb'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
	
	if (isset($_GET['id_import'])){
      $id_import = $_GET['id_import'];
    }
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){
  
  $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Connexion à la base de données impossible : ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_gestion_traitement_hb'){
	
    $query_traitement = "SELECT `acide_id_synthese`,`acide_debut_synthese`,`acide_fin_synthese`,`acide_intervenant_synthese`,`acide_orga_nomination`, IF(Weekday(`acide_fin_synthese`) >= Weekday(`acide_debut_synthese`),ROUND(CONCAT(hour(TIMEDIFF(`acide_fin_synthese`,`acide_debut_synthese`)),'.',MINUTE(TIMEDIFF(`acide_fin_synthese`,`acide_debut_synthese`))) - DATEDIFF(`acide_fin_synthese`,`acide_debut_synthese`)*16, 2),ROUND(CONCAT(hour(TIMEDIFF(`acide_fin_synthese`,`acide_debut_synthese`)),'.',MINUTE(TIMEDIFF(`acide_fin_synthese`,`acide_debut_synthese`))) - DATEDIFF(`acide_fin_synthese`,`acide_debut_synthese`)*16 -2, 2)) AS datee FROM `acide_synthese_hb` ORDER BY `acide_id_synthese`  DESC";	
	
    $query_traitement = mysqli_query($db_connection, $query_traitement);
    if (!$query_traitement){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($traitement = mysqli_fetch_array($query_traitement)){
		  
        
		
		
		$time = '<center><strong>'.str_replace('.', 'h:', $traitement['datee'].'min').'</strong></center>';
		
		$date_debut = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i", strtotime($traitement['acide_debut_synthese'])).'</span></div></center>';
		$date_fin = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i", strtotime($traitement['acide_fin_synthese'])).'</span></div></center>';	
		$jh = (get_nb_open_days($traitement['acide_debut_synthese'], $traitement['acide_fin_synthese'])+1)*0.50;
		
		
		$query_count = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']."";
		$query_count = mysqli_query($db_connection, $query_count);
		if (!$query_count){
		  $result  = 'error';
		  $message = 'Échec de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
		}
		$rowcount = mysqli_num_rows($query_count);
		$rowcount = '<center><span class="badge badge-buttercup badge-rounded mb-3 mr-3">'.$rowcount.'</span></center>';
		
		$query_count_ajout = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']." AND acide_statut_nomination = 'Ajout'";
		$query_count_ajout = mysqli_query($db_connection, $query_count_ajout);
		if (!$query_count_ajout){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountajout = mysqli_num_rows($query_count_ajout);
		$rowcountajout = '<center><span class="badge badge-sm badge-shamrock badge-rounded mb-3 mr-3">'.$rowcountajout.'</span></center>';
		
		$query_count_modif = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']." AND acide_statut_nomination = 'Modification'";
		$query_count_modif = mysqli_query($db_connection, $query_count_modif);
		if (!$query_count_modif){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountmodif = mysqli_num_rows($query_count_modif);
		$rowcountmodif = '<center><span class="badge badge-sm badge-info badge-rounded mb-3 mr-3">'.$rowcountmodif.'</span></center>';
		
		$query_count_supp = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']." AND acide_statut_nomination = 'Suppression'";
		$query_count_supp = mysqli_query($db_connection, $query_count_supp);
		if (!$query_count_supp){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountsupp = mysqli_num_rows($query_count_supp);
		$rowcountsupp = '<center><span class="badge badge-sm badge-bittersweet badge-rounded mb-3 mr-3">'.$rowcountsupp.'</span></center>';
		
		$query_count_nt = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']." AND acide_nt_nomination = 1";
		$query_count_nt = mysqli_query($db_connection, $query_count_nt);
		if (!$query_count_nt){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountnt = mysqli_num_rows($query_count_nt);
		$rowcountnt = '<center><span class="badge badge-sm badge-danger badge-rounded mb-3 mr-3">'.$rowcountnt.'</span></center>';
		
		$query_count_bo = "SELECT * FROM acide_hb WHERE acide_id_synthese = ".$traitement['acide_id_synthese']." AND acide_nt_nomination = 2";
		$query_count_bo = mysqli_query($db_connection, $query_count_bo);
		if (!$query_count_bo){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountbo = mysqli_num_rows($query_count_bo);
		$rowcountbo = '<center><span class="badge badge-sm badge-success badge-rounded mb-3 mr-3">'.$rowcountbo.'</span></center>';
		
			
		  
        $mysql_data[] = array(
		  "collab"  => $traitement['acide_intervenant_synthese'],
          "datedebut"          => $date_debut,
		  "datefin"          => $date_fin,
		  "count"          => $rowcount,
		  "countajout"          => $rowcountajout,
		  "countmodif"          => $rowcountmodif,
		  "countsupp"          => $rowcountsupp,
		  "countbo"          => $rowcountbo,
		  "time"          => $time,
		  "jh"          => $jh
        );
      }
    }
    
  } elseif ($job == 'get_traitement_add_hb'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $traitement = "SELECT * FROM acide_hb WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $traitement = mysqli_query($db_connection, $traitement);
      if (!$traitement){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
        while ($traitement_edit = mysqli_fetch_array($traitement)){
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
		  "bo"  => $traitement_edit['acide_bo_nomination'],
		  "nt"  => $traitement_edit['acide_nt_nomination']
          );
        }
      }
    }
  
  } elseif ($job == 'add_traitement_hb'){
    
	$query_identifiant = "SELECT max(acide_id_synthese) AS max FROM acide_synthese_hb WHERE ";
	if (isset($_GET['user']))         { $query_identifiant .= "	`acide_intervenant_synthese`         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "' "; }
	$query_identifiant .= "AND acide_fin_synthese = '0000-00-00 00:00:00'";
	
	$query_identifiant = mysqli_query($db_connection, $query_identifiant);
	$query_identifiant = mysqli_fetch_array($query_identifiant);
	
    $query = "INSERT INTO acide_hb SET acide_date_nomination = now(),";
	$query .= "	acide_id_synthese         = '".$query_identifiant['max']."', ";
	if (isset($_GET['user']))         { $query .= "	acide_intervenant_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "', "; }
    if (isset($_GET['publication']))         { $query .= "acide_publication_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['publication'])         . "', "; }
    if (isset($_GET['rs'])) { $query .= "acide_rs_nomination = '" . mysqli_real_escape_string($db_connection, $_GET['rs']) . "', "; }
    if (isset($_GET['siret']))   { $query .= "acide_siret_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['siret'])   . "', "; }
	if (isset($_GET['title']))   { $query .= "acide_civilite_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['title'])   . "', "; }
	if (isset($_GET['nom']))   { $query .= "acide_nom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])   . "', "; }
	if (isset($_GET['prenom']))   { $query .= "acide_prenom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
	if (isset($_GET['fonction']))   { $query .= "acide_fe_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['fonction'])   . "', "; }
	if (isset($_GET['statut']))   { $query .= "acide_statut_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])   . "', "; }
	if (isset($_GET['ancienne']))   { $query .= "acide_acienne_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['ancienne'])   . "', "; }
	if (isset($_GET['bo']))   { $query .= "acide_bo_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['bo'])   . "', "; }
	if (isset($_GET['nt']))   { $query .= "acide_nt_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nt'])   . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_traitement_hb'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "UPDATE acide_hb SET ";
		if (isset($_GET['publication']))         { $query .= "acide_publication_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['publication'])         . "', "; }
		if (isset($_GET['rs'])) { $query .= "acide_rs_nomination = '" . mysqli_real_escape_string($db_connection, $_GET['rs']) . "', "; }
		if (isset($_GET['siret']))   { $query .= "acide_siret_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['siret'])   . "', "; }
		if (isset($_GET['title']))   { $query .= "acide_civilite_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['title'])   . "', "; }
		if (isset($_GET['nom']))   { $query .= "acide_nom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])   . "', "; }
		if (isset($_GET['prenom']))   { $query .= "acide_prenom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
		if (isset($_GET['fonction']))   { $query .= "acide_fe_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['fonction'])   . "', "; }
		if (isset($_GET['statut']))   { $query .= "acide_statut_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])   . "', "; }
		if (isset($_GET['ancienne']))   { $query .= "acide_acienne_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['ancienne'])   . "', "; }
		if (isset($_GET['bo']))   { $query .= "acide_bo_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['bo'])   . "', "; }else{$query .= "acide_bo_nomination   = '0',";}
		if (isset($_GET['nt']))   { $query .= "acide_nt_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nt'])   . "' "; }else{$query .= "acide_nt_nomination   = '0'";}
		  $query .= "WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
		  $query  = mysqli_query($db_connection, $query);
      if (!$query){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_gestion_traitement_hb'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM acide_synthese WHERE acide_id_synthese = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
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