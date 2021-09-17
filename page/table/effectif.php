<?php
include '../../config/dbc.php';
page_protect();
$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_companies' ||
      $job == 'get_company'   ||
      $job == 'add_company'   ||
      $job == 'edit_company'  ||
      $job == 'delete_company'){
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
    $result  = 'Échec';
    $message = 'Connexion à la base de données impossible: ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_companies'){
    
    $query = "SELECT collaborateurs.id_collab, collaborateurs.matricule_collaborateurs, collaborateurs.nom_collaborateurs, collaborateurs.prenom_collaborateurs, collaborateurs.anciente_collaborateurs, collaborateurs.email_collaborateurs, collaborateurs.coordinateur, organigramme.nomination_organigramme, collaborateurs.ip_collaborateurs, collaborateurs.somme_abs_collaborateurs  FROM collaborateurs, organigramme WHERE collaborateurs.id_organi = organigramme.id_organigramme";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'Échec';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($company = mysqli_fetch_array($query)){
		$functions = '<center>';
        $functions .= '<a href="#" id="function_edit_company" data-id="'   . $company['id_collab'] . '" data-name="' . $company['nom_collaborateurs'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-pencil"></span></span></a>';
        $functions .= '<a href="#" id="del" data-id="' . $company['id_collab'] . '" data-name="' . $company['nom_collaborateurs'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3"><span class="btn-icon iconfont iconfont-remove"></span></span></a>';
		$functions .= '</center>';
		
		if($company['coordinateur'] == 1){ $coor = "<span style='color:#099; font-weight:bold'>Cordinateur</span>"; }else{ $coor = "<span style='color:#333; font-weight:bold'>Collaborateur</span>"; }
		
		$mailto = "<a href='mailto:".$company['email_collaborateurs']."' style='color:#333; font-weight:bold'>".$company['email_collaborateurs']."</a>";
		
		if($company['ip_collaborateurs'] == 0){ $ip = "Indisponible"; }else{ $ip = $company['ip_collaborateurs']; }
		
		$somme = "<center><span style='color:#333; font-weight:bold;font-size:18px'>".$company['somme_abs_collaborateurs']."</span></center>";
		
		if($company['somme_abs_collaborateurs'] == 0){ $taux = "<center><span style='color:#35ae47; font-weight:bold;font-size:18px'>0%</span></center>"; }else{ $taux = "<center><span style='color:#eb3b48; font-weight:bold;font-size:18px'>".round(($company['somme_abs_collaborateurs']*100/220))."%</span></center>"; }
		
        $mysql_data[] = array(
          "matricule_collaborateurs"          => $company['matricule_collaborateurs'],
          "nom_collaborateurs"  => $company['nom_collaborateurs'],
          "prenom_collaborateurs"    => $company['prenom_collaborateurs'],
		  "ip_collaborateurs"    => $ip,
          "anciente_collaborateurs"  => date("d/m/Y", strtotime($company['anciente_collaborateurs'])),
		  "email_collaborateurs"  => $mailto,
		  "nomination_organigramme"  => $company['nomination_organigramme'],
		  "somme_abs_collaborateurs"  => $somme,
		  "taux_abs_collaborateurs"  => $taux,
		  "coordinateur"       => $coor,
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_company'){
    
    // Get company
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
      $query = "SELECT collaborateurs.id_collab, collaborateurs.matricule_collaborateurs, collaborateurs.nom_collaborateurs, collaborateurs.prenom_collaborateurs, collaborateurs.anciente_collaborateurs, collaborateurs.email_collaborateurs, collaborateurs.coordinateur, organigramme.id_organigramme, collaborateurs.ip_collaborateurs, collaborateurs.somme_abs_collaborateurs  FROM collaborateurs, organigramme WHERE collaborateurs.id_organi = organigramme.id_organigramme AND collaborateurs.id_collab = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'Échec';
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
  
  } elseif ($job == 'add_company'){
    
    // Add company
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
      $result  = 'Échec';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_company'){
    
    // Edit company
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
        $result  = 'Échec';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_company'){
  
    // Delete company
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM collaborateurs WHERE id_collab = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'Échec';
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