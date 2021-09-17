<?php

include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_traitement' ||
      $job == 'get_traitement_add'   ||
      $job == 'add_traitement'   ||
      $job == 'edit_traitement'  ||
      $job == 'delete_traitement'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
	
	if (isset($_GET['id_import'])){
      $id_import = $_GET['id_import'];
      if (!is_numeric($id_import)){
        $id_import = '';
      }
    }
	if (isset($_GET['name_user'])){
      $name_user = $_GET['name_user'];
      
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
  
  if ($job == 'get_traitement'){
	
	if (checkAdmin()) {
    $query_traitement = "SELECT * FROM acide WHERE (id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id_import) . "' AND operateur_acide IS NULL) OR (id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id_import) . "')";
	}else{
	$query_traitement = "SELECT * FROM acide WHERE (id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id_import) . "' AND operateur_acide IS NULL) OR (id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id_import) . "' AND 	operateur_acide = '" . mysqli_real_escape_string($db_connection, $name_user) . "')";	
	}
    $query_traitement = mysqli_query($db_connection, $query_traitement);
    if (!$query_traitement){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
      while ($traitement = mysqli_fetch_array($query_traitement)){
		  
		if (checkAdmin()) {
			
			$functions  = '<center>';
			if($traitement['reporting'] == 0){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">En attente</span>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<span class="badge badge-success mb-3 mr-3">OK</span>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<span class="badge badge-success mb-3 mr-3">Modification OK</span>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<span class="badge badge-danger mb-3 mr-3">Suppression</span>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<span class="badge badge-info mb-3 mr-3">Ajout</span>';	
			}	
			$functions .= '</center>';
			
		}else{
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$functions  = '<center>';			
			if($traitement['reporting'] == 0){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-warning mb-3 mr-3">Traiter</span></a>';
			}elseif($traitement['reporting'] == 1){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	
			}elseif($traitement['reporting'] == 2){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">Modification OK</span></a>';	
			}elseif($traitement['reporting'] == 3){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">Suppression</span></a>';	
			}elseif($traitement['reporting'] == 4){
			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-info mb-3 mr-3">Ajout</span></a>';	
			}			
			$functions .= '</center>';
			
			}else{
			
			$functions  = '<center>';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '</center>';
			
			}
		}
		
		$linkedin = '<a class="iconfont iconfont-social-linkedin-sm container-heading-control" target="_blank" href="'.$traitement['url_linkedin_acide'].'"></a>';
		
        $mysql_data[] = array(
          "raison"          => $traitement['raison_sociale_acide'],
          "codep"  => $traitement['code_postal_acide'],
		  "ville"  => $traitement['ville_acide'],
		  "idcontact"  => $traitement['id_contact_acide'],
		  "civilite"  => $traitement['civilite_acide'],
		  "nom"  => $traitement['nom_acide'],
		  "prenom"  => $traitement['prenom_acide'],
		  "idsociete"  => $traitement['id_societe_acide'],
		  "fonction"  => $traitement['fonction_acide'],
		  "urllinkedin"  => $linkedin,
		  "newposte"  => $traitement['new_poste_acide'],
		  "oldposte"  => $traitement['old_poste_acide'],
		  "newentreprise"  => $traitement['new_entreprise_acide'],
		  "oldentreprise"  => $traitement['old_entreprise_acide'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_traitement_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $traitement = "SELECT * FROM acide WHERE id_acide = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $traitement = mysqli_query($db_connection, $traitement);
      if (!$traitement){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
        while ($traitement_edit = mysqli_fetch_array($traitement)){
          $mysql_data[] = array(
            "raison"  => $traitement_edit['raison_sociale_acide'],
			"prenom"  => $traitement_edit['prenom_acide'],
			"nom"  => $traitement_edit['nom_acide'],
			"title"  => $traitement_edit['civilite_acide'],
			"newe"  => $traitement_edit['new_entreprise_acide'],
			"url"  => $traitement_edit['url_linkedin_acide'],
            "reporting"  => $traitement_edit['reporting']
          );
        }
      }
    }
  
  } elseif ($job == 'add_traitement'){
    
    $query = "INSERT INTO acide SET ";
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
  
  } elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "UPDATE acide SET ";
	  	if (isset($_GET['user']))         { $query .= "operateur_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['user'])         . "', "; }
		if (isset($_GET['reporting']))         { $query .= "reporting         = '" . mysqli_real_escape_string($db_connection, $_GET['reporting'])         . "', "; }
		if (isset($_GET['title']))         { $query .= "civilite_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['title'])         . "', "; }
		if (isset($_GET['nom']))         { $query .= "nom_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "', "; }
		if (isset($_GET['prenom']))         { $query .= "prenom_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['prenom'])         . "', "; }
		if (isset($_GET['newe']))         { $query .= "new_entreprise_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['newe'])         . "', "; }
		if (isset($_GET['url']))         { $query .= "url_linkedin_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['url'])         . "', "; }
		$query .= "date_fin_traitement   = now() ";
		  $query .= "WHERE id_acide = '" . mysqli_real_escape_string($db_connection, $id) . "'";
		  $query  = mysqli_query($db_connection, $query);
      if (!$query){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_qld'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM qualite_de_donnees WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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