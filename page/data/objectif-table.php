<?php 
include '../../config/dbc.php';
page_protect();
if(!checkAdmin()) {header("Location: ../../index.php");exit();}
$db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($db_connection, "utf8");
if (mysqli_connect_errno()){$message = 'Connexion à la base de données impossible : ' . mysqli_connect_error();}
$query_calcul_objectif = mysqli_query($db,"INSERT INTO objectif_acide SET `nbligne_objectif` = '".$_GET['nblignes']."', `nbheure_objectif` = '".$_GET['nbheure']."', section_objectif = ".$_GET['section']."");
if (!$query_calcul_objectif){$message = 'Échec de requête';} else {$message = 'Succès de requête';}
mysqli_close($db_connection);
?>