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
  if (	$job == 'get_collect_liste'){
			
		  	if (isset($_GET['cat'])){
			$cat = $_GET['cat'];}
			
    		if (isset($_GET['id'])){
      		$id = $_GET['id'];
      		if (!is_numeric($id)){
        	$id = '';}}
					
			if (isset($_GET['id_cat'])){
      		$id_cat = $_GET['id_cat'];
      		if (!is_numeric($id_cat)){
        	$id_cat = '';}}
		
    
  }else{$job = '';}
}

$mysql_data = array();
    
	/*$fichier = '../../lot/FichierOriginal/OriginalpourRELANCE.csv';
	$fichier = fopen($fichier, "r");																								
	$cpt = 1;								
	if ($fichier !== FALSE) {									
	while (($data = fgetcsv($fichier, 4096, ";"))) {									
	$num = count($data);								
	$cpt++;								
	for ($c=1; $c < $num; $c++) {
	$col[$c] = $data[$c];
	}							
	if($col[0] !=''){
		
		$query_insert_doc = $bdd->prepare("INSERT INTO collectivite_lot_sqlserver (IdINT, RS1, Categorie, IntervalleMaj, Population, DateMaj, DateAlerte, LotPop) VALUES (:IdINT, :RS1, :Categorie, :IntervalleMaj, :Population, :DateMaj, :DateAlerte, :LotPop)");
			
		$query_insert_doc->bindParam(":IdINT", $col[0], PDO::PARAM_INT);
		$query_insert_doc->bindParam(":RS1", $col[1], PDO::PARAM_STR);
		$query_insert_doc->bindParam(":Categorie", $col[2], PDO::PARAM_STR);
		$query_insert_doc->bindParam(":IntervalleMaj", $col[3], PDO::PARAM_INT);
		$query_insert_doc->bindParam(":Population", $col[4], PDO::PARAM_INT);
		$query_insert_doc->bindParam(":DateMaj", $col[5], PDO::PARAM_STR);
		$query_insert_doc->bindParam(":DateAlerte", $col[6], PDO::PARAM_STR);
		$query_insert_doc->bindParam(":LotPop", $col[7], PDO::PARAM_INT);
		$query_insert_doc->execute();
		$query_insert_doc->closeCursor();
	
	}
	
	}
	fclose($fichier);
	}*/								
	try 
	{
	$query = $bdd->prepare("SELECT * FROM collectivite_lot_sqlserver ORDER BY IntervalleMaj DESC");
	/*$query = $db_sqlserver->prepare("SELECT dbo.INTERVENANT.IdINT, dbo.INTERVENANT.RS1, dbo.INTERVENANT.Categorie, DATEDIFF(day, dbo.INTERVENANT.DateMaj, CURRENT_TIMESTAMP) AS IntervalleMaj, dbo.InfoCollectivites.Population, dbo.INTERVENANT.DateMaj, dbo.INTERVENANT.DateAlerte, dbo.InfoCollectivites.LotPop FROM dbo.INTERVENANT INNER JOIN dbo.InfoCollectivites ON dbo.INTERVENANT.IdINT = dbo.InfoCollectivites.IdINT
WHERE  (dbo.INTERVENANT.Categorie = 'MAI') AND (DATEDIFF(day, dbo.INTERVENANT.DateMaj, CURRENT_TIMESTAMP) > 200) AND (dbo.INTERVENANT.Categorie <> 'CCAS') AND 
                  (dbo.InfoCollectivites.Population > 1999) AND (dbo.INTERVENANT.FlagSup = 0) AND (dbo.INTERVENANT.DateAlerte IS NULL) OR
                  (dbo.INTERVENANT.Categorie IN (N'CA', N'CU', N'CC', N'ME')) AND (DATEDIFF(day, dbo.INTERVENANT.DateMaj, CURRENT_TIMESTAMP) > 200) AND 
                  (dbo.INTERVENANT.FlagSup = 0) AND (dbo.INTERVENANT.DateAlerte IS NULL) OR
                  (dbo.INTERVENANT.Categorie IN (N'CREG', N'CGEN')) AND (DATEDIFF(day, dbo.INTERVENANT.DateMaj, CURRENT_TIMESTAMP) > 200) AND (dbo.INTERVENANT.FlagSup = 0) 
                  AND (dbo.INTERVENANT.DateAlerte IS NULL) ORDER BY DATEDIFF(day, dbo.INTERVENANT.DateMaj, CURRENT_TIMESTAMP) DESC, dbo.INTERVENANT.IdINT DESC");*/
	$query->execute();
	
	while ($doc = $query->fetch()){	
		
		$query_select_update = $bdd->prepare("UPDATE collectivite_fiche SET collect_fiche_intervallemaj = :collect_fiche_intervallemaj, collect_fiche_rs1 = :rs1 WHERE collect_fiche_idint = :id");	
		$query_select_update->bindParam(":id", $doc['IdINT'], PDO::PARAM_INT);
		$query_select_update->bindParam(":rs1", $doc['RS1'], PDO::PARAM_STR);
		$query_select_update->bindParam(":collect_fiche_intervallemaj", $doc['IntervalleMaj'], PDO::PARAM_INT);
		$query_select_update->execute();
		$query_select_update->closeCursor();
		
        $mysql_data[] = array(
          "IdINT" => $doc['IdINT'],
		  "RS1" => $doc['RS1'],
		  "Categorie" => $doc['Categorie'],
		  "IntervalleMaj" => $doc['IntervalleMaj'],
		  "Population" => $doc['Population'],
		  "DateMaj" => $doc['DateMaj'],
		  "DateAlerte" => $doc['DateAlerte'],
		  "LotPop" => $doc['LotPop']
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
    


$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;


?>