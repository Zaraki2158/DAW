<?php
// Inclure le fichier du contrôleur Cours
require_once(realpath(dirname(__FILE__) . '/../../Controller/controleur_Cours.php'));

// V&eacute;rification de la soumission du formulaire et traitement des actions
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST["action"])){
		$action = $_POST["action"];
		switch($action){
			case "ajouterMatiere":
				// Ajouter la mati&egrave;re
				Controleur_Cours::ajouterMatiere();
				break;
			case "Valider":
				// Charger les fichiers pour la mati&egrave;re s&eacute;lectionn&eacute;e
				if (isset($_POST["matiere"])){
					$matiereSelectionnee = $_POST["matiere"];
					//Controleur_Cours::chargerFichiers($matiereSelectionnee);
				}else{
					echo "Aucune mati&egrave;re s&eacute;lectionn&eacute;e.";
				}
				break;  
			case "Supprimer": // Changement ici pour distinguer l'action Supprimer
				// Supprimer la mati&egrave;re
				if(isset($_POST["matiere"])){
					$matiereSelectionnee = $_POST["matiere"];
					if (Controleur_Cours::supprimerMatiere($matiereSelectionnee)){
						echo "Mati&egrave;re supprim&eacute;e avec succ&egrave;s.";
					}else{
						echo "Erreur lors de la suppression de la mati&egrave;re.";
					}
				}else{
					echo "Aucune mati&egrave;re s&eacute;lectionn&eacute;e.";
				}
				break;
			case "importer":
				// Importer des fichiers
				if (isset($_FILES["fichier_importe"])){
					$matiereImportee = $_POST["matiere_import"];
					$fichierImporte = $_FILES["fichier_importe"];
					// Appeler la m&eacute;thode dans le contrôleur pour importer le fichier
					Controleur_Cours::importerFichier($matiereImportee, $fichierImporte);
				}
				 
				break;
			default:
				// Action non reconnue
				echo "Action non reconnue : $action";
				break;
		}
	}else{
		echo "Aucune action sp&eacute;cifi&eacute;e.";
	}
}?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/utils/dmSwitch.css"/>
	<link rel="stylesheet" href="../css/utils/searchBar.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/header.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/navBar.css"/>
	<link rel="stylesheet" href="../css/page_cours.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
	<link href="../../resources/favicons/favicon1.png" rel="icon" type="image/x-icon" />	
	<title>Gestion des cours</title>
