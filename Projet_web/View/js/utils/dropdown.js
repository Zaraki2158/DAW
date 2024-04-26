// fonction pour derouler le menu dropdown
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
