<?php
include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_orgi' ||
      $job == 'get_orgi_add'   ||
      $job == 'add_orgi'   ||
      $job == 'edit_orgi'  ||
      $job == 'delete_orgi'){
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
    $message = 'Échec de la connexion à la base de données : ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_orgi'){
    
    
    $query_qld = "SELECT * FROM organigramme";
    $query_qld = mysqli_query($db_connection, $query_qld);
    if (!$query_qld){
      $result  = 'error';
      $message = 'Erreur de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($qld = mysqli_fetch_array($query_qld)){
		$functions = '<center>';
        $functions .= '<a href="#" id="function_edit_orgi" data-id="'   . $qld['id_organigramme'] . '" data-name="' . $qld['nomination_organigramme'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
        $functions .= '<a href="#" id="del" data-id="' . $qld['id_organigramme'] . '" data-name="' . $qld['nomination_organigramme'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
		$functions .= '</center>';
        $mysql_data[] = array(
          "nom"          => $qld['nomination_organigramme'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_orgi_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "SELECT * FROM organigramme WHERE id_organigramme = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
          $result  = 'error';
		  $message = 'Erreur de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
        	while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "nom"  => $company['nomination_organigramme']
          );
        }
      }
    }
  
  } elseif ($job == 'add_orgi'){
    
    $query = "INSERT INTO organigramme SET ";
    if (isset($_GET['nom']))         { $query .= "nomination_organigramme         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'Erreur de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_orgi'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "UPDATE organigramme SET ";
		if (isset($_GET['nom']))         { $query .= "nomination_organigramme         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
      $query .= "WHERE id_organigramme = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
          $result  = 'error';
		  $message = 'Erreur de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_orgi'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM organigramme WHERE id_organigramme = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
          $result  = 'error';
		  $message = 'Erreur de requête';
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