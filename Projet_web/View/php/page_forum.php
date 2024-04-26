<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_forum = isset($_GET['id']) ? $_GET['id'] : null;
$_SESSION['id_forum'] = $id_forum; 
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/controleur_Message.php'));
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/controleur_Forum.php'));
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
	<link rel="stylesheet" href="../css/page_forum.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
	<link href="../../resources/favicons/favicon1.png" rel="icon" type="image/x-icon" />	
    <title>Forum</title>
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
		<h2>Forums disponibles</h2>
		<div id="liensForums">
			<?php Controleur_Forum::read(); ?>
		</div>
	</nav>

	<main>
		<section id="sectionMessages">
			<?php Controleur_Message::readAll();?>
		</section>
		<br>
		<section id="sectionSendMessage">
			<!-- Formulaire pour ajouter un nouveau message -->
		<form action="../../Controller/routeur.php" method="get">
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
			<label for="contenu_message">Votre message:</label><br>
			<textarea name="contenu_message" id="contenu_message" rows="8" cols="50"></textarea><br>
			<input type="hidden" name="action" value="add_message">
			<input type="submit" value="Envoyer" >
		</form>
		</section>

		<?php if(isset($_SESSION['error_message'])): ?>
			<div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
			<?php unset($_SESSION['error_message']); ?>
		<?php endif; ?>
	
	</main>
	
	
	<script src="../js/utils/dropdown.js"></script>
	<script src="../js/page_forum.js"></script>

</body>
</html>
