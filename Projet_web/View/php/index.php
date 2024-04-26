<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/page_de_connexion.css">
    <title>Se connexion</title>    
</head>
<body>

	<div class="php">
		<?php if(isset($_SESSION['erreur_connexion'])): ?>
			<div class="error-message"><?php echo $_SESSION['erreur_connexion']; ?></div>
			<?php unset($_SESSION['erreur_connexion']); ?>
		<?php endif; ?>
		<?php if(isset($_SESSION['succes'])): ?>
			<div class="succes"><?php echo $_SESSION['succes']; ?></div>
			<?php unset($_SESSION['succes']); ?>
		<?php endif; ?>
	</div>

	<main>
		<section>
			<div class="container">

				<h1>Connexion</h1>		
				
				<form action="../../Controller/routeur.php" method="post">
					<input type="text" name="nom" placeholder="Nom" required>
					<input type="text" name="prenom" placeholder="Pr&eacute;nom" required>
					<input type="password" name="password" placeholder="Mot de passe"required>
					<input type="hidden" name="action" value="connexion">
					<input type="submit" value="Se connecter" onclick="connexion()">
					<div id="noAccount">
						<a href="creation_compte.php">Pas de compte?</a>
					</div>
				</form>    
				
			</div>
		</section>
	</main>

    <script src="../js/page_connexion.js"></script>
	
</body>
</html>
