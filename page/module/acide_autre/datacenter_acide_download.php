<title>DATA Acide - Download</title>
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
    if (file_exists("../../../config/".$page) && $page != 'index.php') {
       include("../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();

try 
{	
$donnees = $bdd->prepare("SELECT * FROM autre_acide_fichier WHERE id_autre_acide_fichier = :id");
$donnees->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
$donnees->execute();
$down = $donnees->fetch();
$urlf = 'upload/'.$down['autre_acide_fichier_doc'];
if(file_exists($urlf))
{
	$donnees_update = $bdd->prepare("UPDATE autre_acide_fichier SET autre_acide_fichier_date_down = now(), user_name = :user_name, user_id = :user_id, autre_acide_fichier_statut = 2 WHERE id_autre_acide_fichier = :id");
	$donnees_update->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
	$donnees_update->bindParam(":user_name", $_SESSION['user_name'], PDO::PARAM_STR);
	$donnees_update->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
	$donnees_update->execute();
	$donnees_update->closeCursor();
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$down['autre_acide_fichier_doc'].'"');
	readfile($urlf);
}
	

$donnees->closeCursor();
$donnees = null;
}
catch(PDOException $x) 
{ 	
die("Échec de requête");	
}

?>