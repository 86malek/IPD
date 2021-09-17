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

echo '<p>Détails du service IPD</p>
<table class="table table-bordered table-condensed table-striped">
<tr>
<th>ÉQUIPES</th>
<th>COLLABORATEURS</th>
</tr>';
 

$query_service = $bdd->prepare("SELECT DISTINCT user_equipe.name_equipe,  user_equipe.id_equipe FROM user_equipe INNER JOIN users WHERE users.equipe_id = user_equipe.id_equipe AND user_equipe.admin_equipe = 0");

$query_service->execute();

while ($query_service_ipd = $query_service->fetch()){

		$query = $bdd->prepare("SELECT COUNT(*) FROM users WHERE equipe_id = :equipe_id");
		$query->bindParam(":equipe_id", $query_service_ipd['id_equipe'], PDO::PARAM_INT);
		$query->execute();
		$nb_collab = $query->fetchColumn();
		$query->closeCursor();

		$nb_collab = $nb_collab.' Collab';

		$nom_equipe = '<a href="#'.$query_service_ipd['name_equipe'].'" title="">'.$query_service_ipd['name_equipe'].'</a>';

		echo'<tr>';
		echo'<td>'.$nom_equipe.'</td>';
		echo'<td>'.$nb_collab.' Collab</td>';
		echo'</tr>'; 

}

$query_service->closeCursor();			

echo'</table>'; 
}
catch(PDOException $x) 
{ 	
die("Secured");	
}
$bdd = null;
?>