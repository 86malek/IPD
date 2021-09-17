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
die("Stop");
}


try 
{
$query = $bdd->prepare("INSERT INTO autre_acide_fichier_heure SET objectif_autre_acide_fichier_heure = :objectif_autre_acide_fichier_heure");	
$query->bindParam(":objectif_autre_acide_fichier_heure", $_GET['nbheure'], PDO::PARAM_INT);
$query->execute();
$query->closeCursor();
}
catch(PDOException $x) 
{ 	
die("Secured");	
}	
$query = null;
$bdd = null;
?>