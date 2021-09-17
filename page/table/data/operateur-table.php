<?php 
include '../config/dbc.php';
page_protect();
if(!checkAdmin()) {
header("Location: ../index.php");
exit();
}
if (isset($_GET['id_stat'])){
  $id_stat = $_GET['id_stat'];
  if (!is_numeric($id_stat)){
	$id_stat = '';
  }
}

echo '<p>
  Loading content asynchronously! (Lazy loading content)<br>
</p>
<table class="table table-bordered table-condensed table-striped"><tr>
    <th>Opérateur</th>
    <th>Statut</th>
    <th>Traitement</th>
	<th>NB lignes</th>
  </tr>';
$query_doc_acide = "SELECT cat_synthese_acide.id_cat_synthese_acide, cat_synthese_acide.intervenant_cat_acide, cat_synthese_acide.statut_cat_fichier, cat_acide.nom_cat_acide, cat_synthese_acide.id_cat_acide, IF(Weekday(cat_synthese_acide.date_fin_traitement) >= Weekday(cat_synthese_acide.date_debut_traitement),ROUND(CONCAT(hour(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)),'.',MINUTE(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement))) - DATEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)*16, 2),CONCAT(hour(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)),'.',MINUTE(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement))) - DATEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)*16 -2) AS datee FROM cat_synthese_acide INNER JOIN cat_acide ON cat_acide.id_cat_acide = cat_synthese_acide.id_cat_acide WHERE cat_synthese_acide.id_cat_acide = ".$id_stat."";
	
$query_doc_acide = mysqli_query($db_connection, $query_doc_acide);
if (!$query_doc_acide){
	$result  = 'Échec';
	$message = 'Échec de requête';
	} else {
	$result  = 'success';
	$message = 'Succès de requête';
	
  while ($acide = mysqli_fetch_array($query_doc_acide)){		
	
	$query_calcul_ligne = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."'");
	if (!$query_calcul_ligne){
	$message = 'Échec de requête';
	} else {
	$message = 'Succès de requête';
	}
	$rowligne = mysqli_num_rows($query_calcul_ligne);
	

	if ($acide['statut_cat_fichier'] == 'Cloturer'){
		$statut = '<center><span class="badge badge-success">CLOTURÉ</span></center>';
	}elseif ($acide['statut_cat_fichier'] == 'en cour'){
		$statut = '<center><span class="badge badge-info">EN COURS</span></center>';	
	}else{
		$statut = '<center><span class="badge badge-warning">EN ATTENTE</span></center>';	
	}
	
	$query_save_traitement = mysqli_query($db,"UPDATE cat_synthese_acide SET `traitement_detail` = '".$acide['datee']."' WHERE id_cat_synthese_acide = ".$acide['id_cat_synthese_acide']."");
	if (!$query_save_traitement){
	$result  = 'error';
	$message = 'Échec de requête';
	} else {
	$result  = 'success';
	$message = 'Succès de requête';
	}
	$traitement = '<center><strong>'.str_replace('.', 'h:', $acide['datee'].'min').'</strong></center>';
	
	echo'<td>'.$acide['intervenant_cat_acide'].'</td>';
	echo'<td>'.$statut.'</td>';
	echo'<td>'.$traitement.'</td>';
	echo'<td>'.$rowligne.'</td>';
	
	
  }
}
echo'</table>';
?>