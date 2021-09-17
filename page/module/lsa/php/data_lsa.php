<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_lsa' ||
      $job == 'get_lsa_add'   ||
      $job == 'add_lsa'   ||
      $job == 'edit_lsa'  ||
      $job == 'delete_lsa'){
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
  
  if ($job == 'get_lsa'){
    
    
	 
	$rs_qld = "SELECT * FROM lsa";
	$rs_qld_sql = mysqli_query($db_connection, $rs_qld);
	if (!$rs_qld_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows = mysqli_fetch_array($rs_qld_sql)) {
			
			if($rrows['date_debut_qld']<>$rrows['date_fin_qld']){
					$jouvers = get_nb_open_days($rrows['date_debut_qld'], $rrows['date_fin_qld']);
				}else{$jouvers = 1;}
			$jouvers = $jouvers*$rrows['nb_participant_qld'];
			$id_qld = $rrows['id'];
			
			$update_qld = "UPDATE lsa SET ";
			if (isset($jouvers))         { $update_qld .= "nb_jh_qld         = '" . mysqli_real_escape_string($db_connection, $jouvers). "' "; }
			$update_qld .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id_qld) . "'";
			$update_qld  = mysqli_query($db_connection, $update_qld);
	
		}
	}
    $query_qld = "SELECT * FROM lsa";
    $query_qld = mysqli_query($db_connection, $query_qld);
    if (!$query_qld){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($qld = mysqli_fetch_array($query_qld)){
        $functions  = '<center>';
        $functions .= '<a href="#" id="function_edit_web" data-id="'   . $qld['id'] . '" data-name="' . $qld['operation_qld'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
        $functions .= '<a href="#" id="del"  data-id="' . $qld['id'] . '" data-name="' . $qld['operation_qld'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
        $functions .= '</center>';
        $mysql_data[] = array(
          "Operation"          => $qld['operation_qld'],
          "participant"  => $qld['nb_participant_qld'],
		  "integre"  => $qld['integres_qld'],
          "realiser"    => $qld['realiser_qld'],
		  "indep"    => $qld['independants_qld'],
		  "jh"  => $qld['nb_jh_qld'],
          "debut"  => date("d/m/Y", strtotime($qld['date_debut_qld'])),
		  "fin"  => date("d/m/Y", strtotime($qld['date_fin_qld'])),
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_lsa_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM lsa WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "operation"  => $company['operation_qld'],
            "nb"  => $company['nb_participant_qld'],
			"integre"  => $company['integres_qld'],
            "indep"    => $company['independants_qld'],
            "realiser"       => $company['realiser_qld'],
			"debut"       => $company['date_debut_qld'],
			"fin"       => $company['date_fin_qld']
          );
        }
      }
    }
  
  } elseif ($job == 'add_lsa'){
    
    $query = "INSERT INTO lsa SET ";
    if (isset($_GET['operation']))         { $query .= "operation_qld         = '" . mysqli_real_escape_string($db_connection, $_GET['operation'])         . "', "; }
    if (isset($_GET['nb'])) { $query .= "nb_participant_qld = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
    if (isset($_GET['indep']))   { $query .= "independants_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['indep'])   . "', "; }
	if (isset($_GET['integre']))   { $query .= "integres_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['integre'])   . "', "; }
	if (isset($_GET['realiser']))   { $query .= "realiser_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['realiser'])   . "', "; }
	if (isset($_GET['debut']))   { $query .= "date_debut_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
	if (isset($_GET['fin']))   { $query .= "date_fin_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_lsa'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE lsa SET ";
		if (isset($_GET['operation']))         { $query .= "operation_qld         = '" . mysqli_real_escape_string($db_connection, $_GET['operation'])         . "', "; }
		if (isset($_GET['nb'])) { $query .= "nb_participant_qld = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
		if (isset($_GET['indep']))   { $query .= "independants_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['indep'])   . "', "; }
		if (isset($_GET['integre']))   { $query .= "integres_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['integre'])   . "', "; }
		if (isset($_GET['realiser']))   { $query .= "realiser_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['realiser'])   . "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_qld   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
      $query .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_lsa'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM lsa WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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