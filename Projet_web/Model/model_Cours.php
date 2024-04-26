<?php
require_once(realpath(dirname(__FILE__) . '/model.php'));
require_once(realpath(dirname(__FILE__) . '/../Config/config.php'));

class Modele_Cours {

    public static function getCours() {
        try {
            $conn = Model::$pdo;
            $sql = "SELECT DISTINCT matiere FROM cours";
            $rep = $conn->query($sql);
            $cours = [];
            while($row = $rep->fetch(PDO::FETCH_ASSOC)) {
                $cours[] = $row['matiere'];
            }
            return $cours;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
    

    public static function ajouterMatiere($matiere, $lien, $url) {
        $conn = Model::$pdo;
    
        try {
            // Préparation de la requête SQL
            $sql = "INSERT INTO cours (matiere, lien, url) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
    
            // Exécution de la requête avec la matière et le lien en tant que paramètres
            $stmt->execute([$matiere, $lien, $url]);
            
            return true; // Succès
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false; // Échec
        }
    }
    
    public static function supprimerMatiere($matiere){
        try {
            $conn = Model::$pdo;
            $stmt = $conn->prepare("DELETE FROM cours WHERE matiere = ?");
            $stmt->execute([$matiere]);
            return true;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
    
    public static function getMatiere($matiere){
        try {
            $conn = Model::$pdo;
            $sql = "SELECT * FROM cours WHERE matiere = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$matiere]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }

    public static function importerFichier($matiere, $fichierImporte){
        // Vérifier si un fichier a été importé
        if (isset($fichierImporte['tmp_name']) && !empty($fichierImporte['tmp_name'])) {
            $nom_fichier = $fichierImporte['name']; // Nom du fichier importé
            $chemin_destination = realpath(dirname(__FILE__) . '/../Cours/') . '/' . $matiere . '/' . $nom_fichier; // Chemin de destination du fichier
            
            // Déplacer le fichier importé vers le dossier de la matière
            if (move_uploaded_file($fichierImporte['tmp_name'], $chemin_destination)) {
                echo "Le fichier a été importé avec succès.";
            } 
        } else {
           // echo "Aucun fichier sélectionné pour l'importation.";
        }
    }
	
    public static function getFichiersMatiere($matiere){
        // Chemin du dossier de la matière
        $dossier_matiere = realpath(dirname(__FILE__) . '/../Cours/') . '/' . $matiere;

        // Vérifier si le dossier existe
        if (is_dir($dossier_matiere)) {
            // Récupérer la liste des fichiers dans le dossier
            $fichiers = scandir($dossier_matiere);
            $fichiers_trouves = [];

            // Filtrer les fichiers pour inclure uniquement les extensions autorisées
            foreach ($fichiers as $fichier) {
                if ($fichier != "." && $fichier != "..") {
                    $extension = pathinfo($fichier, PATHINFO_EXTENSION);
                    if (in_array($extension, ['pdf', 'ppt', 'pptx','mp4'])) {
                        $fichiers_trouves[] = $fichier;
                    }
                }
            }

            return $fichiers_trouves;
        } else {
            return false; // Le dossier de la matière n'existe pas
        }
    }

    public static function supprimerFichier($matiere, $fichier) {
        // Chemin du dossier de la matière
        $dossier_matiere = realpath(dirname(__FILE__) . '/../Cours/') . '/' . $matiere;

        // Chemin du fichier à supprimer
        $chemin_fichier = $dossier_matiere . '/' . $fichier;

        // Vérifier si le fichier existe et le supprimer
        if (file_exists($chemin_fichier)) {
            unlink($chemin_fichier); // Supprimer le fichier
            return true; // Retourner true si le fichier est supprimé avec succès
        } else {
            return false; // Retourner false si le fichier n'existe pas ou a déjà été supprimé
        }
    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        self::rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
            return true; 
        } else {
            return false; 
        }
    }

    public static function getRecommandations($id_user) {
    
        // Connexion à la base de données
        $connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());
    
        $sql = "SELECT DISTINCT id_cours FROM recommandation WHERE id_user = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $connexion->close();
        
        return $result;
    }

    public static function getMatiereByID($id_matiere) {
        // Connexion à la base de données
        $connexion = mysqli_connect(conf::getHostname(), conf::getLogin(), conf::getPassword(), conf::getDatabaseName());
    
        $sql = "SELECT DISTINCT * FROM cours WHERE id = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("i", $id_matiere);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $connexion->close();
        
        return $result;
    }

    public static function getListeCours() { 
        $Listecours = self::getCours();
        if ($Listecours) {
			
            foreach ($Listecours as $cours) {
                echo "<div><a href='page_matiere.php?matiere=" .  urlencode($cours) . "'>$cours</a></div>";
            }
			
        } else {
            echo "Aucune mati&egrave;re trouv&eacute;e.";
        }
    }
	
}?>