</head>
<body>

	<script src="../js/utils/dmSwitch.js"></script>	
	
	<header>		
				
		<span id="logo">PROJET DAW</span>
		
		<div class="dropdown">
			<button class="dropbtn" onclick="derouler(event)"></button>
			<div class="dropdown-content" id="myDropdown">
				<span id="spanDropTop">Tableau de bord</span>
				<hr>
				<a href="page_gestion.php"><i class="fa-solid fa-wrench fa-lg"></i><span class="spanDrop">Paramètres</span></a>
				<hr>
				<form id="themeForm" action="../../Controller/routeur.php" method="post">
					<button type="submit"  id="spanTheme" class="loginButton" onclick="switchThemeLN()" name="action" value="theme">					
							<span id="spanDrop">Th&egrave;me sombre</span>
					</button>
				</form>
				<hr>
				<form action="../../Controller/routeur.php" method="post">
					<button type="submit" class="loginButton" name="action" value="deconnexion">
						<i class="fa-solid fa-power-off fa-lg"></i>
						<span id="spanDropDec">D&eacute;connexion</span>
					</button>
				</form>
			</div>
		</div>

		<?php $initUser = ($_SESSION['nom_utilisateur'] == "admin") ? "admin" : $_SESSION['nom_utilisateur'][0] . $_SESSION['prenom_utilisateur'][0]; 
			  $themeUser = $_SESSION['theme_utilisateur'];?>
			  
		<script>
			var initialesUser = " <?php echo $initUser; ?> ";
			document.querySelector('.dropbtn').innerHTML = initialesUser + '<i class="fa-solid fa-caret-down fa-lg"></i>';
			loadTheme("<?php echo $themeUser; ?>");
		</script>

	</header>
	
	<nav>
		<div id="accueilDiv">
			<a href="page_accueil.php" id=""><i class="fa-solid fa-house fa-lg"></i> <span id="spanAccueil">Accueil</span></a>
		</div>
		<hr>
	</nav>

	<main>
	
		<section>
			<h1 id="bigTitle">Gestion des cours</h1>
			<?php echo '<h1 class="choixMat">Liste des cours:</h1>
						<div id="listeCours">';			
						 Controleur_Cours::getListeCours(); 				
				 echo '</div><br>';			
			?>
			
		</section>
		<hr class="hrMain">		
		<section id="sectionEdit">
			<?php
				// Appel à la méthode getCours() du modèle Modele_Cours
				$Listecours = Controleur_Cours::getCours();

				if($Listecours){
					echo "<h1 class='choixMat'>Ajouter, supprimer une matière :</h1>";
					
					echo '<table id="tableModif">
							<tr>
								<td >';
					
					echo "<form action=\"\" method=\"post\">";
					
					echo '<label class="textAJ" for="matiere">Mati&egrave;re s&eacute;lectionn&eacute;e : </label>';
					echo "<select name=\"matiere\">";
					foreach ($Listecours as $cours) {
						echo "<option value=\"$cours\">$cours</option>";
					}
					echo "</select><br><br>";
					
					echo "<button class='buttonForm' type=\"submit\" name=\"action\" value=\"Valider\">Afficher le contenu de la mati&egrave;re</button><br><br>"; 
					
					if(isset($_SESSION['type_utilisateur']) && $_SESSION['type_utilisateur'] == 'Professeur'){

						echo "<button class='buttonForm' type=\"submit\" name=\"action\" value=\"Supprimer\">Supprimer la mati&egrave;re</button>"; 
					}
					echo "</form>";

					echo '</td>
							<td id="borderRight">';
					
					if(isset($_SESSION['type_utilisateur']) && $_SESSION['type_utilisateur'] == 'Professeur'){

							// Formulaire d'importation de fichiers
							echo "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">";
							echo "<label class='textAJ2' for=\"matiere_import\">Importer un fichier pour la mati&egrave;re : </label>";
							
							echo "<select name=\"matiere_import\">";
							foreach ($Listecours as $cours) {
								echo "<option value=\"$cours\">$cours</option>";
							}
							echo "</select><br><br>";
							
							echo "<input type=\"file\" name=\"fichier_importe\"><br>";
							echo "<input type=\"hidden\" name=\"action\" value=\"importer\">";
							echo "<br><button class='buttonForm' type=\"submit\" name=\"importer\" value=\"Importer\">Importer le fichier</button>";
							echo "</form>";
						} 
					}
					
					echo '</td></tr>';
					echo '<tr><td  colspan="2">';
						
					// Vérifier si une matière a été sélectionnée et soumise
					if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "Valider" && isset($_POST["matiere"])) {
						// Afficher les fichiers pour la matière sélectionnée
						$matiereSelectionnee = $_POST["matiere"];
						echo "<h2> Fichiers pour la mati&egrave;re : $matiereSelectionnee</h2>";
						Controleur_Cours::chargerFichiers($matiereSelectionnee);
						echo"<br>";
					}

					echo '</td></tr></table>';
					
					
				?>

		</section>
		<section><br><br><br>
			<?php if(isset($_SESSION['type_utilisateur']) && $_SESSION['type_utilisateur'] == 'Professeur'){
					echo '  <form action="page_cours.php" method="post">
								<label for="nouvelle_matiere" class="textAJ">Ajouter une nouvelle mati&egrave;re :</label>
								<input type="text" id="nouvelle_matiere" name="nouvelle_matiere">
								<input type="hidden" name="action" value="ajouterMatiere">
								<button class="buttonForm" type="submit" value="Ajouter">Ajouter la mati&egrave;re</button>
							</form>';
			}?>
		</section>
	</main>

	<script src="../js/utils/dropdown.js"></script>
	
</body>
</html>
