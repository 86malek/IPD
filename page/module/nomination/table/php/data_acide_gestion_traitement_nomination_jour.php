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
  if (
  		$job == 'get_gestion_traitement_nomination_jour'){
		  
		if (isset($_GET['id'])){
		  $id = $_GET['id'];
		  if (!is_numeric($id)){
			$id = '';
		  }
		}

		if (isset($_GET['id_user'])){
		  $id_user = $_GET['id_user'];
		  if (!is_numeric($id_user)){
			$id_user = '';
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
  
  if ($job == 'get_gestion_traitement_nomination_jour'){  	
		
		if (!empty($date)){
		$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
			if(empty($id_user)){		
			$PDO_query_traitement = $bdd->prepare("SELECT `acide_intervenant_id_nomination`, `acide_intervenant_nomination`, `date_calcul` FROM `nomination_acide` WHERE date_calcul between :debut and :fin GROUP BY date_calcul, acide_intervenant_id_nomination ORDER BY `date_calcul` DESC");		
			}else{			
			$PDO_query_traitement = $bdd->prepare("SELECT `acide_intervenant_id_nomination`, `acide_intervenant_nomination`, `date_calcul` FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id AND date_calcul between :debut and :fin GROUP BY date_calcul, acide_intervenant_id_nomination ORDER BY `date_calcul` DESC");		
			$PDO_query_traitement->bindParam(":user_id", $id_user, PDO::PARAM_INT);			
			}
		$PDO_query_traitement->bindParam(":debut", $debut, PDO::PARAM_STR);
		$PDO_query_traitement->bindParam(":fin", $fin, PDO::PARAM_STR);
		}else{
		$PDO_query_traitement = $bdd->prepare("SELECT `acide_intervenant_id_nomination`, `acide_intervenant_nomination`, `date_calcul` FROM `nomination_acide` WHERE acide_intervenant_id_nomination = :user_id GROUP BY date_calcul ORDER BY `date_calcul` DESC");		
		$PDO_query_traitement->bindParam(":user_id", $id_user, PDO::PARAM_INT);		
		}
		$PDO_query_traitement->execute();
		while ($traitement = $PDO_query_traitement->fetch()){
		
		
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM nomination_acide_update WHERE user_id = :user_id AND date_calcul = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		if($query_temps['datee'] <> '00:00:00'){			
			
		$time = '<strong>'.$query_temps['datee'].'</strong>';

		$pieces = explode(":", $query_temps['datee']);
		
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);				
		
		$jh = round($duree_decimal/8, 2);
		
		}else{$time = '<strong>X</strong>'; $jh = '<strong>X</strong>';}	
		
		$date = '<span class="badge badge-outline-primary mb-3 mr-3">'.date_change_format($traitement['date_calcul'],'Y-m-d','d/m/Y').'</span>';
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountt = $query->fetchColumn();
		$rowcount = '<b>'.$rowcountt.'</b>';
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 1 AND `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountajout = $query->fetchColumn();
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 2 AND `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountmodif = $query->fetchColumn();
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND acide_statut_nomination = 3 AND `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountsupp = $query->fetchColumn();
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 2 AND `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountbo = $query->fetchColumn();
		$rowcountbo = '<span class="badge badge-sm badge-shamrock mb-3 mr-3">'.$rowcountbo.'</span>';
		$query->closeCursor();
		
		
		$query = $bdd->prepare("SELECT count(*) FROM nomination_acide WHERE acide_nt_nomination = 1 AND `acide_intervenant_id_nomination` = :user_id AND `date_calcul` = :date_calcul");
		$query->bindParam(":user_id", $traitement['acide_intervenant_id_nomination'], PDO::PARAM_INT);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);
		$query->execute();
		$rowcountnt = $query->fetchColumn();
		$rowcountnt = '<span class="badge badge-sm badge-bittersweet mb-3 mr-3">'.$rowcountnt.'</span>';
		$query->closeCursor();	
		
		
		$query = $bdd->prepare("SELECT nbligne_objectif, nbheure_objectif FROM nomination_acide_obj WHERE debut_objectf <= '".$traitement['date_calcul']."' AND fin_objectif >= '".$traitement['date_calcul']."'");
		$query->execute();
		$donnees = $query->fetch();
		$ligne = $donnees['nbligne_objectif'];
		$heure = $donnees['nbheure_objectif'];	
		$query->closeCursor();
		if(empty($ligne) && empty($heure)){$prod = 'x';}else{$prod = round($rowcountt/$ligne,2);}
		
		
		
		
		$mysql_data[] = array(
		  "collab" => $traitement['acide_intervenant_nomination'],
		  "date" => $date,
		  "count" => $rowcount,
		  "countajout" => $rowcountajout,
		  "countmodif" => $rowcountmodif,
		  "countsupp" => $rowcountsupp,
		  "countbo" => $rowcountbo,
		  "countnt" => $rowcountnt,
		  "time" => $time,
		  "prod" => $prod,
		  "jh" => $jh
        );
		
		
		}
		$PDO_query_traitement->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';					
		
		$bdd = null;
		$PDO_query_traitement = null;	
		
		
    
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