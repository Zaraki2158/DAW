<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "Model.php";

/**
 * Classe représentant un message.
 */
class Modele_Message {
    private $id_forum;
    private $id_utilisateur;
    private $contenu;
    private $date;

    /**
     * Constructeur de la classe Modele_Message.
     * @param int $idf Identifiant du forum.
     * @param int $ifu Identifiant de l'utilisateur.
     * @param string $c Contenu du message.
     * @param string $date Date de création du message.
     */
    public function __construct($idf = null, $ifu = null, $c = null, $date = null)
    {
        if (!is_null($idf)) {
            $this->id_forum = $idf;
        }
        if (!is_null($ifu)) {
            $this->id_utilisateur = $ifu;
        }
        if (!is_null($c)) {
            $this->contenu = $c;
        }
        if (!is_null($date)) {
            $this->date_creation = $date;
        }
    }


     /**
     * Lecture de tous les messages pour un forum spécifié.
     * Affiche tous les messages dans l'ordre chronologique inverse.
     */
    public static function readAll(){
		try{
			if(isset($_GET['id'])){
				$id_forum = $_GET['id'];

				$connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());

				if(!$connexion){
					die("Erreur de connexion &agrave; la base de donn&eacute;es: " . mysqli_connect_error());
				}

				$requete_messages = "SELECT m.contenu, u.Prenom , u.Nom, m.date 
                     FROM message m 
                     JOIN utilisateur u ON m.id_utilisateur = u.id 
                     WHERE m.id_forum = ?
                     ORDER BY m.date ASC";
				$statement_messages = mysqli_prepare($connexion, $requete_messages);
				mysqli_stmt_bind_param($statement_messages, "i", $id_forum);
				mysqli_stmt_execute($statement_messages);
				$resultat_messages = mysqli_stmt_get_result($statement_messages);

				if($resultat_messages && mysqli_num_rows($resultat_messages) > 0){
					while ($row = mysqli_fetch_assoc($resultat_messages)) {
						$contenu_message = $row['contenu'];
						$prenom_utilisateur = $row['Prenom'];						
						
						$nom_utilisateur =( $row['Nom'] == 'admin' ) ? '' : $row['Nom'];						
						
						list($dateAMJ, $dateHMS) = explode(' ',date('Y-m-d H:i:s', strtotime($row['date'])));
						
						// Convertir la chaîne en objet de date
						$dateObj = date_create($dateAMJ);

						// Formater la date dans le nouveau format
						$dateJMA = date_format($dateObj, 'd-m-Y');						
						
						echo "<div class='messageBlock'>
								<span class='nomUser'>$prenom_utilisateur $nom_utilisateur</span>
								<br>
								<span class='dateMessage'>le $dateJMA &agrave; $dateHMS</span>
								<hr>						
								<p class='message'>$contenu_message</p>
							  </div>
								";
					}
				}else{
					echo "Aucun message trouv&eacute; pour ce forum.";
				}
				mysqli_close($connexion);
			}else{
				echo "ID du forum non sp&eacute;cifi&eacute;.";
			}
		}catch (PDOException $e){
			echo "Erreur : " . $e->getMessage();
			return false;
		}
	}

    /**
     * Enregistre un nouveau message dans la base de données.
     * Redirige vers la page du forum après l'ajout du message.
     * @return bool Retourne true si l'ajout est réussi, sinon false.
     */
    public function save()
    {
        try {
            if (isset($_GET['id']) && isset($_SESSION['utilisateur_id']) && isset($_GET['contenu_message'])) {
                $id_utilisateur = $_SESSION['utilisateur_id'];
                $id_forum = $_GET['id'];
                $contenu = $_GET['contenu_message'];

                $sql = "INSERT INTO Message (id_forum, id_utilisateur, contenu, date) VALUES (:id_forum, :id_utilisateur, :contenu, NOW())";
                $requete = Model::$pdo->prepare($sql);
                
                $requete->bindParam(':id_forum', $id_forum, PDO::PARAM_INT);
                $requete->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                $requete->bindParam(':contenu', $contenu, PDO::PARAM_STR);
                
                if ($requete->execute()) {
                    header("Location: ../View/php/page_forum.php?id=" . $id_forum);
                    exit();
                } else {
                    echo "Erreur lors de l'exécution de la requête d'insertion dans la base de données.";
                    return false;
                }
            } else {
                echo "ID du forum, ID utilisateur ou contenu du message non spécifié.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
}
?>
