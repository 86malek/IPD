<?php
include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_fiche_auto' ||
      $job == 'get_fiche_auto_add'   ||
      $job == 'add_fiche_auto'   ||
      $job == 'edit_fiche_auto'  ||
      $job == 'delete_fiche_auto'){
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
  
  if ($job == 'get_fiche_auto'){
    
    
    $query_qld = "SELECT * FROM auto_traitement";
    $query_qld = mysqli_query($db_connection, $query_qld);
    if (!$query_qld){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($qld = mysqli_fetch_array($query_qld)){
        $functions  = '<center>';
        $functions .= '<a href="#" id="function_edit_fiche" data-id="'   . $qld['id_auto'] . '" data-name="' . $qld['fiche_auto'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
        $functions .= '<a href="#" id="del" data-id="' . $qld['id_auto'] . '" data-name="' . $qld['fiche_auto'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
        $functions .= '</center>';
		
		$statut  = '<center>';
		
		if($qld['statut_auto'] == NULL){
        $statut .= '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">NT</span>';
		}elseif($qld['statut_auto'] == 'OK'){
		$statut .= '<span class="badge badge-sm badge-shamrock mb-3 mr-3">OK</span>';	
		}elseif($qld['statut_auto'] == 'KO'){
		$statut .= '<span class="badge badge-sm badge-default mb-3 mr-3">KO</span>';	
		}
		
        $statut .= '</center>';
		
		
        $mysql_data[] = array(
          "fiche"          => $qld['fiche_auto'],
		  "statut"          => $statut,
		  "date"          => $qld['date_auto'],
		  "collab"          => $qld['auto_intervenant'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_fiche_auto_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM auto_traitement WHERE id_auto = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        	while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
			"fiche" => $company['fiche_auto'],
		  	"statut" => $company['statut_auto']
          );
        }
      }
    }
  
  } elseif ($job == 'add_fiche_auto'){
    
	$query_identifiant = "SELECT max(auto_id_synthese) AS max FROM auto_synthese WHERE ";
	if (isset($_GET['user']))         { $query_identifiant .= "	`auto_intervenant_synthese`         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "' "; }
	$query_identifiant .= "AND auto_fin_synthese = '0000-00-00 00:00:00'";
	
	$query_identifiant = mysqli_query($db_connection, $query_identifiant);
	$query_identifiant = mysqli_fetch_array($query_identifiant);
	
	
    $query = "INSERT INTO auto_traitement SET ";
	$query .= "	auto_id_synthese         = '".$query_identifiant['max']."', ";
	if (isset($_GET['user']))         { $query .= "	auto_intervenant         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "', "; }
    if (isset($_GET['fiche']))         { $query .= "fiche_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['fiche'])         . "', "; }
	if (isset($_GET['statut']))         { $query .= "statut_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])         . "', "; }
	$query .= "date_auto         = now() ";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_fiche_auto'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
		
		$query_identifiant = "SELECT auto_id_synthese FROM auto_traitement WHERE ";
		$query_identifiant .= "id_auto = '" . mysqli_real_escape_string($db_connection, $id) . "'";		
		$query_identifiant = mysqli_query($db_connection, $query_identifiant);
		$query_identifiant = mysqli_fetch_array($query_identifiant);
	
		$query_temps_reel = "SELECT * FROM auto_synthese WHERE auto_id_synthese ='".$query_identifiant['auto_id_synthese']."' AND auto_fin_synthese = '0000-00-00 00:00:00'";
		
		$query_temps_reel = mysqli_query($db_connection, $query_temps_reel);
		$rowcount = mysqli_num_rows($query_temps_reel);
		if($rowcount <> 0){
			mysqli_query($db,"UPDATE auto_synthese SET auto_fin_synthese = now() WHERE auto_id_synthese ='".$query_identifiant['auto_id_synthese']."'") or die(mysqli_connect_error());	
		
			
			  $query = "UPDATE auto_traitement SET ";
				if (isset($_GET['fiche']))         { $query .= "fiche_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['fiche'])         . "', "; }
				if (isset($_GET['statut']))         { $query .= "statut_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])         . "' "; }
			  $query .= "WHERE id_auto = '" . mysqli_real_escape_string($db_connection, $id) . "'";
			  $query  = mysqli_query($db_connection, $query);
			  if (!$query){
				$result  = 'error';
				$message = 'query error';
			  } else {
				$result  = 'success';
				$message = 'query success';
			  }
			  
			}else{
			  $query = "UPDATE auto_traitement SET ";
				if (isset($_GET['fiche']))         { $query .= "fiche_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['fiche'])         . "', "; }
				if (isset($_GET['statut']))         { $query .= "statut_auto         = '" . mysqli_real_escape_string($db_connection, $_GET['statut'])         . "' "; }
			  $query .= "WHERE id_auto = '" . mysqli_real_escape_string($db_connection, $id) . "'";
			  $query  = mysqli_query($db_connection, $query);
			  if (!$query){
				$result  = 'error';
				$message = 'query error';
			  } else {
				$result  = 'success';
				$message = 'query success';
			  }
			  
			}
	  
    }
    
  } elseif ($job == 'delete_fiche_auto'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM auto_traitement WHERE id_auto = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
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