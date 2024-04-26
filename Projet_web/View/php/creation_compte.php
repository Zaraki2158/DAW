<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cr&eacute;er un compte</title>
    <link rel="stylesheet" href="../css/page_de_connexion.css">
</head>
<body>
<div class="container">

    <?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['erreur_connexion'])): ?>
            <div class="error-message"><?php echo $_SESSION['erreur_connexion']; ?></div>
            <?php unset($_SESSION['erreur_connexion']); ?>
    <?php endif; ?>
	
	<h1>Cr&eacute;er un compte</h1>
	
    <form action="../../Controller/routeur.php" method="post">
	
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Pr&eacute;nom" required>
        <input type="text" name="numero" placeholder="Num&eacute;ro" required>
        <input type="password" name="password" placeholder="Mot de passe"required>
		
        <select name="type" aria-placeholder="S&eacute;lectionner le type d'utilisateur" required>
            <option value="etudiant">&Eacute;tudiant</option>
            <option value="professeur">Professeur</option>
        </select>
		
        <input type="hidden" name="action" value="add">
        <input type="submit" value="Cr&eacute;er son compte">
    </form>
	
</div>


    <script src="../js/page_connexion.js"></script>
	
</body>
</html>
