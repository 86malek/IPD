<?php
// Database details
$db_server   = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name     = 'database';
// Get job (and id)
$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_collec' ||
      $job == 'get_collec_add'   ||
      $job == 'add_collec'   ||
      $job == 'edit_collec'  ||
      $job == 'delete_collec'){
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

// Prepare array
$mysql_data = array();

// Valid job found
if ($job != ''){
  
  // Connect to database
  $db_connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $job     = '';
  }
  
  // Execute job
  if ($job == 'get_collec'){
    


    $query = "SELECT collectivite.id, collectivite.date_collectivite, collectivite.somme_collectivite, collaborateurs.nom_collaborateurs, collaborateurs.prenom_collaborateurs, collectivite.descri_collectivite FROM collectivite, collaborateurs WHERE collectivite.id_colaborateur = collaborateurs.id_collab";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['id'] . '" data-name="' . $company['id'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['id'] . '" data-name="' . $company['id'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
		$nomination = $company['nom_collaborateurs']." ".$company['prenom_collaborateurs'];
        $mysql_data[] = array(
          "somme_collec"          => $company['somme_collectivite'],
          "nom_collaborateurs_collec"  => $company['nom_collaborateurs'],
		  "prenom_collaborateurs_collec"  => $nomination,
          "date_collec"  => date("d/m/Y", strtotime($company['date_collectivite'])),
		  "descri_collec"  => $company['descri_collectivite'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_collec_add'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT collectivite.id, collectivite.date_collectivite, collectivite.somme_collectivite, collaborateurs.id_collab, collectivite.descri_collectivite FROM collectivite, collaborateurs WHERE collectivite.id_colaborateur = collaborateurs.id_collab AND collectivite.id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "somme"          => $company['somme_collectivite'],
            "descri"  => $company['descri_collectivite'],
            "date"       => $company['date_collectivite'],
			"collab"       => $company['id_collab']
          );
        }
      }
    }
  
  } elseif ($job == 'add_collec'){
    
    // Add company
    $query = "INSERT INTO collectivite SET ";
		if (isset($_GET['somme']))         { $query .= "somme_collectivite         = '" . mysqli_real_escape_string($db_connection, $_GET['somme'])         . "', "; }
		if (isset($_GET['descri'])) { $query .= "descri_collectivite = '" . mysqli_real_escape_string($db_connection, $_GET['descri']) . "', "; }
		if (isset($_GET['date']))   { $query .= "date_collectivite   = '" . mysqli_real_escape_string($db_connection, $_GET['date'])   . "', "; }
		if (isset($_GET['collab']))   { $query .= "id_colaborateur   = '" . mysqli_real_escape_string($db_connection, $_GET['collab'])   . "' "; }else{$query .= "id_colaborateur   = '0'";}
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_collec'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE collectivite SET ";
		if (isset($_GET['somme']))         { $query .= "somme_collectivite         = '" . mysqli_real_escape_string($db_connection, $_GET['somme'])         . "', "; }
		if (isset($_GET['descri'])) { $query .= "descri_collectivite = '" . mysqli_real_escape_string($db_connection, $_GET['descri']) . "', "; }
		if (isset($_GET['date']))   { $query .= "date_collectivite   = '" . mysqli_real_escape_string($db_connection, $_GET['date'])   . "', "; }
		if (isset($_GET['collab']))   { $query .= "id_colaborateur   = '" . mysqli_real_escape_string($db_connection, $_GET['collab'])   . "' "; }else{$query .= "id_colaborateur   = '0'";}
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
    
  } elseif ($job == 'delete_collec'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM collectivite WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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