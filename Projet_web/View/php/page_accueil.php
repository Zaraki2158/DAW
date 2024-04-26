<?php
	if(session_status() == PHP_SESSION_NONE){session_start();}
	require_once "../../Model/modele_Utilisateur.php";
	require_once "../../Controller/controleur_Forum.php";
	require_once "../../Controller/controleur_Quiz.php";
	require_once "../../Controller/controleur_Cours.php";
	$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/utils/dmSwitch.css"/>
	<link rel="stylesheet" href="../css/utils/searchBar.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/header.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/navBar.css"/>
	<link rel="stylesheet" href="../css/page_accueil.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
	<link href="../../resources/favicons/favicon1.png" rel="icon" type="image/x-icon" />	
	<title><?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? "Page d'accueil admin"  : "Page d'accueil &eacute;tudiant"; ?></title>
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
		<div>
			<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<a href="page_cours.php" id="lienNavGestCours">Gestion des cours</a>'  : ""; ?>
			<a onclick="redirectNav('coursesList')" id="lienNavCours"></a>
			<a onclick="redirectNav('recomCourses')" id="lienNavCoursRecom">Cours recommand&eacute;s</a>
			<a onclick="redirectNav('quizList')"    id="lienNavQuiz" ></a>
			<a onclick="redirectNav('forumsList')"  id="lienNavFrum" ></a>
		</div>
		
		<script>
			// on stock les titres dans une variable pour s'en servir dans les fonctions JS
			var titreCours = " <?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des cours</legend>'  : '<legend>Cours suivis</legend>'; ?> ";
			var titreQuiz =  " <?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des quiz</legend>'   : '<legend>Quiz disponibles</legend>';?> ";
			var titreForum = " <?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des Forums</legend>' : '<legend>Forums &Eacute;tudiants</legend>';?> ";
			
			document.getElementById('lienNavCours').innerHTML = titreCours;
			document.getElementById('lienNavQuiz').innerHTML = titreQuiz;
			document.getElementById('lienNavFrum').innerHTML = titreForum;			
		</script>
	</nav>
	
	<main>	
		<section id="coursesList">
			<fieldset>
				<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des cours</legend>' : '<legend>Cours suivis</legend>'; ?>
				<input type="text" id="searchInput" onkeyup="searchCQF('searchInput', 'listeCours')" placeholder="Rechercher un cours">
				
				<div id="listeCours">
				
					<?php Controleur_Cours::getListeCours(); ?>
				
				</div>				

			</fieldset>		
		</section>
		<br><br>
		<section id="recomCourses">
			<fieldset>
				<legend>Cours recommand&eacute;s pour vous</legend>
				
				<div id="coursRecom">
				<?php 
					$id_user = $_SESSION['utilisateur_id']; //on recupère le user_id courant
					$recommandations = Controleur_Cours::getRecommandations($id_user); //on récupère les recommandations associées

					if ($recommandations->num_rows > 0){
						while ($row = $recommandations->fetch_assoc()) {
							$url = Controleur_Cours::getMatiereByID($row['id_cours']);
							$ligne = $url->fetch_assoc();
							echo "<div><a href=" . $ligne['url'] . ">" . $ligne['matiere'] . "</a></div>";
						}
					} else {
						echo "Aucune recommandation trouv&eacute;e.";
					}
				?>
				</div>				

			</fieldset>		
		</section>
		<br><br>
		<section id="quizList">		
			<fieldset>
				<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des quiz</legend>' : '<legend>Quiz disponibles</legend>';?>
				<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<form action="../../Controller/routeur.php" method="post" ><button type="submit" name="action" value="add_quiz" id="boutonAjoutQuiz">Cr&eacute;er un quiz</button></form>' : '<br><br>';?>
								
				<div class="cardContainer">					
					<?php ($_SESSION['type_utilisateur'] == 'Professeur') ? Controleur_Quiz::readAll_Admin() : Controleur_Quiz::readAll();?>		
				</div>
		
			</fieldset>		
		</section>
		<br><br>
		<section id="forumsList">			
			<fieldset>
				<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '<legend>Liste des Forums</legend>' : '<legend>Forums &Eacute;tudiants</legend>';?>
				<?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? '
					<form action="../../Controller/routeur.php" method="post" >
						<div id="formAjoutForum">
							<input type="text" name="titre" placeholder="Titre du forum">
							<button type="submit" name="action" value="add_forum">Cr&eacute;er le forum</button>
						</div>
					</form>'
				 : '<br><br>'; ?>				
				
				<div class="cardContainer">
					<?php ($_SESSION['type_utilisateur'] == 'Professeur') ? Controleur_Forum::readAll_Admin() : Controleur_Forum::readAll();?>	
				</div>
				
			</fieldset>			
		</section>		
	</main>
		
	<br>
		
	<script src="../js/utils/searchBar.js"></script>
	<script src="../js/utils/dropdown.js"></script>
    <script src="../js/page_principale.js"></script>
	
	<!-- on trie par ordre alphabetique le tableau des cours -->
	<script>triTableau("listeCours");</script>
	
</body>
</html>