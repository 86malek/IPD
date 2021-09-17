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
  if ($job == 'get_nomination' ||
      $job == 'get_nomination_add'   ||
      $job == 'add_nomination'   ||
      $job == 'edit_nomination'  ||
      $job == 'delete_nomination'){
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
  if ($job == 'get_nomination'){
    

	// Fonction permettant de compter le nombre de jours ouvrés entre deux dates
	function get_nb_open_days($date_start, $date_stop) {
	$arr_bank_holidays = array(); // Tableau des jours feriés
	
	// On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
	$diff_year = date('Y', $date_stop) - date('Y', $date_start);
	for ($i = 0; $i <= $diff_year; $i++) {
	$year = (int)date('Y', $date_start) + $i;
	// Liste des jours feriés
	$arr_bank_holidays[] = '1_1_'.$year; 
	$arr_bank_holidays[] = '14_1_'.$year; 
	$arr_bank_holidays[] = '25_7_'.$year; 
	$arr_bank_holidays[] = '12_9_'.$year; 
	$arr_bank_holidays[] = '13_9_'.$year;
	$arr_bank_holidays[] = '6_7_'.$year; 
	$arr_bank_holidays[] = '7_7_'.$year; 
	$arr_bank_holidays[] = '1_5_'.$year; 
	$arr_bank_holidays[] = '8_5_'.$year; 
	$arr_bank_holidays[] = '14_7_'.$year; 
	$arr_bank_holidays[] = '15_8_'.$year; 
	$arr_bank_holidays[] = '1_11_'.$year; 
	$arr_bank_holidays[] = '11_11_'.$year; 
	$arr_bank_holidays[] = '25_12_'.$year; 
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
  
	 
	$rs_nomination = "SELECT MIN(nomination_compagnes.date_contact_nomination_compagnes) AS min, MAX(nomination_compagnes.date_contact_nomination_compagnes) AS max, COUNT(nomination_compagnes.id_nomination_compagnes) AS production, nomination_compagnes.id_nomination_statistique, nomination_compagnes.effect_nomination_compagnes FROM nomination_compagnes, nomination_statistiques WHERE nomination_statistiques.id_nomination_statistique = nomination_compagnes.id_nomination_statistique group by nomination_compagnes.id_nomination_statistique";
	$rs_sql = mysqli_query($db_connection, $rs_nomination);
	if (!$rs_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows = mysqli_fetch_array($rs_sql)) {
			
			/*if($rrows['date_debut_comite']<>$rrows['date_fin_comite']){
					$jouvers = get_nb_open_days(strtotime($rrows['date_debut_comite']), strtotime($rrows['date_fin_comite']));
			}else{$jouvers = 1;}*/
			
			//$jouvers = $jouvers*$rrows['nb_participants_comite'];
			$id_nomination_stat = $rrows['id_nomination_statistique'];
			
			$somme_nomination_stat = $rrows['production'];
			
			$max_date_nomination_stat = $rrows['max'];
			
			$min_date_nomination_stat = $rrows['min'];
			
			$effectif_nomination_stat = $rrows['effect_nomination_compagnes'];
			
			$min = strtotime($min_date_nomination_stat);
			$max = strtotime($max_date_nomination_stat);
			 
			$nbJoursTimestamp = $max - $min;
			$nbJours = ($nbJoursTimestamp/86400)+1;
			
			$jh_nomination_stat = $nbJours*$effectif_nomination_stat;
			
			$prod_j_nomination_stat = $somme_nomination_stat/$jh_nomination_stat;
			
			$update_nomination_stat = "UPDATE nomination_statistiques SET ";
			if (isset($somme_nomination_stat))         { $update_nomination_stat .= "production_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $somme_nomination_stat). "', "; }
			if (isset($jh_nomination_stat))         { $update_nomination_stat .= "jh_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $jh_nomination_stat). "', "; }
			if (isset($prod_j_nomination_stat))         { $update_nomination_stat .= "production_j_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $prod_j_nomination_stat). "' "; }
			$update_nomination_stat .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id_nomination_stat) . "'";
			$update_nomination_stat  = mysqli_query($db_connection, $update_nomination_stat);
		  
			
		}
	}
	
	$rs_crea_nomination = "SELECT COUNT(nomination_compagnes.id_nomination_compagnes) AS crea, nomination_compagnes.id_nomination_statistique FROM nomination_compagnes, nomination_statistiques WHERE nomination_statistiques.id_nomination_statistique = nomination_compagnes.id_nomination_statistique AND nomination_compagnes.stat_contact_nomination_compagnes = 'CREA' group by nomination_compagnes.id_nomination_statistique";
	$rs_crea_sql = mysqli_query($db_connection, $rs_crea_nomination);
	if (!$rs_crea_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows_crea = mysqli_fetch_array($rs_crea_sql)) {
			$crea_nomination_stat = $rrows_crea['crea'];
			$id_nomination_stat = $rrows_crea['id_nomination_statistique'];
			$update_crea_nomination_stat = "UPDATE nomination_statistiques SET ";
			if (isset($crea_nomination_stat))         { $update_crea_nomination_stat .= "crea_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $crea_nomination_stat). "' "; }
			$update_crea_nomination_stat .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id_nomination_stat) . "'";
			$update_crea_nomination_stat  = mysqli_query($db_connection, $update_crea_nomination_stat);
		  
			
		}
	}
	
	$rs_co_nomination = "SELECT COUNT(nomination_compagnes.id_nomination_compagnes) AS co, nomination_compagnes.id_nomination_statistique FROM nomination_compagnes, nomination_statistiques WHERE nomination_statistiques.id_nomination_statistique = nomination_compagnes.id_nomination_statistique AND nomination_compagnes.stat_contact_nomination_compagnes = 'CO' group by nomination_compagnes.id_nomination_statistique";
	$rs_co_sql = mysqli_query($db_connection, $rs_co_nomination);
	if (!$rs_crea_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows_co = mysqli_fetch_array($rs_co_sql)) {
			$co_nomination_stat = $rrows_co['co'];
			$id_nomination_stat = $rrows_co['id_nomination_statistique'];
			$update_co_nomination_stat = "UPDATE nomination_statistiques SET ";
			if (isset($co_nomination_stat))         { $update_co_nomination_stat .= "co_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $co_nomination_stat). "' "; }
			$update_co_nomination_stat .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id_nomination_stat) . "'";
			$update_co_nomination_stat  = mysqli_query($db_connection, $update_co_nomination_stat);
		  
			
		}
	}
	
	$rs_supp_nomination = "SELECT COUNT(nomination_compagnes.id_nomination_compagnes) AS supp, nomination_compagnes.id_nomination_statistique FROM nomination_compagnes, nomination_statistiques WHERE nomination_statistiques.id_nomination_statistique = nomination_compagnes.id_nomination_statistique AND nomination_compagnes.stat_contact_nomination_compagnes = 'SUPP' group by nomination_compagnes.id_nomination_statistique";
	$rs_supp_sql = mysqli_query($db_connection, $rs_supp_nomination);
	if (!$rs_supp_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows_supp = mysqli_fetch_array($rs_supp_sql)) {
			$supp_nomination_stat = $rrows_supp['supp'];
			$id_nomination_stat = $rrows_supp['id_nomination_statistique'];
			$update_supp_nomination_stat = "UPDATE nomination_statistiques SET ";
			if (isset($supp_nomination_stat))         { $update_supp_nomination_stat .= "supp_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $supp_nomination_stat). "' "; }
			$update_supp_nomination_stat .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id_nomination_stat) . "'";
			$update_supp_nomination_stat  = mysqli_query($db_connection, $update_supp_nomination_stat);
		  
			
		}
	}
	
	$rs_ok_nomination = "SELECT COUNT(nomination_compagnes.id_nomination_compagnes) AS ok, nomination_compagnes.id_nomination_statistique FROM nomination_compagnes, nomination_statistiques WHERE nomination_statistiques.id_nomination_statistique = nomination_compagnes.id_nomination_statistique AND nomination_compagnes.stat_contact_nomination_compagnes = 'OK' group by nomination_compagnes.id_nomination_statistique";
	$rs_ok_sql = mysqli_query($db_connection, $rs_ok_nomination);
	if (!$rs_ok_sql){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
		while ($rrows_ok = mysqli_fetch_array($rs_ok_sql)) {
			$ok_nomination_stat = $rrows_ok['ok'];
			$id_nomination_stat = $rrows_ok['id_nomination_statistique'];
			$update_ok_nomination_stat = "UPDATE nomination_statistiques SET ";
			if (isset($ok_nomination_stat))         { $update_ok_nomination_stat .= "ok_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $ok_nomination_stat). "' "; }
			$update_ok_nomination_stat .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id_nomination_stat) . "'";
			$update_ok_nomination_stat  = mysqli_query($db_connection, $update_ok_nomination_stat);
		  
			
		}
	}
	
    $query = "SELECT * FROM nomination_statistiques";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['id_nomination_statistique'] . '" data-name="' . $company['nom_nomination_statistique'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['id_nomination_statistique'] . '" data-name="' . $company['nom_nomination_statistique'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $mysql_data[] = array(
          "production_nomination_statistique"          => $company['production_nomination_statistique'],
		  "nom_nomination_statistique"          => $company['nom_nomination_statistique'],
		  "jh_nomination_statistique"          => $company['jh_nomination_statistique'],
		  "production_j_nomination_statistique"          => $company['production_j_nomination_statistique'],
		  "crea_nomination_statistique"          => $company['crea_nomination_statistique'],
		  "co_nomination_statistique"          => $company['co_nomination_statistique'],
		  "supp_nomination_statistique"          => $company['supp_nomination_statistique'],
		  "ok_nomination_statistique"          => $company['ok_nomination_statistique'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_nomination_add'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM nomination_statistiques WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
			$mysql_data[] = array(
			"lot"          => $company['nom_nomination_statistique']
          );
        }
      }
    }
  
  } elseif ($job == 'add_nomination'){
    
    // Add company
    $query = "INSERT INTO nomination_statistiques SET ";
		if (isset($_GET['lot']))         { $query .= "nom_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "' "; }
		
		
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_nomination'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE nomination_statistiques SET ";
		if (isset($_GET['lot']))         { $query .= "nom_nomination_statistique         = '" . mysqli_real_escape_string($db_connection, $_GET['lot'])         . "' "; }
      $query .= "WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_nomination'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM nomination_statistiques WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
	  $query2 = "DELETE FROM nomination_compagnes WHERE id_nomination_statistique = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query2 = mysqli_query($db_connection, $query2);
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