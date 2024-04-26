<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id_forum = isset($_GET['id']) ? $_GET['id'] : null;
$_SESSION['id_forum'] = $id_forum; 
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/controleur_Quiz.php'));
$_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/utils/dmSwitch.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/header.css"/>
	<link rel="stylesheet" href="../css/compoCommuns/navBar.css"/>
	<link rel="stylesheet" href="../css/page_creationQuiz.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
	<link href="../../resources/favicons/favicon1.png" rel="icon" type="image/x-icon" />	
    <title>Cr&eacute;ation de quiz</title>
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
		<div id="liensForums">
			<a href="../php/page_cours.php" >Liste des cours</a>		
		</div>
		
	</nav>

	<div id="confirmationExitDiv">
		<h2>Voulez vous vraiment quitter ?<br> Tout ce qui a &eacute;t&eacute; effectu&eacute; sur ce quiz sera perdu.</h2>
		<br>
		<a href="page_accueil.php" class="boutonExit">Quitter la cr&eacute;ation du quiz</a>
	</div>	

	<main>
		<section>
		
			<h1>Cr&eacute;ation de quiz</h1>
		
			<div id="warning">
			
				<p>Avant de cr&eacute;er un quiz, vous devez d'abord cr&eacute;er le cours correspondant.</p>
				<p>Par exemple, si vous souhaitez cr&eacute;er un quiz de SVT, vous devez avoir une mati&egrave;re SVT r&eacute;pertori&eacute;e parmi vos cours.</p>
				<p>Si le nom du quiz ne correspond pas &agrave; un cours, vos entr&eacute;es seront r&eacute;initialis&eacute;es.</p>
				<p>Utilisez le lien &agrave; gauche pour aller v&eacute;rifier.</p>
			
			</div>
		
			<form id="qcmForm" action="../../Controller/routeur.php" method="post" enctype="multipart/form-data">

				<div id="divTitre">
					<label for="quiz_title">Titre du quiz :</label>
					<input type="text" id="quiz_title" name="quiz_title" required>
				</div>
				<br>
				<div id="divImg">
					<label for="quiz_image">Image du quiz :</label>
					<input type="file" id="quiz_image" name="quiz_image" accept="image/*">					
				</div>
				<br>
				
				<div id="boutonsModif">
					<button type="button" id="ajQestion" name="action" value="add_question" onclick="addQuestion()">Ajouter une question</button>
					<button type="submit" id="saveQuizButton" name="action" value="save_quiz">Sauvegarder</button>
				</div>
				<br>
				
				<div class="divQuestion">
					<hr><br>
					<label for="question1">Question 1 :</label>
					<input type="text" id="question1" name="questions[0]" required>
				</div>
				<br>
				
				<span>R&eacute;ponses question 1:</span><br><br>
				<div class="divReponses">										
					<div>
						<label for="answer1_1">R&eacute;ponse 1 :</label>
						<input type="text" id="answer1_1" name="answers[0][0]" required>
						<input type="radio" name="correct_answers[0]" value="0" required>
					</div>
					<div>
						<label for="answer1_2">R&eacute;ponse 2 :</label>
						<input type="text" id="answer1_2" name="answers[0][1]" required>
						<input type="radio" name="correct_answers[0]" value="1">
					</div>
					<div>
						<label for="answer1_3">R&eacute;ponse 3 :</label>
						<input type="text" id="answer1_3" name="answers[0][2]" required>
						<input type="radio" name="correct_answers[0]" value="2">
					</div>
					<div>
						<label for="answer1_4">R&eacute;ponse 4 :</label>
						<input type="text" id="answer1_4" name="answers[0][3]" required>
						<input type="radio" name="correct_answers[0]" value="3">
					</div>
					<br>
				</div>				
			</form>
		</section>
	</main>

	<script src="../js/utils/dropdown.js"></script>
    <script src="../js/page_creationQuiz.js"> </script>

</body>
</html>
