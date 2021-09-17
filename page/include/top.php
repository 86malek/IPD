
<div class="navbar navbar-light navbar-expand-lg">
  <button class="sidebar-toggler" type="button">
    <span class="iconfont iconfont-sidebar-open sidebar-toggler__open"></span>
    <span class="iconfont iconfont-alert-close sidebar-toggler__close"></span>
  </button>

  <a class="navbar-brand" href="TableadeBord"><!--<img src="img/logo/logo.png" alt=""/>--><img src="img/logo/LogoEnr.png" alt="" height="30"/></a>
  <a class="navbar-brand-sm" href="TableadeBord"><img src="img/logop.png" alt=""  height="30"/></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse">
    <span class="iconfont iconfont-navbar-open navbar-toggler__open"></span>
    <span class="iconfont iconfont-alert-close navbar-toggler__close"></span>
  </button>
	
  <div class="collapse navbar-collapse" id="navbar-collapse">

    	<div class="navbar-search">
      		<div class="input-group iconfont icon-right"></div>
    	</div>
        	
     <!--<div class="dropdown navbar-dropdown no-arrow navbar-notify-dropdown navbar-notify-dropdown--messages">
      
		<?php 
        /*$query_message = "SELECT * FROM messages_prive WHERE message_id_destinataire = ".$_SESSION['user_id']." ORDER BY message_id DESC";
        $query_message = mysqli_query($db, $query_message);
		$query_row = mysqli_num_rows($query_message);
		
		$query_message_non_lu = "SELECT * FROM messages_prive WHERE message_id_destinataire = ".$_SESSION['user_id']." AND message_lu = 0";
		$query_message_non_lu = mysqli_query($db, $query_message_non_lu);
		$query_row_non_lu = mysqli_num_rows($query_message_non_lu);
		
		echo'<a class="dropdown-toggle navbar-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
		<span class="navbar-notify">
		<span>
		<span class="navbar-notify__icon iconfont iconfont-envelope"></span>
		<span class="navbar-notify__text">Boite de réception</span>
		</span>';
		if($query_row_non_lu == 0) {}else{echo'<span class="navbar-notify__amount">'.$query_row_non_lu.'</span>';}
		echo'</span>
		</a>
		<div class="dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-notifications navbar-dropdown-messages">
		<div class="navbar-dropdown-notifications__header"><span>Messages recents</span></div>
		<div class="navbar-dropdown-notifications__body navbar-dropdown-notifications__body-messages js-scrollable">';
		
		if($query_row == 0) { 
				
				echo '<div class="navbar-dropdown-notifications__item">
				<div class="navbar-dropdown-notifications__item-notify">
				<div>
				<span class="icon iconfont iconfont-alert-comment"></span>
				<strong>Energis :</strong> Vous n\'avez aucun message pour le moment
				</div>
				</div>
				</div>';
				}else{
			while ($liste_message = mysqli_fetch_array($query_message)){
				
				$query_message_details = "SELECT full_name FROM users WHERE id = ".$liste_message['message_id_expediteur']."";
        		$query_message_details = mysqli_query($db, $query_message_details);
				$query_message_details = mysqli_fetch_array($query_message_details);
				if($liste_message['message_lu'] == 1){
				echo '<div class="navbar-dropdown-notifications__item">
				<div class="navbar-dropdown-notifications__item-notify">
				<div>
				<span class="icon iconfont iconfont-reply-to"></span>
				<strong>'.$query_message_details['full_name'].' :</strong><br> 
				'.$liste_message['message_objet'].'
				</div>
				</div>
				</div>';
				}else{
				echo '<div class="navbar-dropdown-notifications__item is-unread">
				<div class="navbar-dropdown-notifications__item-notify">
				<div>
				<span class="icon iconfont iconfont-comments"></span>
				<strong>'.$query_message_details['full_name'].' :</strong><br>
				'.$liste_message['message_objet'].'
				</div>
				</div>
				</div>';
				}
			}
		}*/
        ?>         
                
        </div>
        <a class="navbar-dropdown-notifications__view-all" href="box.php">
        <span class="icon iconfont iconfont-view-all"></span><span>Boîte de réception</span>
        </a>
      </div>
    </div> -->

    <?php if (checkAdmin()) { ?>

    


    <div class="dropdown navbar-dropdown no-arrow navbar-notify-dropdown">
      	<a class="dropdown-toggle navbar-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="navbar-notify">
          <span>
            <span class="navbar-notify__icon iconfont iconfont-bell"></span>
            <span class="navbar-notify__text">Notification formulaire de contact :</span>
          </span>
          <span class="navbar-notify__amount">
          <?php
		  $query = $bdd->prepare("SELECT COUNT(*) FROM contact WHERE stat_message = 0");
		  $query->execute();
		  $notif = $query->fetchColumn();
		  $query->closeCursor();
		  echo $notif;
		  ?> 
          </span>
        </span>
    	</a>
      	<div class="dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-notifications">
              	<div class="navbar-dropdown-notifications__header"><span>Notification formulaire de contact :</span></div>
                	<?php
					if ($notif == 0){
						echo '<div class="navbar-dropdown-notifications__item"><div class="navbar-dropdown-notifications__item-notify">
                              <div><div class="navbar-dropdown-notifications__body-empty-text" style="text-align:center">Aucunes demandes en attente !</div></div>
                          </div></div>';
					}else{
                      $query = $bdd->prepare("SELECT * FROM contact WHERE stat_message = 0 LIMIT 5");
                      $query->execute();
                      while ($query_client = $query->fetch()){
							echo '<div class="navbar-dropdown-notifications__item">
                            <div class="navbar-dropdown-notifications__item-notify">
                              <div>
                                <span class="icon iconfont iconfont-comments"></span>
                                <strong>'.$query_client['np_message'].'</strong> : '.$query_client['sujet_message'].'</strong>
                              </div>
                            </div>
                          </div>';
                      }
                      $query->closeCursor();
					}
                    ?> 
                      
        		<a class="navbar-dropdown-notifications__view-all" href="ListeNotif"><span class="icon iconfont iconfont-view-all"></span><span>Notifications</span></a>
      	</div>
    </div>
	<?php } ?>
	<div class="dropdown navbar-dropdown no-arrow navbar-help-dropdown">

      <a class="dropdown-toggle navbar-dropdown-toggle" data-toggle="dropdown" href="#">
        <span class="navbar-notify">
          <span>
            <span class="navbar-notify__icon iconfont iconfont-info"></span>
            <span class="navbar-notify__text">SOUDANI, Sirine (TUN)</span>
          </span>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-center navbar-dropdown-menu">

        <h6 class="navbar-help-dropdown__heading">Besoin d'aide</h6>
        <p class="navbar-help-dropdown__desc">
          Mme Sirine SOUDANI : 2052 <br>
          <a href="mailto:Sirine.SOUDANI@infopro-digital.com" title="Mail to Sirine.SOUDANI@infopro-digital.com">Sirine.SOUDANI@infopro-digital.com</a>
        </p>
        <!--<div>
          <a href="help-center-submit-ticket.html" class="btn btn-info navbar-help-dropdown__submit">Submit a Ticket</a>
        </div>-->
      </div>
    </div>
    <div class="dropdown navbar-dropdown">
      <a class="dropdown-toggle navbar-dropdown-toggle navbar-dropdown-toggle__user" data-toggle="dropdown" href="#">
      	<img src="img/menu/top.png" alt="" width="32" class="rounded-circle">
        <span class="navbar-dropdown__user-name"><?php echo $_SESSION['user_name'];?></span>
      </a>
      <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu__user">
      	<div class="navbar-dropdown-notifications__header"><span>Paramétres :</span></div>
        <div class="navbar-dropdown-user-content">        
          <div class="dropdown-info">
            <div class="dropdown-info__name"><?php echo $_SESSION['user_name'];?></div>
            <div class="dropdown-info__job">
			
						
			<?php
			$query = $bdd->prepare("SELECT name_niveau FROM user_niveau WHERE id_niveau = :id_niveau");
			$query->bindParam(":id_niveau", $_SESSION['user_level'], PDO::PARAM_INT);
			$query->execute();
			$query_niveau = $query->fetch();
			 echo ''.$query_niveau['name_niveau'].'';	
			$query->closeCursor();							
			?>
		
            
            
            </div>
            <div class="dropdown-info-buttons"><a class="dropdown-info__viewprofile" href="PUsers">Gestion Profil</a><a class="dropdown-info__addaccount" href="Bye">Déconnexion</a></div>
          </div>
        </div>
      </div>
      
    </div>
    
    
  </div>
</div>