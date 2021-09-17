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

  	$job == 'get_rapport_acommp_general' ||
  	$job == 'get_tri' ||
  	$job == 'get_err' ||
  	$job == 'get_bonus' ||

  	$job == 'add_err' ||
  	$job == 'add_tri' ||
  	$job == 'add_err_type' ||
  	$job == 'add_bonus' ||

  	$job == 'add_err_modif' ||
  	$job == 'add_tri_modif' ||
  	$job == 'add_err_type_modif' ||
  	$job == 'add_bonus_modif' ||

  	$job == 'modif_err' ||
  	$job == 'modif_tri' ||
  	$job == 'modif_err_type' ||
  	$job == 'modif_bonus_type' ||

  	$job == 'delete_err' ||
  	$job == 'delete_tri' ||
  	$job == 'delete_err_type' ||

	$job == 'get_rapport_acommp_details'){
		  
		if (isset($_GET['id'])){
		  $id = $_GET['id'];
		  if (!is_numeric($id)){
			$id = '';
		  }
		}

		if (isset($_GET['web'])){
		  $web = $_GET['web'];
		  if (!is_numeric($web)){
			$web = '';
		  }
		}


		if (isset($_GET['tri'])){
		  $tri = $_GET['tri'];
		  if (!is_numeric($tri)){
			$tri = '';
		  }
		}
		
		if (isset($_GET['intervalle'])){
		  $date = $_GET['intervalle'];
		}
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){
  
	if ($job == 'get_rapport_acommp_general'){
	
	
		try
		{
			
		$PDO_query_accomp = $bdd->prepare("SELECT * FROM webmaster_accomp_details GROUP BY web");
		$PDO_query_accomp->execute();
		
		while ($traitement = $PDO_query_accomp->fetch()){	
			
			
			
			$query = $bdd->prepare("SELECT * FROM users WHERE id = :user_id");
			$query->bindParam(":user_id", $traitement['web'], PDO::PARAM_INT);
			$query->execute();	
			$query_users = $query->fetch();
			$query->closeCursor();


			$query = $bdd->prepare("SELECT * FROM webmaster_accomp_tri WHERE id_accomp_tri = :id_accomp_tri");
			$query->bindParam(":id_accomp_tri", $traitement['tri_accomp'], PDO::PARAM_INT);
			$query->execute();	
			$query_tri = $query->fetch();
			$query->closeCursor();
			

			$query = $bdd->prepare("SELECT SUM(webmaster_accomp_type_erreur.accomp_note) AS note FROM webmaster_accomp_details INNER JOIN  webmaster_accomp_type_erreur ON webmaster_accomp_type_erreur.id_accomp_type_erreur = webmaster_accomp_details.type_err_accomp_details WHERE web = :user_id");
			$query->bindParam(":user_id", $traitement['web'], PDO::PARAM_INT);
			$query->execute();	
			$query_note = $query->fetch();
			$query->closeCursor();


			$query = $bdd->prepare("SELECT * FROM webmaster_accomp_bonus WHERE web_id = :web_id AND accomp_tri = :accomp_tri");
			$query->bindParam(":web_id", $traitement['web'], PDO::PARAM_INT);
			$query->bindParam(":accomp_tri", $traitement['tri_accomp'], PDO::PARAM_INT);
			$query->execute();	
			$query_bonus = $query->fetch();
			$query->closeCursor();

			
			$bonus = $query_bonus['accomp_montant_bonus'];

			$total_note = 100 - $query_note['note'];

			$prime_calcul = round(($total_note / 100) * 360);
			$prime_calcul_bonus = round(($total_note / 100) * 360) + $bonus;

			$suivi = '	<center><div class="dropdown mr-3">
				            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Détails HR/Primes</button>
				            <div class="dropdown-menu">
				              <a class="dropdown-item" href="WebsAccompDetails-'.$traitement['web'].'-'.$traitement['tri_accomp'].'">Plan d\'accompagnement</a>
				              <a class="dropdown-item" href="WebsAccompBonus-'.$traitement['web'].'">Détails BONUS</a>
				            </div>
				          </div></center>';	
			
					
			$point = '<span class="widget-welcome__stats-item-value">'.$total_note.'</span>/100';
			
			if(empty($bonus) || $bonus == 0){$prime = '<span class="widget-welcome__stats-item-value">'.$prime_calcul_bonus.'</span>';}else{$prime = ''.$prime_calcul.' + Bonus de : <span class="badge badge-shamrock badge-rounded">'.$bonus.'</span><span class="widget-welcome__stats-item-value"> = '.$prime_calcul_bonus.'</span>';}
			
			
			$tri = $query_tri['nom_accomp_tri'].' - '.$query_tri['annee_accomp_tri'];

			//$date_fin = date("d-m-Y", strtotime($select_date['date_fin']));
			
				
			$mysql_data[] = array(	
			"webs"  => $query_users['full_name'],
			"tri" => $tri,
			"point" => $point,
			"prime" => $prime,
			"suivi" => $suivi,
			);
					
					
			
			
		}
		$PDO_query_accomp->closeCursor();
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
		$PDO_query_accomp = null;	
    
	}elseif ($job == 'get_tri'){
	
	
		try
		{
			
		$PDO_query_accomp = $bdd->prepare("SELECT * FROM webmaster_accomp_tri");
		$PDO_query_accomp->execute();
		
		while ($traitement = $PDO_query_accomp->fetch()){	
			
				$date_debut = date_change_format($traitement['date_debut'],'Y-m-d','d/m/Y');
				$date_fin = date_change_format($traitement['date_fin'],'Y-m-d','d/m/Y');
				
				$datenow = date("Y-m-d");
					if($datenow <= $traitement['date_fin'] && $datenow >= $traitement['date_debut']){
						$actif = '<span class="badge-circle badge-circle-success mr-3">Actif</span>';
					}else{
						$actif = '<span class="badge-circle badge-circle-danger mr-3">Non actif</span>';
					}
			
				$query = $bdd->prepare("SELECT COUNT(*) FROM webmaster_accomp_details WHERE tri_accomp = :tri_accomp");
				$query->bindParam(":tri_accomp", $traitement['id_accomp_tri'], PDO::PARAM_INT);
				$query->execute();
				$verif_existe = $query->fetchColumn();
				$query->closeCursor();

					if($verif_existe == 0){
						$fonction = '	<center><div class="dropdown mr-3">
							            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Gestion</button>
							            <div class="dropdown-menu">
							              <a class="dropdown-item" href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_tri'].'" data-name="'.$traitement['nom_accomp_tri'].' - '.$traitement['annee_accomp_tri'].'">Modification</a>
							              <a class="dropdown-item"  href="#" id="del" data-id="' . $traitement['id_accomp_tri'] . '" data-name="' . $traitement['nom_accomp_tri'].' - '.$traitement['annee_accomp_tri'] . '">Supprission</a>
							            </div>
							          	</div></center>';
							          	$tri = $traitement['nom_accomp_tri'].' - '.$traitement['annee_accomp_tri'];
					}else{

						$fonction = '	<center><div class="dropdown mr-3">
							            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Gestion</button>
							            <div class="dropdown-menu">
							            <a class="dropdown-item" href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_tri'].'" data-name="'.$traitement['nom_accomp_tri'].' - '.$traitement['annee_accomp_tri'].'">Modification</a>
							            </div>
							          	</div></center>';
							          	$tri = $traitement['nom_accomp_tri'].' - '.$traitement['annee_accomp_tri'].' : ( <b>En cours - Supprission impossible</b> )';
					}
			
				
				$mysql_data[] = array(	
				"nom" => $tri,
				"debut" => $date_debut,
				"fin" => $date_fin,
				"actif" => $actif,
				"fonction" => $fonction
				);		
			
		}
		$PDO_query_accomp->closeCursor();
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
		$PDO_query_accomp = null;	
    
	}elseif ($job == 'get_err'){
	
	
		try
		{
			
		$PDO_query_accomp = $bdd->prepare("SELECT * FROM webmaster_accomp_type_erreur");
		$PDO_query_accomp->execute();
		
		while ($traitement = $PDO_query_accomp->fetch()){	
			
			
			
				$query = $bdd->prepare("SELECT COUNT(*) FROM webmaster_accomp_details WHERE type_err_accomp_details = :err_accomp");
				$query->bindParam(":err_accomp", $traitement['id_accomp_type_erreur'], PDO::PARAM_INT);
				$query->execute();
				$verif_existe = $query->fetchColumn();
				$query->closeCursor();

					if($verif_existe == 0){
						$fonction = '	<center><div class="dropdown mr-3">
							            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Gestion</button>
							            <div class="dropdown-menu">
							              <a class="dropdown-item" href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_type_erreur'].'" data-name="'.$traitement['accomp_type_erreur'].'">Modification</a>
							              <a class="dropdown-item"  href="#" id="del" data-id="' . $traitement['id_accomp_type_erreur'] . '" data-name="' . $traitement['accomp_type_erreur'].'">Supprission</a>
							            </div>
							          	</div></center>';
							          	$err = $traitement['accomp_type_erreur'];
					}else{

						$fonction = '	<center><div class="dropdown mr-3">
							            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Gestion</button>
							            <div class="dropdown-menu">
							            <a class="dropdown-item" href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_type_erreur'].'" data-name="'.$traitement['accomp_type_erreur'].'">Modification</a>
							            </div>
							          	</div></center>';
							          	$err = $traitement['accomp_type_erreur'].' <br> ( <b>En cours - Supprission impossible</b> )';
					}

					$point = '<span class="widget-welcome__stats-item-value">'.$traitement['accomp_note'].'</span>';
					$comm = '<p>'.$traitement['accomp_comm'].'</p>';

					if($traitement['accomp_grave'] == 1){
						$grave = '<span class="badge badge-buttercup mb-3 mr-3">Peu Grave</span>';
					}elseif($traitement['accomp_grave'] == 2){
						$grave = '<span class="badge badge-bittersweet mb-3 mr-3">Grave</span>';
					}elseif($traitement['accomp_grave'] == 3){
						$grave = '<span class="badge badge-danger mb-3 mr-3">Très Grave</span>';
					}elseif($traitement['accomp_grave'] == 0){
						$grave = '<span class="badge badge-shamrock mb-3 mr-3">Accéptable</span>';
					}
				
				$mysql_data[] = array(	
				"nom" => $err,
				"point" => $point,
				"grave" => $grave,
				"comm" => $comm,
				"fonction" => $fonction
				);		
			
		}
		$PDO_query_accomp->closeCursor();
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
		$PDO_query_accomp = null;	
    
	}elseif ($job == 'get_bonus'){
	
	
		try
		{
			
		$PDO_query_accomp = $bdd->prepare("SELECT * FROM webmaster_accomp_bonus WHERE web_id = :web");
		$PDO_query_accomp->bindParam(":web", $web, PDO::PARAM_INT);
		$PDO_query_accomp->execute();
		
		while ($traitement = $PDO_query_accomp->fetch()){				

					$query = $bdd->prepare("SELECT * FROM webmaster_accomp_tri WHERE id_accomp_tri = :id_accomp_tri");
					$query->bindParam(":id_accomp_tri", $traitement['accomp_tri'], PDO::PARAM_INT);
					$query->execute();
					$query_tri = $query->fetch();
					$query->closeCursor();
					$tri = $query_tri['nom_accomp_tri'].' - '.$query_tri['annee_accomp_tri'];

					$fonction = '<center><div class="dropdown mr-3">
					            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Gestion</button>
					            <div class="dropdown-menu">
					            <a class="dropdown-item" href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_bonus'].'" data-name="Bonus">Modification</a>
					            </div>
					          	</div></center>';
					
					$query = $bdd->prepare("SELECT * FROM users WHERE id = :web_id");
					$query->bindParam(":web_id", $traitement['web_id'], PDO::PARAM_INT);
					$query->execute();	
					$query_web = $query->fetch();
					$query->closeCursor();


					$web = $query_web['full_name'];
					$bonus = '<span class="widget-welcome__stats-item-value">'.$traitement['accomp_montant_bonus'].'</span>';
					$comm = '<p>'.$traitement['accomp_comm_bonus'].'</p>';
				
				$mysql_data[] = array(	
				"web" => $web,
				"tri" => $tri,
				"bonus" => $bonus,
				"comm" => $comm,
				"fonction" => $fonction
				);		
			
		}
		$PDO_query_accomp->closeCursor();
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
		$PDO_query_accomp = null;	
    
	}elseif ($job == 'get_rapport_acommp_details'){
	
	
		try
		{
			
		$PDO_query_accomp = $bdd->prepare("SELECT * FROM webmaster_accomp_details WHERE web = :web AND tri_accomp = :tri");
		$PDO_query_accomp->bindParam(":web", $web, PDO::PARAM_INT);
		$PDO_query_accomp->bindParam(":tri", $tri, PDO::PARAM_INT);
		$PDO_query_accomp->execute();
		
		while ($traitement = $PDO_query_accomp->fetch()){	
			
			
			
			$query = $bdd->prepare("SELECT * FROM users WHERE id = :web");
			$query->bindParam(":web", $traitement['web'], PDO::PARAM_INT);
			$query->execute();	
			$query_users = $query->fetch();
			$query->closeCursor();


			$query = $bdd->prepare("SELECT * FROM webmaster_accomp_type_erreur WHERE id_accomp_type_erreur = :type_err");
			$query->bindParam(":type_err", $traitement['type_err_accomp_details'], PDO::PARAM_INT);
			$query->execute();	
			$query_err = $query->fetch();
			$query->closeCursor();
			
			$fonction = '<center><div class="dropdown mr-3">
				            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
				            <div class="dropdown-menu">
				              <a class="dropdown-item"  href="#" id="function_edit_web" data-id="'.$traitement['id_accomp_details'].'" data-name="'.$traitement['err_accomp_details'].'">Modifier</a>
				              <a class="dropdown-item" href="#" id="del" data-id="' . $traitement['id_accomp_details'] . '" data-name="' . $traitement['err_accomp_details'] . '">Supprimer</a>
				            </div>
				          </div></center>';
			
			//$detail = '<a href="WebsRapportCollab-'.$traitement['user_id'].'"><span class="badge badge-info ">détails points/primes</span></a>';
					
			//$point = '<span class="widget-welcome__stats-item-value">'.$traitement['point_accomp'].'</span>/100';
			
			//$prime = '<span class="widget-welcome__stats-item-value">'.$traitement['primes_accomp'].' Dinars</span>';
				
			$date_err = date("d-m-Y", strtotime($traitement['date_accomp_details']));
			
			$note = '<center><span class="widget-welcome__stats-item-value">'.$query_err['accomp_note'].'</span></center>';

			$mysql_data[] = array(	
			"type"  => $query_err['accomp_type_erreur'],
			"webs"  => $query_users['full_name'],
			"erreur" => $traitement['err_accomp_details'],
			"constat" => $traitement['constat_accomp_details'],
			"axe" => $traitement['axe_accomp_details'],
			"date" => $date_err,
			"inpact" => $note,
			"manger" => $traitement['manager'],
			"fonction" => $fonction
			);
					
					
			
			
		}
		$PDO_query_accomp->closeCursor();
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
		$PDO_query_accomp = null;	
    
	}elseif ($job == 'add_err'){
	  
	   	try 
		{

		$query = $bdd->prepare("INSERT INTO `webmaster_accomp_details` (tri_accomp, web, type_err_accomp_details, err_accomp_details, constat_accomp_details, axe_accomp_details, 	date_accomp_details, manager, date_insert, id_manager) VALUES (:tri_accomp, :web, :type_err_accomp_details, :err_accomp_details, :constat_accomp_details, :axe_accomp_details, :date_accomp_details, :user_name, now(), :user_id)");

		$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":tri_accomp", $_GET['tri'], PDO::PARAM_INT);		
		$query->bindParam(":web", $_GET['web'], PDO::PARAM_INT);		
		$query->bindParam(":type_err_accomp_details", $_GET['err'], PDO::PARAM_INT);		
		$query->bindParam(":err_accomp_details", $_GET['errtxt'], PDO::PARAM_STR);		
		$query->bindParam(":constat_accomp_details", $_GET['constat'], PDO::PARAM_STR);
		$query->bindParam(":axe_accomp_details", $_GET['axe'], PDO::PARAM_STR);
		$query->bindParam(":date_accomp_details", $_GET['date'], PDO::PARAM_STR);

		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
		
		}
		catch(PDOException $x) 
		{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
		}	
		$query = null;
		$bdd = null;
		
		
	  
	  }elseif ($job == 'add_tri'){
	  
	   	try 
		{

			$debut = substr($_GET['intervalle'], 0,10);
			$fin = substr($_GET['intervalle'], 13,22);

			$query = $bdd->prepare("INSERT INTO `webmaster_accomp_tri` (nom_accomp_tri, annee_accomp_tri, actif, user_name, date_insert, user_id, date_debut, date_fin) VALUES (:nom_accomp_tri, :annee_accomp_tri, 1, :user_name, now(), :user_id, :date_debut, :date_fin)");

			$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
			$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
			$query->bindParam(":nom_accomp_tri", $_GET['nom'], PDO::PARAM_STR);		
			$query->bindParam(":annee_accomp_tri", $_GET['annee'], PDO::PARAM_INT);

			$query->bindParam(":date_debut", $debut, PDO::PARAM_STR);	
			$query->bindParam(":date_fin", $fin, PDO::PARAM_STR);	

			$query->execute();
			$query->closeCursor();
			
			$result  = 'success';
			$message = 'Succès de requête';
			
			}
			catch(PDOException $x) 
			{ 	
				die("Secured");	
				$result  = 'error';
				$message = 'Échec de requête'; 	
			}
			$query = null;
			$bdd = null;
		
		
	  
	  }elseif ($job == 'add_err_type'){
	  
	   	try 
		{

		$query = $bdd->prepare("INSERT INTO `webmaster_accomp_type_erreur` (accomp_type_erreur, accomp_note, accomp_grave, accomp_comm, actif, user_name, date_insert, user_id) VALUES (:accomp_type_erreur, :accomp_note, :accomp_grave, :accomp_comm, 1, :user_name, now(), :user_id)");

		$query->bindParam(":user_name", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);

		$query->bindParam(":accomp_type_erreur", $_GET['nom'], PDO::PARAM_STR);		
		$query->bindParam(":accomp_note", $_GET['point'], PDO::PARAM_INT);
		$query->bindParam(":accomp_grave", $_GET['grave'], PDO::PARAM_INT);		
		$query->bindParam(":accomp_comm", $_GET['comm'], PDO::PARAM_STR);

		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
		
		}
		catch(PDOException $x) 
		{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
		}
		$query = null;
		$bdd = null;
		
		
	  
	  }elseif ($job == 'add_bonus'){
	  
	   	/*try 
		{*/

		$query = $bdd->prepare("INSERT INTO `webmaster_accomp_bonus` (web_id, accomp_tri, accomp_montant_bonus, accomp_comm_bonus, manger, date_insert, manger_id) VALUES (:web_id, :accomp_tri, :accomp_montant_bonus, :accomp_comm_bonus, :manger, now(), :manger_id)");

		$query->bindParam(":manger", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":manger_id", $_GET['user_id'], PDO::PARAM_INT);

		$query->bindParam(":web_id", $_GET['web'], PDO::PARAM_INT);		
		$query->bindParam(":accomp_tri", $_GET['tri'], PDO::PARAM_INT);
		$query->bindParam(":accomp_montant_bonus", $_GET['bonus'], PDO::PARAM_INT);		
		$query->bindParam(":accomp_comm_bonus", $_GET['comm'], PDO::PARAM_STR);

		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
		
		/*}
		catch(PDOException $x) 
		{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
		}*/
		$query = null;
		$bdd = null;
		
		
	  
	  }elseif ($job == 'add_err_modif'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {
			try 
			{
			$query_select_add = $bdd->prepare("SELECT * FROM webmaster_accomp_details WHERE id_accomp_details = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){

				$mysql_data[] = array(

				"web"  => $traitement_edit['web'],
				"err"  => $traitement_edit['type_err_accomp_details'],
				"tri"  => $traitement_edit['tri_accomp'],
				"date"  => $traitement_edit['date_accomp_details'],
				"errtxt"  => $traitement_edit['err_accomp_details'],
				"constat"  => $traitement_edit['constat_accomp_details'],
				"axe"  => $traitement_edit['axe_accomp_details']
				);

			}
			
			$query_select_add->closeCursor();		
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
				die("Secured");	
				$result  = 'error';
				$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
	      
	    }
	  
	  }elseif ($job == 'add_tri_modif'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {
			try 
			{
			$query_select_add = $bdd->prepare("SELECT * FROM webmaster_accomp_tri WHERE id_accomp_tri = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){

				$debut = $traitement_edit['date_debut'];
				$fin = $traitement_edit['date_fin'];

				$intervalle = $traitement_edit['date_debut'].' - '.$traitement_edit['date_fin'];
				$mysql_data[] = array(

				"nom"  => $traitement_edit['nom_accomp_tri'],
				"annee"  => $traitement_edit['annee_accomp_tri'],
				"intervalle"  => $intervalle

				);

			}
			
			$query_select_add->closeCursor();		
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
				die("Secured");	
				$result  = 'error';
				$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
	      
	    }
	  
	  }elseif ($job == 'add_err_type_modif'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {
			try 
			{
			$query_select_add = $bdd->prepare("SELECT * FROM webmaster_accomp_type_erreur WHERE id_accomp_type_erreur = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){

				$mysql_data[] = array(

				"nom"  => $traitement_edit['accomp_type_erreur'],
				"point"  => $traitement_edit['accomp_note'],
				"grave"  => $traitement_edit['accomp_grave'],
				"comm"  => $traitement_edit['accomp_comm']
				);

			}
			
			$query_select_add->closeCursor();		
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
				die("Secured");	
				$result  = 'error';
				$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
	      
	    }
	  
	  }elseif ($job == 'add_bonus_modif'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {
			try 
			{
			$query_select_add = $bdd->prepare("SELECT * FROM webmaster_accomp_bonus WHERE id_accomp_bonus = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){

				$mysql_data[] = array(

				"bonus"  => $traitement_edit['accomp_montant_bonus'],
				"comm"  => $traitement_edit['accomp_comm_bonus']
				);

			}
			
			$query_select_add->closeCursor();		
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
				die("Secured");	
				$result  = 'error';
				$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
	      
	    }
	  
	  }elseif ($job == 'modif_err'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {

				try 
				{
				
				$query = $bdd->prepare("UPDATE webmaster_accomp_details SET tri_accomp = :tri_accomp, web = :web, type_err_accomp_details = :type_err_accomp_details, err_accomp_details = :err_accomp_details, constat_accomp_details = :constat_accomp_details, axe_accomp_details = :axe_accomp_details, date_accomp_details = :date_accomp_details, id_manager = :id_manager, manager = :manager WHERE id_accomp_details = :id");	

				$query->bindParam(":id", $id, PDO::PARAM_INT);
				$query->bindParam(":manager", $_GET['user'], PDO::PARAM_STR);
				$query->bindParam(":id_manager", $_GET['user_id'], PDO::PARAM_INT);			
				$query->bindParam(":tri_accomp", $_GET['tri'], PDO::PARAM_INT);
				$query->bindParam(":web", $_GET['web'], PDO::PARAM_INT);		
				$query->bindParam(":type_err_accomp_details", $_GET['err'], PDO::PARAM_INT);		
				$query->bindParam(":err_accomp_details", $_GET['errtxt'], PDO::PARAM_STR);		
				$query->bindParam(":constat_accomp_details", $_GET['constat'], PDO::PARAM_STR);
				$query->bindParam(":axe_accomp_details", $_GET['axe'], PDO::PARAM_STR);	
				$query->bindParam(":date_accomp_details", $_GET['date'], PDO::PARAM_STR);					
				$query->execute();
				$query->closeCursor();
				
				
				$result  = 'success';
				$message = 'Succès de requête';
		
		
				}

		catch(PDOException $x) 
		{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
		}	
		$query = null;
		$bdd = null;
		}
	    
	  }elseif ($job == 'modif_tri'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {

				try 
				{
					
					$debut = substr($_GET['intervalle'], 0,10);
					$fin = substr($_GET['intervalle'], 13,22);

					$query = $bdd->prepare("UPDATE webmaster_accomp_tri SET date_debut = :date_debut, date_fin = :date_fin, nom_accomp_tri = :nom_accomp_tri, annee_accomp_tri = :annee_accomp_tri, date_modif = now() WHERE id_accomp_tri = :id");	

					$query->bindParam(":id", $id, PDO::PARAM_INT);		
					$query->bindParam(":nom_accomp_tri", $_GET['nom'], PDO::PARAM_STR);
					$query->bindParam(":annee_accomp_tri", $_GET['annee'], PDO::PARAM_INT);
					$query->bindParam(":date_debut", $debut, PDO::PARAM_STR);
					$query->bindParam(":date_fin", $fin, PDO::PARAM_STR);
					$query->execute();
					$query->closeCursor();
					
					
					$result  = 'success';
					$message = 'Succès de requête';
		
		
				}

				catch(PDOException $x) 
				{ 	
					die("Secured");	
					$result  = 'error';
					$message = 'Échec de requête'; 	
				}

				$query = null;
				$bdd = null;
		}
	    
	}elseif ($job == 'modif_err_type'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {

				try 
				{
				
					$query = $bdd->prepare("UPDATE webmaster_accomp_type_erreur SET accomp_type_erreur = :accomp_type_erreur, accomp_note = :accomp_note, accomp_grave = :accomp_grave, accomp_comm = :accomp_comm, date_modif = now() WHERE id_accomp_type_erreur = :id");	

					$query->bindParam(":id", $id, PDO::PARAM_INT);		
					$query->bindParam(":accomp_type_erreur", $_GET['nom'], PDO::PARAM_STR);
					$query->bindParam(":accomp_note", $_GET['point'], PDO::PARAM_INT);
					$query->bindParam(":accomp_grave", $_GET['grave'], PDO::PARAM_INT);
					$query->bindParam(":accomp_comm", $_GET['comm'], PDO::PARAM_STR);
					$query->execute();
					$query->closeCursor();
					
					
					$result  = 'success';
					$message = 'Succès de requête';
		
		
				}

				catch(PDOException $x) 
				{ 	
					die("Secured");	
					$result  = 'error';
					$message = 'Échec de requête'; 	
				}

				$query = null;
				$bdd = null;
		}
	    
	}elseif ($job == 'modif_bonus_type'){
    
	    if ($id == ''){
	      $result  = 'error';
	      $message = 'Échec id';
	    } else {

				try 
				{
				
					$query = $bdd->prepare("UPDATE webmaster_accomp_bonus SET accomp_comm_bonus = :accomp_comm_bonus, accomp_montant_bonus = :accomp_montant_bonus, date_modif = now() WHERE id_accomp_bonus = :id");	

					$query->bindParam(":id", $id, PDO::PARAM_INT);		
					$query->bindParam(":accomp_montant_bonus", $_GET['bonus'], PDO::PARAM_STR);
					$query->bindParam(":accomp_comm_bonus", $_GET['comm'], PDO::PARAM_INT);
					$query->execute();
					$query->closeCursor();
					
					
					$result  = 'success';
					$message = 'Succès de requête';
		
		
				}

				catch(PDOException $x) 
				{ 	
					die("Secured");	
					$result  = 'error';
					$message = 'Échec de requête'; 	
				}

				$query = null;
				$bdd = null;
		}
	    
	} elseif ($job == 'delete_err'){
  
	    if ($id == ''){
			
	      $result  = 'Échec';
	      $message = 'Échec id';
		  
	    } else {
			
			try 
			{		
			$query_del = $bdd->prepare("DELETE FROM webmaster_accomp_details WHERE id_accomp_details = :id");	
			$query_del->bindParam(":id", $id, PDO::PARAM_INT);
			$query_del->execute();
			$query_del->closeCursor();	
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
			
		  
	    }
  
  	} elseif ($job == 'delete_tri'){
  
	    if ($id == ''){
			
	      $result  = 'Échec';
	      $message = 'Échec id';
		  
	    } else {
			
			try 
			{		
			$query_del = $bdd->prepare("DELETE FROM webmaster_accomp_tri WHERE id_accomp_tri = :id");	
			$query_del->bindParam(":id", $id, PDO::PARAM_INT);
			$query_del->execute();
			$query_del->closeCursor();	
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
			
		  
	    }
  
  	}elseif ($job == 'delete_err_type'){
  
	    if ($id == ''){
			
	      $result  = 'Échec';
	      $message = 'Échec id';
		  
	    } else {
			
			try 
			{		
			$query_del = $bdd->prepare("DELETE FROM webmaster_accomp_type_erreur WHERE id_accomp_type_erreur = :id");	
			$query_del->bindParam(":id", $id, PDO::PARAM_INT);
			$query_del->execute();
			$query_del->closeCursor();	
			$result  = 'success';
			$message = 'Succès de requête';
			}
			catch(PDOException $x) 
			{ 	
			die("Secured");	
			$result  = 'error';
			$message = 'Échec de requête'; 	
			}	
			$query_del = null;
			$bdd = null;
			
		  
	    }
  
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