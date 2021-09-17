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
	
	
	$query_jour = $bdd->prepare("SELECT client_traitement.id_client, client_traitement.id_cat, client_traitement.reporting_contact, client_traitement.operateur_contact, client_traitement.user_id_contact, client_traitement.date_calcul_contact FROM client_traitement INNER JOIN client_cat ON client_cat.id_cat = client_traitement.id_cat WHERE client_traitement.id_cat = :id_cat AND client_traitement.reporting_contact = 1 GROUP BY client_traitement.date_calcul_contact, client_traitement.user_id_contact");
	$query_jour->bindParam(":id_cat", $id_stat, PDO::PARAM_INT);
	
	
	$query_jour->execute();
	
	while ($acide = $query_jour->fetch()){
	
	//SEC_TO_TIME(SUM(TIME_TO_SEC(client_cat_synthese_details.traitement_detail))) AS datee, 
	
	$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = :lot_id AND user_id = :user_id AND date_calcul = :date_calcul");
	$query->bindParam(":lot_id", $acide['id_cat'], PDO::PARAM_INT);	
	$query->bindParam(":user_id", $acide['user_id_contact'], PDO::PARAM_INT);
	$query->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
	$query->execute();	
	$query_temps = $query->fetch();
	$query->closeCursor();
	
	if(!empty($query_temps['datee'])){			
	$traitement = '<strong>'.$query_temps['datee'].'</strong>';
	}else{$traitement = '<strong>X</strong>';}
		
		
			$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE reporting_contact <> 0 AND user_id_contact = :user_id AND date_calcul_contact = :date_calcul");
			$query_ligne_taiter->bindParam(":user_id", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_ligne_taiter->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_ligne_taiter->execute();
			$ligne_taiter_societe = $query_ligne_taiter->fetchColumn();
			$query_ligne_taiter->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 0 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_indispo = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 1 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_non_verif = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 2 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_quitter = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 3 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_ok = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 4 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_ok_modif = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 5 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_remplace = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 6 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_hc = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
			
			
			$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 7 AND date_calcul_contact = :date_calcul AND user_id_contact = :user_id_contact");
			$query_somme_traite_ligne->bindParam(":user_id_contact", $acide['user_id_contact'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_somme_traite_ligne->bindParam(":date_calcul", $acide['date_calcul_contact'], PDO::PARAM_STR);
			$query_somme_traite_ligne->execute();
			$somme_traite_ajouter = $query_somme_traite_ligne->fetchColumn();
			$query_somme_traite_ligne->closeCursor();
		
			
			
			
			if($somme_traite > 0){
			if(!empty($query_temps['datee'])){
			
			$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM client_cat_synthese_fiche_obj WHERE debut_objectf <= '".$acide['date_calcul_contact']."' AND fin_objectif >= '".$acide['date_calcul_contact']."' AND type = 1 ORDER BY id_objectif DESC LIMIT 0, 1");
			$query->execute();
			$donnees = $query->fetch();
			$ligne = $donnees['nbligne_objectif'];
			$heure = $donnees['nbheure_objectif'];	
			$query->closeCursor();
	
			$pieces = explode(":", $query_temps['datee']);
			
			$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);
			
			$obj_ideal = $ligne/$heure;
			
			$ideal = $obj_ideal*round($duree_decimal, 3);
			
			$ecart_neutre = ($duree_decimal/$heure)*$ligne;
			
			$ecart_brut = (($somme_traite - $ideal)/1);
			$ecart_final_base = round($ecart_brut);	
			$ecart_final = round($ecart_brut).'%';
			
			$pieces = explode(":", $query_temps['datee']);
			$dmt = round($duree_decimal/$somme_traite, 2).'m';
			
			}else{$ecart_final = '<center><strong>En cours de calcul</strong></center>';$dmt = '<center><strong>En cours de calcul</strong></center>';}
			}else{$ecart_final = '<center><strong>Aucune valeur</strong></center>';$dmt = '<center><strong>Aucune valeur</strong></center>';}
			
			$jour = date("d-m-Y", strtotime($acide['date_calcul_contact']));	
			
			$mysql_data[] = array(
			  "collab"  => $acide['operateur_contact'],
			  "temps"  => $traitement,
			  "traite"  => $somme_traite,
			  "traitesociete"  => $ligne_taiter_societe,
			  "traiteindispo"  => $somme_traite_indispo,
			  "traitenonverif"  => $somme_traite_non_verif,
			  "traitequite"  => $somme_traite_quitter,
			  "traiteok"  => $somme_traite_ok,
			  "traiteokmodif"  => $somme_traite_ok_modif,
			  "traiteremplace"  => $somme_traite_remplace,
			  "traitehc"  => $somme_traite_hc,
			  "traiteajout"  => $somme_traite_ajouter,
			  "dmt"     => $dmt,
			  "ecart"     => $ecart_final,
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