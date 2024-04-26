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

let questionCount = 1; // Initial number of questions

function addQuestion() {
    questionCount++; // Increment question count
    let form = document.getElementById('qcmForm');
    let newQuestionHTML = `
				<div class="divQuestion">
					<hr><br>
					<label for="question${questionCount}">Question ${questionCount} :</label>
					<input type="text" id="question${questionCount}" name="questions[${questionCount - 1}]" required>
				</div>
				<br>
				
				<span>R&eacute;ponses question ${questionCount}:</span>	<br><br>
				<div class="divReponses">									
					<div>
						<label for="answer${questionCount}_1">R&eacute;ponse 1 :</label>
						<input type="text" id="answer${questionCount}_1" name="answers[${questionCount - 1}][0]" required>
						<input type="radio" name="correct_answers[${questionCount - 1}]" value="0" required>
					</div>
					<div>
						<label for="answer${questionCount}_2">R&eacute;ponse 2 :</label>
						<input type="text" id="answer${questionCount}_2" name="answers[${questionCount - 1}][1]" required>
						<input type="radio" name="correct_answers[${questionCount - 1}]" value="1">
					</div>
					<div>
						 <label for="answer${questionCount}_3">R&eacute;ponse 3 :</label>
						<input type="text" id="answer${questionCount}_3" name="answers[${questionCount - 1}][2]" required>
						<input type="radio" name="correct_answers[${questionCount - 1}]" value="2">
					</div>
					<div>
						<label for="answer${questionCount}_4">R&eacute;ponse 4 :</label>
						<input type="text" id="answer${questionCount}_4" name="answers[${questionCount - 1}][3]" required>
						<input type="radio" name="correct_answers[${questionCount - 1}]" value="3">
					</div>
				</div>	
				<br>				
			`;
    form.insertAdjacentHTML('beforeend', newQuestionHTML);
}
