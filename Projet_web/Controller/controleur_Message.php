<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once(realpath(dirname(__FILE__) . '\..\Model/modele_Message.php'));
require_once(realpath(dirname(__FILE__) . '\..\Config/config.php'));

class Controleur_Message{

	public static function readAll(){
		Modele_Message::readAll();
	}

	public static function add(){
		try{
			if(isset($_GET['id']) && isset($_SESSION['utilisateur_id']) && isset($_GET['contenu_message'])){
				$contenu = $_GET['contenu_message'];
				
				// Vérifier si le message n'est pas vide
				if(!empty($contenu)){
					$message = new Modele_Message();
					$message->save();
				}else{
					$_SESSION['error_message'] = "ERROR: Message vide.";
					header("Location: ../View/php/page_forum.php?id=" . $_GET['id']);
					exit(); // Terminer le script après la redirection
				}
			}
			else{
				echo "ID du forum, ID utilisateur ou contenu du message non sp&eacute;cifi&eacute;.";
				return false;
			}
		}catch (PDOException $e){
			echo "Erreur : " . $e->getMessage();
			return false;
		}
	}
}
    
?>