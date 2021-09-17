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
  if ($job == 'get_comite' ||
      $job == 'get_comite_add'   ||
      $job == 'add_comite'   ||
      $job == 'edit_comite'  ||
      $job == 'delete_comite'){
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
  if ($job == 'get_comite'){
    

	// Fonction permettant de compter le nombre de jours ouvrés entre deux dates
	function get_nb_open_days($date_start, $date_stop) {
	$arr_bank_holidays = array(); // Tableau des jours feriés
	
	// On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
	$diff_year = date('Y', $date_stop) - date('Y', $date_start);
	for ($i = 0; $i <= $diff_year; $i++) {
	$year = (int)date('Y', $date_start) + $i;
	// Liste des jours feriés
	
		$arr_bank_holidays[] = '1_1_'.$year; // Jour de l'an
		$arr_bank_holidays[] = '1_5_'.$year; // Fete du travail
		$arr_bank_holidays[] = '5_5_'.$year;
		$arr_bank_holidays[] = '16_5_'.$year; 
		$arr_bank_holidays[] = '14_7_'.$year; // Fete nationale
		$arr_bank_holidays[] = '15_8_'.$year; // Assomption
		$arr_bank_holidays[] = '1_11_'.$year; // Toussaint
		$arr_bank_holidays[] = '11_11_'.$year; // Armistice 1918
		$arr_bank_holidays[] = '25_12_'.$year; // Noel
		
		$arr_bank_holidays[] = '14_1_'.$year;
		$arr_bank_holidays[] = '20_3_'.$year;
		$arr_bank_holidays[] = '25_7_'.$year;
		$arr_bank_holidays[] = '6_7_'.$year;
		$arr_bank_holidays[] = '7_7_'.$year;
		$arr_bank_holidays[] = '3_10_'.$year;
		$arr_bank_holidays[] = '12_9_'.$year;
		$arr_bank_holidays[] = '13_9_'.$year;
		$arr_bank_holidays[] = '1_9_'.$year;
		$arr_bank_holidays[] = '12_12_'.$year;
		
	// Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	$easter = easter_date($year);
	$arr_bank_holidays[] = date('j_n_'.$year, $easter + 86400); // Paques
	$arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*39)); // Ascension
	
	}
	//print_r($arr_bank_holidays);
	$nb_days_open = 0;
	while ($date_start < $date_stop) {
	// Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés
	if (!in_array(date('w', $date_start), array(0, 6))
	&& !in_array(date('j_n_'.date('Y', $date_start), $date_start), $arr_bank_holidays)) {
	$nb_days_open++;
	 }
	 $date_start += 86400;
	 }
	return $nb_days_open;
	}
  
	// Exemple : Du 11 au 15 juillet il n'y a qu'un jour ouvré (week-end + 1 jours férié)
	 
	$rs_comite = "SELECT * FROM comite";
	$rs_comite_sql = mysqli_query($db_connection, $rs_comite);
	if (!$rs_comite_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
	while ($rrows = mysqli_fetch_array($rs_comite_sql)) {
		
		if($rrows['date_debut_comite']<>$rrows['date_fin_comite']){
				$jouvers = get_nb_open_days(strtotime($rrows['date_debut_comite']), strtotime($rrows['date_fin_comite']))+1;
		}else{$jouvers = 1;}
		
		$jouvers = $jouvers*$rrows['nb_participants_comite'];
		$id_comite = $rrows['id_comite'];
		$somme_nt = $rrows['nt_site_comite']+$rrows['nt_lien_comite'];
		$prod = $rrows['ce_comite']+$somme_nt;
		$taux_enrich_comite = round(($rrows['ce_comite']/$prod)*100);
		$taux_site_comite = round(($rrows['nt_site_comite']/$prod)*100);
		$taux_lien_comite = round(($rrows['nt_lien_comite']/$prod)*100);
		
		
		
		
		$update_comite = "UPDATE comite SET ";
		if (isset($jouvers))         { $update_comite .= "jh_comite         = '" . mysqli_real_escape_string($db_connection, $jouvers). "', "; }
		if (isset($somme_nt))         { $update_comite .= "somme_nt_comite         = '" . mysqli_real_escape_string($db_connection, $somme_nt). "', "; }
		if (isset($prod))         { $update_comite .= "prod_comite         = '" . mysqli_real_escape_string($db_connection, $prod). "', "; }
		if (isset($taux_enrich_comite))         { $update_comite .= "taux_enrich_comite         = '" . mysqli_real_escape_string($db_connection, $taux_enrich_comite). "', "; }
		if (isset($taux_lien_comite))         { $update_comite .= "taux_lien_comite         = '" . mysqli_real_escape_string($db_connection, $taux_lien_comite). "', "; }
		if (isset($taux_site_comite))         { $update_comite .= "taux_site_comite         = '" . mysqli_real_escape_string($db_connection, $taux_site_comite). "' "; }
		$update_comite .= "WHERE id_comite = '" . mysqli_real_escape_string($db_connection, $id_comite) . "'";
		$update_comite  = mysqli_query($db_connection, $update_comite);
	  
		
	}
	}
	
    $query = "SELECT * FROM comite";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['id_comite'] . '" data-name="' . $company['nom_comite'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['id_comite'] . '" data-name="' . $company['nom_comite'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
		$taux_enri = $company['taux_enrich_comite']."%";
		$taux_site = $company['taux_site_comite']."%";
		$taux_lien = $company['taux_lien_comite']."%";
        $mysql_data[] = array(
          "nom_comite"          => $company['nom_comite'],
		  "ce_comite"          => $company['ce_comite'],
		  "nt_site_comite"          => $company['nt_site_comite'],
          "nt_lien_comite"  => $company['nt_lien_comite'],
		  "somme_nt_comite"  => $company['somme_nt_comite'],
          "prod_comite"  => $company['prod_comite'],
		  "debut"  => date("d/m/Y", strtotime($company['date_debut_comite'])),
		  "fin"  => date("d/m/Y", strtotime($company['date_fin_comite'])),
		  "jh_comite"  => $company['jh_comite'],
		  "taux_enrich_comite"  => $taux_enri,
		  "taux_site_comite"  => $taux_site,
		  "taux_lien_comite"  => $taux_lien,
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_comite_add'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM comite WHERE id_comite = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
			$mysql_data[] = array(
			"lot"          => $company['nom_comite'],
			"ce"  => $company['ce_comite'],
			"ntsite"  => $company['nt_site_comite'],
			"ntlien"  => $company['nt_lien_comite'],
			"participant"       => $company['nb_participants_comite'],
			"debut"       => $company['date_debut_comite'],
			"fin"       => $company['date_fin_comite']
          );
        }
      }
    }
  
  } elseif ($job == 'add_comite'){
    
    // Add company
    $query = "INSERT INTO comite SET ";
		if (isset($_GET['lot']))         { $query .= "nom_comite         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "', "; }
		if (isset($_GET['ce'])) { $query .= "ce_comite = '" . mysqli_real_escape_string($db_connection, $_GET['ce']) . "', "; }
		if (isset($_GET['ntsite'])) { $query .= "nt_site_comite = '" . mysqli_real_escape_string($db_connection, $_GET['ntsite']) . "', "; }
		if (isset($_GET['ntlien']))   { $query .= "nt_lien_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['ntlien'])   . "', "; }
		if (isset($_GET['participant']))   { $query .= "nb_participants_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['participant'])   . "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
		
		
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_comite'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE comite SET ";
		if (isset($_GET['lot']))         { $query .= "nom_comite         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "', "; }
		if (isset($_GET['ce'])) { $query .= "ce_comite = '" . mysqli_real_escape_string($db_connection, $_GET['ce']) . "', "; }
		if (isset($_GET['ntsite'])) { $query .= "nt_site_comite = '" . mysqli_real_escape_string($db_connection, $_GET['ntsite']) . "', "; }
		if (isset($_GET['ntlien']))   { $query .= "nt_lien_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['ntlien'])   . "', "; }
		if (isset($_GET['participant']))   { $query .= "nb_participants_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['participant'])   . "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_comite   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
      $query .= "WHERE id_comite = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_comite'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM comite WHERE id_comite = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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