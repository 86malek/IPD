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
if(!checkAdmin()) {
header("Location: ../../../index.php");
exit();
}
?>            
        <?php
		
		if($_GET['id'] == ''){
		header("Location: ../../../index.php");
		}
		else{
        $id = $_GET['id'];			
                         
            try 
            {
            
            $dossier = 'upload/bd/OriginalpourRELANCE.csv';
            $fichier = fopen($dossier, "r");																								
            $cpt = 1;
										
            if ($fichier !== FALSE) {	
				$ligne = fgets($fichier,4096);							
				while (($data = fgetcsv($fichier, 4096, ";"))) {
														
				$num = count($data);								
				$cpt++;	
											
					for ($c=0; $c < $num; $c++) {
					$col[$c] = $data[$c];
					}	
										
					if($col[0] !=''){
						
						if($id = 99){
							
							$verif = $bdd->prepare("SELECT collect_lot_id FROM collectivite_lot WHERE collect_lot_nom = :collect_lot_nom");	
							$verif->bindParam(":collect_lot_nom", $col[6], PDO::PARAM_INT);
							$verif->execute();
							$verif_lot_nom = $verif->fetch();
							$verif->closeCursor();
							
							$verif = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_idint = :collect_fiche_idint");	
							$verif->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
							$verif->execute();
							$verif_import = $verif->fetchColumn();
							$verif->closeCursor();
							
							if($verif_import == 0){
								
								
										
								$query_insert_doc = $bdd->prepare("INSERT INTO collectivite_fiche (collect_lot_id, collect_fiche_idint, collect_fiche_import,collect_fiche_intervallemaj, collect_fiche_rs1) VALUES (:collect_lot_id, :collect_fiche_idint, now(), :collect_fiche_intervallemaj, :collect_fiche_rs1)");										
								$query_insert_doc->bindParam(":collect_lot_id", $verif_lot_nom['collect_lot_id'], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[3], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_rs1", $col[1], PDO::PARAM_INT);
								$query_insert_doc->execute();
								$query_insert_doc->closeCursor();
								
														
							}else{
								
								
									
								$query_insert_doc = $bdd->prepare("UPDATE collectivite_fiche SET collect_fiche_intervallemaj = :collect_fiche_intervallemaj WHERE collect_fiche_idint = :collect_fiche_idint");	
								$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[3], PDO::PARAM_INT);							
								$query_insert_doc->execute();
								$query_insert_doc->closeCursor();
								
								
								
							}
							
						}else{
							
							$verif = $bdd->prepare("SELECT count(*) FROM collectivite_fiche WHERE collect_fiche_idint = :collect_fiche_idint");	
							$verif->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
							$verif->execute();
							$verif_import = $verif->fetchColumn();
							$verif->closeCursor();
							
							$verif = $bdd->prepare("SELECT collect_lot_nom FROM collectivite_lot WHERE collect_lot_id = :collect_lot_id");	
							$verif->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
							$verif->execute();
							$verif_lot = $verif->fetch();
							$verif->closeCursor();
							
							if($verif_import == 0){
								
								if($col[7] == $verif_lot['collect_lot_nom']){
										
								$query_insert_doc = $bdd->prepare("INSERT INTO collectivite_fiche (collect_lot_id, collect_fiche_idint, collect_fiche_import,collect_fiche_intervallemaj, collect_fiche_rs1) VALUES (:collect_lot_id, :collect_fiche_idint, now(), :collect_fiche_intervallemaj, :collect_fiche_rs1)");										
								$query_insert_doc->bindParam(":collect_lot_id", $id, PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[3], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_rs1", $col[1], PDO::PARAM_INT);
								$query_insert_doc->execute();
								$query_insert_doc->closeCursor();
								
								}						
							}else{
								
								if($col[7] == $verif_lot['collect_lot_nom']){
									
								$query_insert_doc = $bdd->prepare("UPDATE collectivite_fiche SET collect_fiche_intervallemaj = :collect_fiche_intervallemaj WHERE collect_fiche_idint = :collect_fiche_idint");	
								$query_insert_doc->bindParam(":collect_fiche_idint", $col[0], PDO::PARAM_INT);
								$query_insert_doc->bindParam(":collect_fiche_intervallemaj", $col[3], PDO::PARAM_INT);							
								$query_insert_doc->execute();
								$query_insert_doc->closeCursor();
								
								}
								
							}
						
						}
					}
				
				}
           		fclose($fichier);
            }      

            echo "<script type='text/javascript'>document.location.replace('Collect');</script>";
            }
            catch(PDOException $x) 
            { 	
            die("Secured");	
            $message = 'Échec de requête';
            }	
            $query_insert = null;
            $bdd = null;		 
         
        
        }
        ?>        