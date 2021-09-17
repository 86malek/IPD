<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=database', 'root', '');
if(isset($_SESSION['user_id']) AND !empty($_SESSION['user_id'])) {
   if(isset($_GET['id']) AND !empty($_GET['id'])) {
      $id_message = intval($_GET['id']);
      $msg = $bdd->prepare('DELETE FROM messages WHERE id = ? AND id_destinataire = ?');
      $msg->execute(array($_GET['id'],$_SESSION['user_id']));
      header('Location:reception.php');
   }
} 
?>