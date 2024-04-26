

var triCroissantNomFam = true; 

// fonction pour trier le tableau des cours par ordre alphabétique
function triTableauNomPren(tab,colonne) {
    var rowsTab = document.getElementById(tab).rows;
    var switching = true;
    // Boucle tant qu'il y a des éléments à déplacer
    while (switching) {
        switching = false;
        // Parcourt toutes les lignes du tableau
        for (var i = 1; i < rowsTab.length - 1; i++) {
            // Initialisation de la variable indiquant s'il doit y avoir un échange
            var shouldSwitch = false;
            // Obtient les deux éléments à comparer, un de la ligne courante et un de la suivante
            var x = rowsTab[i].getElementsByTagName("TD")[colonne].textContent.toLowerCase();
            var y = rowsTab[i + 1].getElementsByTagName("TD")[colonne].textContent.toLowerCase();
            // Vérifie si les deux lignes doivent être échangées
            if (triCroissantNomFam) {
                if (x > y) {
                    shouldSwitch = true;
                    break;
                }
            } else {
                if (x < y) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            // Si un échange a été marqué, effectue l'échange et marque qu'un échange a été effectué
            rowsTab[i].parentNode.insertBefore(rowsTab[i + 1], rowsTab[i]);
            switching = true;
        }
    }
    // Inverser l'état de tri pour le prochain appel
    triCroissantNomFam = !triCroissantNomFam;
}

// fonction pour trier le tableau des cours par ordre alphabétique
function triTableauID(tab) {
    var rowsTab = document.getElementById(tab).rows;
    var switching = true;
    // Boucle tant qu'il y a des éléments à déplacer
    while (switching) {
        switching = false;
        // Parcourt toutes les lignes du tableau
        for (var i = 1; i < rowsTab.length - 1; i++) {
            // Initialisation de la variable indiquant s'il doit y avoir un échange
            var shouldSwitch = false;
            // Obtient les deux éléments à comparer, un de la ligne courante et un de la suivante
            var x = parseFloat(rowsTab[i].getElementsByTagName("TD")[2].textContent);
            var y = parseFloat(rowsTab[i + 1].getElementsByTagName("TD")[2].textContent);
            // Vérifie si les deux lignes doivent être échangées
            if (triCroissantNomFam) {
                if (x > y) {
                    shouldSwitch = true;
                    break;
                }
            } else {
                if (x < y) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            // Si un échange a été marqué, effectue l'échange et marque qu'un échange a été effectué
            rowsTab[i].parentNode.insertBefore(rowsTab[i + 1], rowsTab[i]);
            switching = true;
        }
    }
    // Inverser l'état de tri pour le prochain appel
    triCroissantNomFam = !triCroissantNomFam;
}

function derouler(event){
	// on stop le comportement par defaut du bouton, qui submit ( pour pouvoir cliquer sur la balise <i>
	event.stopPropagation();
    var myDropdown = document.getElementById("myDropdown");
	myDropdown.classList.toggle('show');
	var iconClass = myDropdown.classList.contains('show') ? 'up' : 'down';
	document.querySelector('.dropbtn').innerHTML = initialesUser + `<i class="fa-solid fa-caret-${iconClass} fa-lg"></i>`;	
}

// ferme le menu quand on ne clique pas sur les liens qu'il contient
window.onclick = function(e){
	if(!e.target.matches('.dropbtn')){
		var myDropdown = document.getElementById("myDropdown");
		if(myDropdown.classList.contains('show')){
			myDropdown.classList.remove('show');
			document.querySelector('.dropbtn').innerHTML = initialesUser + `<i class="fa-solid fa-caret-down fa-lg"></i>`;
		}
	}
}


