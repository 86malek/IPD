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
	
	if (isset($_GET['id_stat'])){
  	$id_stat = $_GET['id_stat'];
  	if (!is_numeric($id_stat)){
	$id_stat = '';
  }
}

echo '<p>Affichage du plus récent au plus ancien !</p>
<table class="table table-bordered table-condensed table-striped"><tr>
<th></th>
<th>DÉBUT</th>
<th>FIN</th>
<th>SEC</th>
</tr>';
 

$query = $bdd->prepare("SELECT user_name, DATE_FORMAT(date_debut_traitement, '%d/%m/%Y à %H:%i:%S') AS DateTempsdebut, DATE_FORMAT(date_fin_traitement, '%d/%m/%Y à %H:%i:%S') AS DateTempsfin, SEC_TO_TIME(temps_sec) AS traitement FROM client_cat_synthese_fiche_update_contact WHERE fiche_id = :fiche_id ORDER BY fiche_up_id DESC");	
$query->bindParam(":fiche_id", $id_stat, PDO::PARAM_INT);
$query->execute();			
while ($query_mood = $query->fetch()){
	echo'<tr>';
echo'<td>'.$query_mood['user_name'].'</td>';
echo'<td>'.$query_mood['DateTempsdebut'].'</td>';
echo'<td>'.$query_mood['DateTempsfin'].'</td>';
echo'<td>'.$query_mood['traitement'].'</td>';
echo'</tr>'; 
}
$query->closeCursor();			

echo'</table>'; 
}
catch(PDOException $x) 
{ 	
die("Secured");	
}
$bdd = null;
?>