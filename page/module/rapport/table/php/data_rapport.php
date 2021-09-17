<?php
$page = '';
if (empty($page)) {
 $page = "dbc";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/config/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../../../../config/".$page) && $page != 'index.php') {
       include("../../../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();
if(!checkAdmin()) {
die("Secured");
exit();
}

$job = '';
$id  = '';

if (isset($_GET['job'])){
	
  $job = $_GET['job'];
  
  if ($job == 'get_rapport_equipe' ||
      $job == 'get_rapport_stat_equipe'   ||
	  $job == 'get_rapport_cumul'   ||
      $job == 'edit_notif'  ||
      $job == 'delete_notif'){
		  
		if (isset($_GET['id'])){

			$id = $_GET['id'];
			if (!is_numeric($id)){
				$id = '';
			}
		}

		if (isset($_GET['equipe'])){

			$equipe_id = $_GET['equipe'];
			if (!is_numeric($equipe_id)){
				$equipe_id = '';
			}

		}

		if (isset($_GET['date'])){
		  $date = $_GET['date'];
		}
		
  } else {
	  
    $job = '';
	
  }
  
}

$mysql_data = array();

if ($job != ''){ 
  
if ($job == 'get_rapport_equipe'){
    
    try 
	{ 

	$PDO_query_equipe = $bdd->prepare("SELECT DISTINCT user_equipe.name_equipe,  user_equipe.id_equipe FROM user_equipe INNER JOIN users WHERE users.equipe_id = user_equipe.id_equipe AND user_equipe.admin_equipe = 0");

	$PDO_query_equipe->execute();

	while ($team = $PDO_query_equipe->fetch()){	
		
		$query = $bdd->prepare("SELECT COUNT(*) FROM users WHERE equipe_id = :equipe_id");
		$query->bindParam(":equipe_id", $team['id_equipe'], PDO::PARAM_INT);
		$query->execute();
		$nb_collab = $query->fetchColumn();
		$query->closeCursor();

		$nb_collab = $nb_collab.' Collab';

		$nom_equipe = '<a href="#'.$team['name_equipe'].'" title="">'.$team['name_equipe'].'</a>';

        $mysql_data[] = array(
			"equipe" => $nom_equipe,
			"nb" => $nb_collab
        );
	}

	$PDO_query_equipe->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';	

	}

	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}

	$bdd = null;
	$PDO_query_equipe = null; 
	
    
    
} elseif ($job == 'get_rapport_cumul'){
    
    try 
	{ 

	$PDO_query_equipe = $bdd->prepare("SELECT * FROM externe");

	$PDO_query_equipe->execute();

	while ($team = $PDO_query_equipe->fetch()){	
	
        $debut = date("d-m-Y", strtotime($team['debut']));
		$fin = date("d-m-Y", strtotime($team['fin']));
		
		$mysql_data[] = array(
			"demande" => $team['demandeur_ext'],
			"equipe" => $team['equipe_ext'],
			"operation" => $team['op_ext'],
			"nbcc" => $team['nb_cc_ext'],
			"obj" => $team['obj_ext'],
			"rea" => $team['rea_ext'],
			"nature" => $team['nature_ext'],
			"taux" => $team['taux_ext'],
			"jh" => $team['jh_ext'],
			"debut" => $debut,
			"fin" => $fin
        );
	}

	$PDO_query_equipe->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';	

	}

	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}

	$bdd = null;
	$PDO_query_equipe = null; 
	
    
    
}elseif ($job == 'get_rapport_stat_equipe'){

	//8
	/*try 
	{*/
		if($date == ''){
		
		$debut = '2005-01-01';
	$fin = '2100-01-01';


	}else{
	$debut = substr($date, 0,10);
	$fin = substr($date, 13,22);
}
		 
				if($equipe_id == 8){
				$query_rapport = $bdd->prepare("


				SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_debut FROM data_ie AS a WHERE a.date_calcul between :debut and :fin AND a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin AND n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide  AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide  AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide  AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin AND h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM acide AS x WHERE x.date_calcul between :debut and :fin AND x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin AND z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin AND y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT op_ext, nb_cc_ext, obj_ext, rea_ext, '**', fin, debut FROM externe AS b WHERE b.debut between :debut and :fin AND b.equipe_ext = 'LEAD GEN'
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Client - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin AND m.user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id)

				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Sicétè - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin AND m.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)

				");
				}elseif($equipe_id == 16){
				$query_rapport = $bdd->prepare("


				SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_debut FROM data_ie AS a WHERE a.date_calcul between :debut and :fin AND a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin AND n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin AND h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM acide AS x WHERE x.date_calcul between :debut and :fin AND x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin AND z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin AND y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Client - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin AND m.user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Sicétè - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin AND m.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				");
				}elseif($equipe_id == 2){
				$query_rapport = $bdd->prepare("


				SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_debut FROM data_ie AS a WHERE a.date_calcul between :debut and :fin AND a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin AND n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin AND h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM acide AS x WHERE x.date_calcul between :debut and :fin AND x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin AND z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin AND y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT op_ext, nb_cc_ext, obj_ext, rea_ext, '**', fin, debut FROM externe AS b WHERE b.debut between :debut and :fin AND b.equipe_ext = 'Qualité de données'
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Client - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin AND m.user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Sicétè - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin AND m.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				");
				}elseif($equipe_id == 6){
				$query_rapport = $bdd->prepare("


				SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_debut FROM data_ie AS a WHERE a.date_calcul between :debut and :fin AND a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin AND n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin AND h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM acide AS x WHERE x.date_calcul between :debut and :fin AND x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin AND z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin AND y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT op_ext, nb_cc_ext, obj_ext, rea_ext, '**', fin, debut FROM externe AS b WHERE b.debut between :debut and :fin AND (b.equipe_ext = 'Industrie Explorer' OR b.equipe_ext = 'Automobile')
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Client - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin AND m.user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Sicétè - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin AND m.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				");
				}elseif($equipe_id == 21){
				$query_rapport = $bdd->prepare("


				SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) AS date_debut FROM data_ie AS a WHERE a.date_calcul between :debut and :fin AND a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM data_siret AS n WHERE n.date_calcul between :debut and :fin AND n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM hb_acide AS h WHERE h.date_calcul between :debut and :fin AND h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM acide AS x WHERE x.date_calcul between :debut and :fin AND x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM collectivite_fiche AS z WHERE z.date_calcul between :debut and :fin AND z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update WHERE user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM nomination_acide AS y WHERE y.date_calcul between :debut and :fin AND y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Client - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id_contact <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting_contact != 0 AND user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul_contact between :debut and :fin AND m.user_id_contact IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT CONCAT('Partie Sicétè - ', nom_cat) FROM client_cat WHERE id_cat = m.id_cat), (SELECT COUNT(DISTINCT(user_id)) FROM `client_traitement` WHERE id_cat = m.id_cat AND user_id <> 0), (SELECT count(*) FROM client_traitement WHERE id_cat = m.id_cat), (SELECT count(*) FROM client_traitement WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat = m.id_cat), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MAX(date_fin_traitement) AS date_fin FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update WHERE lot_id = m.id_cat AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)) FROM client_traitement AS m WHERE m.date_calcul between :debut and :fin AND m.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				");
				}
				
	$query_rapport->bindParam(":equipe_id", $equipe_id, PDO::PARAM_INT);
	
	$query_rapport->bindParam(":debut", $debut, PDO::PARAM_STR);
	$query_rapport->bindParam(":fin", $fin, PDO::PARAM_STR);
	$query_rapport->execute();
	
	while ($rapport = $query_rapport->fetch()){
		
			if($rapport['datee'] == '**'){
			
			$query = $bdd->prepare("SELECT jh_ext FROM externe WHERE op_ext = :op_ext");
			$query->bindParam(":op_ext", $rapport['Mission'], PDO::PARAM_STR);	
			$query->execute();
			$rapport_statique = $query->fetch();
			$neuw_jh = str_replace(",",".",$rapport_statique['jh_ext']);
			$query->closeCursor();
			$traitement = '<strong>'.$rapport['datee'].'</strong>';
			
			}else{
				
			if($rapport['datee'] == NULL){$datee = '00:49:18';}else{$datee = $rapport['datee'];}
			$traitement = '<strong>'.$datee.'</strong>';
			$pieces = explode(":", $datee);		
			$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);		
			$jh = round($duree_decimal/8, 2);
			
			$ajout_20 = ($jh*35)/100;
			$neuw_jh = round($ajout_20 + $jh, 2);
			}
		$perf = round($neuw_jh/$rapport['CC'], 2);
		
		
		$mysql_data[] = array(
		
		  "mission"  => $rapport['Mission'],
		  "cc"  => $rapport['CC'],
		  "global"  => $rapport['globall'],
		  "traite"  => $rapport['traite'],
		  "datee"  => $traitement,
		  "jh"  => $neuw_jh,
		  "perf"  => $perf
        );
		
	}
	  
    $query_rapport->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';
	/*}

	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}*/

	$bdd = null;
	$query_rapport = null; 

} elseif ($job == 'add_orgi'){


} elseif ($job == 'edit_notif'){


} elseif ($job == 'delete_notif'){


}
}
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;


