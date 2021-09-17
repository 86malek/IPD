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
  if ($job == 'get_rand' ||
      $job == 'get_rand_add'   ||
      $job == 'add_rand'   ||
      $job == 'edit_rand'  ||
      $job == 'delete_rand'){
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
  if ($job == 'get_rand'){
    

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
	$arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*50)); // Pentecote
	
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
	 
	$rs_rand = "SELECT * FROM randstad";
	$rs_rand_sql = mysqli_query($db_connection, $rs_rand);
	if (!$rs_rand_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
	while ($rrows = mysqli_fetch_array($rs_rand_sql)) {
		if($rrows['date_debut_rand']<>$rrows['date_fin_rand']){
				$jouvers = get_nb_open_days(strtotime($rrows['date_debut_rand']), strtotime($rrows['date_fin_rand']));
			}else{$jouvers = 1;}
		$jouvers = $jouvers*$rrows['nb_participants_rand'];
		$id_rand = $rrows['id_rand'];
		
		
		
		$update_rand = "UPDATE randstad SET ";
		if (isset($jouvers))         { $update_rand .= "jh_rand         = '" . mysqli_real_escape_string($db_connection, $jouvers). "' "; }
		$update_rand .= "WHERE id_rand = '" . mysqli_real_escape_string($db_connection, $id_rand) . "'";
		$update_rand  = mysqli_query($db_connection, $update_rand);
	  
		
	}
	}
    $query = "SELECT * FROM randstad";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['id_rand'] . '" data-name="' . $company['lot_rand'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['id_rand'] . '" data-name="' . $company['lot_rand'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $mysql_data[] = array(
          "lot_rand"          => $company['lot_rand'],
		  "societe_rand"          => $company['societe_rand'],
          "somme_cont_randstad"  => $company['somme_cont_randstad'],
		  "nb_email_rand"  => $company['nb_email_rand'],
		  "debut"  => date("d/m/Y", strtotime($company['date_debut_rand'])),
		  "fin"  => date("d/m/Y", strtotime($company['date_fin_rand'])),
		  "nb_participants_rand"  => $company['nb_participants_rand'],
		  "jh_rand"  => $company['jh_rand'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_rand_add'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM randstad WHERE id_rand = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
			$mysql_data[] = array(
			"lot"          => $company['lot_rand'],
			"somme"  => $company['somme_cont_randstad'],
			"nb"  => $company['nb_email_rand'],
			"descri"  => $company['info_rand'],
			"societe"  => $company['societe_rand'],
			"participant"       => $company['nb_participants_rand'],
			"debut"       => $company['date_debut_rand'],
			"fin"       => $company['date_fin_rand']
          );
        }
      }
    }
  
  } elseif ($job == 'add_rand'){
    
    // Add company
    $query = "INSERT INTO randstad SET ";
		if (isset($_GET['lot']))         { $query .= "lot_rand         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "', "; }
		if (isset($_GET['societe'])) { $query .= "societe_rand = '" . mysqli_real_escape_string($db_connection, $_GET['societe']) . "', "; }
		if (isset($_GET['somme'])) { $query .= "somme_cont_randstad = '" . mysqli_real_escape_string($db_connection, $_GET['somme']) . "', "; }
		if (isset($_GET['descri']))   { $query .= "info_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['descri'])   . "', "; }
		if (isset($_GET['nb']))   { $query .= "nb_email_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['nb'])   . "', "; }
		if (isset($_GET['participant']))   { $query .= "nb_participants_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['participant'])   . "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
		
		
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_rand'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE randstad SET ";
		if (isset($_GET['lot']))         { $query .= "lot_rand         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "', "; }
		if (isset($_GET['societe'])) { $query .= "societe_rand = '" . mysqli_real_escape_string($db_connection, $_GET['societe']) . "', "; }
		if (isset($_GET['descri']))   { $query .= "info_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['descri'])   . "', "; }
		if (isset($_GET['somme'])) { $query .= "somme_cont_randstad = '" . mysqli_real_escape_string($db_connection, $_GET['somme']) . "', "; }
		if (isset($_GET['nb']))   { $query .= "nb_email_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['nb'])   . "', "; }
		if (isset($_GET['participant']))   { $query .= "nb_participants_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['participant'])   . "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_rand   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
      $query .= "WHERE id_rand = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_rand'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM randstad WHERE id_rand = '" . mysqli_real_escape_string($db_connection, $id) . "'";
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