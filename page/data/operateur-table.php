<?php 
include '../../config/dbc.php';
page_protect();
if(!checkAdmin()) {
header("Location: ../../index.php");
exit();
}

if (isset($_GET['id_stat'])){
  $id_stat = $_GET['id_stat'];
  if (!is_numeric($id_stat)){
	$id_stat = '';
  }
}

$db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($db_connection, "utf8");
if (mysqli_connect_errno()){
$message = 'Connexion à la base de données impossible : ' . mysqli_connect_error();
}
echo '<p>
Statistiques rapides du fichier<br>
</p>
<table class="table table-bordered table-condensed table-striped"><tr>
<th>Opérateur</th>
<th>Statut</th>
<th>Total</th>
<th>OK</th>
<th>MODIF</th>
<th>SUPP</th>
<th>AJOUT</th>
</tr>';
  
$query_doc_acide = "SELECT cat_synthese_acide.id_cat_synthese_acide, cat_synthese_acide.intervenant_cat_acide, cat_synthese_acide.statut_cat_fichier, cat_acide.nom_cat_acide, cat_synthese_acide.id_cat_acide, IF(Weekday(cat_synthese_acide.date_fin_traitement) >= Weekday(cat_synthese_acide.date_debut_traitement),ROUND(CONCAT(hour(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)),'.',MINUTE(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement))) - DATEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)*16, 2),CONCAT(hour(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)),'.',MINUTE(TIMEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement))) - DATEDIFF(cat_synthese_acide.date_fin_traitement,cat_synthese_acide.date_debut_traitement)*16 -2) AS datee FROM cat_synthese_acide INNER JOIN cat_acide ON cat_acide.id_cat_acide = cat_synthese_acide.id_cat_acide WHERE cat_synthese_acide.id_cat_acide = ".$id_stat."";
	
$query_doc_acide = mysqli_query($db_connection, $query_doc_acide);
if (!$query_doc_acide){
	$result  = 'Échec';
	$message = 'Échec de requête';
	} else {
	$result  = 'success';
	$message = 'Succès de requête';
$count_affichage = mysqli_num_rows($query_doc_acide);

if($count_affichage > 0){
	
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
	
	if(!empty($acide['datee'])){
		$traitement = '<center><strong>'.str_replace('.', 'h:', $acide['datee'].'min').'</strong></center>';
		}else{$traitement = '<center><strong>X</strong></center>';}
	
	$query_calcul_ligne_OK = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '1'");
	$rowligne_ok = mysqli_num_rows($query_calcul_ligne_OK);
	
	$query_calcul_ligne_MODIF = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '2'");
	$rowligne_MODIF = mysqli_num_rows($query_calcul_ligne_MODIF);
	
	$query_calcul_ligne_SUPP = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '3'");
	$rowligne_SUPP = mysqli_num_rows($query_calcul_ligne_SUPP);
	
	$query_calcul_ligne_AJOUT = mysqli_query($db, "SELECT * FROM acide WHERE id_cat_acide = ".$acide['id_cat_acide']." AND `operateur_acide` = '".$acide['intervenant_cat_acide']."' AND `reporting` = '4'");
	$rowligne_AJOUT = mysqli_num_rows($query_calcul_ligne_AJOUT);
	
	echo'<tr>';
	echo'<td>'.$acide['intervenant_cat_acide'].'</td>';
	echo'<td>'.$statut.'</td>';
	echo'<td><b>'.$rowligne.'</b></td>';
	echo'<td>'.$rowligne_ok.'</td>';
	echo'<td>'.$rowligne_MODIF.'</td>';
	echo'<td>'.$rowligne_SUPP.'</td>';
	echo'<td>'.$rowligne_AJOUT.'</td>';
	echo'</tr>';
	
  }
}else{
echo '<td colspan="8"><b>Aucun opérateur pour le môment !<b></td>';
}
 mysqli_close($db_connection);

}
echo'</table>';
?>