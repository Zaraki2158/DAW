
// Fonction pour trier les divs par ordre alphabétique
function triTableau(tab) {
    // Récupérer les divs dans l'élément avec l'ID `tab`
    var container = document.getElementById(tab);
    var divs = container.getElementsByTagName('div');
    var switching = true;
    
    // Boucle tant qu'il y a des éléments à déplacer
    while (switching) {
        switching = false;
        
        // Parcourt tous les divs dans le conteneur
        for (var i = 0; i < divs.length - 1; i++) {
            // Initialisation de la variable indiquant s'il doit y avoir un échange
            var shouldSwitch = false;
            
            // Obtient les deux éléments à comparer
            var x = divs[i].textContent.toLowerCase();
            var y = divs[i + 1].textContent.toLowerCase();
            
            // Vérifie si les deux divs doivent être échangés
            if (x > y) {
                shouldSwitch = true;
                break;
            }
        }
        
        if (shouldSwitch) {
            // Si un échange a été marqué, effectue l'échange et marque qu'un échange a été effectué
            container.insertBefore(divs[i + 1], divs[i]);
            switching = true;
        }
    }
}

// fonction pour que la page scroll correctement a l'element selectionne
function redirectNav(elemID) {
  var headerHeight = document.querySelector('header').offsetHeight;
  var targetElement = document.getElementById(elemID);
  
  if (targetElement) {
    var offsetPosition = targetElement.offsetTop - headerHeight - 25;

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });
  }
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


