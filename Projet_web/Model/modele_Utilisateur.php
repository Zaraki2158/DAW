<?php
/**
 * @file
 * Contient la classe Modele_Utilisateur.
 */

/**
 * Représente un modèle d'utilisateur.
 */
class Modele_Utilisateur {
    /**
     * @var string $nom Le nom de famille de l'utilisateur.
     */
    private $nom;
    /**
     * @var string $prenom Le prénom de l'utilisateur.
     */
    private $prenom;
    /**
     * @var string $numero Le numéro de téléphone de l'utilisateur.
     */
    private $numero;
    /**
     * @var string $password Le mot de passe de l'utilisateur.
     */
    private $password; 
    /**
     * @var string $type Le type de l'utilisateur.
     */
    private $type;
    /**
     * @var string $theme Le thème de l'utilisateur.
     */
    private $theme;

    /**
     * Constructeur d'un nouvel objet utilisateur.
     *
     * @param string $name Le nom de famille de l'utilisateur.
     * @param string $p Le prénom de l'utilisateur.
     * @param string $n Le numéro de téléphone de l'utilisateur.
     * @param string $psswd Le mot de passe de l'utilisateur.
     * @param string $t Le type de l'utilisateur.
     * @param string $th Le thème de l'utilisateur.
     */
    public function __construct($name = null, $p = null, $n = null, $psswd = null, $t = null, $th = null){
        if (!is_null($name)) {
            $this->nom = $name;
        }
        if (!is_null($p)) {
            $this->prenom = $p;
        }
        if (!is_null($n)) {
            $this->numero = $n;
        }
        if(!is_null($psswd)){
            $this->password = $psswd;
        }
        if(!is_null($t)){
            $this->type = $t;
        }
		if(!is_null($th)){
            $this->theme = $th;
        }
    }

    /**
     * Récupère le nom de l'utilisateur.
     *
     * @return string Le nom de l'utilisateur.
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Récupère le prénom de l'utilisateur.
     *
     * @return string Le prénom de l'utilisateur.
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Récupère le numéro de téléphone de l'utilisateur.
     *
     * @return string Le numéro de téléphone de l'utilisateur.
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Récupère le mot de passe de l'utilisateur.
     *
     * @return string Le mot de passe de l'utilisateur.
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Récupère le type de l'utilisateur.
     *
     * @return string Le type de l'utilisateur.
     */
    public function getType() {
        return $this->type;
    }

    // Setters

