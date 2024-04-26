<?php
require_once(realpath(dirname(__FILE__) . '/../Model/model_Cours.php'));

class Controleur_Cours {
    public static function getCours() {
        return Modele_Cours::getCours();
    }

    public static function ajouterMatiere() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "ajouterMatiere") {
            if (isset($_POST["nouvelle_matiere"])) {
                $nouvelle_matiere = $_POST["nouvelle_matiere"];
                
                // Vérifier si la matière existe déjà dans la base de données
                $cours_existant = Modele_Cours::getMatiere($nouvelle_matiere);
                
                if (!$cours_existant) {
                    // La matière n'existe pas, nous pouvons l'ajouter
                    
                    // Vérifier si le dossier de la matière existe déjà, sinon le créer
                    $dossier_matiere = "../../Cours/" . $nouvelle_matiere;
                    if (!file_exists($dossier_matiere)) {
                        mkdir($dossier_matiere, 0777, true); // Créer le dossier avec les permissions 0777
                    }

                    $url_matiere = "page_matiere.php?matiere=" . urlencode($nouvelle_matiere);
                    
                    // Ajouter la matière dans la base de données
                    if (Modele_Cours::ajouterMatiere($nouvelle_matiere, $dossier_matiere, $url_matiere)) {
                        echo "Matière ajoutée avec succès.";
                    } else {
                        echo "Erreur lors de l'ajout de la matière.";
                    }
                } else {
                    echo "La matière existe déjà dans la base de données.";
                }
            }
        }
    }

    public static function supprimerMatiere($matiere) {
        // Chemin du dossier de la matière
        $dossier_matiere ='../../Cours/' . $matiere;
    
        // Supprimer le dossier
        if (is_dir($dossier_matiere)) {
            if (!self::rrmdir($dossier_matiere)) {
                return false; // Erreur lors de la suppression du dossier
            }
        }
    
        // Supprimer l'enregistrement de la matière dans la base de données
        return Modele_Cours::supprimerMatiere($matiere);
    }
    
    // Méthode pour supprimer récursivement un répertoire et son contenu
    private static function rrmdir($dir) {
        return Modele_Cours::rrmdir($dir);
    }

    public static function chargerFichiers($matiere) {
        $fichiers = Modele_Cours::getFichiersMatiere($matiere);
        if ($fichiers) {
			
            foreach ($fichiers as $fichier) {
                echo "<ul>";
                
                $extension = pathinfo($fichier, PATHINFO_EXTENSION);
                if ($extension == 'pdf' || $extension == 'ppt' || $extension == 'pptx' || $extension == 'mp4'){
                    echo "<li><a href='../../Cours/$matiere/$fichier' class='lienCours' target='_blank'>$fichier</a></li>";
                } else {
                    echo $fichier;
                }
                echo "</ul>";
            }
        } else {
            echo "Aucun fichier trouv&eacute; pour la mati&egrave;re s&eacute;lectionn&eacute;e.";
        }
    }    

    public static function supprimerFichier($matiere, $fichier) {
       return Modele_Cours::supprimerFichier($matiere,$fichier);
    }

    public static function importerFichier($matiere,$fichierImporte) {
        return Modele_Cours::importerFichier($matiere,$fichierImporte);
    }


    public static function getRecommandations($id_user) {
        return Modele_Cours::getRecommandations($id_user);
    }

    public static function getMatiereByID($id_matiere) {
        return Modele_Cours::getMatiereByID($id_matiere);
    }

    public static function getListeCours() { 
        return Modele_Cours::getListeCours();
    }
}
?>
