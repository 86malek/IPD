<div class="sidebar">
  <div class="sidebar__scroll">
    <div>      
          <ul class="sidebar-nav">
            <?php if (checkAdmin()) { ?>

              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/home.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Stat</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="TableadeBord">STATS : GENERALE</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="StatHebdo">STATS : HEBDO</a></li>                  
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="StatHebdoCumul">STATS : IPDATA</a></li>
                </ul>
              </li> 

              <!--<li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/qualite.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Primes</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="#">WEBMASTERS</a></li>
                </ul>
              </li>	-->

              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                 	<img src="img/menu/admin.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Service</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ListeUsers">COLLABORATEURS</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ListeGroupe">ÉQUIPES</a></li>
                </ul>
              </li>


              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                 	<img src="img/menu/top.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">TimeS</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ListeService">liste des catégorie (Services)</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ListeTache">liste des tâches</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ListeActivite">liste des Activités</a></li>
                </ul>
              </li>
              
              
              
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                    <img  src="img/menu/client.png" alt="" width="40px">
                    <span class="sidebar-nav__item-text">Client</span>                
                  </a>
                  <ul class="sidebar-subnav">
                      <?php
                      $query = $bdd->prepare("SELECT * FROM client_cat_oraga");
                      $query->execute();
                      while ($query_client = $query->fetch()){

                          echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Client-'.$query_client['id_client_cat_oraga'].'">'.$query_client['nom_client_cat_oraga'].'</a></li>';
                      }
                      $query->closeCursor();
                      ?>              
                  </ul> 
              </li> 
                          
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/team.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">IE</span>
                  
                </a>
                <ul class="sidebar-subnav">
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="IE">INDUSTRIE EXPLORER</a></li>
                </ul>
              </li>
              
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                 	<img src="img/menu/slider-hp2.png" alt="" width="25px">
                  <span class="sidebar-nav__item-text">DATA</span>                
                </a>             
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Siret">SIRETISATION</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Bedouk">BEDOUK</a></li>
                  <!--<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="LeadsDataCat">Missions</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="LeadsDataCatDemand">Demandeur</a></li>-->
                </ul>
              </li>
              
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                	<img src="img/menu/logo_acide.png" alt="" width="35px">
                  <span class="sidebar-nav__item-text">Acide</span>
                </a>
                <ul class="sidebar-subnav">
                	
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Linkedin">LINKEDIN</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="NominationBiblio">NOMINATION</a></li>                
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="HB">HARD BOUNCE</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="DataAcide">ACIDE-AUTRES</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="DataAcideCat">ACIDE-AUTRES > Catégories</a></li>
                  <!--<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Archiveacide">ARCHIVE</a></li>-->
                </ul>
              </li>
              
              
              
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/webmaster.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Webs</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsGenClicLeads">CLIC LEADS</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsRapport">INTEGRATION / CRÉA</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsAccomp">PLAN D'ACCOMPAGNEMENT</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsRapportGlobal">CAMPAGNES SF/MG</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsDoc">DOCUMENTS</a></li>
                  
                </ul>
              </li>
              
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/cnil.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Cnil</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="CnilRapport">CNIL</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="DmpRapport">DMP</a></li>
                </ul>
              </li>
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                 	<img src="img/menu/Municipality.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Gazette</span>                
                </a>             
                <ul class="sidebar-subnav">
                 <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Collect">COLLECTIVITÉS</a></li>                 
                </ul>
              </li>
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/doc.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Doc</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="Doc">DOCUMENTS</a></li>
                </ul>
              </li>
            <?php }else{ ?>
            
            <?php if (checkCollect()) { ?>
            	<li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                 	<img src="img/menu/Municipality.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Gazette</span>                
                </a>             
                <ul class="sidebar-subnav">
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="CollectBiblio">TRAITEMENT Collectivités</a></li>                 
                </ul>
              </li>
              <?php } ?>
              
              <?php if (checkCNIL()) { ?>
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/cnil.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Cnil</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="CnilRapportCollab">Suivi CNIL</a></li>
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="DMPRapportCollab">Suivi DMP</a></li>
                </ul>
              </li>
              <?php }?>
              
              <?php if (checkWeb()) { ?>
              <li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/webmaster.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Webs</span>
                </a>
                <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="WebsRapportCollab">Suivi Intégration et création</a></li>
                </ul>
              </li>
              <?php } ?>
              
              <?php if (checkLead()) { ?>
              <li class="sidebar-nav__item">
              <a class="sidebar-nav__link" href="#">
              	<img src="img/menu/logo_acide.png" alt="" width="35px">
                <span class="sidebar-nav__item-text">Acide</span>
              </a>
              <ul class="sidebar-subnav">
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="LinkedinBiblio">TRAITEMENT Linkedin</a></li>
                 
                <!--<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="datacenter_acide.php">TRAITEMENT Fichier</a></li>-->
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="NominationBiblioCollab">TRAITEMENT Nomination</a></li>
                 
                    <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="HBBiblio">TRAITEMENT HB</a></li>
                    <?php
				
                		/*$query = "SELECT * FROM autre_acide_fichier_categorie";
        						$query = mysqli_query($db, $query);
        						while ($menu = mysqli_fetch_array($query)){
        						
        						  echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="DataAcide-'.$menu['id_autre_acide_fichier_categorie'].'.html">'.$menu['autre_acide_fichier_categorie_nom'].'</a></li>';
        						}*/
                  ?>
                  </ul>
            </li>
              <!--<li class="sidebar-nav__item">
                <a class="sidebar-nav__link" href="#">
                  <img src="img/menu/lampe.png" alt="" width="40px">
                  <span class="sidebar-nav__item-text">Lead gen</span>
                </a>
                <ul class="sidebar-subnav">
						<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="#">En construction</a></li>
						    <?php 
        				/*$query = "SELECT * FROM organigramme WHERE nomination_organigramme = 'LEAD GENERATION'";
        				$query = mysqli_query($db, $query);
        				while ($menu_lead = mysqli_fetch_array($query)){				
        				  echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="datacenter.php?id_team='.$menu_lead['id_organigramme'].'">'.$menu_lead['nomination_organigramme'].'</a></li>';
        				}*/
        				?> 
                </ul>
              </li>-->
              
              <?php } ?>
              <?php if (checkIE()) { ?>
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                    <img src="img/menu/team.png" alt="" width="40px">
                    <span class="sidebar-nav__item-text">IE</span>
                  </a>
                  <ul class="sidebar-subnav">
						
						<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="IEBiblio">INDUSTRIE EXPLORER</a></li>
                  </ul>
              </li>
              <?php } ?>
              <?php if (checkQD()) { ?>
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                    <img src="img/menu/books.png" alt="" width="40px">
                    <span class="sidebar-nav__item-text">QD</span>
                  </a>
                  <ul class="sidebar-subnav">
						
						<?php 
          				$query = "SELECT * FROM organigramme WHERE nomination_organigramme='QUALITE DE DONNEES'";
          				$query = mysqli_query($db, $query);
          				while ($menu_lead = mysqli_fetch_array($query)){
          				
          				  echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="datacenter.php?id_team='.$menu_lead['id_organigramme'].'">'.$menu_lead['nomination_organigramme'].'</a></li>';
          				}
          				?> 
                  </ul>
              </li>
              <?php } ?>
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                   	<img  src="img/menu/client.png" alt="" width="40px">
                    <span class="sidebar-nav__item-text">Client</span>                
                  </a>
                  <ul class="sidebar-subnav">
                    <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="ClientBiblio">TRAITEMENT des dossiers</a></li>               
                  </ul> 
              </li>
              <?php if (checkAuto()) { ?>
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                    <img src="img/menu/voiture.png" alt="" width="40px">
                    <span class="sidebar-nav__item-text">Auto</span>
                  </a>
                  <ul class="sidebar-subnav">
                    <?php				
        						$query = "SELECT * FROM auto_synthese WHERE auto_intervenant_synthese = '".$_SESSION['user_name']."' AND auto_fin_synthese = '0000-00-00 00:00:00'";
        						$query = mysqli_query($db, $query);
        						$rowcount = mysqli_num_rows($query);
        						if($rowcount == 0){	
        						echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="auto_traitement.php">TRAITEMENT Automobile</a></li>';					  
        						} else {
        						  echo '<li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="auto_traitement.php?mode=debut">TRAITEMENT Automobile</a></li>';
        						}
                    ?>
                  </ul>
              </li>
              <?php } ?>
              <?php if (checkSIRET()) { ?>
              <li class="sidebar-nav__item">
                  <a class="sidebar-nav__link" href="#">
                   	<img  src="img/menu/slider-hp2.png" alt="" width="30px">
                    <span class="sidebar-nav__item-text">DATA</span>                
                  </a>
                  <ul class="sidebar-subnav">
                  <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="SiretBiblio">SIRETISATION</a></li>             
                  </ul> 
              </li>
              <?php } ?>
              
            <?php } ?>               
              

            <!--<li class="sidebar-nav__item">
              <a class="sidebar-nav__link" href="#">
                <span class="sidebar-nav__item_icon iconfont iconfont-help-circle"></span>
                <span class="sidebar-nav__item-text">Aide</span>
              </a>
              <ul class="sidebar-subnav">
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="#">Télécharger des Documents</a></li>
                <li class="sidebar-subnav__item"><a class="sidebar-subnav__link" href="#">Envoyer un Tickets</a></li>
              </ul>
            </li>
            <li class="sidebar-nav__item">
              <span class="sidebar-nav__link sidebar-nav__source">Version 1.1.1</span>
            </li>-->
        
      </ul>      
    </div>

    <div class="sidebar-nav__footer">
      <div class="sidebar__collapse">
        <span class="icon iconfont iconfont-collapse-left-arrows"></span>
      </div>
    </div>
    
  </div>
</div>