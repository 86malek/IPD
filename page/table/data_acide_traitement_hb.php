<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_traitement_hb' ||
      $job == 'get_traitement_add_hb'   ||
      $job == 'add_traitement_hb'   ||
      $job == 'edit_traitement_hb'  ||
      $job == 'delete_traitement_hb'){
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
  
  if ($job == 'get_traitement_hb'){
	
	if (checkAdmin()) {
    $query_traitement = "SELECT * FROM acide_hb";
	}else{$query_traitement = "SELECT * FROM acide_hb WHERE acide_intervenant_nomination = '" . mysqli_real_escape_string($db_connection, $id_import) . "'";}
    $query_traitement = mysqli_query($db_connection, $query_traitement);
    if (!$query_traitement){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($traitement = mysqli_fetch_array($query_traitement)){
		  
        $statut  = '<center>';
		
		if($traitement['acide_statut_nomination'] == NULL){
        $statut .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">NT</span>';
		}elseif($traitement['acide_statut_nomination'] == 'Ajout'){
		$statut .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">Ajout</span>';	
		}elseif($traitement['acide_statut_nomination'] == 'Modification'){
		$statut .= '<span class="badge badge-sm badge-default mb-3 mr-3">Modification</span>';	
		}elseif($traitement['acide_statut_nomination'] == 'Suppression'){
		$statut .= '<span class="badge badge-sm badge-info mb-3 mr-3">Suppression</span>';	
		}
		
        $statut .= '</center>';
		
		
		$title  = '<center>';
		
		if($traitement['acide_civilite_nomination'] == NULL){
        $title .= '<span class="badge badge-danger mb-3 mr-3">Non renseignée</span>';
		}elseif($traitement['acide_civilite_nomination'] == 'Mme'){
		$title .= '<span class="badge badge-bittersweet badge-rounded mb-3 mr-3">Mme</span>';	
		}elseif($traitement['acide_civilite_nomination'] == 'M'){
		$title .= '<span class="badge badge-shamrock badge-rounded mb-3 mr-3">M</span>';	
		}
		
        $title .= '</center>';
		
		
		$bo  = '<center>';
		
		if($traitement['acide_nt_nomination'] == 2){
				$bo .= '<span class="badge badge-sm badge-shamrock badge-rounded mb-3 mr-3">OK</span>';	
        
		}else{
				$bo .= '<span class="badge badge-sm badge-bittersweet badge-rounded mb-3 mr-3">KO</span>';
		}
		
        $bo .= '</center>';
		
		
		$nt  = '<center>';
		
		if($traitement['acide_nt_nomination'] == 1){
        $nt .= '<span class="badge badge-sm badge-shamrock badge-rounded mb-3 mr-3">OK</span>';	
		}else{
		
		$nt .= '<span class="badge badge-sm badge-bittersweet badge-rounded mb-3 mr-3">KO</span>';
		}
		
        $nt .= '</center>';
		
		
		
		$date = '<center><div class="table__cell-widget"><span class="table__cell-widget-name">'.date("d-m-Y", strtotime($traitement['acide_date_nomination'])).'</span></div></center>';
		
		$functions  = '<center>';
        $functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['acide_id_nomination'] . '" data-name="' . $traitement['acide_rs_nomination'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
		if (checkAdmin()) {
        $functions .= '<a href="#" id="del" data-id="' . $traitement['acide_id_nomination'] . '" data-name="' . $traitement['acide_rs_nomination'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
		}
        $functions .= '</center>';
		
        $mysql_data[] = array(
		  "collab"  => $traitement['acide_intervenant_nomination'],
          "date"          => $date,
		  "publication"  => $traitement['acide_publication_nomination'],
          "rs"  => $traitement['acide_rs_nomination'],
		  "siret"  => $traitement['acide_siret_nomination'],
		  "title"  => $title,
		  "nom"  => $traitement['acide_nom_nomination'],
		  "prenom"  => $traitement['acide_prenom_nomination'],
		  "fe"  => $traitement['acide_fe_nomination'],
		  "statut"  => $statut,
		  "ancienne"  => $traitement['acide_acienne_nomination'],
		  "bo"  => $bo,
          "functions"     => $functions
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
		  "etat"  => $traitement_edit['acide_nt_nomination']
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
	if($_GET['etat'] == 1){
			
			$query .= "acide_nt_nomination   = '1' ";
			}else{
			$query .= "acide_nt_nomination   = '2' ";
			}
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
		
		$query_identifiant = "SELECT acide_id_synthese FROM acide_hb WHERE ";
		$query_identifiant .= "acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";		
		$query_identifiant = mysqli_query($db_connection, $query_identifiant);
		$query_identifiant = mysqli_fetch_array($query_identifiant);
	
		$query_temps_reel = "SELECT * FROM acide_synthese WHERE acide_id_synthese ='".$query_identifiant['acide_id_synthese']."' AND acide_fin_synthese = '0000-00-00 00:00:00'";
		
		$query_temps_reel = mysqli_query($db_connection, $query_temps_reel);
		$rowcount = mysqli_num_rows($query_temps_reel);
		if($rowcount <> 0){
			mysqli_query($db,"UPDATE acide_synthese SET acide_fin_synthese = now() WHERE acide_id_synthese ='".$query_identifiant['acide_id_synthese']."'") or die(mysqli_connect_error());	
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
		
			if($_GET['etat'] == 1){
			$query .= "acide_nt_nomination   = '1' ";
			}else{
			$query .= "acide_nt_nomination   = '2' ";
			}
	
		  $query .= "WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
		  $query  = mysqli_query($db_connection, $query);
		}else{
			
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
		
			if($_GET['etat'] == 1){
			$query .= "acide_nt_nomination   = '1' ";
			}else{
			$query .= "acide_nt_nomination   = '2' ";
			}
	
		  $query .= "WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
		  $query  = mysqli_query($db_connection, $query);}
      
      if (!$query){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_traitement_hb'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM acide_hb WHERE acide_id_nomination = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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