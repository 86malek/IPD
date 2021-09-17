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
if(!checkAdmin()) {die("Secured");}
try 
{
$query = $bdd->prepare("INSERT INTO objectif_acide SET nbligne_objectif = :nbligne_objectif, nbheure_objectif = :nbheure_objectif, section_objectif = :section_objectif");
$query->bindParam(":nbligne_objectif", $_GET['nblignes'], PDO::PARAM_INT);
$query->bindParam(":nbheure_objectif", $_GET['nbheure'], PDO::PARAM_INT);
$query->bindParam(":section_objectif", $_GET['section'], PDO::PARAM_INT);
$query->execute();
$query->closeCursor();
}
catch(PDOException $x) 
{ 	
die("Secured");	
}
$bdd = null;
?>