/*SELECT DISTINCT(SELECT nom_cat_ie FROM data_cat_ie WHERE id_cat_ie = a.id_cat_ie) AS Mission, (SELECT COUNT(DISTINCT(user_id)) FROM `data_ie` WHERE id_cat_ie = a.id_cat_ie AND user_id <> 0) AS CC, (SELECT count(*) FROM data_ie WHERE id_cat_ie = a.id_cat_ie) AS globall, (SELECT count(*) FROM data_ie WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_ie = a.id_cat_ie) AS traite, (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS datee, (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS date_fin, (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_ie WHERE id_cat_ie = a.id_cat_ie) AS date_debut, (SELECT nbligne_objectif FROM data_cat_synthese_fiche_obj_ie WHERE debut_objectf <= a.date_calcul AND fin_objectif >= a.date_calcul ORDER BY id_objectif DESC LIMIT 0, 1) AS ligne FROM data_ie AS a WHERE a.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_siretisation FROM data_cat_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT COUNT(DISTINCT(user_id)) FROM `data_siret` WHERE id_cat_siretisation = n.id_cat_siretisation AND user_id <> 0), (SELECT count(*) FROM data_siret WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT count(*) FROM data_siret WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_siretisation = n.id_cat_siretisation), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT MAX(date_fin_traitement) AS date_fin FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT MIN(date_debut_traitement) AS date_debut FROM data_cat_synthese_fiche_update_siretisation WHERE id_cat_siretisation = n.id_cat_siretisation), (SELECT nbligne_objectif FROM data_cat_synthese_fiche_obj_siretisation WHERE debut_objectf <= n.date_calcul AND fin_objectif >= n.date_calcul ORDER BY id_objectif DESC LIMIT 0, 1) FROM data_siret AS n WHERE n.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM hb_cat_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `hb_acide` WHERE id_cat_acide = h.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM hb_acide WHERE id_cat_acide = h.id_cat_acide), (SELECT count(*) FROM hb_acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = h.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide), (SELECT MAX(date_fin_traitement) AS date_fin FROM hb_cat_synthese_fiche_update WHERE  id_cat_acide = h.id_cat_acide), (SELECT MIN(date_debut_traitment) AS date_debut FROM hb_cat_synthese_fiche_update WHERE id_cat_acide = h.id_cat_acide), (SELECT nbligne_objectif FROM hb_cat_synthese_fiche_obj WHERE debut_objectf <= h.date_calcul AND fin_objectif >= h.date_calcul ORDER BY id_objectif DESC LIMIT 0, 1) FROM hb_acide AS h WHERE h.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT nom_cat_acide FROM cat_acide WHERE id_cat_acide = x.id_cat_acide), (SELECT COUNT(DISTINCT(user_id)) FROM `acide` WHERE id_cat_acide = x.id_cat_acide AND user_id <> 0), (SELECT count(*) FROM acide WHERE id_cat_acide = x.id_cat_acide), (SELECT count(*) FROM acide WHERE reporting != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND id_cat_acide = x.id_cat_acide), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide), (SELECT MAX(date_fin_traitement) AS date_fin FROM cat_synthese_fiche_update WHERE  linkedin_lot_id = x.id_cat_acide), (SELECT MIN(date_debut_traitment) AS date_debut FROM cat_synthese_fiche_update WHERE linkedin_lot_id = x.id_cat_acide), (SELECT nbligne_objectif FROM cat_synthese_fiche_obj WHERE debut_objectf <= x.date_calcul AND fin_objectif >= x.date_calcul ORDER BY id_objectif DESC LIMIT 0, 1) FROM acide AS x WHERE x.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT(SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id), (SELECT COUNT(DISTINCT(user_id)) FROM `collectivite_fiche` WHERE collect_lot_id = z.collect_lot_id AND user_id <> 0), (SELECT count(*) FROM collectivite_fiche WHERE collect_lot_id = z.collect_lot_id), (SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_statut != 0 AND user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id) AND collect_lot_id = z.collect_lot_id), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id), (SELECT MAX(date_fin_traitement) AS date_fin FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id), (SELECT MIN(date_debut_traitment) AS date_debut FROM collectivite_fiche_update WHERE collect_lot_id = z.collect_lot_id), (SELECT collect_lot_objectif FROM collectivite_lot WHERE collect_lot_id = z.collect_lot_id) FROM collectivite_fiche AS z WHERE z.user_id IN (SELECT id FROM users WHERE equipe_id = :equipe_id)
				UNION
				SELECT DISTINCT('Nomination'), (SELECT COUNT(DISTINCT(acide_intervenant_id_nomination)) FROM `nomination_acide`), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT count(*) FROM nomination_acide WHERE acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)), (SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update), (SELECT MAX(date_fin_traitement) AS date_fin FROM nomination_acide_update), (SELECT MIN(date_debut_traitment) AS date_debut FROM nomination_acide_update), (SELECT nbligne_objectif FROM nomination_acide_obj WHERE debut_objectf <= y.date_calcul AND fin_objectif >= y.date_calcul ORDER BY id_objectif DESC LIMIT 0, 1) FROM nomination_acide AS y WHERE y.acide_intervenant_id_nomination IN (SELECT id FROM users WHERE equipe_id = :equipe_id)*/
?>