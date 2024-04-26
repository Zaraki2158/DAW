
function searchCQF(txtInput, div2Search) {
    var filter, divs, i, txtValue;
    filter = document.getElementById(txtInput).value.toUpperCase();
    
    // Récupérer les divs à l'intérieur de l'élément div2Search
    divs = document.getElementById(div2Search).getElementsByTagName("div");

    for (i = 0; i < divs.length; i++) {
        var div = divs[i];
        // Rechercher le lien <a> à l'intérieur de chaque div
        var a = div.getElementsByTagName("a")[0];
        if (a) {
            // Obtenir le texte de l'élément <a>
            txtValue = a.textContent || a.innerText;
            // Vérifier si le texte contient le filtre
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                div.style.display = "";
            } else {
                div.style.display = "none";
            }
        }
    }
}
