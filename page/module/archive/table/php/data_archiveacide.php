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
$st = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if (	$job == 'get_liste_user' ||
  		$job == 'add_user' ||
		$job == 'get_user_edit' ||
		$job == 'user_statut' ||
		$job == 'user_mdp' ||
		$job == 'edit_user'){
		  
		if (isset($_GET['id'])){
		  $id = $_GET['id'];
		  if (!is_numeric($id)){
			$id = '';
		  }
		}
		
		if (isset($_GET['st'])){
		  $st = $_GET['st'];
		  if (!is_numeric($st)){
			$st = '';
		  }
		}
		
		if (isset($_GET['mdp'])){
		  $mdp = $_GET['mdp'];
		  if (!is_numeric($mdp)){
			$mdp = '';
		  }
		}
		
		
  } else {
    $job = '';
  }
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_liste_user'){
	 
	try 
	{ 
	$PDO_query_user = $bdd->prepare("SELECT * FROM users ORDER BY id ASC");		
	$PDO_query_user->execute();
	while ($user = $PDO_query_user->fetch()){
		
		$query = $bdd->prepare("SELECT name_equipe FROM user_equipe WHERE id_equipe = :id_equipe");
		$query->bindParam(":id_equipe", $user['equipe_id'], PDO::PARAM_INT);
		$query->execute();	
		$query_equipe = $query->fetch();
		$query->closeCursor();
		
		$query = $bdd->prepare("SELECT name_niveau FROM user_niveau WHERE id_niveau = :id_niveau");
		$query->bindParam(":id_niveau", $user['user_level'], PDO::PARAM_INT);
		$query->execute();	
		$query_niveau = $query->fetch();
		$query->closeCursor();		
		
		if($user['approved'] == 1 && $user['user_level'] == 1){			
			$etat = '<a href="#" id="function_actif" data-id="'   . $user['id'] . '" data-st="0"><span class="badge badge-success mb-3 mr-3">COMPTE ACTIF</span></a>';			
		}elseif($user['banned'] == 1 && $user['user_level'] == 1){			
			$etat = '<a href="#" id="function_actif" data-id="'   . $user['id'] . '" data-st="1"><span class="badge badge-danger mb-3 mr-3">COMPTE bloqué</span></a>';		
		}elseif($user['user_level'] == 5){			
			$etat = '<span class="badge badge-info mb-3 mr-3">ADMIN</span>';			
		}
		
		$functions  = '';
				
		$functions .= '<a href="#" id="function_edit_web" data-id="' . $user['id'] . '"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';
		
		//$functions .= '<a href="#" id="del" data-id="' . $user['id'] . '" data-name="' . $user['full_name'] . '"  data-doc="' . $user['full_name'] . '"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
		
				
		$functions .= '';
		
		
		$mdp  = '';	
		
		$mdp .= '<a href="#" id="function_mdp" data-id="' . $user['id'] . '" data-mdp="1"><span class="badge badge-warning mb-3 mr-3">Réinitialiser MDP</span></a>';	
				
		$mdp .= '';
		
		if($user['date_reni'] == NULL){
			$date ='';
		}else{
		$date = date("d-m-Y", strtotime($user['date_reni']));
		}
        $mysql_data[] = array(
			"full_name" => $user['full_name'],
			"equipe" => $query_equipe['name_equipe'],
			"ip" => $user['tel_ip'],
			"user_email" => $user['user_email'],
			"niveau" => $query_niveau['name_niveau'],
			"actif" => $etat,
			"matricule" => $user['user_matricule'],
			"date" => $date,
			"functions" => $functions,
			"mdp" => $mdp
        );
	}
	$PDO_query_user->closeCursor();
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
	$PDO_query_user = null;     
    
}elseif ($job == 'add_user'){   
    		
				try 
				{
				$query = $bdd->prepare("select count(*) from users where user_name = :user_name OR user_email = :user_email");
				$query->bindParam(":user_name", $_GET['user_name'], PDO::PARAM_STR);
				$query->bindParam(":user_email", $_GET['user_email'], PDO::PARAM_STR);
				$query->execute();
				$dups = $query->fetchColumn();
				$query->closeCursor();
            
                if($dups > 0) {
					
							$result  = 'error';
							$message = 'Le nom ou l\'adresse mail existe déjà dans la base';
						
                }else{
            
                    
						
				if(passComplex($_GET['pwd']) == true){
			  
				$pwd = $_GET['pwd'];	
				$hash = PwdHash($_GET['pwd']);
			 
				$query = $bdd->prepare("INSERT INTO users (`full_name`,`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`, `tel_ip`, `user_matricule`, `equipe_id`)
			 VALUES (:full_name,:user_name,:user_email,:pwd,'1',now(),:user_level, :tel_ip, :user_matricule, :equipe_id)");		
				$query->bindParam(":full_name", $_GET['full_name'], PDO::PARAM_STR);
				$query->bindParam(":user_name", $_GET['full_name'], PDO::PARAM_INT);	
				$query->bindParam(":user_email", $_GET['user_email'], PDO::PARAM_INT);		
				$query->bindParam(":pwd", $hash, PDO::PARAM_STR);		
				$query->bindParam(":user_level", $_GET['niveau'], PDO::PARAM_INT);
				$query->bindParam(":tel_ip", $_GET['ip'], PDO::PARAM_INT);
				$query->bindParam(":user_matricule", $_GET['matricule'], PDO::PARAM_INT);
				$query->bindParam(":equipe_id", $_GET['equipe'], PDO::PARAM_INT);	
				$query->execute();
				$query->closeCursor();        

				$result  = 'success';
				$message = 'Utilisateur ajouté avec succés et approuvé automatiquement';
						
				}else{
				
				$result  = 'error';
				$message = 'Mot de passe non conforme';
						
				}        	
                      
                }	
		
				}
				catch(PDOException $x) 
				{ 	
					die("Secured");	
					$result  = 'error';
					$message = 'Échec de requête'; 	
				}	
				$query = null;
				$bdd = null;


}elseif ($job == 'get_user_edit'){
	
	if ($id == ''){
		
      $result  = 'error';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{
		$query_select_add = $bdd->prepare("SELECT * FROM users WHERE id = :id");	
		$query_select_add->bindParam(":id", $id, PDO::PARAM_INT);
		$query_select_add->execute();
		
		while ($traitement_edit = $query_select_add->fetch()){
			$mysql_data[] = array(
			"equipe"  => $traitement_edit['equipe_id'],
			"full_name"  => $traitement_edit['full_name'],
			"ip"  => $traitement_edit['tel_ip'],
			"matricule"  => $traitement_edit['user_matricule'],
			"niveau"  => $traitement_edit['user_level']
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

}elseif ($job == 'edit_user'){
	
	if ($id == ''){
		
      $result  = 'error';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{
			
				$query = $bdd->prepare("select count(*) from users where user_name = :user_name OR user_email = :user_email");
				$query->bindParam(":user_name", $_GET['user_name'], PDO::PARAM_STR);
				$query->bindParam(":user_email", $_GET['user_email'], PDO::PARAM_STR);
				$query->execute();
				$dups = $query->fetchColumn();
				$query->closeCursor();
            
                if($dups > 0) {
					
							$result  = 'error';
							$message = 'Le nom ou l\'adresse mail existe déjà dans la base';
						
                }else{
				if(empty($_GET['user_email'])){
					
				$query = $bdd->prepare("UPDATE users SET full_name = :full_name, user_name = :user_name, user_level = :user_level, tel_ip = :tel_ip, user_matricule = :user_matricule, equipe_id = :equipe_id WHERE id = :id");				
				
				}else{
					
				$email = $_GET['user_email'];
				$query = $bdd->prepare("UPDATE users SET full_name = :full_name, user_name = :user_name, user_email = :user_email, user_level = :user_level, tel_ip = :tel_ip, user_matricule = :user_matricule, equipe_id = :equipe_id WHERE id = :id");	
				$query->bindParam(":user_email", $email, PDO::PARAM_INT);
				
				}		
				
				$query->bindParam(":id", $id, PDO::PARAM_INT);			
				$query->bindParam(":full_name", $_GET['full_name'], PDO::PARAM_STR);
				$query->bindParam(":user_name", $_GET['full_name'], PDO::PARAM_INT);					
				$query->bindParam(":user_level", $_GET['niveau'], PDO::PARAM_INT);
				$query->bindParam(":tel_ip", $_GET['ip'], PDO::PARAM_INT);
				$query->bindParam(":user_matricule", $_GET['matricule'], PDO::PARAM_INT);
				$query->bindParam(":equipe_id", $_GET['equipe'], PDO::PARAM_INT);						
				$query->execute();
				$query->closeCursor();
				
				$result  = 'success';
				$message = 'Utilisateur modifier avec succès';
			
				}
					
		
		
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

}elseif ($job == 'user_statut'){
	
	if ($id == ''){
		
      $result  = 'error';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{
			
				
				if($st == 0){		
					$query = $bdd->prepare("UPDATE users SET banned = 1, approved = 0 WHERE id = :id");				
					$query->bindParam(":id", $id, PDO::PARAM_INT);			
					$query->execute();
					$query->closeCursor();
					$result  = 'success';
				$message = 'Utilisateur modifier avec succès';
				}elseif($st == 1){
					$query = $bdd->prepare("UPDATE users SET banned = 0, approved = 1 WHERE id = :id");				
					$query->bindParam(":id", $id, PDO::PARAM_INT);			
					$query->execute();
					$query->closeCursor();
					$result  = 'success';
				$message = 'Utilisateur modifier avec succès';
				}
				
			
				
					
		
		
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

}elseif ($job == 'user_mdp'){
	
	if ($id == ''){
		
      $result  = 'error';
      $message = 'Échec id';
	  
    } else {
		
		try 
		{
			
			$hash = PwdHash('Wellcome01');
			$query = $bdd->prepare("UPDATE users SET pwd = :pwd, date_reni = now() WHERE id = :id");				
			$query->bindParam(":id", $id, PDO::PARAM_INT);
			$query->bindParam(":pwd", $hash, PDO::PARAM_STR);			
			$query->execute();
			$query->closeCursor();
			$result  = 'success';
			$message = 'Utilisateur modifier avec succès';				
		
		
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