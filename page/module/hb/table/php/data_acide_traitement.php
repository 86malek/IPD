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

$job = '';
$id  = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  
  if ($job == 'get_traitement' ||
  		$job == 'get_traitement_admin' ||
      	$job == 'get_traitement_add'   ||
		$job == 'get_traitement_add_admin' ||
      	$job == 'add_traitement'   ||
      	$job == 'edit_traitement'  ||
		$job == 'edit_traitement_admin'  ||
      	$job == 'delete_traitement'){
			
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
	
	if (isset($_GET['id_import'])){
      $id_import = $_GET['id_import'];
      if (!is_numeric($id_import)){
        $id_import = '';
      }
    }
	if (isset($_GET['id_user'])){
      $id_user = $_GET['id_user'];
      
    }
	
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){ 
   
  if ($job == 'get_traitement'){
	
	
	try 
	{
		
	
	$query = $bdd->prepare("SELECT * FROM hb_acide WHERE (id_cat_acide = :id_cat_acide AND user_id = 0) OR (id_cat_acide = :id_cat_acide AND user_id = :user_id) ORDER BY `id_cat_acide` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat_acide", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$functions  = '<center>';	

			if($traitement['reporting'] == 0){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-warning mb-3 mr-3">En Attente</span></a>';

			}elseif($traitement['reporting'] == 1){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">AJOUT/SUPPRESSION</span></a>';	

			}elseif($traitement['reporting'] == 2){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">FERMÉE</span></a>';	

			}elseif($traitement['reporting'] == 3){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">MODIFICATION</span></a>';

			}elseif($traitement['reporting'] == 4){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	

			}elseif($traitement['reporting'] == 5){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">SUPPRESSION</span></a>';

			}elseif($traitement['reporting'] == 6){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-info mb-3 mr-3">EN COURS</span></a>';

			}elseif($traitement['reporting'] == 7){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">KO</span></a>';

			}elseif($traitement['reporting'] == 8){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">AJOUT</span></a>';

			}	


			$functions .= '</center>';
			
			}else{
			
			$functions  = '<center>';			
			$functions .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$functions .= '</center>';
			
			}
			
				
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';
		}
		$alerte .= '';
		if($traitement['telephone'] == NULL){
		$phone = '';
		}else{$phone = '<b>'.$traitement['telephone'].'</b>';}


		if($traitement['id_contact_acide_hb'] == 0){
		$idcontact = '';
		}else{$idcontact = ''.$traitement['id_contact_acide_hb'].'';}

		if($traitement['id_societe_acide_hb'] == 0){
		$idsociete = '';
		}else{$idsociete = ''.$traitement['id_societe_acide_hb'].'';}

		
		$correction = '<b>'.$traitement['correction'].'</b>';

        $mysql_data[] = array(

          "raison" => $traitement['raison_sociale_acide_hb'],
          "codep"  => $traitement['code_postal_acide_hb'],
		  "ville"  => $traitement['ville_acide_hb'],
		  "siret"  => $traitement['siret_acide_hb'],
		  "idc"  => $idcontact,
		  "idrefs"  => $traitement['id_ref_statut_acide_hb'],
		  "statc"  => $traitement['statut_contact_acide_hb'],
		  "idrefc"  => $traitement['id_ref_civilite_acide_hb'],
		  "civilite"  => $traitement['civilite_acide_hb'],
		  "nom"  => $traitement['nom_acide_hb'],
		  "prenom"  => $traitement['prenom_acide_hb'],
		  "idsc"  => $traitement['id_societe_contact_acide_hb'],
		  "ids"  => $idsociete,
		  "idreff"  => $traitement['id_ref_fonction_acide_hb'],
		  "fonction"  => $traitement['fonction_acide_hb'],
		  "codefonction"  => $traitement['code_fonction_acide_hb'],
		  "fonctionexacte"  => $traitement['fonction_exacte_acide_hb'],
		  "email"  => $traitement['email_acide_hb'],
		  "emailcollect"  => $traitement['email_collecte_acide_hb'],
		  "emailactif"  => $traitement['email_actif_acide_hb'],
		  "correction"  => $correction,
		  "telephone"  => $phone,
		  "alerte"     => $alerte,
          "functions"     => $functions

        );
      
	
	
	}	  
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
	$bdd = null;
    
  }elseif ($job == 'get_traitement_admin'){
	
	
	/*try 
	{*/
		
	$query = $bdd->prepare("SELECT * FROM hb_acide WHERE id_cat_acide = :id_cat_acide");
	$query->bindParam(":id_cat_acide", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
		$functions  = '<center>';			
			if($traitement['reporting'] == 0){
			$functions .= '<span class="badge badge-warning mb-3 mr-3">En Attente</span>';

			}elseif($traitement['reporting'] == 1){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">AJOUT/SUPPRESSION</span></a>';	

			}elseif($traitement['reporting'] == 2){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">FERMÉE</span></a>';	

			}elseif($traitement['reporting'] == 3){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">MODIFICATION</span></a>';

			}elseif($traitement['reporting'] == 4){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">OK</span></a>';	

			}elseif($traitement['reporting'] == 5){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">SUPPRESSION</span></a>';

			}elseif($traitement['reporting'] == 6){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-info mb-3 mr-3">EN COURS</span></a>';

			}elseif($traitement['reporting'] == 7){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-danger mb-3 mr-3">KO</span></a>';

			}elseif($traitement['reporting'] == 8){

			$functions .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_acide'] . '" data-name="Numéro : ' . $traitement['id_acide'] . '"><span class="badge badge-success mb-3 mr-3">AJOUT</span></a>';

			}	


			$functions .= '</center>';
		
		$mood  = '';

		$query_count = $bdd->prepare("SELECT COUNT(*) FROM hb_cat_synthese_fiche_update WHERE id_acide = :id_acide");
		$query_count->bindParam(":id_acide", $traitement['id_acide'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_acide'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(hb_cat_synthese_fiche_update.temps_sec)) AS traitement FROM hb_cat_synthese_fiche_update INNER JOIN hb_acide ON hb_acide.id_acide = hb_cat_synthese_fiche_update.id_acide WHERE hb_cat_synthese_fiche_update.id_acide = :id");	
		$query_temps->bindParam(":id", $traitement['id_acide'], PDO::PARAM_INT);
		$query_temps->execute();			
		$query_sum = $query_temps->fetch();						
		$query_temps->closeCursor();
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à vérifier</span>';
		}
		$alerte .= '';
		
		$temps = '<p class="custom-line-height font-light">'.$query_sum['traitement'].'</p>';
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur_acide'].'</p>';
		
		if($traitement['telephone'] == NULL){
		$phone = '';
		}else{$phone = '<b>'.$traitement['telephone'].'</b>';}
		
		$correction = '<b>'.$traitement['correction'].'</b>';

		$newraison = '<b>'.$traitement['new_raison_sociale_acide_hb'].'</b>';
		$newsiret = '<b>'.$traitement['new_siret_acide_hb'].'</b>';
		$newcivilite = '<b>'.$traitement['new_civilite_acide_hb'].'</b>';
		$newnom = '<b>'.$traitement['new_nom_acide_hb'].'</b>';
		$newprenom = '<b>'.$traitement['new_prenom_acide_hb'].'</b>';
		$newfonction = '<b>'.$traitement['new_fonction_acide_hb'].'</b>';
		
					
        $mysql_data[] = array(

          "raison" => $traitement['raison_sociale_acide_hb'],
          "codep"  => $traitement['code_postal_acide_hb'],
		  "ville"  => $traitement['ville_acide_hb'],
		  "siret"  => $traitement['siret_acide_hb'],
		  "idc"  => $traitement['id_contact_acide_hb'],
		  "idrefs"  => $traitement['id_ref_statut_acide_hb'],
		  "statc"  => $traitement['statut_contact_acide_hb'],
		  "idrefc"  => $traitement['id_ref_civilite_acide_hb'],
		  "civilite"  => $traitement['civilite_acide_hb'],
		  "nom"  => $traitement['nom_acide_hb'],
		  "prenom"  => $traitement['prenom_acide_hb'],
		  "idsc"  => $traitement['id_societe_contact_acide_hb'],
		  "ids"  => $traitement['id_societe_acide_hb'],
		  "idreff"  => $traitement['id_ref_fonction_acide_hb'],
		  "fonction"  => $traitement['fonction_acide_hb'],
		  "codefonction"  => $traitement['code_fonction_acide_hb'],
		  "fonctionexacte"  => $traitement['fonction_exacte_acide_hb'],
		  "email"  => $traitement['email_acide_hb'],
		  "emailcollect"  => $traitement['email_collecte_acide_hb'],
		  "emailactif"  => $traitement['email_actif_acide_hb'],
		  "date_hard"  => $traitement['date_hard'],
		  "correction"  => $correction,
		  "telephone"  => $phone,

		  "newraison"  => $newraison,
		  "newsiret"  => $newsiret,
		  "newcivilite"  => $newcivilite,
		  "newnom"  => $newnom,
		  "newprenom"  => $newprenom,
		  "newfonction"  => $newfonction,

          "functions"     => $functions,
		  "collab" => $operateur,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "alerte"     => $alerte
        );
      
	
	
	}	  
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
	$bdd = null;
    
  } elseif ($job == 'get_traitement_add'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{		
			$query_select_add = $bdd->prepare("SELECT * FROM hb_acide WHERE id_acide = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$nomprenom = $traitement_edit['civilite_acide_hb'].' '.$traitement_edit['nom_acide_hb'].' '.$traitement_edit['prenom_acide_hb'];
				if($traitement_edit['telephone'] == NULL){
				$phone = '';
				}else{$phone = $traitement_edit['telephone'];}
				$mysql_data[] = array(

				"raison" => $traitement_edit['raison_sociale_acide_hb'],
				"newraison" => $traitement_edit['new_raison_sociale_acide_hb'],
				"cp"  => $traitement_edit['code_postal_acide_hb'],
				"ville"  => $traitement_edit['ville_acide_hb'],
				"siret"  => $traitement_edit['siret_acide_hb'],
				"newsiret"  => $traitement_edit['new_siret_acide_hb'],
				"idcontact"  => $traitement_edit['id_contact_acide_hb'],
				"idrefstat"  => $traitement_edit['id_ref_statut_acide_hb'],
				"statcontact"  => $traitement_edit['statut_contact_acide_hb'],
				"refcivilite"  => $traitement_edit['id_ref_civilite_acide_hb'],
				"nomprenom"  => $nomprenom,

				"newprenom"  => $traitement_edit['new_prenom_acide_hb'],
				"newnom"  => $traitement_edit['new_nom_acide_hb'],
				"newfonction"  => $traitement_edit['new_fonction_acide_hb'],
				"newtitle"  => $traitement_edit['new_civilite_acide_hb'],

				"idsc"  => $traitement_edit['id_societe_contact_acide_hb'],
				"ids"  => $traitement_edit['id_societe_acide_hb'],
				"idrf"  => $traitement_edit['id_ref_fonction_acide_hb'],
				"fonction"  => $traitement_edit['fonction_acide_hb'],
				"codefonction"  => $traitement_edit['code_fonction_acide_hb'],
				"fonctionexacte"  => $traitement_edit['fonction_exacte_acide_hb'],
				"email"  => $traitement_edit['email_acide_hb'],
				"emailc"  => $traitement_edit['email_collecte_acide_hb'],
				"emaila"  => $traitement_edit['email_actif_acide_hb'],
				"phone"  => $phone,
				"correctemail"  => $traitement_edit['correction'],
				"commentaire"  => $traitement_edit['commentaire'],
				"commentaire_collab"  => $traitement_edit['commentaire_collab'],
				"reporting"  => $traitement_edit['reporting']

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
  
  } elseif ($job == 'get_traitement_add_admin'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{		
			$query_select_add = $bdd->prepare("SELECT * FROM hb_acide WHERE id_acide = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$nomprenom = $traitement_edit['civilite_acide_hb'].' '.$traitement_edit['nom_acide_hb'].' '.$traitement_edit['prenom_acide_hb'];
				if($traitement_edit['telephone'] == NULL){
				$phone = '';
				}else{$phone = $traitement_edit['telephone'];}

				$mysql_data[] = array(

				"raison" => $traitement_edit['raison_sociale_acide_hb'],
				"cp"  => $traitement_edit['code_postal_acide_hb'],
				"ville"  => $traitement_edit['ville_acide_hb'],
				"siret"  => $traitement_edit['siret_acide_hb'],
				"idcontact"  => $traitement_edit['id_contact_acide_hb'],
				"idrefstat"  => $traitement_edit['id_ref_statut_acide_hb'],
				"statcontact"  => $traitement_edit['statut_contact_acide_hb'],
				"refcivilite"  => $traitement_edit['id_ref_civilite_acide_hb'],
				"nomprenom"  => $nomprenom,
				"newprenom"  => $traitement_edit['new_prenom_acide_hb'],
				"newnom"  => $traitement_edit['new_nom_acide_hb'],
				"newraison" => $traitement_edit['new_raison_sociale_acide_hb'],
				"idsc"  => $traitement_edit['id_societe_contact_acide_hb'],
				"ids"  => $traitement_edit['id_societe_acide_hb'],
				"idrf"  => $traitement_edit['id_ref_fonction_acide_hb'],
				"fonction"  => $traitement_edit['fonction_acide_hb'],
				"codefonction"  => $traitement_edit['code_fonction_acide_hb'],
				"fonctionexacte"  => $traitement_edit['fonction_exacte_acide_hb'],
				"email"  => $traitement_edit['email_acide_hb'],
				"emailc"  => $traitement_edit['email_collecte_acide_hb'],
				"emaila"  => $traitement_edit['email_actif_acide_hb'],
				"correctemail"  => $traitement_edit['correction'],
				"phone"  => $phone,
				"commentaire"  => $traitement_edit['commentaire'],
				"commentaire_collab"  => $traitement_edit['commentaire_collab'],
				"reporting"  => $traitement_edit['reporting'],
				"user_name"  => $traitement_edit['operateur_acide']
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
  
  } elseif ($job == 'add_traitement'){



  				
  				$query = $bdd->prepare("INSERT INTO hb_acide (`operateur_acide`, `user_id`, `reporting`, `new_raison_sociale_acide_hb`,`new_siret_acide_hb`,`new_civilite_acide_hb`,`new_prenom_acide_hb`,`new_nom_acide_hb`, `id_cat_acide`, `date_calcul`, `etat`, `telephone`, `correction`, `commentaire_collab`)
			 	VALUES (:user, :user_id, :reporting, :new_raison_sociale_acide_hb, :new_siret_acide_hb, :new_civilite_acide_hb, :new_prenom_acide_hb, :new_nom_acide_hb, :id_cat_acide, now(), 1, :telephone, :correction, :commentaire_collab)");

  				$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);

				$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);

  				$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);

				$query->bindParam(":new_raison_sociale_acide_hb", $_GET['newraison'], PDO::PARAM_STR);

				$query->bindParam(":new_siret_acide_hb", $_GET['newsiret'], PDO::PARAM_STR);

				$query->bindParam(":new_civilite_acide_hb", $_GET['newtitle'], PDO::PARAM_STR);

				$query->bindParam(":new_prenom_acide_hb", $_GET['newprenom'], PDO::PARAM_STR);

				$query->bindParam(":new_nom_acide_hb", $_GET['newnom'], PDO::PARAM_STR);

				$query->bindParam(":id_cat_acide", $_GET['lot'], PDO::PARAM_INT);

				$query->bindParam(":telephone", $_GET['phone'], PDO::PARAM_STR);

				$query->bindParam(":correction", $_GET['correctemail'], PDO::PARAM_STR);

				$query->bindParam(":commentaire_collab", $_GET['commentaire_collab'], PDO::PARAM_STR);

				$query->execute();
				$query->closeCursor();        

				$result  = 'success';
				$message = 'Utilisateur ajouté avec succés';
    			
    			

    			$query = $bdd->prepare("SELECT MAX(id_acide) AS MAX FROM hb_acide WHERE user_id = :user_id AND id_cat_acide = :id_cat_acide");	
				$query->bindParam(":user_id", $_GET['user_id'], PDO::PARAM_INT);
				$query->bindParam(":id_cat_acide", $_GET['lot'], PDO::PARAM_INT);
				$query->execute();
				$max_id = $query->fetch();
				$query->closeCursor();
				
  				$fin = $_GET['fin'];
				$debut = $_GET['debut'];
				$go = get_working_hours_2($debut,$fin);
				$query = $bdd->prepare("INSERT INTO `hb_cat_synthese_fiche_update` (`id_acide`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `id_cat_acide`, `date_calcul`) VALUES (:id_acide, :fiche_debut, :fiche_fin, :go, :id_user, :user, :id_cat_acide, now())");	
				$query->bindParam(":id_acide", $max_id['MAX'], PDO::PARAM_INT);
				$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
				$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
				$query->bindParam(":go", $go, PDO::PARAM_INT);
				$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
				$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
				$query->bindParam(":id_cat_acide", $_GET['lot'], PDO::PARAM_INT);
				$query->execute();
				$query->closeCursor();

  
  } elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		if($_GET['reporting'] == 0){
			$result  = 'success';
			$message = 'Succès de requête';	
		}else{
				
		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `hb_cat_synthese_fiche_update` (`id_acide`, `date_debut_traitment`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `id_cat_acide`, `date_calcul`) VALUES (:id_acide, :fiche_debut, :fiche_fin, :go, :id_user, :user, :id_cat_acide, now())");	
		$query->bindParam(":id_acide", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":id_cat_acide", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$query = $bdd->prepare("UPDATE hb_acide SET operateur_acide = :user , user_id = :id_user, reporting = :reporting, correction = :correction, telephone = :phone, date_calcul = now(), etat = 1, new_raison_sociale_acide_hb = :newraison, new_siret_acide_hb = :newsiret, new_prenom_acide_hb = :newprenom, new_nom_acide_hb = :newnom, new_fonction_acide_hb = :newfonction, new_civilite_acide_hb =:newtitle, commentaire_collab = :commentaire_collab WHERE id_acide = :id");	
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $_GET['reporting'], PDO::PARAM_INT);
		$query->bindParam(":correction", $_GET['correctemail'], PDO::PARAM_STR);

		$query->bindParam(":newtitle", $_GET['newtitle'], PDO::PARAM_STR);
		$query->bindParam(":newfonction", $_GET['newfonction'], PDO::PARAM_STR);
		$query->bindParam(":newraison", $_GET['newraison'], PDO::PARAM_STR);
		$query->bindParam(":newsiret", $_GET['newsiret'], PDO::PARAM_STR);
		$query->bindParam(":newprenom", $_GET['newprenom'], PDO::PARAM_STR);
		$query->bindParam(":newnom", $_GET['newnom'], PDO::PARAM_STR);
		$query->bindParam(":commentaire_collab", $_GET['commentaire_collab'], PDO::PARAM_STR);


		$query->bindParam(":phone", $_GET['phone'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  	}
	  
    }
    
  } elseif ($job == 'edit_traitement_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		$query = $bdd->prepare("UPDATE hb_acide SET commentaire = :commentaire, commentaire_alerte = :commentaire_alerte WHERE id_acide = :id");			
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		if(empty($_GET['commentaire'])){$push = 0;$query->bindParam(":commentaire_alerte", $push, PDO::PARAM_INT);}else{$push = 1;$query->bindParam(":commentaire_alerte", $push, PDO::PARAM_INT);}
		$query->bindParam(":commentaire", $_GET['commentaire'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  } elseif ($job == 'delete_traitement'){} 

}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>