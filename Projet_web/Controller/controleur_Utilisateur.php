<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
require_once(realpath(dirname(__FILE__) . '\..\Model\modele_Utilisateur.php'));
require_once(realpath(dirname(__FILE__) . '\..\Config\config.php'));
#[\AllowDynamicProperties]
class Controleur_Utilisateur{

    public static function saveTheme(){
        $userID = $_SESSION['utilisateur_id']; 
        $newTheme = ($_SESSION['theme_utilisateur'] == 'noir') ? 'blanc' : 'noir';
        Modele_Utilisateur::saveTheme($userID, $newTheme);		
        header("Location:" . $_SESSION['previous_page']);
		exit();
    }

	/**
	 * Modifie les informations de l'utilisateur dans la base de données.
	 *
	 * Cette méthode vérifie d'abord si les données nécessaires sont présentes dans la requête POST.
	 * Ensuite, elle appelle la méthode modifier() de Modele_Utilisateur pour effectuer la modification.
	 * Si des données sont manquantes, elle affiche un message d'erreur.
	 *
	 * @return void
	 */
	public static function modifier(){
		try {
			// Vérifier si les données nécessaires sont présentes dans la requête POST
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['numero']) && isset($_POST['password'])) {
				// Appeler la méthode modifier() de Modele_Utilisateur pour effectuer la modification
				Modele_Utilisateur::modifier();
				exit(); // Terminer l'exécution du script après la modification
			} else {
				// Afficher un message d'erreur si des données sont manquantes
				echo "Certains champs requis sont manquants dans le formulaire.";
			}
		} catch (PDOException $e) {
			// Gérer les erreurs PDO
			echo "Erreur : " . $e->getMessage();
		}
	}

	/**
	 * Lit tous les utilisateurs depuis la base de données et les affiche.
	 *
	 * Cette méthode appelle la méthode readAll() de Modele_Utilisateur pour récupérer tous les utilisateurs depuis la base de données.
	 * Elle gère également les erreurs PDO.
	 *
	 * @return void
	 */
	public static function readAll(){
		try {
			// Appeler la méthode readAll() de Modele_Utilisateur pour récupérer tous les utilisateurs
			Modele_Utilisateur::readAll();
		} catch (PDOException $e) {
			// Gérer les erreurs PDO
			echo "Erreur : " . $e->getMessage();
		}
	}

	/**
	 * Ajoute un nouvel utilisateur dans la base de données.
	 *
	 * Cette méthode vérifie d'abord si toutes les données nécessaires sont présentes dans la requête POST.
	 * Ensuite, elle appelle la méthode add() de Modele_Utilisateur pour ajouter un nouvel utilisateur.
	 * Si des données sont manquantes, elle affiche un message d'erreur.
	 *
	 * @return void
	 */
	public static function add(){
		try {
			// Vérifier si les données nécessaires sont présentes dans la requête POST
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['numero']) && isset($_POST['password']) && isset($_POST['type'])) {
				// Appeler la méthode add() de Modele_Utilisateur pour ajouter un nouvel utilisateur
				Modele_Utilisateur::add();
			} else {
				// Afficher un message d'erreur si des données sont manquantes
				echo "Certains champs requis sont manquants dans le formulaire.";
			}
		} catch (PDOException $e) {
			// Gérer les erreurs PDO
			echo "Erreur : " . $e->getMessage();
		}
	}

	/**
	 * Supprime un utilisateur de la base de données.
	 *
	 * Cette méthode vérifie d'abord si l'identifiant de l'utilisateur à supprimer est présent dans la requête POST.
	 * Ensuite, elle appelle la méthode supprimer() de Modele_Utilisateur pour supprimer l'utilisateur.
	 * Si l'identifiant est manquant, elle affiche un message d'erreur.
	 *
	 * @return void
	 */
	public static function supprimer(){
		try {
			// Vérifier si l'identifiant de l'utilisateur à supprimer est présent dans la requête POST
			if(isset($_POST['id'])) {
				// Appeler la méthode supprimer() de Modele_Utilisateur pour supprimer l'utilisateur
				Modele_Utilisateur::supprimer();
			} else {
				// Afficher un message d'erreur si l'identifiant est manquant
				echo "ID de l'utilisateur non trouvé dans la requête POST.";
			}
		} catch (PDOException $e) {
			// Gérer les erreurs PDO
			echo "Erreur : " . $e->getMessage();
		}
	}

	/**
	 * Déconnecte l'utilisateur en supprimant la session.
	 *
	 * Cette méthode appelle simplement la méthode deconnexion() de Modele_Utilisateur pour déconnecter l'utilisateur.
	 *
	 * @return void
	 */
	public static function deconnexion(){
		// Appeler la méthode deconnexion() de Modele_Utilisateur pour déconnecter l'utilisateur
		Modele_Utilisateur::deconnexion();
	}

	/**
	 * Connecte l'utilisateur en vérifiant les informations d'identification.
	 *
	 * Cette méthode vérifie d'abord si les données nécessaires sont présentes dans la requête POST.
	 * Ensuite, elle appelle la méthode connexion() de Modele_Utilisateur pour connecter l'utilisateur.
	 * Si des données sont manquantes, elle affiche un message d'erreur.
	 *
	 * @return void
	 */
	public static function connexion()
	{
		try {
			// Vérifier si les données nécessaires sont présentes dans la requête POST
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['password'])) {
				// Appeler la méthode connexion() de Modele_Utilisateur pour connecter l'utilisateur
				Modele_Utilisateur::connexion();
			} else {
				// Afficher un message d'erreur si des données sont manquantes
				echo "Certains champs requis sont manquants dans le formulaire.";
			}
		} catch (PDOException $e) {
			// Gérer les erreurs PDO
			echo "Erreur : " . $e->getMessage();
		}
	}

}
?>
