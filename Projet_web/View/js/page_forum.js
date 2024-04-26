function ajouterMessage() {
    // Récupérer les données du formulaire
    let id_forum = document.getElementById('id_forum').value;
    let contenu_message = document.getElementById('contenu_message').value;
  
    // Créer un objet XMLHttpRequest
    let xhr = new XMLHttpRequest();
  
    // Configurer la requête
    xhr.open('GET', '../Controller/routeur.php?action=save&id=' + id_forum + '&contenu_message=' + encodeURIComponent(contenu_message), true);
  
    // Définir la fonction de rappel lorsque la requête est terminée
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            // Recharger dynamiquement la section des messages avec la réponse reçue du serveur
            document.getElementById('messages_section').innerHTML = xhr.responseText;
        } else {
            console.error("Erreur lors de la requête AJAX.");
        }
    };
  
    // Envoyer la requête
    xhr.send();
  }