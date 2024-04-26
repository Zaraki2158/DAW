// fonction pour afficher le div de confirmation
function afficher(event) {
    event.stopPropagation();	
    var myDropdown = document.getElementById("confirmationExitDiv");
    
    var computedStyle = window.getComputedStyle(myDropdown);
    var displayStyle = computedStyle.display;
	myDropdown.style.display = (displayStyle === "none") ? "block" : "none" ;

}
// ferme le menu quand on ne clique pas sur les liens qu'il contient
window.onclick = function(e) {
    var myDropdown = document.getElementById("confirmationExitDiv");
    var target = e.target;
    var isExitDiv = target.matches('#confirmationExitDiv, #confirmationExitDiv *');

    if (!isExitDiv && myDropdown.style.display === 'block') {
        myDropdown.style.display = 'none';
    }
}

