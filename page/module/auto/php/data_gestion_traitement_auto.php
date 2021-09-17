<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_gestion_traitement_auto' ||
      $job == 'get_gestion_traitement_add_auto'   ||
      $job == 'add_gestion_traitement_auto'   ||
      $job == 'edit_gestion_traitement_auto'  ||
      $job == 'delete_gestion_traitement_auto'){
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
  
  if ($job == 'get_gestion_traitement_auto'){
	
    $query_traitement = "SELECT `auto_id_synthese`,`auto_debut_synthese`,`auto_fin_synthese`,`auto_intervenant_synthese`,`auto_orga`, IF(Weekday(`auto_fin_synthese`) >= Weekday(`auto_debut_synthese`),ROUND(CONCAT(hour(TIMEDIFF(`auto_fin_synthese`,`auto_debut_synthese`)),'.',MINUTE(TIMEDIFF(`auto_fin_synthese`,`auto_debut_synthese`))) - DATEDIFF(`auto_fin_synthese`,`auto_debut_synthese`)*16, 2),ROUND(CONCAT(hour(TIMEDIFF(`auto_fin_synthese`,`auto_debut_synthese`)),'.',MINUTE(TIMEDIFF(`auto_fin_synthese`,`auto_debut_synthese`))) - DATEDIFF(`auto_fin_synthese`,`auto_debut_synthese`)*16 -2, 2)) AS datee FROM `auto_synthese` ORDER BY `auto_id_synthese`  DESC";	
	
    $query_traitement = mysqli_query($db_connection, $query_traitement);
    if (!$query_traitement){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
	  
      while ($traitement = mysqli_fetch_array($query_traitement)){
		  
        
		
		
		$time = '<center><strong>'.str_replace('.', 'h:', $traitement['datee'].'min').'</strong></center>';
		
		$date_debut = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i", strtotime($traitement['auto_debut_synthese'])).'</span></div></center>';
		if($traitement['auto_fin_synthese'] == '0000-00-00 00:00:00'){
		$date_fin = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">En attente</span></div></center>';
		}else{$date_fin = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y H:i", strtotime($traitement['auto_fin_synthese'])).'</span></div></center>';}
		$jh = (get_nb_open_days($traitement['auto_debut_synthese'], $traitement['auto_fin_synthese'])+1)*0.50;
		
		
		$query_count = "SELECT * FROM auto_traitement WHERE auto_id_synthese = ".$traitement['auto_id_synthese']."";
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
		
		$query_count_ko = "SELECT * FROM auto_traitement WHERE auto_id_synthese = ".$traitement['auto_id_synthese']." AND statut_auto = 'KO'";
		$query_count_ko = mysqli_query($db_connection, $query_count_ko);
		if (!$query_count_ko){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountko = mysqli_num_rows($query_count_ko);
		$rowcountko = '<center><span class="badge badge-sm badge-shamrock badge-rounded mb-3 mr-3">'.$rowcountko.'</span></center>';
		
		$query_count_ok = "SELECT * FROM auto_traitement WHERE auto_id_synthese = ".$traitement['auto_id_synthese']." AND statut_auto = 'OK'";
		$query_count_ok = mysqli_query($db_connection, $query_count_ok);
		if (!$query_count_ok){
		  $message = 'Échec de requête';
		} else {
		  $message = 'Succès de requête';
		}
		$rowcountok = mysqli_num_rows($query_count_ok);
		$rowcountok = '<center><span class="badge badge-sm badge-info badge-rounded mb-3 mr-3">'.$rowcountok.'</span></center>';
		
		
		
			
		  
        $mysql_data[] = array(
		  "collab"  => $traitement['auto_intervenant_synthese'],
          "datedebut"          => $date_debut,
		  "datefin"          => $date_fin,
		  "count"          => $rowcount,
		  "countko"          => $rowcountko,
		  "countok"          => $rowcountok,
		  "time"          => $time,
		  "jh"          => $jh
        );
      }
    }
    
  } elseif ($job == 'get_traitement_add_auto'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $traitement = "SELECT * FROM auto_traitement WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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
		  "statut"  => $traitement_edit['statut_auto'],
		  "ancienne"  => $traitement_edit['acide_acienne_nomination'],
		  "bo"  => $traitement_edit['acide_bo_nomination'],
		  "nt"  => $traitement_edit['acide_nt_nomination']
          );
        }
      }
    }
  
  } elseif ($job == 'add_traitement_auto'){
    
	$query_identifiant = "SELECT max(auto_id_synthese) AS max FROM acide_synthese WHERE ";
	if (isset($_GET['user']))         { $query_identifiant .= "	`acide_intervenant_synthese`         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "' "; }
	$query_identifiant .= "AND auto_fin_synthese = '0000-00-00 00:00:00'";
	
	$query_identifiant = mysqli_query($db_connection, $query_identifiant);
	$query_identifiant = mysqli_fetch_array($query_identifiant);
	
    $query = "INSERT INTO auto_traitement SET acide_date_nomination = now(),";
	$query .= "	auto_id_synthese         = '".$query_identifiant['max']."', ";
	if (isset($_GET['user']))         { $query .= "	acide_intervenant_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "', "; }
    if (isset($_GET['publication']))         { $query .= "acide_publication_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['publication'])         . "', "; }
    if (isset($_GET['rs'])) { $query .= "acide_rs_nomination = '" . mysqli_real_escape_string($db_connection, $_GET['rs']) . "', "; }
    if (isset($_GET['siret']))   { $query .= "acide_siret_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['siret'])   . "', "; }
	if (isset($_GET['title']))   { $query .= "acide_civilite_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['title'])   . "', "; }
	if (isset($_GET['nom']))   { $query .= "acide_nom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])   . "', "; }
	if (isset($_GET['prenom']))   { $query .= "acide_prenom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
	if (isset($_GET['fonction']))   { $query .= "acide_fe_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['fonction'])   . "', "; }
	if (isset($_GET['statut']))   { $query .= "statut_auto   = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])   . "', "; }
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
  
  } elseif ($job == 'edit_traitement_auto'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "UPDATE auto_traitement SET ";
		if (isset($_GET['publication']))         { $query .= "acide_publication_nomination         = '" . mysqli_real_escape_string($db_connection, $_GET['publication'])         . "', "; }
		if (isset($_GET['rs'])) { $query .= "acide_rs_nomination = '" . mysqli_real_escape_string($db_connection, $_GET['rs']) . "', "; }
		if (isset($_GET['siret']))   { $query .= "acide_siret_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['siret'])   . "', "; }
		if (isset($_GET['title']))   { $query .= "acide_civilite_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['title'])   . "', "; }
		if (isset($_GET['nom']))   { $query .= "acide_nom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])   . "', "; }
		if (isset($_GET['prenom']))   { $query .= "acide_prenom_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])   . "', "; }
		if (isset($_GET['fonction']))   { $query .= "acide_fe_nomination   = '" . mysqli_real_escape_string($db_connection, $_GET['fonction'])   . "', "; }
		if (isset($_GET['statut']))   { $query .= "statut_auto   = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])   . "', "; }
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
    
  } elseif ($job == 'delete_gestion_traitement_auto'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM auto_synthese WHERE auto_id_synthese = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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