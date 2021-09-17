<?php
include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_cat_fichier' ||
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
  }else{$job = '';}
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
  
  if ($job == 'get_cat_fichier'){
    
    
    $query_doc_acide = "SELECT date_fin_traitement, date_debut_traitement, intervenant_cat_acide, statut_cat_fichier, fichier_cat_acide, nom_cat_acide, id_cat_acide, fichier_cat_acide, traitement_detail FROM cat_acide";
    $query_doc_acide = mysqli_query($db_connection, $query_doc_acide);
    	if (!$query_doc_acide){
		$result  = 'Échec';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		
      	while ($acide = mysqli_fetch_array($query_doc_acide)){
		
		$query_update_date = "SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitement) AS date_debut FROM cat_synthese_acide_details WHERE id_cat_acide = " .$acide['id_cat_acide']. "";
		$query_update_date = mysqli_query($db_connection, $query_update_date);
		$update_date = mysqli_fetch_array($query_update_date);
		
		$query_update_date_1 = "UPDATE cat_acide SET ";
		$query_update_date_1 .= "date_debut_traitement = '" . $update_date['date_debut']. "' ,";
		$query_update_date_1 .= "date_fin_traitement = '" . $update_date['date_fin']. "' WHERE id_cat_acide = ".$acide['id_cat_acide']."";
		mysqli_query($db_connection, $query_update_date_1);
		  
		/*if (empty($acide['intervenant_cat_acide'])) {
		$intervenant = '<center><span  class="badge badge-bittersweet mb-3 mr-3">Non attribuer</span></center>';
		}else{
		$intervenant = '<center><span class="badge badge-shamrock mb-3 mr-3">'.$acide['intervenant_cat_acide'].'</span></center>';	
		}*/
		
		$query_somme_total_ligne = mysqli_query($db, "SELECT * FROM `acide` WHERE id_cat_acide = ".$acide['id_cat_acide']."");
		if (!$query_somme_total_ligne){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		$somme_total = mysqli_num_rows($query_somme_total_ligne);
		
		$query_somme_traite_ligne = mysqli_query($db, "SELECT * FROM `acide` WHERE id_cat_acide = ".$acide['id_cat_acide']." AND reporting IS NOT NULL");
		if (!$query_somme_traite_ligne){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		$somme_traite = mysqli_num_rows($query_somme_traite_ligne);
		
		$query_somme_collab = mysqli_query($db, "SELECT * FROM `cat_synthese_acide` WHERE id_cat_acide = ".$acide['id_cat_acide']."");
		if (!$query_somme_collab){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		$somme_collab = mysqli_num_rows($query_somme_collab);
		
		$query_calcul_update = mysqli_query($db, "SELECT * FROM cat_synthese_acide WHERE id_cat_acide IN (SELECT id_cat_acide FROM cat_synthese_acide WHERE id_cat_acide  = ".$acide['id_cat_acide'].")");
		if (!$query_calcul_update){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		$rowcountupdate = mysqli_num_rows($query_calcul_update);
		
		if ($rowcountupdate > 0){
	
			$query_calcul_statut = mysqli_query($db, "SELECT * FROM cat_synthese_acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `niveau` <> 2");
			if (!$query_calcul_statut){
			$result  = 'error';
			$message = 'Échec de requête';
			} else {
			$result  = 'success';
			$message = 'Succès de requête';
			}
			$rowcountst = mysqli_num_rows($query_calcul_statut);
		
			if ($rowcountst > 0){
				mysqli_query($db,"UPDATE cat_acide SET `statut_cat_fichier` = 'EN COURS' WHERE id_cat_acide = ".$acide['id_cat_acide']."") or die(mysqli_connect_error());
				$statut = '<span class="badge badge-primary">EN COURS</span>';
			}elseif ($rowcountst == 0){
				$verif_ligne = mysqli_query($db,"SELECT * FROM acide WHERE reporting IS NULL AND id_cat_acide =".$acide['id_cat_acide']."") or die(mysqli_connect_error());
				$verif_ligne = mysqli_num_rows($verif_ligne);
				if($verif_ligne > 0){
				mysqli_query($db,"UPDATE cat_acide SET `statut_cat_fichier` = 'EN PROGRESSION' WHERE id_cat_acide = ".$acide['id_cat_acide']."") or die(mysqli_connect_error());
				$statut = '<span class="badge badge-bittersweet">EN PROGRESSION</span>';
				}else{
				mysqli_query($db,"UPDATE cat_acide SET `statut_cat_fichier` = 'CLOTURÉ' WHERE id_cat_acide = ".$acide['id_cat_acide']."") or die(mysqli_connect_error());
				$statut = '<span class="badge badge-shamrock">CLOTURÉ</span>';	
				}
				
			}
		}else{
			mysqli_query($db,"UPDATE cat_acide SET `statut_cat_fichier` = 'EN ATTENTE' WHERE id_cat_acide = ".$acide['id_cat_acide']."") or die(mysqli_connect_error());
			$statut = '<span class="badge badge-warning">EN ATTENTE</span>';	
		}
		
		
		$query_up_stat = mysqli_query($db, "SELECT statut_cat_fichier FROM `cat_acide` WHERE id_cat_acide = ".$acide['id_cat_acide']."");
		if (!$query_up_stat){
		$result  = 'error';
		$message = 'Échec de requête';
		} else {
		$result  = 'success';
		$message = 'Succès de requête';
		}
		$update = mysqli_fetch_array($query_up_stat);
			
		if (empty($acide['fichier_cat_acide'])) {
		$fichier = '<span class="badge badge-outline-danger mb-3 mr-3">Aucun fichier</span>';
		}else{
		$fichier = '<span class="badge badge-sm badge-outline-primary mb-3 mr-3"><a href="http://10.9.6.7/ipd/page/ftp/server/php/files/acide/'.$acide['fichier_cat_acide'].'" title="'.$acide['fichier_cat_acide'].'">'.$acide['fichier_cat_acide'].'</a></span>';	
		}
		 
		 
        $functions  = '';
		
		if ($update['statut_cat_fichier'] == 'EN ATTENTE') {
			
        $functions .= '<a href="acide_ajout.php?mode=update&id=' . $acide['id_cat_acide'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';
		
		}else{$functions .= '<a href="LinkedinBiblioDetails-' . $acide['id_cat_acide'] . '"><span class="badge badge-primary mb-3 mr-3">Cumul</span></a> <a href="LinkedinBiblioDetailsJour-' . $acide['id_cat_acide'] . '"><span class="badge badge-primary mb-3 mr-3">Journalier</span></a> <a href="LinkedinBiblio-' . $acide['id_cat_acide'] . '"><span class="badge badge-primary mb-3 mr-3">Tableau des données</span></a>';}
		
		if ($update['statut_cat_fichier'] == 'EN ATTENTE') {
        $functions .= '<a href="#" id="del" data-id="' . $acide['id_cat_acide'] . '" data-name="' . $acide['nom_cat_acide'] . '"  data-doc="' . $acide['fichier_cat_acide'] . '"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
		}		
		$functions .= '';
		
		
		if(!empty($acide['traitement_detail'])){			
			
		$traitement = '<strong>'.$acide['traitement_detail'].'</strong>';
		$somme_non_traite = $somme_total - $somme_traite;
		
		 
		
		$somme_traite_pourcentage = '<span class="badge badge-buttercup badge-rounded mb-3 mr-3">'.round(($somme_traite/$somme_total)*100).'%</span>';
		
		
		$pieces = explode(":", $acide['traitement_detail']);
		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
		
		
		$jh = round($duree_decimal/8, 2);
		
		}else{$traitement = '<strong>X</strong>';
		$jh = 'x';
		$somme_traite_pourcentage = 'x';
		}
		$operateur = '<a href="#"  data-id="' . $acide['id_cat_acide'] . '" id="operateur_affichage" class="badge badge-sm badge-outline-lasur mb-3 mr-3">Afficher</a>';
		
		

		if($acide['date_fin_traitement'] <> '0000-00-00 00:00:00'){$fin = date_change_format($acide['date_fin_traitement'],'Y-m-d H:i:s','d/m/Y');}else{$fin = '<strong>En attente</strong>';}
		if($acide['date_debut_traitement'] <> '0000-00-00 00:00:00'){$debut = date_change_format($acide['date_debut_traitement'],'Y-m-d H:i:s','d/m/Y');}else{$debut = '<strong>En attente</strong>';}
		
        $mysql_data[] = array(
		
		  "statut"  => $statut,
		  "total"  => $somme_total,
		  "traite"  => $somme_traite,
		  "pourcent"  => $somme_traite_pourcentage,
		  "fichier"  => $fichier,
		  "collab" => $somme_collab,
		  "fin"  => $fin,
		  "debut"  => $debut,
		  "temps"  => $traitement,
		  "jh"  => $jh,
          "functions"     => $functions
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
			mysqli_query($db,"DELETE FROM acide WHERE id_cat_acide = ".$id."") or die(mysqli_connect_error());	
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