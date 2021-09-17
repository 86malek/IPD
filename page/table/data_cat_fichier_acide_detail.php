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
    
    
    $query_doc_acide = "SELECT cat_synthese_acide.id_cat_synthese_acide, cat_synthese_acide.intervenant_cat_acide, cat_synthese_acide.statut_cat_fichier, cat_acide.nom_cat_acide, cat_synthese_acide.id_cat_acide, cat_synthese_acide.date_debut_traitement, cat_synthese_acide.date_fin_traitement FROM cat_synthese_acide INNER JOIN cat_acide ON cat_acide.id_cat_acide = cat_synthese_acide.id_cat_acide WHERE cat_synthese_acide.id_cat_acide = ".$id_stat."";


	
		
	
	
    $query_doc_acide = mysqli_query($db_connection, $query_doc_acide);
    if (!$query_doc_acide){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		
      while ($acide = mysqli_fetch_array($query_doc_acide)){
		  
		$query_update_sum_biss_hour = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(traitement_detail))) AS datee FROM cat_synthese_acide_details WHERE id_cat_acide = " .$acide['id_cat_acide']. " AND `intervenant_cat_acide` = '".$acide['intervenant_cat_acide']."'";
		$query_update_sum_biss_hour = mysqli_query($db_connection, $query_update_sum_biss_hour);
		$update_sum_biss_hour = mysqli_fetch_array($query_update_sum_biss_hour);
		
		$query_update_sum_biss_hour_2 = "UPDATE cat_synthese_acide SET ";
		$query_update_sum_biss_hour_2 .= "traitement_detail = '" . $update_sum_biss_hour['datee']. "' WHERE id_cat_synthese_acide = ".$acide['id_cat_synthese_acide']."";	
		mysqli_query($db_connection, $query_update_sum_biss_hour_2);
		
		
		$query_update_sum_biss_hour_global = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(traitement_detail))) AS datee FROM cat_synthese_acide WHERE id_cat_acide = " .$acide['id_cat_acide']. "";
		$query_update_sum_biss_hour_global = mysqli_query($db_connection, $query_update_sum_biss_hour_global);
		$update_sum_biss_hour_global = mysqli_fetch_array($query_update_sum_biss_hour_global);
		
		$query_update_sum_biss_hour_2_1 = "UPDATE cat_acide SET ";
		$query_update_sum_biss_hour_2_1 .= "traitement_detail = '" . $update_sum_biss_hour_global['datee']. "' WHERE id_cat_acide = ".$acide['id_cat_acide']."";	
		mysqli_query($db_connection, $query_update_sum_biss_hour_2_1);
			
	
		//$go = biss_hours($acide['date_debut_traitement'],$acide['date_fin_traitement']);

		$query_calcul_ligne = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."'");
		if (!$query_calcul_ligne){
		$message = 'Échec de requête';
		} else {
		$message = 'Succès de requête';
		}
		$rowligne = mysqli_num_rows($query_calcul_ligne);
		$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
	
		if ($acide['statut_cat_fichier'] == 'Cloturer'){
			$statut = '<span class="badge badge-success">CLOTURÉ</span>';
		}elseif ($acide['statut_cat_fichier'] == 'en cour'){
			$statut = '<span class="badge badge-info">EN COURS</span>';	
		}else{
			$statut = '<span class="badge badge-warning">EN ATTENTE</span>';	
		}
		
		/*$query_save_traitement = mysqli_query($db,"UPDATE cat_synthese_acide SET `traitement_detail` = '".$go."' WHERE id_cat_synthese_acide = ".$acide['id_cat_synthese_acide']."");
		if (!$query_save_traitement){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}*/
		
		if(!empty($update_sum_biss_hour['datee'])){			
		$traitement = '<strong>'.$update_sum_biss_hour['datee'].'</strong>';
		}else{$traitement = '<strong>X</strong>';}
		
		$query_calcul_ligne_OK = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '1'");
		$rowligne_ok = mysqli_num_rows($query_calcul_ligne_OK);
		
		$query_calcul_ligne_MODIF = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '2'");
		$rowligne_MODIF = mysqli_num_rows($query_calcul_ligne_MODIF);
		
		$query_calcul_ligne_SUPP = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '3'");
		$rowligne_SUPP = mysqli_num_rows($query_calcul_ligne_SUPP);
		
		$query_calcul_ligne_AJOUT = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '4'");
		$rowligne_AJOUT = mysqli_num_rows($query_calcul_ligne_AJOUT);	
		
		if (empty($acide['nom_cat_acide'])) {
		$fichier = '<span class="badge badge-outline-danger mb-3 mr-3">Aucun fichier</span>';
		}else{
		$fichier = '<span class="badge badge-sm badge-outline-primary mb-3 mr-3">'.$acide['nom_cat_acide'].'</span>';	
		}
		
		
		
        $mysql_data[] = array(
          "nom"          => $fichier,
		  "collab"  => $acide['intervenant_cat_acide'],
		  "statut"  => $statut,
		  "temps"  => $traitement,
          "ligne"     => $rowligne_style,
		  "ok"     => $rowligne_ok,
		  "modif"     => $rowligne_MODIF,
		  "supp"     => $rowligne_SUPP,
		  "ajout"     => $rowligne_AJOUT        );
		
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