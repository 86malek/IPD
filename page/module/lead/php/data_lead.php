<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_lead' ||
      $job == 'get_lead_add'   ||
      $job == 'add_lead'   ||
      $job == 'edit_lead'  ||
      $job == 'delete_lead'){
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

$mysql_data = array();

if ($job != ''){
  
  $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_lead'){   
	 
	$rs_lead = "SELECT * FROM lead_gen";
	$rs_lead_sql = mysqli_query($db_connection, $rs_lead);
	
	if (!$rs_lead_sql){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
	  
		while ($rrows = mysqli_fetch_array($rs_lead_sql)) {
			
			if($rrows['date_debut_qld']<>$rrows['date_fin_qld']){
					$jouvers = get_nb_open_days($rrows['date_debut_qld'], $rrows['date_fin_qld']);
					}else{$jouvers = 1;}
			$jouvers = $jouvers*$rrows['nb_participant_qld'];
			$id_lead = $rrows['id'];
			if($id_lead == 2){$jouvers = ($jouvers*$rrows['nb_participant_qld'])*0.0625;}
			$taux_resolution = round(($rrows['realiser_qld']/$rrows['objectif_qld'])*100);
			$ecart_objectif = round(1-($rrows['realiser_qld']/$rrows['objectif_qld']),2);
			
			
			
			$update_lead = "UPDATE lead_gen SET ";
			if (isset($jouvers))         { $update_lead .= "nb_jh_qld         = '" . mysqli_real_escape_string($db_connection, $jouvers). "', "; }
			if (isset($taux_resolution))         { $update_lead .= "taux_resolution_qld         = '" . mysqli_real_escape_string($db_connection, $taux_resolution). "' "; }
			$update_lead .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id_lead) . "'";
			$update_lead  = mysqli_query($db_connection, $update_lead);
		  
			
		}
	
	}
    $query_lead = "SELECT * FROM lead_gen";
    $query_lead = mysqli_query($db_connection, $query_lead);
    if (!$query_lead){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($lead = mysqli_fetch_array($query_lead)){
		  
		$functions  = '<center>';
        $functions .= '<a href="#" id="function_edit_web" data-id="'   . $lead['id'] . '" data-name="' . $lead['operation_qld'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
        $functions .= '<a href="#" id="del" data-id="' . $lead['id'] . '" data-name="' . $lead['operation_qld'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
        $functions .= '</center>';
		
       
		$taux = $lead['taux_resolution_qld']."%";
		
        $mysql_data[] = array(
          "Operation"          => $lead['operation_qld'],
          "participant"  => $lead['nb_participant_qld'],
		  "objectif"  => $lead['objectif_qld'],
          "realiser"    => $lead['realiser_qld'],
		  "nature"    => $lead['nature_objet_qld'],
		  "taux"    => $taux,
		  "ecart"    => $lead['ecart_objectif_qld'],
		  "jh"  => $lead['nb_jh_qld'],
          "debut"  => date("d/m/Y", strtotime($lead['date_debut_qld'])),
		  "fin"  => date("d/m/Y", strtotime($lead['date_fin_qld'])),
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_lead_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'ID manquant';
    } else {
      $query = "SELECT * FROM lead_gen WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "operation"  => $company['operation_qld'],
            "nb"  => $company['nb_participant_qld'],
			"nature"  => $company['nature_objet_qld'],
            "object"    => $company['objectif_qld'],
            "realiser"       => $company['realiser_qld'],
			"debut"       => $company['date_debut_qld'],
			"fin"       => $company['date_fin_qld']
          );
        }
      }
    }
  
  } elseif ($job == 'add_lead'){
    
    $query = "INSERT INTO lead_gen SET ";
    if (isset($_GET['operation']))         { $query .= "operation_qld         = '" . mysqli_real_escape_string($db_connection, $_GET['operation'])         . "', "; }
    if (isset($_GET['nb'])) { $query .= "nb_participant_qld = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
    if (isset($_GET['object']))   { $query .= "objectif_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['object'])   . "', "; }
	if (isset($_GET['nature']))   { $query .= "nature_objet_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['nature'])   . "', "; }
	if (isset($_GET['realiser']))   { $query .= "realiser_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['realiser'])   . "', "; }
	if (isset($_GET['debut']))   { $query .= "date_debut_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
	if (isset($_GET['fin']))   { $query .= "date_fin_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_lead'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'ID manquant';
    } else {
      $query = "UPDATE lead_gen SET ";
		if (isset($_GET['operation']))         { $query .= "operation_qld         = '" . mysqli_real_escape_string($db_connection, $_GET['operation'])         . "', "; }
    if (isset($_GET['nb'])) { $query .= "nb_participant_qld = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
    if (isset($_GET['object']))   { $query .= "objectif_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['object'])   . "', "; }
	if (isset($_GET['nature']))   { $query .= "nature_objet_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['nature'])   . "', "; }
	if (isset($_GET['realiser']))   { $query .= "realiser_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['realiser'])   . "', "; }
	if (isset($_GET['debut']))   { $query .= "date_debut_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
	if (isset($_GET['fin']))   { $query .= "date_fin_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
      $query .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_lead'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'ID manquant';
    } else {
      $query = "DELETE FROM lead_gen WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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
  
  // Close database connection
  mysqli_close($db_connection);

}

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>