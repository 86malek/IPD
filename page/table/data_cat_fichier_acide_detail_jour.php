<?php
include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_cat_fichier_detail' ||
      $job == 'get_cat_fichier_add'   ||
      $job == 'add_cat_fichier'   ||
      $job == 'edit_cat_fichier'  ||
      $job == 'delete_cat_fichier'){
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

if (isset($_GET['id_stat'])){
  $id_stat = $_GET['id_stat'];
  if (!is_numeric($id_stat)){
	$id_stat = '';
  }
}

$mysql_data = array();

if ($job != ''){
  
  $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'Échec';
    $message = 'Connexion à la base de données impossible : ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_cat_fichier_detail'){
    
	$query_update_sum_biss_hour = "SELECT id_cat_synthese_acide, date_debut_traitement, date_fin_traitement FROM cat_synthese_acide_details";
	
	$query_update_sum_biss_hour = mysqli_query($db_connection, $query_update_sum_biss_hour);
	
    while ($update_sum_biss_hour = mysqli_fetch_array($query_update_sum_biss_hour)){
		
	//$go = biss_hours($update_sum_biss_hour['date_debut_traitement'],$update_sum_biss_hour['date_fin_traitement']);
	$go = get_working_hours($update_sum_biss_hour['date_debut_traitement'],$update_sum_biss_hour['date_fin_traitement']);
	$query_update_sum_biss_hour_2 = "UPDATE cat_synthese_acide_details SET ";
	
	$query_update_sum_biss_hour_2 .= "traitement_detail = '" . mysqli_real_escape_string($db_connection, $go). "' WHERE date_fin_traitement <> '0000-00-00 00:00:00' AND id_cat_synthese_acide = " . mysqli_real_escape_string($db_connection, $update_sum_biss_hour['id_cat_synthese_acide']). "";	
	
	mysqli_query($db_connection, $query_update_sum_biss_hour_2);
		
	}
	
	
    $query_doc_acide = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(cat_synthese_acide_details.traitement_detail))) AS datee, cat_synthese_acide_details.id_cat_synthese_acide, cat_synthese_acide_details.traitement_detail, cat_synthese_acide_details.date_debut_traitement, cat_synthese_acide_details.date_fin_traitement, cat_synthese_acide_details.date_calcul, cat_synthese_acide_details.id_cat_synthese_acide, cat_synthese_acide_details.intervenant_cat_acide, cat_acide.nom_cat_acide, cat_synthese_acide_details.id_cat_acide FROM cat_synthese_acide_details INNER JOIN cat_acide ON cat_acide.id_cat_acide = cat_synthese_acide_details.id_cat_acide WHERE cat_synthese_acide_details.id_cat_acide = ".$id_stat." GROUP BY cat_synthese_acide_details.date_calcul, cat_synthese_acide_details.intervenant_cat_acide";
	
	
	
    $query_doc_acide = mysqli_query($db_connection, $query_doc_acide);
    if (!$query_doc_acide){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		
		
		
      	while ($acide = mysqli_fetch_array($query_doc_acide)){
			
		  
		 	
		$query_calcul_ligne = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `date_fin_traitement` = '".$acide['date_calcul']."'");
		if (!$query_calcul_ligne){
		$message = 'Échec de requête';
		} else {
		$message = 'Succès de requête';
		}
		$rowligne = mysqli_num_rows($query_calcul_ligne);
		$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';	
		
		
		$query_save_traitement = mysqli_query($db,"UPDATE cat_synthese_acide_details SET `traitement_detail` = '".$acide['datee']."' WHERE id_cat_synthese_acide_details = ".$acide['id_cat_synthese_acide']."");
		if (!$query_save_traitement){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		
		$query_update_sum_biss_hour_global = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(traitement_detail))) AS datee FROM cat_synthese_acide_details WHERE id_cat_acide = " .$acide['id_cat_acide']. "";
		$query_update_sum_biss_hour_global = mysqli_query($db_connection, $query_update_sum_biss_hour_global);
		$update_sum_biss_hour_global = mysqli_fetch_array($query_update_sum_biss_hour_global);
		
		$query_update_sum_biss_hour_2_1 = "UPDATE cat_acide SET ";
		$query_update_sum_biss_hour_2_1 .= "traitement_detail = '" . $update_sum_biss_hour_global['datee']. "' WHERE id_cat_acide = ".$acide['id_cat_acide']."";	
		mysqli_query($db_connection, $query_update_sum_biss_hour_2_1);
		
		if(!empty($acide['datee'])){			
		$traitement = '<strong>'.$acide['datee'].'</strong>';
		}else{$traitement = '<strong>X</strong>';}
		
		$query_calcul_ligne_OK = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '1' AND `date_fin_traitement` = '".$acide['date_calcul']."'");
		$rowligne_ok = mysqli_num_rows($query_calcul_ligne_OK);
		
		$query_calcul_ligne_MODIF = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '2' AND `date_fin_traitement` = '".$acide['date_calcul']."'");
		$rowligne_MODIF = mysqli_num_rows($query_calcul_ligne_MODIF);
		
		$query_calcul_ligne_SUPP = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '3' AND `date_fin_traitement` = '".$acide['date_calcul']."'");
		$rowligne_SUPP = mysqli_num_rows($query_calcul_ligne_SUPP);
		
		$query_calcul_ligne_AJOUT = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '4' AND `date_fin_traitement` = '".$acide['date_calcul']."'");
		$rowligne_AJOUT = mysqli_num_rows($query_calcul_ligne_AJOUT);		
		if($rowligne > 0){
		if(!empty($acide['datee'])){
			
		$query_ecart = "SELECT nbligne_objectif, nbheure_objectif FROM objectif_acide WHERE section_objectif = 1 ORDER BY id_objectif DESC LIMIT 0, 1";
		$query_ecart = mysqli_query($db_connection, $query_ecart);
		if (!$query_ecart){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		$ecart = mysqli_fetch_array($query_ecart);
			
		$ligne = $ecart['nbligne_objectif'];
		$heure = $ecart['nbheure_objectif']-0.5;			
		}		


		$pieces = explode(":", $acide['datee']);
		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
		
		$obj_ideal = $ligne/$heure;
		
		$ideal = $obj_ideal*round($duree_decimal, 1);
		
		$ecart_neutre = ($duree_decimal/$heure)*$ligne;
		
		$ecart_brut = (($rowligne - $ideal)/$ideal)*100;
		$ecart_final_base = round($ecart_brut);	
		$ecart_final = round($ecart_brut).'%';
		
		$query_insert_ecart = "UPDATE cat_synthese_acide_details SET ";
		$query_insert_ecart .= "ecart_journalier = '" . mysqli_real_escape_string($db_connection, $ecart_final_base). "' WHERE id_cat_synthese_acide = ".mysqli_real_escape_string($db_connection, $acide['id_cat_synthese_acide'])." AND ecart_journalier = 0 AND traitement_detail IS NULL";
		mysqli_query($db_connection, $query_insert_ecart);
		
		$pieces = explode(":", $acide['datee']);
		$dmt = round($duree_decimal/$rowligne, 2).'m';
		
		}else{$ecart_final = '<center><strong>En cours de calcul</strong></center>';$dmt = '<center><strong>En cours de calcul</strong></center>';}
		}else{$ecart_final = '<center><strong>Aucune valeur</strong></center>';$dmt = '<center><strong>Aucune valeur</strong></center>';}
		
		if (empty($acide['nom_cat_acide'])) {
		$fichier = '<span class="badge badge-outline-danger mb-3 mr-3">Aucun fichier</span>';
		}else{
		$fichier = '<span class="badge badge-sm badge-outline-primary mb-3 mr-3">'.$acide['nom_cat_acide'].'</span>';	
		}	
		
        $mysql_data[] = array(
          "nom"          => $fichier,
		  "collab"  => $acide['intervenant_cat_acide'],
		  "temps"  => $traitement,
          "ligne"     => $rowligne_style,
		  "ok"     => $rowligne_ok,
		  "modif"     => $rowligne_MODIF,
		  "supp"     => $rowligne_SUPP,
		  "ajout"     => $rowligne_AJOUT,
		  "dmt"     => $dmt,
		  "ecart"     => $ecart_final,
		  "date"     => $acide['date_calcul']
        );
		
      }
    }
    
  } elseif ($job == 'get_cat_fichier_add'){
    
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
      $query = "SELECT * FROM cat_acide WHERE id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
			$result  = 'Échec';
			$message = 'Échec de requête';
			} else {
			$result  = 'success';
			$message = 'Succès de requête';
        	while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "nom"  => $company['nom_cat_acide']
          );
        }
      }
    }
  
  } elseif ($job == 'add_cat_fichier'){
    
    $query = "INSERT INTO cat_acide SET ";
    if (isset($_GET['nom']))         { $query .= "nom_cat_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_cat_fichier'){
    
    if ($id == ''){
      $result  = 'Échec';
      $message = 'Échec id';
    } else {
      $query = "UPDATE cat_acide SET ";
		if (isset($_GET['nom']))         { $query .= "nom_cat_acide         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
      $query .= "WHERE id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_cat_fichier'){
  
    if ($id == ''){
		
      $result  = 'Échec';
      $message = 'Échec id';
	  
    } else {
		
      $query = "DELETE FROM cat_acide WHERE id_cat_acide = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
	  
      if (!$query){
			$result  = 'Échec';
			$message = 'Échec de requête';
			} else {
			mysql_query("DELETE FROM acide WHERE id_cat_acide = ".$id."") or die(mysql_error());	
			unlink("../ftp/server/php/files/acide/".$_GET['cat']);
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