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
		$job == 'get_traitement_admin_contact' ||
		$job == 'get_traitement_admin_globale' ||
		$job == 'get_traitement_contact_admin' ||
      	$job == 'get_traitement_add'   ||
		$job == 'get_traitement_add_admin' ||
      	$job == 'add_traitement'   ||
      	$job == 'edit_traitement'  ||
		$job == 'edit_traitement_admin'  ||
		$job == 'get_traitement_contact'  ||
		$job == 'get_traitement_contact_qualif'  ||
		$job == 'edit_traitement_contact_admin'  ||
		$job == 'get_traitement_add_contact'   ||
		$job == 'get_traitement_add_contact_admin'   ||
		$job == 'edit_traitement_contact'  ||
		$job == 'add_traitement_contact'  ||
      	$job == 'delete_traitement'){
			
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
	
	if (isset($_GET['id_cat_mere'])){
			$id_cat_mere = $_GET['id_cat_mere'];
			if (!is_numeric($id_cat_mere)){
			$id_cat_mere = '';
			}
    	}
		
		if (isset($_GET['qalif'])){
			$qalif = $_GET['qalif'];
			if (!is_numeric($qalif)){
			$qalif = '';
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
   
  if ($job == 'get_traitement_contact'){
	
	
	try 
	{
		
	
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE siret_client = :siret_client AND id_cat IN (SELECT id_cat FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga) ORDER BY `id_cat` DESC");
	$query->bindParam(":id_client_cat_oraga", $id_cat_mere, PDO::PARAM_INT);
	$query->bindParam(":siret_client", $id_import, PDO::PARAM_STR);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			
				
			$fonction  = '<center>';

			if($traitement['reporting_contact'] == 0 && $traitement['n_stat_contact'] == 0){

			$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
			<span class="badge badge-warning mb-3 mr-3">EN ATTENTE</span></a>';

			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 1){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-warning mb-3 mr-3">Non Vérifié</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 2){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">A quitté</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 3){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 4){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK avec modif</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 5){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">Remplacé</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 6){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">HORS CIBLE</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 7){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">AJOUT</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 8){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">Refus</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 9){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">NRP</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 10){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">EN COURS</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 11){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK / En charge du Transport</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 12){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK / Prise en charge externe</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 13){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">KO</span></a>';
				
			}else{
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">INDISPONIBLE</span></a>';
			}

			$fonction .= '</center>';
				
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte_contact'] == 0){

		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';

		}elseif($traitement['commentaire_alerte_contact'] == 1){

		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';

		}
		
		$alerte .= '';
		if($traitement['n_title_client'] == NULL){$tt = $traitement['title_client'];}else{$tt = $traitement['n_title_client'];}
		if($traitement['n_raison_sociale_client'] == NULL){$rs = $traitement['raison_sociale_client'];}else{$rs = $traitement['n_raison_sociale_client'];}
		if($traitement['n_prenom_client'] == NULL){$prenom = $traitement['prenom_client'];}else{$prenom = $traitement['n_prenom_client'];}
		if($traitement['n_nom_client'] == NULL){$nom = $traitement['nom_client'];}else{$nom = $traitement['n_nom_client'];}
		if($traitement['n_fonction_client'] == NULL){$fc = $traitement['fonction_client'];}else{$fc = $traitement['n_fonction_client'];}
		if($traitement['n_email_client'] == NULL){$emmail = $traitement['email_client'];}else{$emmail = $traitement['n_email_client'];}
        $mysql_data[] = array(		
		
		
          "rs" => $rs,
          "civ"  => $tt,
		  "prenom"  => $prenom,
		  "nom"  => $nom,
		  "fonctione"  => $fc,
		  "email"  => $emmail,
		  
		  "alerte"     => $alerte,
          "fonction"     => $fonction
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
    
  }elseif ($job == 'get_traitement_contact_qualif'){
	
	
	try 
	{
		
	
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE id_cat = :id_cat AND n_stat_contact = :qalif AND user_id_contact = :user_id_contact");
	$query->bindParam(":qalif", $qalif, PDO::PARAM_INT);
	$query->bindParam(":id_cat", $id_import, PDO::PARAM_INT);
	$query->bindParam(":user_id_contact", $id_user, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			
				
			$fonction  = '<center>';

			if($traitement['reporting_contact'] == 0 && $traitement['n_stat_contact'] == 0){

			$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
			<span class="badge badge-warning mb-3 mr-3">EN ATTENTE</span></a>';

			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 1){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-warning mb-3 mr-3">Non Vérifié</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 2){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">A quitté</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 3){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 4){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK avec modif</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 5){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">Remplacé</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 6){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">HORS CIBLE</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 7){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">AJOUT</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 8){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">Refus</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 9){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">NRP</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 10){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">EN COURS</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 11){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK / En charge du Transport</span></a>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 12){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK / Prise en charge externe</span></a>';
				
			}else{
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">INDISPONIBLE</span></a>';
			}

			$fonction .= '</center>';
				
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte_contact'] == 0){

		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';

		}elseif($traitement['commentaire_alerte_contact'] == 1){

		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';

		}
		
		$alerte .= '';
		if($traitement['n_title_client'] == NULL){$tt = $traitement['title_client'];}else{$tt = $traitement['n_title_client'];}
		if($traitement['n_raison_sociale_client'] == NULL){$rs = $traitement['raison_sociale_client'];}else{$rs = $traitement['n_raison_sociale_client'];}
		if($traitement['n_prenom_client'] == NULL){$prenom = $traitement['prenom_client'];}else{$prenom = $traitement['n_prenom_client'];}
		if($traitement['n_nom_client'] == NULL){$nom = $traitement['nom_client'];}else{$nom = $traitement['n_nom_client'];}
		if($traitement['n_fonction_client'] == NULL){$fc = $traitement['fonction_client'];}else{$fc = $traitement['n_fonction_client'];}
		if($traitement['n_email_client'] == NULL){$emmail = $traitement['email_client'];}else{$emmail = $traitement['n_email_client'];}
        $mysql_data[] = array(		
		
		
          "rs" => $rs,
          "civ"  => $tt,
		  "prenom"  => $prenom,
		  "nom"  => $nom,
		  "fonctione"  => $fc,
		  "email"  => $emmail,
		  
		  "alerte"     => $alerte,
          "fonction"     => $fonction
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
    
  }elseif ($job == 'get_traitement_contact_admin'){
	
	
	try 
	{
		
	
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE siret_client = :siret_client ORDER BY `id_cat` DESC");
	$query->bindParam(":siret_client", $id_import, PDO::PARAM_STR);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			
				
			$fonction  = '<center>';

			if($traitement['reporting_contact'] == 0 && $traitement['n_stat_contact'] == 0){

			$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
			<span class="badge badge-warning mb-3 mr-3">EN ATTENTE</span></a>';

			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 1){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-warning mb-3 mr-3">Non Vérifié</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 2){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">A quitté</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 3){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 4){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-success mb-3 mr-3">OK avec modif</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 5){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">Remplacé</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 6){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-info mb-3 mr-3">HORS CIBLE</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 7){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">AJOUT</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 8){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">Refus</span></a>';
				
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat_contact'] == 9){
				
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-primary mb-3 mr-3">NRP</span></a>';
				
			}else{
				$fonction .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '">
				<span class="badge badge-danger mb-3 mr-3">INDISPONIBLE</span></a>';
			}

			$fonction .= '</center>';
				
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte_contact'] == 0){

		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';

		}elseif($traitement['commentaire_alerte_contact'] == 1){

		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';

		}
		
		$alerte .= '';
		if($traitement['n_title_client'] == NULL){$tt = $traitement['title_client'];}else{$tt = $traitement['n_title_client'];}
		if($traitement['n_raison_sociale_client'] == NULL){$rs = $traitement['raison_sociale_client'];}else{$rs = $traitement['n_raison_sociale_client'];}
		if($traitement['n_prenom_client'] == NULL){$prenom = $traitement['prenom_client'];}else{$prenom = $traitement['n_prenom_client'];}
		if($traitement['n_nom_client'] == NULL){$nom = $traitement['nom_client'];}else{$nom = $traitement['n_nom_client'];}
		if($traitement['n_fonction_client'] == NULL){$fc = $traitement['fonction_client'];}else{$fc = $traitement['n_fonction_client'];}
		if($traitement['n_email_client'] == NULL){$emmail = $traitement['email_client'];}else{$emmail = $traitement['n_email_client'];}
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_cat_synthese_fiche_update_contact WHERE fiche_id = :fiche_id");
		$query_count->bindParam(":fiche_id", $traitement['id_client'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_client'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';	
		
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(client_cat_synthese_fiche_update_contact.temps_sec)) AS traitement FROM client_cat_synthese_fiche_update_contact INNER JOIN client_traitement ON client_traitement.id_client = client_cat_synthese_fiche_update_contact.fiche_id WHERE client_cat_synthese_fiche_update_contact.fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_client'], PDO::PARAM_INT);
		$query_temps->execute();			
		$query_sum = $query_temps->fetch();						
		$query_temps->closeCursor();
		
		
		
		$temps = '<p class="custom-line-height font-light">'.$query_sum['traitement'].'</p>';
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur_contact'].'</p>';
		
		
        $mysql_data[] = array(		
		
		
          "rs" => $rs,
          "civ"  => $tt,
		  "prenom"  => $prenom,
		  "nom"  => $nom,
		  "fonctione"  => $fc,
		  "email"  => $emmail,
		  
          "fonction"     => $fonction,
		  
		  "collab" => $operateur,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "alerte"     => $alerte
		  
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
    
  }elseif ($job == 'add_traitement_contact'){
	  
   	
		
		$query = $bdd->prepare("SELECT * FROM client_traitement WHERE siret_client = :siret_client AND id_cat IN (SELECT id_cat FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga) GROUP BY siret_client");
		$query->bindParam(":siret_client", $_GET['siret'], PDO::PARAM_STR);
		$query->bindParam(":id_client_cat_oraga", $_GET['idcatt'], PDO::PARAM_INT);
		$query->execute();
		$traitement = $query->fetch();
		$query->closeCursor();


		$query = $bdd->prepare("INSERT INTO `client_traitement` (id_cat, reporting, operateur, user_id, n_raison_sociale_client, n_adresse1_client, n_adresse2_client, n_adresse3_client, n_code_postal_client, n_ville_client, n_tel_client, n_fax_client, n_siret_client, n_effectif_site_client, tranche_1, n_effectif_groupe_site, tranche_2, n_effectif_nat_client,  tranche_3, n_ca_client, n_ca_tranche_client, raison_sociale_client, adresse1_client, adresse2_client, adresse3_client, code_postal_client, ville_client, tel_client, fax_client, siret_client, effectif_site_client, effectif_groupe_site, ca_client, n_stat, etat, date_calcul, operateur_contact, user_id_contact, reporting_contact, n_title_client, n_prenom_client, n_nom_client, n_fonction_client, n_email_client, n_lk_client, n_comm_client, n_stat_contact, n_type_client, date_calcul_contact, n_tel_client_2, n_service_client) VALUES (:id_cat, :reporting, :operateur, :user_id, :n_raison_sociale_client, :n_adresse1_client, :n_adresse2_client,
			:n_adresse3_client,
			:n_code_postal_client,

			:n_ville_client,
			:n_tel_client,
			:n_fax_client,
			:n_siret_client,
			:n_effectif_site_client,
			:tranche_1,
			:n_effectif_groupe_site,
			:tranche_2,
			:n_effectif_nat_client,
			:tranche_3,
			:n_ca_client,
			:n_ca_tranche_client,
			:raison_sociale_client,
	        :adresse1_client,
			:adresse2_client,
			:adresse3_client,
			:code_postal_client,
			:ville_client,
			:tel_client,
			:fax_client,
			:siret_client,
			:effectif_site_client,
			:effectif_groupe_site,
			:ca_client,
			:n_stat, 1, :date_calcul, :user, :id_user, 1, :n_title_client, :n_prenom_client, :n_nom_client, :n_fonction_client, :n_email_client, :n_lk_client, :n_comm_client, :n_stat_contact, :n_type_client, now(), :n_tel_client_2,:n_service_client)");

		$query->bindParam(":id_cat", $traitement['id_cat'], PDO::PARAM_INT);
		$query->bindParam(":reporting", $traitement['reporting'], PDO::PARAM_INT);
		$query->bindParam(":operateur", $traitement['operateur'], PDO::PARAM_STR);
		$query->bindParam(":user_id", $traitement['user_id'], PDO::PARAM_INT);
		$query->bindParam(":n_raison_sociale_client", $traitement['n_raison_sociale_client'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse1_client", $traitement['n_adresse1_client'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse2_client", $traitement['n_adresse2_client'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse3_client", $traitement['n_adresse3_client'], PDO::PARAM_STR);
		$query->bindParam(":n_code_postal_client", $traitement['n_code_postal_client'], PDO::PARAM_INT);
		$query->bindParam(":n_ville_client", $traitement['n_ville_client'], PDO::PARAM_STR);
		$query->bindParam(":n_tel_client", $traitement['n_tel_client'], PDO::PARAM_STR);
		$query->bindParam(":n_fax_client", $traitement['n_fax_client'], PDO::PARAM_STR);
		$query->bindParam(":n_siret_client", $traitement['n_siret_client'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_site_client", $traitement['n_effectif_site_client'], PDO::PARAM_STR);
		$query->bindParam(":tranche_1", $traitement['tranche_1'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_groupe_site", $traitement['n_effectif_groupe_site'], PDO::PARAM_STR);
		$query->bindParam(":tranche_2", $traitement['tranche_2'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_nat_client", $traitement['n_effectif_nat_client'], PDO::PARAM_STR);
		$query->bindParam(":tranche_3", $traitement['tranche_3'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_client", $traitement['n_ca_client'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_tranche_client", $traitement['n_ca_tranche_client'], PDO::PARAM_STR);
		$query->bindParam(":raison_sociale_client", $traitement['raison_sociale_client'], PDO::PARAM_STR);
		$query->bindParam(":adresse1_client", $traitement['adresse1_client'], PDO::PARAM_STR);
		$query->bindParam(":adresse2_client", $traitement['adresse2_client'], PDO::PARAM_STR);
		$query->bindParam(":adresse3_client", $traitement['adresse3_client'], PDO::PARAM_STR);
		$query->bindParam(":code_postal_client", $traitement['code_postal_client'], PDO::PARAM_INT);
		$query->bindParam(":ville_client", $traitement['ville_client'], PDO::PARAM_STR);
		$query->bindParam(":tel_client", $traitement['tel_client'], PDO::PARAM_STR);
		$query->bindParam(":fax_client", $traitement['fax_client'], PDO::PARAM_STR);
		$query->bindParam(":siret_client", $traitement['siret_client'], PDO::PARAM_STR);
		$query->bindParam(":effectif_site_client", $traitement['effectif_site_client'], PDO::PARAM_STR);
		$query->bindParam(":effectif_groupe_site", $traitement['effectif_groupe_site'], PDO::PARAM_STR);
		$query->bindParam(":ca_client", $traitement['ca_client'], PDO::PARAM_STR);
		$query->bindParam(":n_stat", $traitement['n_stat'], PDO::PARAM_STR);
		$query->bindParam(":date_calcul", $traitement['date_calcul'], PDO::PARAM_STR);

		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":n_title_client", $_GET['civ'], PDO::PARAM_STR);
		$query->bindParam(":n_prenom_client", $_GET['prenom'], PDO::PARAM_STR);
		$query->bindParam(":n_nom_client", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":n_fonction_client", $_GET['fc'], PDO::PARAM_STR);
		$query->bindParam(":n_email_client", $_GET['email'], PDO::PARAM_STR);
		$query->bindParam(":n_lk_client", $_GET['lk'], PDO::PARAM_STR);
		$query->bindParam(":n_comm_client", $_GET['commentaire_collab'], PDO::PARAM_STR);
		$query->bindParam(":n_stat_contact", $_GET['stat'], PDO::PARAM_INT);
		$query->bindParam(":n_tel_client_2", $_GET['tel2'], PDO::PARAM_STR);
		$query->bindParam(":n_type_client", $_GET['type'], PDO::PARAM_INT);
		$query->bindParam(":n_service_client", $_GET['service'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		
		
		$query = $bdd->prepare("SELECT MAX(id_client) AS MAX FROM client_traitement WHERE user_id_contact = :user_id_contact");	
		$query->bindParam(":user_id_contact", $_GET['user_id'], PDO::PARAM_INT);
		$query->execute();
		$max_id = $query->fetch();
		$query->closeCursor();

		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `client_cat_synthese_fiche_update_contact` (`fiche_id`, `date_debut_traitement`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `lot_id`, `date_calcul`) VALUES (:fiche_id, :fiche_debut, :fiche_fin, :go, :id_user, :user, :lot_id, now())");	
		$query->bindParam(":fiche_id", $max_id['MAX'], PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":lot_id", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();		

	
	
	$result  = 'success';
	$message = 'Succès de requête';
	
		
	$query = null;
	$bdd = null;
	
	
  
  } elseif ($job == 'get_traitement_add_contact'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{

			$query_select_add = $bdd->prepare("SELECT * FROM client_traitement WHERE id_client = :id_client");	
			$query_select_add->bindParam(":id_client", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				
				"civ_o" => $traitement_edit['title_client'],
				"prenom_o"  => $traitement_edit['prenom_client'],
				"nom_o"  => $traitement_edit['nom_client'],
				"fc_o"  => $traitement_edit['fonction_client'],
				"email_o"  => $traitement_edit['email_client'],	
							  
				  
				"civ" => $traitement_edit['n_title_client'],
				"prenom"  => $traitement_edit['n_prenom_client'],
				"nom"  => $traitement_edit['n_nom_client'],
				"fc"  => $traitement_edit['n_fonction_client'],
				"email"  => $traitement_edit['n_email_client'],
				"lk"  => $traitement_edit['n_lk_client'],
				"type"  => $traitement_edit['n_type_client'],
				
				"rs"  => $traitement_edit['n_raison_sociale_client'],
				"siret"  => $traitement_edit['n_siret_client'],
				"ad"  => $traitement_edit['n_adresse2_client'],
				"cp"  => $traitement_edit['n_code_postal_client'],
				"ville"  => $traitement_edit['n_ville_client'],
				"tel"  => $traitement_edit['n_tel_client'],
				"tel2"  => $traitement_edit['n_tel_client_2'],
				"service"  => $traitement_edit['n_service_client'],
				
				"ors"  => $traitement_edit['raison_sociale_client'],
				"osiret"  => $traitement_edit['siret_client'],
				"oad"  => $traitement_edit['adresse2_client'],
				"ocp"  => $traitement_edit['code_postal_client'],
				"oville"  => $traitement_edit['ville_client'],
				"otel"  => $traitement_edit['tel_client'],
				
				"commentaire_collab"  => $traitement_edit['n_comm_client'],
				
				"commentaire"  => $traitement_edit['commentaire_contact'],
				
				"reporting"  => $traitement_edit['reporting_contact'],
				"stat"  => $traitement_edit['n_stat_contact']
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
  
  }  elseif ($job == 'get_traitement_add_contact_admin'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{

			$query_select_add = $bdd->prepare("SELECT * FROM client_traitement WHERE id_client = :id_client");	
			$query_select_add->bindParam(":id_client", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				
				"civ_o" => $traitement_edit['title_client'],
				  "prenom_o"  => $traitement_edit['prenom_client'],
				  "nom_o"  => $traitement_edit['nom_client'],
				  "fc_o"  => $traitement_edit['fonction_client'],
				  "email_o"  => $traitement_edit['email_client'],				  
				  			  
				  "civ" => $traitement_edit['n_title_client'],
				  "prenom"  => $traitement_edit['n_prenom_client'],
				  "nom"  => $traitement_edit['n_nom_client'],
				  "fc"  => $traitement_edit['n_fonction_client'],
				  "email"  => $traitement_edit['n_email_client'],
				  "lk"  => $traitement_edit['n_lk_client'],
				  "type"  => $traitement_edit['n_type_client'],
				  
				  "rs"  => $traitement_edit['n_raison_sociale_client'],
				  "siret"  => $traitement_edit['n_siret_client'],
				  "ad"  => $traitement_edit['n_adresse2_client'],
				  "cp"  => $traitement_edit['n_code_postal_client'],
				  "ville"  => $traitement_edit['n_ville_client'],
				  "tel"  => $traitement_edit['n_tel_client'],
				  "tel2"  => $traitement_edit['n_tel_client_2'],
				  "service"  => $traitement_edit['n_service_client'],
				  
				  "commentaire_collab"  => $traitement_edit['n_comm_client'],
				  
				  "commentaire"  => $traitement_edit['commentaire_contact'],
				  
				  "reporting"  => $traitement_edit['reporting_contact'],
				  "stat"  => $traitement_edit['n_stat_contact']
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
  
  }  elseif ($job == 'edit_traitement_contact'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
				
		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `client_cat_synthese_fiche_update_contact` (`fiche_id`, `date_debut_traitement`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `lot_id`, `date_calcul`) VALUES (:fiche_id, :fiche_debut, :fiche_fin, :go, :id_user, :user, :lot_id, now())");	
		$query->bindParam(":fiche_id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":lot_id", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();	
		
		$query = $bdd->prepare("SELECT date_calcul_contact FROM client_traitement WHERE id_client = :id_client");
		$query->bindParam(":id_client", $id, PDO::PARAM_INT);
		$query->execute();
		$verif_maj = $query->fetch();
		$query->closeCursor();

		if($verif_maj['date_calcul_contact'] == NULL){

			$query = $bdd->prepare("UPDATE client_traitement SET operateur_contact = :user, user_id_contact = :id_user, reporting_contact = 1, n_title_client = :n_title_client, n_prenom_client = :n_prenom_client, n_nom_client = :n_nom_client, n_fonction_client = :n_fonction_client, n_email_client = :n_email_client, n_tel_client_2 = :n_tel_client_2, n_lk_client = :n_lk_client, n_comm_client = :n_comm_client, n_stat_contact = :n_stat_contact, n_type_client = :n_type_client, date_calcul_contact = now(), n_service_client = :n_service_client WHERE id_client = :id_client");
		}else{

			$query = $bdd->prepare("UPDATE client_traitement SET operateur_contact = :user, user_id_contact = :id_user, reporting_contact = 1, n_title_client = :n_title_client, n_prenom_client = :n_prenom_client, n_nom_client = :n_nom_client, n_fonction_client = :n_fonction_client, n_email_client = :n_email_client, n_tel_client_2 = :n_tel_client_2, n_lk_client = :n_lk_client, n_comm_client = :n_comm_client, n_stat_contact = :n_stat_contact, n_type_client = :n_type_client, n_service_client = :n_service_client WHERE id_client = :id_client");
		}
		
		
		$query->bindParam(":id_client", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":n_title_client", $_GET['civ'], PDO::PARAM_STR);
		$query->bindParam(":n_prenom_client", $_GET['prenom'], PDO::PARAM_STR);
		$query->bindParam(":n_nom_client", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":n_fonction_client", $_GET['fc'], PDO::PARAM_STR);
		$query->bindParam(":n_email_client", $_GET['email'], PDO::PARAM_STR);
		$query->bindParam(":n_lk_client", $_GET['lk'], PDO::PARAM_STR);
		$query->bindParam(":n_comm_client", $_GET['commentaire_collab'], PDO::PARAM_STR);
		$query->bindParam(":n_stat_contact", $_GET['stat'], PDO::PARAM_STR);
		$query->bindParam(":n_type_client", $_GET['type'], PDO::PARAM_INT);
		$query->bindParam(":n_tel_client_2", $_GET['tel2'], PDO::PARAM_STR);
		$query->bindParam(":n_service_client", $_GET['service'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  }  elseif ($job == 'edit_traitement_contact_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
				
		

		
		
		$query = $bdd->prepare("UPDATE client_traitement SET commentaire_contact = :commentaire_contact, commentaire_alerte_contact = :commentaire_alerte_contact, n_title_client = :n_title_client, n_prenom_client = :n_prenom_client, n_nom_client = :n_nom_client, n_fonction_client = :n_fonction_client, n_email_client = :n_email_client, n_tel_client_2 = :n_tel_client_2, n_lk_client = :n_lk_client, n_stat_contact = :n_stat_contact, n_type_client = :n_type_client, n_service_client = :n_service_client WHERE id_client = :id");
		$query->bindParam(":n_title_client", $_GET['civ'], PDO::PARAM_STR);
		$query->bindParam(":n_prenom_client", $_GET['prenom'], PDO::PARAM_STR);
		$query->bindParam(":n_nom_client", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":n_fonction_client", $_GET['fc'], PDO::PARAM_STR);
		$query->bindParam(":n_email_client", $_GET['email'], PDO::PARAM_STR);
		$query->bindParam(":n_lk_client", $_GET['lk'], PDO::PARAM_STR);
		$query->bindParam(":n_stat_contact", $_GET['stat'], PDO::PARAM_STR);
		$query->bindParam(":n_type_client", $_GET['type'], PDO::PARAM_INT);
		$query->bindParam(":n_tel_client_2", $_GET['tel2'], PDO::PARAM_STR);
		$query->bindParam(":n_service_client", $_GET['service'], PDO::PARAM_INT);			
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		if(empty($_GET['commentaire'])){$push = 0;$query->bindParam(":commentaire_alerte_contact", $push, PDO::PARAM_INT);}else{$push = 1;$query->bindParam(":commentaire_alerte_contact", $push, PDO::PARAM_INT);}
		$query->bindParam(":commentaire_contact", $_GET['commentaire'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  } elseif ($job == 'get_traitement'){
	
	
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE (id_cat = :id_cat AND user_id = 0) OR (id_cat = :id_cat AND user_id = :user_id) GROUP BY siret_client ORDER BY `id_cat` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat", $id_import, PDO::PARAM_INT);
		
	
	/*$query = $bdd->prepare("SELECT * FROM client_traitement WHERE (rtime = 0 OR rtime = :user_id) AND id_cat = :id_cat GROUP BY siret_client, raison_sociale_client ORDER BY `id_cat` DESC");
	$query->bindParam(":user_id", $id_user, PDO::PARAM_INT);
	$query->bindParam(":id_cat", $id_import, PDO::PARAM_INT);*/
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			if(!empty($_GET['mode']) && $_GET['mode'] == 'debut'){
				
			$societe  = '<center>';

			if($traitement['reporting'] == 0 && $traitement['n_stat'] == NULL){

			$societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-info mb-3 mr-3">société</span></a>';

			}elseif($traitement['reporting'] == 1 && $traitement['n_stat'] == NULL){

				$societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-success mb-3 mr-3">société OK</span></a>';
			}else{ 

				$societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-danger mb-3 mr-3">fermée</span></a>';

			}

			$societe .= '</center>';
			
			
			$contact  = '<center>';
			
			$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_traitement WHERE id_cat = :id_cat AND siret_client = :siret_client AND reporting_contact = 0");
			$query_count->bindParam(":id_cat", $traitement['id_cat'], PDO::PARAM_INT);
			$query_count->bindParam(":siret_client", $traitement['siret_client'], PDO::PARAM_STR);
			$query_count->execute();
			$alerte_mood = $query_count->fetchColumn();
			$query_count->closeCursor();
			
			$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_traitement WHERE id_cat = :id_cat AND siret_client = :siret_client AND reporting_contact = 1 AND (n_stat_contact = 0 OR n_stat_contact = 9 OR n_stat_contact = 6 OR n_stat_contact = 8)");
			$query_count->bindParam(":id_cat", $traitement['id_cat'], PDO::PARAM_INT);
			$query_count->bindParam(":siret_client", $traitement['siret_client'], PDO::PARAM_STR);
			$query_count->execute();
			$alerte_mood_ko = $query_count->fetchColumn();
			$query_count->closeCursor();
			
			
			$query_save = $bdd->prepare("SELECT id_client_cat_oraga FROM `client_cat` WHERE id_cat = :orga");
			$query_save->bindParam(":orga", $traitement['id_cat'], PDO::PARAM_STR);
			$query_save->execute();
			$ligne_savee = $query_save->fetch();
			$query_save->closeCursor();
			
						
			if($traitement['siret_client'] <> NULL){
				
					
					if($alerte_mood == 0){						
						
							
								
								if($alerte_mood_ko > 0){
								$contact .= '<a href="ClientBiblioContact-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$ligne_savee['id_client_cat_oraga'].'"><span class="badge badge-danger mb-3 mr-3">CONTACT KO</span></a>';	
								}else{
								$contact .= '<a href="ClientBiblioContact-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$ligne_savee['id_client_cat_oraga'].'"><span class="badge badge-success mb-3 mr-3">CONTACT OK</span></a>';	
								}
							
						
					}else{
						
						$contact .= '<a href="ClientBiblioContact-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$ligne_savee['id_client_cat_oraga'].'"><span class="badge badge-primary mb-3 mr-3">CONTACT</span></a>';
					
					}

			}else{				
			$contact .= '<span class="badge badge-danger mb-3 mr-3">NO siret</span>';			
			}
			
			$contact .= '</center>';
			
			}else{
			
			$societe  = '<center>';			
			$societe .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$societe .= '</center>';
			
			$contact  = '<center>';			
			$contact .= '<span class="badge badge-warning mb-3 mr-3">X</span>';			
			$contact .= '</center>';
			
			}
				
		
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à varifier</span>';
		}
		
		$alerte .= '';
		
        $mysql_data[] = array(
		
		
          "rs" => $traitement['raison_sociale_client'],
          "ad1"  => $traitement['adresse1_client'],
		  "ad2"  => $traitement['adresse2_client'],
		  "ad3"  => $traitement['adresse3_client'],
		  "cp"  => $traitement['code_postal_client'],
		  "ville"  => $traitement['ville_client'],
		  "tel"  => $traitement['tel_client'],
		  "fax"  => $traitement['fax_client'],
		  "siret"  => $traitement['siret_client'],
		  "nsiret"  => $traitement['n_siret_client'],
		  "es"  => $traitement['effectif_site_client'],
		  "eg"  => $traitement['effectif_groupe_site'],
		  "ca"  => $traitement['ca_client'],
		  
		  "alerte"     => $alerte,
          "societe"     => $societe,
		  "contact"     => $contact
        );
      
	
	
	}	  
    $query->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	
	$bdd = null;
    
  }elseif ($job == 'get_traitement_admin'){
	
	
	try 
	{
		
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE id_cat = :id_cat GROUP BY siret_client ORDER BY `id_cat` DESC");
	$query->bindParam(":id_cat", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 
	
			
			$societe  = '<center>';
			if($traitement['reporting'] == 0 && $traitement['n_stat'] == NULL){
			$societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-info mb-3 mr-3">société</span></a>';			
			}elseif($traitement['reporting'] == 1 && $traitement['n_stat'] == NULL){$societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-success mb-3 mr-3">société OK</span></a>';}else{ $societe .= '<a href="#" id="function_edit_web" data-id="'   . $traitement['id_client'] . '" data-name="Numéro : ' . $traitement['id_client'] . '"><span class="badge badge-danger mb-3 mr-3">fermée</span></a>';}
			$societe .= '</center>';
			
			
			
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_cat_synthese_fiche_update WHERE fiche_id = :fiche_id");
		$query_count->bindParam(":fiche_id", $traitement['id_client'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_client'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';	
		
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(client_cat_synthese_fiche_update.temps_sec)) AS traitement FROM client_cat_synthese_fiche_update INNER JOIN client_traitement ON client_traitement.id_client = client_cat_synthese_fiche_update.fiche_id WHERE client_cat_synthese_fiche_update.fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_client'], PDO::PARAM_INT);
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
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur'].'</p>';
		
		
		
					
        $mysql_data[] = array(
          "rs" => $traitement['raison_sociale_client'],
          "ad1"  => $traitement['adresse1_client'],
		  "ad2"  => $traitement['adresse2_client'],
		  "ad3"  => $traitement['adresse3_client'],
		  "cp"  => $traitement['code_postal_client'],
		  "ville"  => $traitement['ville_client'],
		  "tel"  => $traitement['tel_client'],
		  "fax"  => $traitement['fax_client'],
		  "siret"  => $traitement['siret_client'],
		  "es"  => $traitement['effectif_site_client'],
		  "eg"  => $traitement['effectif_groupe_site'],
		  "ca"  => $traitement['ca_client'],
		  "collab" => $operateur,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "societe"     => $societe,
		  "alerte"     => $alerte
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
    
  }elseif ($job == 'get_traitement_admin_contact'){
	
	
	try 
	{
		
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE id_cat = :id_cat GROUP BY siret_client ORDER BY `id_cat` DESC");
	$query->bindParam(":id_cat", $id_import, PDO::PARAM_INT);
	
	$query->execute();
	while ($traitement = $query->fetch()){ 		
			
			
			$contact  = '<center>';
			
			$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_traitement WHERE id_cat = :id_cat AND siret_client = :siret_client AND reporting_contact = 0");
			$query_count->bindParam(":id_cat", $traitement['id_cat'], PDO::PARAM_INT);
			$query_count->bindParam(":siret_client", $traitement['siret_client'], PDO::PARAM_STR);
			$query_count->execute();
			$alerte_mood = $query_count->fetchColumn();
			$query_count->closeCursor();
			
			$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_traitement WHERE id_cat = :id_cat AND siret_client = :siret_client AND reporting_contact = 1 AND n_stat_contact = 0");
			$query_count->bindParam(":id_cat", $traitement['id_cat'], PDO::PARAM_INT);
			$query_count->bindParam(":siret_client", $traitement['siret_client'], PDO::PARAM_STR);
			$query_count->execute();
			$alerte_mood_indis = $query_count->fetchColumn();
			$query_count->closeCursor();
		
			if($alerte_mood == 0){
				if($alerte_mood_indis > 0){
				$contact .= '<a href="ClientBiblioContactAdmin-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$id_cat_mere.'"><span class="badge badge-danger mb-3 mr-3">CONTACT KO</span></a>';
				}else{
				$contact .= '<a href="ClientBiblioContactAdmin-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$id_cat_mere.'"><span class="badge badge-success mb-3 mr-3">CONTACT OK</span></a>';	
				}
			}else{
				
				$contact .= '<a href="ClientBiblioContactAdmin-'.$traitement['id_cat'].'-'.$traitement['siret_client'].'-'.$id_cat_mere.'"><span class="badge badge-primary mb-3 mr-3">CONTACT</span></a>';
			
			}
				
			$contact .= '</center>';
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_cat_synthese_fiche_update WHERE fiche_id = :fiche_id");
		$query_count->bindParam(":fiche_id", $traitement['id_client'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(1)</a></span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup"><a id="mood_affichage" data-id="'.$traitement['id_client'].'">(2)</a></span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger"><a id="mood_affichage" data-id="'.$traitement['id_client'].'"><b>('.$alerte_modif.')</b></a></span>';
		}
		$mood .= '';	
		
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(client_cat_synthese_fiche_update.temps_sec)) AS traitement FROM client_cat_synthese_fiche_update INNER JOIN client_traitement ON client_traitement.id_client = client_cat_synthese_fiche_update.fiche_id WHERE client_cat_synthese_fiche_update.fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_client'], PDO::PARAM_INT);
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
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur'].'</p>';
		
		
		
					
        $mysql_data[] = array(
          "rs" => $traitement['raison_sociale_client'],
          "ad1"  => $traitement['adresse1_client'],
		  "ad2"  => $traitement['adresse2_client'],
		  "ad3"  => $traitement['adresse3_client'],
		  "cp"  => $traitement['code_postal_client'],
		  "ville"  => $traitement['ville_client'],
		  "tel"  => $traitement['tel_client'],
		  "fax"  => $traitement['fax_client'],
		  "siret"  => $traitement['siret_client'],
		  "es"  => $traitement['effectif_site_client'],
		  "eg"  => $traitement['effectif_groupe_site'],
		  "ca"  => $traitement['ca_client'],
		  "collab" => $operateur,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "contact"     => $contact,
		  "alerte"     => $alerte
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
    
  }elseif ($job == 'get_traitement_admin_globale'){
	
	
	
	//$query = $bdd->prepare("SELECT * FROM client_traitement WHERE id_cat = :id_cat GROUP BY raison_sociale_client ORDER BY `id_cat` DESC");	
		
	$query = $bdd->prepare("SELECT * FROM client_traitement WHERE reporting_contact = 1 AND id_cat IN (SELECT id_cat FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga) ORDER BY `id_cat` DESC");
	
	//$query = $bdd->prepare("SELECT * FROM client_traitement WHERE reporting_contact = 0 AND id_cat IN (SELECT id_cat FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga) ORDER BY `id_cat` DESC LIMIT 500");
	
	$query->bindParam(":id_client_cat_oraga", $id_cat_mere, PDO::PARAM_INT);
	
	$query->execute();
	
	while ($traitement = $query->fetch()){ 
	
			
		$societe  = '<center>';
		if($traitement['reporting'] == 1 && $traitement['n_stat'] == NULL){$societe .= '<span class="badge badge-success mb-3 mr-3">société OK</span>';}elseif($traitement['reporting'] == 1 && $traitement['n_stat'] <> NULL){ $societe .= '<span class="badge badge-danger mb-3 mr-3">fermée</span>';}
		$societe .= '</center>';
		
		$mood  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_cat_synthese_fiche_update_contact WHERE fiche_id = :fiche_id");
		$query_count->bindParam(":fiche_id", $traitement['id_client'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood .= '<span class="badge badge-info">1</span>';
		}elseif($alerte_modif == 2){
		$mood .= '<span class="badge badge-buttercup">2</span>';
		}elseif($alerte_modif > 2){
		$mood .= '<span class="badge badge-danger">('.$alerte_modif.')</span>';
		}
		$mood .= '';
		
		$mood_societe  = '';
		$query_count = $bdd->prepare("SELECT COUNT(*) FROM client_cat_synthese_fiche_update WHERE fiche_id = :fiche_id");
		$query_count->bindParam(":fiche_id", $traitement['id_client'], PDO::PARAM_INT);
		$query_count->execute();
		$alerte_modif_s = $query_count->fetchColumn();
		$query_count->closeCursor();
		
		if($alerte_modif == 1){
		$mood_societe .= '<span class="badge badge-info">1</span>';
		}elseif($alerte_modif == 2){
		$mood_societe .= '<span class="badge badge-buttercup">2</span>';
		}elseif($alerte_modif > 2){
		$mood_societe .= '<span class="badge badge-danger">('.$alerte_modif_s.')</span>';
		}
		$mood_societe .= '';	
		
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(client_cat_synthese_fiche_update_contact.temps_sec)) AS traitement FROM client_cat_synthese_fiche_update_contact INNER JOIN client_traitement ON client_traitement.id_client = client_cat_synthese_fiche_update_contact.fiche_id WHERE client_cat_synthese_fiche_update_contact.fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_client'], PDO::PARAM_INT);
		$query_temps->execute();			
		$query_sum = $query_temps->fetch();						
		$query_temps->closeCursor();
		
		$query_temps = $bdd->prepare("SELECT SEC_TO_TIME(SUM(client_cat_synthese_fiche_update.temps_sec)) AS traitement FROM client_cat_synthese_fiche_update INNER JOIN client_traitement ON client_traitement.id_client = client_cat_synthese_fiche_update.fiche_id WHERE client_cat_synthese_fiche_update.fiche_id = :id");	
		$query_temps->bindParam(":id", $traitement['id_client'], PDO::PARAM_INT);
		$query_temps->execute();			
		$query_sum_societe = $query_temps->fetch();						
		$query_temps->closeCursor();
		
		$alerte  = '';
		
		if($traitement['commentaire_alerte'] == 0){
		$alerte .= '<span class="badge badge-shamrock mb-3 mr-3"><span class="icon iconfont iconfont-fm-check"></span></span>';
		}elseif($traitement['commentaire_alerte'] == 1){
		$alerte .= '<span class="badge badge-danger mb-3 mr-3">à vérifier</span>';
		}
		$alerte .= '';
		
		$temps = '<p class="custom-line-height font-light">'.$query_sum['traitement'].'</p>';
		$temps_societe = '<p class="custom-line-height font-light">'.$query_sum_societe['traitement'].'</p>';
		$operateur_societe = '<p class="custom-line-height font-light">'.$traitement['operateur'].'</p>';
		$operateur = '<p class="custom-line-height font-light">'.$traitement['operateur_contact'].'</p>';
		
		
		$rs_o = mb_strtoupper($traitement['n_raison_sociale_client'], 'UTF-8');
		$ad1_o = mb_strtoupper($traitement['n_adresse1_client'], 'UTF-8');
		$ad2_o = mb_strtoupper($traitement['n_adresse2_client'], 'UTF-8');
		$ad3_o = mb_strtoupper($traitement['n_adresse3_client'], 'UTF-8');
		$cp_o = $traitement['n_code_postal_client'];
		$ville_o = mb_strtoupper($traitement['n_ville_client'], 'UTF-8');
		$tel_o = $traitement['n_tel_client'];
		$fax_o = $traitement['n_fax_client'];
		$siret_o = $traitement['n_siret_client'];
		$es_o = mb_strtoupper($traitement['n_effectif_site_client'], 'UTF-8');
		$est_o = mb_strtoupper($traitement['tranche_1'], 'UTF-8');
		$eg_o = mb_strtoupper($traitement['n_effectif_groupe_site'], 'UTF-8');
		$egt_o = mb_strtoupper($traitement['tranche_2'], 'UTF-8');
		$en_o = mb_strtoupper($traitement['n_effectif_nat_client'], 'UTF-8');
		$ent_o = mb_strtoupper($traitement['tranche_3'], 'UTF-8');
		$ca_o = mb_strtoupper($traitement['n_ca_client'], 'UTF-8');
		$cat_o = mb_strtoupper($traitement['n_ca_tranche_client'], 'UTF-8');
		
		
		
		
			$fonction  = '<center>';

			if($traitement['reporting_contact'] == 0 && $traitement['n_stat_contact'] == 0){

			$fonction .= '<span class="badge badge-warning mb-3 mr-3">EN ATTENTE</span>';

			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 1){
				
				$fonction .= '<span class="badge badge-warning mb-3 mr-3">Non Vérifié</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 2){
				
				$fonction .= '<span class="badge badge-danger mb-3 mr-3">A quitté</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 3){
				
				$fonction .= '<span class="badge badge-success mb-3 mr-3">OK</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 4){
				
				$fonction .= '<span class="badge badge-success mb-3 mr-3">OK avec modif</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 5){
				
				$fonction .= '<span class="badge badge-info mb-3 mr-3">Remplacé</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 6){
				
				$fonction .= '<span class="badge badge-info mb-3 mr-3">HORS CIBLE</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 7){
				
				$fonction .= '<span class="badge badge-primary mb-3 mr-3">AJOUT</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 8){
				
				$fonction .= '<span class="badge badge-danger mb-3 mr-3">REFUS</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 9){
				
				$fonction .= '<span class="badge badge-danger mb-3 mr-3">NRP</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 10){
				
				$fonction .= '<span class="badge badge-success mb-3 mr-3">EN COURS</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 11){
				
				$fonction .= '<span class="badge badge-success mb-3 mr-3">OK / En charge du Transport</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 12){
				
				$fonction .= '<span class="badge badge-success mb-3 mr-3">OK / Prise en charge externe</span>';
				
			}elseif($traitement['reporting_contact'] == 1 && $traitement['n_stat_contact'] == 13){
				
				$fonction .= '<span class="badge badge-danger mb-3 mr-3">KO</span>';
				
			}else{
				$fonction .= '<span class="badge badge-danger mb-3 mr-3">INDISPONIBLE</span>';
			}

			$fonction .= '</center>';
			
			$title_o = mb_strtoupper($traitement['title_client'], 'UTF-8');
			$prenom_o = mb_strtoupper($traitement['prenom_client'], 'UTF-8');
			$nom_o = mb_strtoupper($traitement['nom_client'], 'UTF-8');
			$fc_o = mb_strtoupper($traitement['fonction_client'], 'UTF-8');
			$email_o = mb_strtoupper($traitement['email_client'], 'UTF-8');			
			$title = mb_strtoupper($traitement['n_title_client'], 'UTF-8');
			$prenom = mb_strtoupper($traitement['n_prenom_client'], 'UTF-8');
			$nom = mb_strtoupper($traitement['n_nom_client'], 'UTF-8');



			if($traitement['n_fonction_client'] == 1){

				$fc = 'Responsable Achats';

			}elseif($traitement['n_fonction_client'] == 2){
				
				$fc = 'Responsable Achats';
				
			}elseif($traitement['n_fonction_client'] == 3){
				
				$fc = 'Responsable logistique';
				
			}elseif($traitement['n_fonction_client'] == 4){
				
				$fc = 'Directeur logistique';
				
			}elseif($traitement['n_fonction_client'] == 5){
				
				$fc = 'Responsable Service Généraux';
				
			}elseif($traitement['n_fonction_client'] == 6){
				
				$fc = 'Directeur service généraux';
				
			}elseif($traitement['n_fonction_client'] == 7){
				
				$fc = 'Directeur Général';
				
			}elseif($traitement['n_fonction_client'] == 8){
				
				$fc = 'Gérant';
				
			}else{

			$fc = mb_strtoupper($traitement['n_fonction_client'], 'UTF-8');	

			}



			if($traitement['n_service_client'] == 1){

				$n_service = 'SERVICE ACHATS';

			}elseif($traitement['n_service_client'] == 3){
				
				$n_service = 'SERVICES GENERAUX';
				
			}elseif($traitement['n_service_client'] == 4){
				
				$n_service = 'SERVICE LOGISTIQUE';
				
			}elseif($traitement['n_service_client'] == 5){
				
				$n_service = 'DIRECTION GENERALE';
				
			}elseif($traitement['n_service_client'] == 6){
				
				$n_service = 'AUTRES';
				
			}else{

				$n_service = mb_strtoupper($traitement['n_service_client'], 'UTF-8');	

			}




			$email = $traitement['n_email_client'];
			$lk = mb_strtoupper($traitement['n_lk_client'], 'UTF-8');
			$tel2 = mb_strtoupper($traitement['n_tel_client_2'], 'UTF-8');
			$com = mb_strtoupper($traitement['n_comm_client'], 'UTF-8');
				
				$originalDate = $traitement['date_calcul_contact'];
				$newDate = date("d-m-Y", strtotime($originalDate));


        $mysql_data[] = array(
		"date"  => $newDate,	
		"title_o"  => $title_o,
		  "prenom_o"  => $prenom_o,
		  "nom_o"  => $nom_o,
		  "fc_o"  => $fc_o,
		  "email_o"  => $email_o,
		  "title"  => $title,
		  "prenom"  => $prenom,
		  "nom"  => $nom,
		  "fc"  => $fc,
		  "email"  => $email,
		  "lk"  => $lk,
		  "tel2"  => $tel2,
		  "com"  => $com,
		  
		  
          "rs" => $traitement['raison_sociale_client'],
          "ad1"  => $traitement['adresse1_client'],
		  "ad2"  => $traitement['adresse2_client'],
		  "ad3"  => $traitement['adresse3_client'],
		  "cp"  => $traitement['code_postal_client'],
		  "ville"  => $traitement['ville_client'],
		  "tel"  => $traitement['tel_client'],
		  "fax"  => $traitement['fax_client'],
		  "siret"  => $traitement['siret_client'],
		  "es"  => $traitement['effectif_site_client'],
		  "eg"  => $traitement['effectif_groupe_site'],
		  "ca"  => $traitement['ca_client'],
		  "n_service" => $n_service,	  
		  "rs_o" => $rs_o,
          "ad1_o"  => $ad1_o,
		  "ad2_o"  => $ad2_o,
		  "ad3_o"  => $ad3_o,
		  "cp_o"  => $cp_o,
		  "ville_o"  => $ville_o,
		  "tel_o"  => $tel_o,
		  "fax_o"  => $fax_o,
		  "siret_o"  => $siret_o,
		  "es_o"  => $es_o,
		  "est_o"  => $est_o,
		  "eg_o"  => $eg_o,
		  "egt_o"  => $egt_o,
		  "en_o"  => $en_o,
		  "ent_o"  => $ent_o,
		  "ca_o"  => $ca_o,
		  "cat_o"  => $cat_o,		  
		  "collab" => $operateur,
		  "mood"     => $mood,
		  "temps" => $temps,
		  "fonction"     => $fonction,
		  "societe"     => $societe,
		  "alerte"     => $alerte,
		  
		  "collab_societe" => $operateur_societe,
		  "mood_societe"     => $mood_societe,
		  "temps_societe" => $temps_societe
		  
        );
      
	
	
	}	  
    $query->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';	
	$bdd = null;
    
  } elseif ($job == 'get_traitement_add'){	
	
	if ($id == ''){
		
	  $result  = 'error';
	  $message = 'Échec id';
	  
	}else{
		
		try 
		{

			$query_select_add = $bdd->prepare("SELECT * FROM client_traitement WHERE id_client = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"rs_o" => $traitement_edit['raison_sociale_client'],
				  "ad1_o"  => $traitement_edit['adresse1_client'],
				  "ad2_o"  => $traitement_edit['adresse2_client'],
				  "ad3_o"  => $traitement_edit['adresse3_client'],
				  "cp_o"  => $traitement_edit['code_postal_client'],
				  "ville_o"  => $traitement_edit['ville_client'],
				  "tel_o"  => $traitement_edit['tel_client'],
				  "fax_o"  => $traitement_edit['fax_client'],
				  "siret_o"  => $traitement_edit['siret_client'],
				  "esite_o"  => $traitement_edit['effectif_site_client'],
				  "egroupe_o"  => $traitement_edit['effectif_groupe_site'],
				  "ca_o"  => $traitement_edit['ca_client'],				  
				  "rs" => $traitement_edit['n_raison_sociale_client'],
				  "ad1"  => $traitement_edit['n_adresse1_client'],
				  "ad2"  => $traitement_edit['n_adresse2_client'],
				  "ad33"  => $traitement_edit['n_adresse3_client'],
				  "cp"  => $traitement_edit['n_code_postal_client'],
				  "ville"  => $traitement_edit['n_ville_client'],
				  "tel"  => $traitement_edit['n_tel_client'],
				  "fax"  => $traitement_edit['n_fax_client'],
				  "siret"  => $traitement_edit['n_siret_client'],
				  "esite"  => $traitement_edit['n_effectif_site_client'],
				  "egroupe"  => $traitement_edit['n_effectif_groupe_site'],
				  "enat"  => $traitement_edit['n_effectif_nat_client'],
				  "ca"  => $traitement_edit['n_ca_client'],
				  "commentaire"  => $traitement_edit['commentaire'],
				  "t1"  => $traitement_edit['tranche_1'],
				  "t2"  => $traitement_edit['tranche_2'],
				  "t3"  => $traitement_edit['tranche_3'],
				  
				  "catt"  => $traitement_edit['n_ca_tranche_client'],
				  "reporting"  => $traitement_edit['reporting'],
				  "stat"  => $traitement_edit['n_stat']
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
			$query_select_add = $bdd->prepare("SELECT * FROM client_traitement WHERE id_client = :id");	
			$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
			$query_select_add->execute();
			
			while ($traitement_edit = $query_select_add->fetch()){
				$mysql_data[] = array(
				"rs_o" => $traitement_edit['raison_sociale_client'],
				  "ad1_o"  => $traitement_edit['adresse1_client'],
				  "ad2_o"  => $traitement_edit['adresse2_client'],
				  "ad3_o"  => $traitement_edit['adresse3_client'],
				  "cp_o"  => $traitement_edit['code_postal_client'],
				  "ville_o"  => $traitement_edit['ville_client'],
				  "tel_o"  => $traitement_edit['tel_client'],
				  "fax_o"  => $traitement_edit['fax_client'],
				  "siret_o"  => $traitement_edit['siret_client'],
				  "esite_o"  => $traitement_edit['effectif_site_client'],
				  "egroupe_o"  => $traitement_edit['effectif_groupe_site'],
				  "ca_o"  => $traitement_edit['ca_client'],				  
				  "rs" => $traitement_edit['n_raison_sociale_client'],
				  "ad1"  => $traitement_edit['n_adresse1_client'],
				  "ad2"  => $traitement_edit['n_adresse2_client'],
				  "ad33"  => $traitement_edit['n_adresse3_client'],
				  "cp"  => $traitement_edit['n_code_postal_client'],
				  "ville"  => $traitement_edit['n_ville_client'],
				  "tel"  => $traitement_edit['n_tel_client'],
				  "fax"  => $traitement_edit['n_fax_client'],
				  "siret"  => $traitement_edit['n_siret_client'],
				  "esite"  => $traitement_edit['n_effectif_site_client'],
				  "egroupe"  => $traitement_edit['n_effectif_groupe_site'],
				  "enat"  => $traitement_edit['n_effectif_nat_client'],
				  "ca"  => $traitement_edit['n_ca_client'],
				  "catt"  => $traitement_edit['n_ca_tranche_client'],
				  "t1"  => $traitement_edit['tranche_1'],
				  "t2"  => $traitement_edit['tranche_2'],
				  "t3"  => $traitement_edit['tranche_3'],
				"commentaire"  => $traitement_edit['commentaire'],
				"stat"  => $traitement_edit['n_stat']
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
    
    
  
  } elseif ($job == 'edit_traitement'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
				
		$fin = $_GET['fin'];
		$debut = $_GET['debut'];
		$go = get_working_hours_2($debut,$fin);
		$query = $bdd->prepare("INSERT INTO `client_cat_synthese_fiche_update` (`fiche_id`, `date_debut_traitement`, `date_fin_traitement`, `temps_sec`, `user_id`, `user_name`, `lot_id`, `date_calcul`) VALUES (:fiche_id, :fiche_debut, :fiche_fin, :go, :id_user, :user, :lot_id, now())");	
		$query->bindParam(":fiche_id", $id, PDO::PARAM_INT);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":go", $go, PDO::PARAM_INT);
		$query->bindParam(":fiche_fin", $fin, PDO::PARAM_STR);
		$query->bindParam(":fiche_debut", $debut, PDO::PARAM_STR);
		$query->bindParam(":lot_id", $_GET['lot'], PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		
		if($_GET['siret_o'] == 0){
		
		$query = $bdd->prepare("SELECT count(*) FROM client_traitement WHERE raison_sociale_client = :rs_o AND date_calcul IS NULL");
		$query->bindParam(":rs_o", $_GET['rs_o'], PDO::PARAM_STR);
		$query->execute();
		$verif_maj = $query->fetchColumn();
		$query->closeCursor();

		if($verif_maj == 0){
$query = $bdd->prepare("UPDATE client_traitement SET operateur = :user , user_id = :id_user, reporting = 1, n_raison_sociale_client = :n_raison_sociale_client, n_adresse1_client = :n_adresse1_client, n_adresse2_client = :n_adresse2_client, n_adresse3_client = :n_adresse3_client, n_code_postal_client = :n_code_postal_client, n_ville_client = :n_ville_client, n_tel_client = :n_tel_client, n_fax_client = :n_fax_client, n_siret_client = :n_siret_client, siret_client = :siret_client, n_effectif_site_client = :n_effectif_site_client, n_effectif_groupe_site = :n_effectif_groupe_site, n_effectif_nat_client = :n_effectif_nat_client, n_ca_client = :n_ca_client, n_ca_tranche_client = :n_ca_tranche_client, tranche_1 = :n_t1, tranche_2 = :n_t2, tranche_3 = :n_t3,  n_stat = :n_stat, etat = 1 WHERE raison_sociale_client = :rs_o");
			
		}else{
$query = $bdd->prepare("UPDATE client_traitement SET operateur = :user , user_id = :id_user, reporting = 1, n_raison_sociale_client = :n_raison_sociale_client, n_adresse1_client = :n_adresse1_client, n_adresse2_client = :n_adresse2_client, n_adresse3_client = :n_adresse3_client, n_code_postal_client = :n_code_postal_client, n_ville_client = :n_ville_client, n_tel_client = :n_tel_client, n_fax_client = :n_fax_client, n_siret_client = :n_siret_client, siret_client = :siret_client, n_effectif_site_client = :n_effectif_site_client, n_effectif_groupe_site = :n_effectif_groupe_site, n_effectif_nat_client = :n_effectif_nat_client, n_ca_client = :n_ca_client, n_ca_tranche_client = :n_ca_tranche_client, tranche_1 = :n_t1, tranche_2 = :n_t2, tranche_3 = :n_t3,  n_stat = :n_stat, date_calcul = now(), etat = 1 WHERE raison_sociale_client = :rs_o");
			
		}		
		
		$query->bindParam(":rs_o", $_GET['rs_o'], PDO::PARAM_STR);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":n_raison_sociale_client", $_GET['rs'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse1_client", $_GET['ad1'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse2_client", $_GET['ad2'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse3_client", $_GET['ad33'], PDO::PARAM_STR);
		if(empty($_GET['cp'])){$cp = 0;}else{$cp = $_GET['cp'];}
		$query->bindParam(":n_code_postal_client", $cp, PDO::PARAM_INT);		
		$query->bindParam(":n_ville_client", $_GET['ville'], PDO::PARAM_STR);
		$query->bindParam(":n_tel_client", $_GET['tel'], PDO::PARAM_STR);
		$query->bindParam(":n_fax_client", $_GET['fax'], PDO::PARAM_STR);
		$query->bindParam(":n_siret_client", $_GET['siret'], PDO::PARAM_STR);
		$query->bindParam(":siret_client", $_GET['siret'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_site_client", $_GET['esite'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_groupe_site", $_GET['egroupe'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_nat_client", $_GET['enat'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_client", $_GET['ca'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_tranche_client", $_GET['catt'], PDO::PARAM_STR);		
		$query->bindParam(":n_t1", $_GET['t1'], PDO::PARAM_STR);
		$query->bindParam(":n_t2", $_GET['t2'], PDO::PARAM_STR);
		$query->bindParam(":n_t3", $_GET['t3'], PDO::PARAM_STR);
		$query->bindParam(":n_stat", $_GET['stat'], PDO::PARAM_STR);
		
		$query->execute();
		$query->closeCursor();
			
		}else{
		
		$query = $bdd->prepare("SELECT count(*) FROM client_traitement WHERE siret_client = :siret_client AND date_calcul IS NULL");
		$query->bindParam(":siret_client", $_GET['siret_o'], PDO::PARAM_STR);
		$query->execute();
		$verif_maj = $query->fetchColumn();
		$query->closeCursor();

		if($verif_maj == 0){
				
		$query = $bdd->prepare("UPDATE client_traitement SET operateur = :user , user_id = :id_user, reporting = 1, n_raison_sociale_client = :n_raison_sociale_client, n_adresse1_client = :n_adresse1_client, n_adresse2_client = :n_adresse2_client, n_adresse3_client = :n_adresse3_client, n_code_postal_client = :n_code_postal_client, n_ville_client = :n_ville_client, n_tel_client = :n_tel_client, n_fax_client = :n_fax_client, n_siret_client = :n_siret_client, n_effectif_site_client = :n_effectif_site_client, n_effectif_groupe_site = :n_effectif_groupe_site, n_effectif_nat_client = :n_effectif_nat_client, n_ca_client = :n_ca_client, n_ca_tranche_client = :n_ca_tranche_client, tranche_1 = :n_t1, tranche_2 = :n_t2, tranche_3 = :n_t3, n_stat = :n_stat, etat = 1 WHERE siret_client = :siret_client");
		
		}else{
		
		$query = $bdd->prepare("UPDATE client_traitement SET operateur = :user , user_id = :id_user, reporting = 1, n_raison_sociale_client = :n_raison_sociale_client, n_adresse1_client = :n_adresse1_client, n_adresse2_client = :n_adresse2_client, n_adresse3_client = :n_adresse3_client, n_code_postal_client = :n_code_postal_client, n_ville_client = :n_ville_client, n_tel_client = :n_tel_client, n_fax_client = :n_fax_client, n_siret_client = :n_siret_client, n_effectif_site_client = :n_effectif_site_client, n_effectif_groupe_site = :n_effectif_groupe_site, n_effectif_nat_client = :n_effectif_nat_client, n_ca_client = :n_ca_client, n_ca_tranche_client = :n_ca_tranche_client, tranche_1 = :n_t1, tranche_2 = :n_t2, tranche_3 = :n_t3, n_stat = :n_stat, date_calcul = now(), etat = 1 WHERE siret_client = :siret_client");
		
		}
		
		$query->bindParam(":siret_client", $_GET['siret_o'], PDO::PARAM_STR);
		$query->bindParam(":user", $_GET['user'], PDO::PARAM_STR);
		$query->bindParam(":id_user", $_GET['user_id'], PDO::PARAM_INT);
		$query->bindParam(":n_raison_sociale_client", $_GET['rs'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse1_client", $_GET['ad1'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse2_client", $_GET['ad2'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse3_client", $_GET['ad33'], PDO::PARAM_STR);
		if(empty($_GET['cp'])){$cp = 0;}else{$cp = $_GET['cp'];}
		$query->bindParam(":n_code_postal_client", $cp, PDO::PARAM_INT);
		$query->bindParam(":n_ville_client", $_GET['ville'], PDO::PARAM_STR);
		$query->bindParam(":n_tel_client", $_GET['tel'], PDO::PARAM_STR);
		$query->bindParam(":n_fax_client", $_GET['fax'], PDO::PARAM_STR);
		$query->bindParam(":n_siret_client", $_GET['siret'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_site_client", $_GET['esite'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_groupe_site", $_GET['egroupe'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_nat_client", $_GET['enat'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_client", $_GET['ca'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_tranche_client", $_GET['catt'], PDO::PARAM_STR);
		$query->bindParam(":n_t1", $_GET['t1'], PDO::PARAM_STR);
		$query->bindParam(":n_t2", $_GET['t2'], PDO::PARAM_STR);
		$query->bindParam(":n_t3", $_GET['t3'], PDO::PARAM_STR);
		$query->bindParam(":n_stat", $_GET['stat'], PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		
		}
		
		$result  = 'success';
		$message = 'Succès de requête';
	
		
		$query_del = null;
		$bdd = null;
	  
	  
    }
    
  } elseif ($job == 'edit_traitement_admin'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		
		
		$query = $bdd->prepare("UPDATE client_traitement SET commentaire = :commentaire, commentaire_alerte = :commentaire_alerte, n_raison_sociale_client = :n_raison_sociale_client, n_adresse1_client = :n_adresse1_client, n_adresse2_client = :n_adresse2_client, n_adresse3_client = :n_adresse3_client, n_code_postal_client = :n_code_postal_client, n_ville_client = :n_ville_client, n_tel_client = :n_tel_client, n_fax_client = :n_fax_client, n_siret_client = :n_siret_client, n_effectif_site_client = :n_effectif_site_client, n_effectif_groupe_site = :n_effectif_groupe_site, n_effectif_nat_client = :n_effectif_nat_client, n_ca_client = :n_ca_client, n_ca_tranche_client = :n_ca_tranche_client, tranche_1 = :n_t1, tranche_2 = :n_t2, tranche_3 = :n_t3, n_stat = :n_stat WHERE id_client = :id");				
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":n_raison_sociale_client", $_GET['rs'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse1_client", $_GET['ad1'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse2_client", $_GET['ad2'], PDO::PARAM_STR);
		$query->bindParam(":n_adresse3_client", $_GET['ad33'], PDO::PARAM_STR);
		if(empty($_GET['cp'])){$cp = 0;}else{$cp = $_GET['cp'];}
		$query->bindParam(":n_code_postal_client", $cp, PDO::PARAM_INT);
		$query->bindParam(":n_ville_client", $_GET['ville'], PDO::PARAM_STR);
		$query->bindParam(":n_tel_client", $_GET['tel'], PDO::PARAM_STR);
		$query->bindParam(":n_fax_client", $_GET['fax'], PDO::PARAM_STR);
		$query->bindParam(":n_siret_client", $_GET['siret'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_site_client", $_GET['esite'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_groupe_site", $_GET['egroupe'], PDO::PARAM_STR);
		$query->bindParam(":n_effectif_nat_client", $_GET['enat'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_client", $_GET['ca'], PDO::PARAM_STR);
		$query->bindParam(":n_ca_tranche_client", $_GET['catt'], PDO::PARAM_STR);
		$query->bindParam(":n_t1", $_GET['t1'], PDO::PARAM_STR);
		$query->bindParam(":n_t2", $_GET['t2'], PDO::PARAM_STR);
		$query->bindParam(":n_t3", $_GET['t3'], PDO::PARAM_STR);
		$query->bindParam(":n_stat", $_GET['stat'], PDO::PARAM_STR);	
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