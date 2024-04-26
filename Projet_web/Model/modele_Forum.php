<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "Model.php";

/**
 * Classe représentant un forum.
 */
#[\AllowDynamicProperties]
class Modele_Forum {
    private $titre;

    /**
     * Constructeur de la classe Modele_Forum.
     * @param string $t Titre du forum.
     */
    public function __construct($t = null)
    {
        if(!is_null($t)){
            $this->titre = $t;
        }
    }

    /**
     * Getter pour le titre du forum.
     * @return string Le titre du forum.
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Setter pour le titre du forum.
     * @param string $t Le nouveau titre du forum.
     */
    public function setTitre($t) {
        $this->titre = $t;
    }

    /**
     * Lit tous les forums depuis la base de données.
     * @return array|bool Tableau contenant tous les forums ou false en cas d'erreur.
     */
    public static function readAll(){
        try {
            $sql = "SELECT * FROM Forum";
            $conn = Model::$pdo;
            $rep = $conn->query($sql);
            $rep->setFetchMode(PDO::FETCH_CLASS, 'Modele_Forum');
            $forums = [];
            while($forum = $rep->fetch()){
                echo '<form action="../../Controller/routeur.php" method="get" class="quizCard">
						<img src="../../resources/cardImages/card_reseau.png">
						<div class="quizDiv">
							<a href="page_forum.php?id='.$forum->id_forum.'" class="lienQuiz">'.$forum->titre.'</a>
							<input type="hidden" name="id" value="'.$forum->id_forum.'"> 
						</div>
					  </form>';
                $forums[] = $forum;
            }
            return $forums;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

	/**
     * Lit tous les forums depuis la base de données avec des options d'administration.
     * Permet de supprimer des forums et d'ajouter de nouveaux forums.
     * @return array|bool Tableau contenant tous les forums ou false en cas d'erreur.
     */
    public static function readAll_Admin(){
        try {
            $sql = "SELECT * FROM Forum";
            $conn = Model::$pdo;
            $rep = $conn->query($sql);
            $rep->setFetchMode(PDO::FETCH_CLASS, 'Modele_Forum');
            $forums = [];
            while($forum = $rep->fetch()){
                echo '<form action="../../Controller/routeur.php" method="get" class="quizCard">
						  <img src="../../resources/cardImages/card_reseau.png">
						  <div class="quizDiv">
							<a href="page_forum.php?id='.$forum->id_forum.'" class="lienQuiz">'.$forum->titre.'</a>
							<input type="hidden" name="id" value="'.$forum->id_forum.'"> 
							<button type="submit" name="action" value="supprimer_forum" class="boutSupQuiz">Supprimer</button>
						  </div>
					  </form>';
					  
                $forums[] = $forum;
            }            
            return $forums;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
	
	 /**
     * Lit tous les forums depuis la base de données.
     * @return array|bool Tableau contenant tous les forums ou false en cas d'erreur.
     */
    public static function read(){
        try {
            $sql = "SELECT * FROM Forum";
            $conn = Model::$pdo;
            $rep = $conn->query($sql);
            $rep->setFetchMode(PDO::FETCH_CLASS, 'Modele_Forum');
            $forums = [];
            while($forum = $rep->fetch()){
                echo'<a href="page_forum.php?id='.$forum->id_forum.'">'.$forum->titre.'</a><br>';
                $forums[] = $forum;
            }
            return $forums;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Supprime un forum et tous ses messages associés de la base de données.
     * Redirige vers la page d'accueil après la suppression.
     * @return bool Retourne true si la suppression est réussie, sinon false.
     */
    public static function supprimer(){
        try {
            if (isset($_GET['id'])){
                $id_forum = $_GET['id'];
                $conn = Model::$pdo;
                $conn->beginTransaction();

                $sql_delete_messages = "DELETE FROM Message WHERE id_forum = ?";
                $stmt_delete_messages = $conn->prepare($sql_delete_messages);
                $stmt_delete_messages->execute([$id_forum]);

                $sql_delete_forum = "DELETE FROM Forum WHERE id_forum = ?";
                $stmt_delete_forum = $conn->prepare($sql_delete_forum);
                $stmt_delete_forum->execute([$id_forum]);

                $conn->commit();

                header("Location: ../View/php/page_accueil.php");
                exit();
            }else{
                echo "ID du forum non trouvé dans la requête GET.";
                return false;
            }
        }catch(PDOException $e){
            $conn->rollBack(); 
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }  

    /**
     * Enregistre un nouveau forum dans la base de données.
     * Redirige vers la page d'accueil après l'ajout du forum.
     * @return bool Retourne true si l'ajout est réussi, sinon false.
     */
    public function save(){
		try {
			if(isset($_POST['titre'])) {
				$titre = $_POST['titre'];
				$sql = "INSERT INTO Forum (titre) VALUES (?)";
				$requete = Model::$pdo->prepare($sql);
				if ($requete->execute([$titre])) {
					header("Location: ../View/php/page_accueil.php");
				} else {
					echo "Erreur lors de l'exécution de la requête d'insertion dans la base de données.";
					return false;
				}
			}
			// Si le paramètre 'titre' n'est pas défini dans $_POST, la méthode s'exécute sans erreur.
			// Dans ce cas, nous pouvons simplement retourner true pour indiquer que la méthode s'est exécutée avec succès.
			return true;
		} catch (PDOException $e) {
			echo "Erreur : " . $e->getMessage();
			return false;
		}
	}

}
?>
