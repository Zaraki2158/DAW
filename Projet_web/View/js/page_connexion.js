function connexion() {
    document.getElementById("connexionForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
    
        var nom = document.querySelector('input[name="nom"]').value;
        var prenom = document.querySelector('input[name="prenom"]').value;
        var numero = document.querySelector('input[name="numero"]').value;
        var typeUtilisateur = document.querySelector('select[name="type_utilisateur"]').value;
    
        //cookies
        document.cookie = "nom=" + nom;
        document.cookie = "prenom=" + prenom;
        document.cookie = "numero=" + numero;
        document.cookie = "type_utilisateur=" + typeUtilisateur;
    
    });
}




//J'ai un doute sur son utilité

function creation() {
    document.getElementById("connexionForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
    
        var nom = document.querySelector('input[name="nom"]').value;
        var prenom = document.querySelector('input[name="prenom"]').value;
        var numero = document.querySelector('input[name="numero"]').value;
        var typeUtilisateur = document.querySelector('select[name="type_utilisateur"]').value;
    
        //cookies
        document.cookie = "nom=" + nom;
        document.cookie = "prenom=" + prenom;
        document.cookie = "numero=" + numero;
        document.cookie = "type_utilisateur=" + typeUtilisateur;
    });
}