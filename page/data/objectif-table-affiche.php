<?php 
include '../../config/dbc.php';
page_protect();
if(!checkAdmin()) {
header("Location: ../../index.php");
exit();
}
$db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($db_connection, "utf8");
$query_affichage_val_journaliere = "SELECT MAX(id_objectif) AS Max, nbligne_objectif, nbheure_objectif FROM objectif_acide WHERE section_objectif = 1";
$query_affichage_val_journaliere = mysqli_query($db_connection, $query_affichage_val_journaliere);
if (!$query_affichage_val_journaliere){$message = 'Échec de requête';} else {$message = 'Succès de requête';}
$valeur_journaliere = mysqli_fetch_array($query_affichage_val_journaliere);

echo 'Les valeurs actives à date, <strong>Linkedin</strong><br><br>';
echo '<h3>NB lignes : '.$valeur_journaliere['nbligne_objectif'].'</h3>';
echo '<h3>NB Heure : '.$valeur_journaliere['nbheure_objectif'].'H</h3>';
echo '<h4>and you get a dialog modal</h4>';
echo '<button type="button" class="btn btn-success" id="chargement">Changer les Valeurs</button>';

mysqli_close($db_connection);

?>