<?php
// page_recommandation.php
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/controleur_Cours.php'));

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

    $id_user = $_SESSION['utilisateur_id']; //on recupère le user_id courant
    $recommandations = Controleur_Cours::getRecommandations($id_user); //on récupère les recommandations associées

    if ($recommandations->num_rows > 0) {
        echo "<ul>";
        while ($row = $recommandations->fetch_assoc()) {
            $url = Controleur_Cours::getMatiereByID($row['id_cours']);
            $ligne = $url->fetch_assoc();
            echo "<li><a href=" . $ligne['url'] . ">" . $ligne['matiere'] . "</a></li>"; //ça recup l'url de la table "cours"
        }
        echo "</ul>";
    } else {
        echo "Aucune recommandation trouvée.";
    }


//CE BOUT DE CODE MARCHE INDEPENDAMMENT, JE L'AI PAS INTEGRE A PAGE_ACCUEIL PARCE QUE JE VOULAIS PAS TOUT CASSER

?>
