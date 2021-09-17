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
}

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_cat_fichier_detail'){
		  
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
  
  
  if ($job == 'get_cat_fichier_detail'){
	  
	  
	  
	  
	  
	try 
	{
	$query = $bdd->prepare("SELECT id_cat_synthese_acide, date_debut_traitement, date_fin_traitement FROM cat_synthese_acide_details");
	$query->execute();		
	while ($update_sum_biss_hour = $query->fetch()){
		$go = get_working_hours_2($update_sum_biss_hour['date_debut_traitement'],$update_sum_biss_hour['date_fin_traitement']);
		$query_update = $bdd->prepare("UPDATE cat_synthese_acide_details SET temps_sec = :temps_sec WHERE date_fin_traitement <> '0000-00-00 00:00:00' AND id_cat_synthese_acide = :id_cat_synthese_acide");
		$query_update->bindParam(":id_cat_synthese_acide", $update_sum_biss_hour['id_cat_synthese_acide'], PDO::PARAM_INT);	
		$query_update->bindParam(":temps_sec", $go, PDO::PARAM_INT);
		$query_update->execute();	
		$query_update->closeCursor();
	}	  
	$query->closeCursor();
		
	/*$query_jour = $bdd->prepare("SELECT cat_synthese_acide_details.id_cat_synthese_acide, cat_synthese_acide_details.traitement_detail, cat_synthese_acide_details.date_debut_traitement, cat_synthese_acide_details.date_fin_traitement, cat_synthese_acide_details.date_calcul, cat_synthese_acide_details.date_calcul, cat_synthese_acide_details.id_cat_synthese_acide, cat_synthese_acide_details.intervenant_cat_acide, cat_synthese_acide_details.id_intervenant_cat_acide, cat_acide.nom_cat_acide, cat_synthese_acide_details.id_cat_acide FROM cat_synthese_acide_details INNER JOIN cat_acide ON cat_acide.id_cat_acide = cat_synthese_acide_details.id_cat_acide WHERE cat_synthese_acide_details.id_cat_acide = ".$id_stat." GROUP BY cat_synthese_acide_details.date_calcul, cat_synthese_acide_details.intervenant_cat_acide");*/
	
	$query_jour = $bdd->prepare("SELECT acide.id_acide, acide.id_cat_acide, acide.reporting, acide.operateur_acide, acide.user_id, acide.date_calcul, acide.temps_sec, acide.etat FROM acide INNER JOIN cat_acide ON cat_acide.id_cat_acide = acide.id_cat_acide WHERE acide.id_cat_acide = :id_cat_acide AND acide.etat = 1 GROUP BY acide.date_calcul, acide.user_id");
	$query_jour->bindParam(":id_cat_acide", $id_stat, PDO::PARAM_INT);
	
	
	$query_jour->execute();
	
	while ($acide = $query_jour->fetch()){
	
	//SEC_TO_TIME(SUM(TIME_TO_SEC(cat_synthese_acide_details.traitement_detail))) AS datee, 
	if($id_stat == 2){
	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(traitement_detail))) AS datee, SUM(TIME_TO_SEC(traitement_detail)) AS minutes FROM cat_synthese_acide_details WHERE id_cat_acide = 2 AND id_intervenant_cat_acide = 65 AND date_calcul = '2018-03-21'");
	$query->execute();	
	$query_temps = $query->fetch();
	$query->closeCursor();
	}
	else{
	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee, SUM(temps_sec) AS minutes FROM cat_synthese_fiche_update WHERE linkedin_lot_id = :linkedin_lot_id AND user_id = :user_id AND date_calcul = :date_calcul");
	$query->bindParam(":linkedin_lot_id", $acide['id_cat_acide'], PDO::PARAM_INT);	
	$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
	$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
	$query->execute();	
	$query_temps = $query->fetch();
	$query->closeCursor();
	}
	
	if(!empty($query_temps['datee'])){			
	$traitement = '<strong>'.$query_temps['datee'].'</strong>';
	}else{$traitement = '<strong>X</strong>';}
		
		
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND user_id = :user_id AND date_calcul = :date_calcul AND etat = 1");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
			$query->execute();
			$rowligne = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_style = '<span class="badge badge-bittersweet mb-3 mr-3"><strong>'.$rowligne.'</strong></span>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 1 AND `date_calcul` = :date_calcul");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
			$query->execute();
			$rowligne_okk = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_ok = '<strong>'.$rowligne_okk.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 2 AND `date_calcul` = :date_calcul");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
			$query->execute();
			$rowligne_MODIFF = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_MODIF = '<strong>'.$rowligne_MODIFF.'</strong>';
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 3 AND `date_calcul` = :date_calcul");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
			$query->execute();
			$rowligne_SUPPP = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_SUPP = '<strong>'.$rowligne_SUPPP.'</strong>';
			
			
			$query = $bdd->prepare("SELECT count(*) FROM acide WHERE id_cat_acide = :id_cat_acide AND `user_id` = :user_id AND `reporting` = 4 AND `date_calcul` = :date_calcul");
			$query->bindParam(":id_cat_acide", $acide['id_cat_acide'], PDO::PARAM_INT);
			$query->bindParam(":user_id", $acide['user_id'], PDO::PARAM_INT);
			$query->bindParam(":date_calcul", $acide['date_calcul'], PDO::PARAM_STR);
			$query->execute();
			$rowligne_AJOUTT = $query->fetchColumn();
			$query->closeCursor();
			$rowligne_AJOUT = '<strong>'.$rowligne_AJOUTT.'</strong>';
			
			
			if($rowligne > 0){
			if(!empty($query_temps['datee'])){
			
			$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM cat_synthese_fiche_obj WHERE debut_objectf <= '".$acide['date_calcul']."' AND fin_objectif >= '".$acide['date_calcul']."' ORDER BY id_objectif DESC LIMIT 0, 1");
			$query->execute();
			$donnees = $query->fetch();
			$ligne = $donnees['nbligne_objectif'];
			$heure = $donnees['nbheure_objectif'];	
			$query->closeCursor();
	
			$pieces = explode(":", $query_temps['datee']);
			
			$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
			
			$obj_ideal = $ligne/$heure;
			
			$ideal = $obj_ideal*round($duree_decimal, 2);
			
			$ecart_neutre = ($duree_decimal/$heure)*$ligne;
			
			$ecart_brut = (($rowligne - $ideal)/$ideal)*100;
			$ecart_final_base = round($ecart_brut);	
			$ecart_final = round($ecart_brut).'%';
			
			$pieces = explode(":", $query_temps['datee']);
			$dmt = round($duree_decimal/$rowligne, 2).'m';

			// Calcul des ecarts journalier Sirine
			$heure_ecrat = $heure*60;

			$minutes = round($query_temps['minutes']/60);
			
			$une_ligne_objctif_ligne = round($heure_ecrat/$ligne,2);
			
			$temps_ligne_traite = $rowligne*$une_ligne_objctif_ligne;
			
			$ecart_nouveau = round((($temps_ligne_traite - $minutes)/$temps_ligne_traite)*100,1);
			if($ecart_nouveau > 0){
			$ecart_nouveau_style = '<span class="table__cell-up">'.$ecart_nouveau.'%</span>';
			}else{$ecart_nouveau_style = '<span class="table__cell-down">'.$ecart_nouveau.'%</span>';}
			
			}else{$ecart_final = '<center><strong>En cours de calcul</strong></center>';$dmt = '<center><strong>En cours de calcul</strong></center>';}
			}else{$ecart_final = '<center><strong>Aucune valeur</strong></center>';$dmt = '<center><strong>Aucune valeur</strong></center>';}
			
			$jour = date("d-m-Y", strtotime($acide['date_calcul']));	
			
			$mysql_data[] = array(
			  "collab"  => $acide['operateur_acide'],
			  "temps"  => $traitement,
			  "ligne"     => $rowligne_style,
			  "ok"     => $rowligne_ok,
			  "modif"     => $rowligne_MODIF,
			  "supp"     => $rowligne_SUPP,
			  "ajout"     => $rowligne_AJOUT,
			  "dmt"     => $dmt,
			  "ecart"     => $ecart_nouveau_style,
			  "date"     => $jour
			);
		
			
	}
	  
    $query_jour->closeCursor();
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
    
  }

}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>