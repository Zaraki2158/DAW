<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/controleur_Utilisateur.php'));
require_once(realpath(dirname(__FILE__) . '\..\..\Controller/routeur.php'));

$nom =  $_SESSION['nom_utilisateur'];
$prenom = $_SESSION['prenom_utilisateur'];
$numero = $_SESSION['numero_utilisateur'];
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
	<link rel="stylesheet" href="../css/page_gestion.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
	<link href="../../resources/favicons/favicon1.png" rel="icon" type="image/x-icon" />	
    <title><?php echo ($_SESSION['type_utilisateur'] == 'Professeur') ? "Gestion des utilisateurs"  : "Param&egrave;tres &eacute;tudiants"; ?></title>
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
			<a href="page_accueil.php" id=""><i class="fa-solid fa-house fa-lg"></i> Accueil</a>
		</div>
		<hr>
		<h2>Trier la liste</h2>
		<div id="liensForums">
			<a class="liensTri" onclick="triTableauNomPren('UserTable','0')">Par pr&eacute;nom</a>
			<a class="liensTri" onclick="triTableauNomPren('UserTable','1')">Par nom de famille</a>
			<a class="liensTri" onclick="triTableauID('UserTable')">Par ID</a>
		</div>		
	</nav>
  
	<main>
		<section>
			
			<form action="../../Controller/routeur.php" method="post">
				<fieldset id="test">
					<legend>Modification du compte actuel</legend>
					<br>					
					<table>
					
						<tbody>	
						
							<tr>
								<td>
									<label class="labelNodif" for="nom">Nom :</label>
								</td>
								<td>
									<input type="text" name="nom" placeholder="Nom" value="<?php echo isset($nom) ? $nom : ''; ?>" required>
								</td>								
							</tr>
							<tr>
								<td>
									<label class="labelNodif" for="prenom">Pr&eacute;nom :</label>
								</td>
								<td>
									<input type="text" name="prenom" placeholder="Pr&eacute;nom" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required>
								</td>								
							</tr>
							<tr>
								<td>
									<label class="labelNodif" for="numero">Num&eacute;ro :</label>
								</td>
								<td>
									<input type="text" name="numero" placeholder="Num&eacute;ro" value="<?php echo isset($numero) ? $numero : ''; ?>" required>
								</td>								
							</tr>
							<tr>
								<td>
									<label class="labelNodif" for="password">Mot de passe:</label>
								</td>
								<td>
									<input type="password" name="password" placeholder="Mot de passe" required>
								</td>								
							</tr>
							
						</tbody>
					
					</table>							

					<input type="hidden" name="action" value="modif">
					<input id="boutonValMod" type="submit" value="Modifier">			
					
				</fieldset>	
			</form>
		</section>
		<br>
		<section>
			
			<fieldset>
				<legend>Liste des comptes</legend>
		
					<h3>Affichage des comptes ayant les privil&egrave;ges Professeur et Admin en rouge</h3>
			
					<table id="UserTable">
							
						<thead>					
							<tr>
								<th>Nom de famille</th>
								<th>Pr&eacute;nom</th>
								<th>ID</th>
								<th>Action</th>							
							</tr>				
						</thead>
						
						<tbody>		
							<?php if(isset($_SESSION['type_utilisateur']) && $_SESSION['type_utilisateur'] == 'Professeur'){Controleur_Utilisateur::readAll();}?>
						</tbody>
						
					</table>	
			
			</fieldset>
		</section>
	</main>
	
	<script src="../js/utils/dropdown.js"></script>
    <script src="../js/page_gestion.js"></script>  

	<script>triTableauNomPren('UserTable','0')</script>

</body>
</html>
