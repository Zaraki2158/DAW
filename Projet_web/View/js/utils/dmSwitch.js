function switchThemeLN(event){
	var bouton = document.getElementById('spanTheme');
	var root = document.documentElement.style;
    var styleRoot = getComputedStyle(document.documentElement).getPropertyValue('--bgColor').trim();
    if(styleRoot === '#3e4c59'){
		// sombre->clair
		document.documentElement.classList.remove('changeColorAnimLD');
		document.documentElement.classList.add('changeColorAnimDL');
		root.setProperty('--bgColorHover', '#CDCDCD');
		root.setProperty('--primTxtColor', 'black');
		root.setProperty('--secTxtColor', 'gray');
		root.setProperty('--thirdColor', '#B8B8B8');
		root.setProperty('--shadowTxtColor', '#9b9b9b');
		root.setProperty('--shadowBoxColor', '#9b9b9b');
		root.setProperty('--borderColor', 'gray');		
		bouton.innerHTML = '<i class="fa-solid fa-moon fa-lg"></i><span class="spanDrop">Th&egrave;me sombre</span>';		
    }else{
		// clair->sombre
		document.documentElement.classList.remove('changeColorAnimDL');
		document.documentElement.classList.add('changeColorAnimLD');
		root.setProperty('--bgColorHover', '#526476');
		root.setProperty('--primTxtColor', '#FFFFFF');
		root.setProperty('--secTxtColor', '#D2D2D2');
		root.setProperty('--thirdColor', 'gray');
		root.setProperty('--shadowTxtColor', 'black');
		root.setProperty('--shadowBoxColor', '#1E1E1E');
		root.setProperty('--borderColor', '#CBCBCB');		
		bouton.innerHTML = '<i class="fa-solid fa-sun fa-lg"></i><span class="spanDrop">Th&egrave;me clair</span>';	
    }
}

function loadTheme(userTheme){
	var bouton = document.getElementById('spanTheme');
	var root = document.documentElement.style;
	if(userTheme == 'blanc'){
		root.setProperty('--bgColor', '#E3E3E3');
		root.setProperty('--bgColorHover', '#CDCDCD');
		root.setProperty('--primTxtColor', 'black');
		root.setProperty('--secTxtColor', 'gray');
		root.setProperty('--thirdColor', '');
		root.setProperty('--shadowTxtColor', '#9b9b9b');
		root.setProperty('--shadowBoxColor', '#9b9b9b');
		root.setProperty('--borderColor', 'gray');		
		bouton.innerHTML = '<i class="fa-solid fa-moon fa-lg"></i><span class="spanDrop">Th&egrave;me sombre</span>';		
	}else{		
		root.setProperty('--bgColor', '#3e4c59');
		root.setProperty('--bgColorHover', '#526476');
		root.setProperty('--primTxtColor', '#FFFFFF');
		root.setProperty('--secTxtColor', '#D2D2D2');
		root.setProperty('--thirdColor', '');
		root.setProperty('--shadowTxtColor', 'black');
		root.setProperty('--shadowBoxColor', '#1E1E1E');
		root.setProperty('--borderColor', '#CBCBCB');		
		bouton.innerHTML = '<i class="fa-solid fa-sun fa-lg"></i><span class="spanDrop">Th&egrave;me clair</span>';	
	}
}
