    /**
     * Définit le nom de l'utilisateur.
     *
     * @param string $nom Le nom de l'utilisateur.
     */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /**
     * Définit le prénom de l'utilisateur.
     *
     * @param string $prenom Le prénom de l'utilisateur.
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    /**
     * Définit le numéro de téléphone de l'utilisateur.
     *
     * @param string $numero Le numéro de téléphone de l'utilisateur.
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * Définit le mot de passe de l'utilisateur.
     *
     * @param string $password Le mot de passe de l'utilisateur.
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Définit le type de l'utilisateur.
     *
     * @param string $type Le type de l'utilisateur.
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function deconnexion(){
        session_start();
        $_SESSION = array();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/'); 
        header("Location: ../View/php/index.php");
        exit();
    }

		/**
	 * Met à jour les informations de l'utilisateur dans la base de données.
	 *
	 * Cette méthode récupère les nouvelles valeurs des champs du formulaire,
	 * exécute une requête SQL pour mettre à jour les informations de l'utilisateur dans la base de données,
	 * puis redirige vers une page de succès après la modification.
	 *
	 * @return void
	 */
	public static function modifier(){
		try {
			// Vérifier si les champs nécessaires sont définis dans $_POST
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['numero']) && isset($_POST['password'])) {
				// Récupérer les nouvelles valeurs depuis $_POST
				$nom = $_POST['nom']; /**< Le nouveau nom de l'utilisateur. */
				$prenom = $_POST['prenom']; /**< Le nouveau prénom de l'utilisateur. */
				$numero = $_POST['numero']; /**< Le nouveau numéro de téléphone de l'utilisateur. */
				$password = $_POST['password']; /**< Le nouveau mot de passe de l'utilisateur. */

				// Vérifier s'il existe une session utilisateur active
				if(isset($_SESSION['utilisateur_id'])) {
					$id_utilisateur = $_SESSION['utilisateur_id']; /**< L'identifiant de l'utilisateur. */
					
					// Exécuter la requête de mise à jour dans la base de données
					$sql = "UPDATE utilisateur SET nom = ?, prenom = ?, numero = ?, mdp = ? WHERE id = ?";
					$connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());
					$statement = mysqli_prepare($connexion, $sql);
					if ($statement) {
						mysqli_stmt_bind_param($statement, "ssssi", $nom, $prenom, $numero, $password, $id_utilisateur);
						if (mysqli_stmt_execute($statement)) {
							// Rediriger vers une page de succès après la modification
							$_SESSION['nom_utilisateur'] = $nom;
							$_SESSION['prenom_utilisateur'] = $prenom;
							$_SESSION['numero_utilisateur'] = $numero;
							header("Location: ../View/php/page_accueil.php");
							exit();
						} else {
							echo "Erreur lors de l'exécution de la requête de mise à jour dans la base de données.";
						}
						mysqli_stmt_close($statement);
					} else {
						echo "Erreur lors de la préparation de la requête SQL.";
					}
					mysqli_close($connexion);
				} else {
					echo "Aucune session utilisateur active.";
				}
			} else {
				echo "Certains champs requis sont manquants dans le formulaire.";
			}
		} catch (PDOException $e) {
			echo "Erreur : " . $e->getMessage();
		}
	}
	
    /**
     * Lit tous les utilisateurs depuis la base de données et les affiche.
     */
    public static function readAll(){
        try{
            $sql = "SELECT * FROM Utilisateur";
            $conn = Model::$pdo;
            $rep = $conn->query($sql);
            $rep->setFetchMode(PDO::FETCH_ASSOC);
            
            while ($row = $rep->fetch()) {
                echo "<tr>";                
                
				if($row['Type'] == 'Professeur'){
					echo "<td class='tdNomFam tdAdmin'>" . $row['Nom'] . "</td>";
					echo "<td class='tdPrenom tdAdmin'>" . $row['Prenom'] . "</td>";
					echo "<td class='tdID tdAdmin'>" . $row['id'] . "</td>";
				}else{
					echo "<td class='tdNomFam'>" . $row['Nom'] . "</td>";
					echo "<td class='tdPrenom'>" . $row['Prenom'] . "</td>";
					echo "<td class='tdID'>" . $row['id'] . "</td>";
				}			
				
                echo '<td class="tdAction">
							<form action="../../Controller/routeur.php" method="post">
								<input type="hidden" name="id" value="'.$row['id'].'"> 
								<button type="submit" name="action" value="supprimer_utilisateur">Supprimer</button>
							</form>
						</td>';
                echo "</tr>";
            }	
        }catch (PDOException $e){
            echo "Erreur : " . $e->getMessage();
        }
    }

    /**
     * Supprime un utilisateur de la base de données.
     * @return bool True si la suppression réussit, sinon false.
     */
    public static function supprimer()
    {
        try{
            if(isset($_POST['id'])){
                $id_user = $_POST['id'];
            
                $sql = "DELETE FROM Utilisateur WHERE id = ?";
                $conn = Model::$pdo;
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$id_user])) {
                   header("Location: ../View/php/page_gestion.php");
                    exit(); 
                } else {
                    echo "Erreur lors de l'exécution de la requête de suppression dans la base de données.";
                    return false;
                }
            } else {
                echo "ID du forum non trouvé dans la requête POST.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }

	/**
	 * Met à jour le thème de l'utilisateur dans la base de données.
	 *
	 * @param int $userID L'identifiant de l'utilisateur.
	 * @param string $theme Le thème à mettre à jour.
	 * @return bool True si la mise à jour réussit, sinon false.
	 */
	public static function saveTheme($userID, $theme) {
		try {
			// Préparation de la requête SQL
			$sql = "UPDATE utilisateur SET theme = :theme WHERE id = :id";
			$requete = Model::$pdo->prepare($sql);

			// Liaison des valeurs des paramètres
			$requete->bindParam(':theme', $theme);
			$requete->bindParam(':id', $userID);

			// Exécution de la requête
			$resultat = $requete->execute();

			// Mise à jour de la variable de session
			$_SESSION['theme_utilisateur'] = $theme;

			// Vérification du succès de l'exécution
			if ($resultat) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			echo "Erreur : " . $e->getMessage();
			return false;
		}
	}

    /**
     * Ajoute un nouvel utilisateur dans la base de données.
     */
    public static function add()
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $numero = $_POST['numero'];
        $mdp = $_POST['password'];
        $type = $_POST['type'];
        $theme = 'sombre';
    
        // Connexion à la base de données
        $connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());
    
        // Vérification de la connexion
        if (!$connexion) {
            die("La connexion à la base de données a échoué : " . mysqli_connect_error());
        }
    
        // Vérifier s'il existe déjà un utilisateur avec le même nom et prénom
        $requete_verif = "SELECT COUNT(*) AS count FROM utilisateur WHERE nom = ? AND prenom = ?";
        $statement_verif = mysqli_prepare($connexion, $requete_verif);
        mysqli_stmt_bind_param($statement_verif, "ss", $nom, $prenom);
        mysqli_stmt_execute($statement_verif);
        mysqli_stmt_bind_result($statement_verif, $count);
        mysqli_stmt_fetch($statement_verif);
    
        if ($count > 0) {
            // Un utilisateur avec le même nom et prénom existe déjà
            $_SESSION['erreur_connexion'] = "Un utilisateur avec ce nom et prénom existe déjà.";
            header("Location: ../View/php/creation_compte.php");
            exit();
        }
        $statement_verif->close();
    
        // Insertion de l'utilisateur s'il n'existe pas déjà
        $requete_insert = "INSERT INTO utilisateur (nom, prenom, numero, mdp, type, theme) VALUES (?, ?, ?, ?, ?, ?)";
        $statement_insert = mysqli_prepare($connexion, $requete_insert);
    
        if ($statement_insert) {
            mysqli_stmt_bind_param($statement_insert, "ssssss", $nom, $prenom, $numero, $mdp, $type, $theme);
            mysqli_stmt_execute($statement_insert);
    
            if (mysqli_stmt_affected_rows($statement_insert) > 0) {
                $_SESSION['succes'] = "Compte créé avec succès.";
                header("Location: ../View/php/index.php");
            
            } else {
                $_SESSION['erreur_connexion'] = "Erreur lors de la création du compte.";
                header("Location: ../View/php/creation_compte.php");
        
            }
    
        } else {
            $_SESSION['erreur_connexion'] = "Erreur lors de la préparation de la requête SQL.";
            header("Location: ../View/php/creation_compte.php");
            
        }
    
        // Fermeture de la connexion à la base de données
        mysqli_close($connexion);
    }

    /**
     * Connecte un utilisateur.
     */
    public static function connexion(){
        $nom_utilisateur = $_POST['nom'];
        $prenom_utilisateur = $_POST['prenom'];
        $mot_de_passe = $_POST['password'];
            
        $connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());

        if (!$connexion) {
            die("La connexion à la base de données a échoué : " . mysqli_connect_error());
        }

        $requete = "SELECT MDP, type, id, theme FROM utilisateur WHERE NOM = '$nom_utilisateur' AND PRENOM = '$prenom_utilisateur'";
        $resultat = mysqli_query($connexion, $requete);
		
        if (mysqli_num_rows($resultat) == 1) {
            $ligne = mysqli_fetch_assoc($resultat);
            $hash = $ligne['MDP'];
            $role = $ligne['type'];
            $num = $ligne['numero'];
			$theme = $ligne['theme'];
                
            if ($mot_de_passe == $hash) {
                $_SESSION['utilisateur_id'] = $ligne['id']; 
                $_SESSION['nom_utilisateur'] = $nom_utilisateur; 
				$_SESSION['prenom_utilisateur'] = $prenom_utilisateur;
                $_SESSION['numero_utilisateur'] = $num;
                $_SESSION['type_utilisateur'] = $role;
				$_SESSION['theme_utilisateur'] = $theme;
				//self::updateThemeUser($ligne['id'], $theme);
                header("Location: ../View/php/page_accueil.php");
                
                exit();
            } else {
                $_SESSION['erreur_connexion'] = "Mot de passe incorrect.";
                header("Location: ../View/php/index.php");
                    
            }
        } else {
            $_SESSION['erreur_connexion'] = "Nom d'utilisateur incorrect.";
            header("Location: ../View/php/index.php"); 
                
        }

        mysqli_close($connexion);
    }   
	
	
	
	
}
?>
