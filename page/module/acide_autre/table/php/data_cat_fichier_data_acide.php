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
  if ($job == 'get_cat_fichier' ||
      $job == 'get_cat_fichier_add'   ||
      $job == 'add_cat_fichier'   ||
      $job == 'edit_cat_fichier'  ||
      $job == 'delete_cat_fichier'){
		  
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

$mysql_data = array();

if ($job != ''){ 
  
  if ($job == 'get_cat_fichier'){
    
    try 
	{
	$query = $bdd->prepare("SELECT * FROM autre_acide_fichier_categorie");
	$query->execute();	
	while ($doc = $query->fetch()){
	$functions  = '<center>';
	$functions .= '<a href="#" id="function_edit_cat_fichier" data-id="'   . $doc['id_autre_acide_fichier_categorie'] . '" data-name="' . $doc['autre_acide_fichier_categorie_nom'] . '"><span class="badge badge-success mb-3 mr-3">Modifier</span></a>';
	
		$query_define = $bdd->prepare("SELECT count(*) FROM autre_acide_fichier WHERE id_autre_acide_fichier_categorie = :id_autre_acide_fichier_categorie");
		$query_define->bindParam(":id_autre_acide_fichier_categorie", $doc['id_autre_acide_fichier_categorie'], PDO::PARAM_INT);
		$query_define->execute();	
		$rowligne = $query_define->fetchColumn();
		$query_define->closeCursor();
		if($rowligne > 0){$functions .= '<span  class="badge badge-danger mb-3 mr-3">X</span>';}else{$functions .= '<a  href="#" id="del" data-id="' . $doc['id_autre_acide_fichier_categorie'] . '" data-name="' . $doc['autre_acide_fichier_categorie_nom'] . '" data-doc="' . $doc['autre_acide_fichier_categorie_nom'] . '"><span  class="badge badge-danger mb-3 mr-3">Effacer</span></a>';}
	
	$functions .= '</center>';
	$mysql_data[] = array(
	  "nom"          => $doc['autre_acide_fichier_categorie_nom'],
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
    
    
  } elseif ($job == 'get_cat_fichier_add'){
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
    try 
	{
	$query = $bdd->prepare("SELECT * FROM autre_acide_fichier_categorie WHERE id_autre_acide_fichier_categorie = :id");
	$query->bindParam(":id", $id, PDO::PARAM_INT);
	$query->execute();	
	while ($doc = $query->fetch()){
	$mysql_data[] = array(
	"nom"  => $doc['autre_acide_fichier_categorie_nom']
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
	}
  
  } elseif ($job == 'add_cat_fichier'){
    
    try 
	{
	$query = $bdd->prepare("INSERT INTO autre_acide_fichier_categorie SET autre_acide_fichier_categorie_nom = :nom");
	$query->bindParam(":nom", $_GET['nom'], PDO::PARAM_STR);
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
	$bdd = null;
  
  } elseif ($job == 'edit_cat_fichier'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
		
		try 
		{
		$query = $bdd->prepare("UPDATE autre_acide_fichier_categorie SET autre_acide_fichier_categorie_nom = :nom WHERE id_autre_acide_fichier_categorie = :id");
		$query->bindParam(":nom", $_GET['nom'], PDO::PARAM_STR);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
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
		$bdd = null;
    }
    
  } elseif ($job == 'delete_cat_fichier'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
		
		try 
		{
		$query = $bdd->prepare("DELETE FROM autre_acide_fichier_categorie WHERE id_autre_acide_fichier_categorie = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
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