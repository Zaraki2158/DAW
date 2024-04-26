<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "Model.php";


class Modele_Quiz{
    private $titre;

    public static function read(){
		$xmlFile = __DIR__ . "/../xml/quiz.xml";
		$xml = simplexml_load_file($xmlFile);

		if($xml !== false){ 
			foreach ($xml->quiz as $quiz){
				if(isset($quiz['id']) && !empty($quiz['id'])){
					$quizTitle = isset($quiz->title) ? $quiz->title : 'Titre non disponible';
					echo '<a href="page_quiz.php?id='.$quiz['id'].'" class="lienQuiz">'.$quizTitle.'</a>';
				}
			}
		}else{
			echo "Erreur lors du chargement du fichier XML.";
		}
	}

	public static function readAll_Admin(){
		$xmlFile = __DIR__ . "/../xml/quiz.xml";
		$xml = simplexml_load_file($xmlFile);
		
		if($xml !== false){ 
	
			foreach ($xml->quiz as $quiz){
				if(isset($quiz['id']) && !empty($quiz['id'])) {
					$quizTitle = isset($quiz->title) ? $quiz->title : 'Titre non disponible';
					$quizImage = isset($quiz->image) ? "../../resources/cardImages/" . $quiz->image : "../../resources/cardImages/card_reseau.png";
					echo '';
					echo '<form action="../../Controller/routeur.php" method="get" class="quizCard">
							<img src="'.$quizImage.'">
							<div class="quizDiv">
								<a href="page_quiz.php?id='.$quiz['id'].'" class="lienQuiz">'.$quizTitle.'</a>
								<input type="hidden" name="id" value="'.$quiz['id'].'">
								<button type="submit" name="action" value="supprimer_quiz" class="boutSupQuiz">Supprimer</button>
							</div>
						</form>';
				}
			}		   
		}else{
			echo "Erreur lors du chargement du fichier XML.";
		}
	}
	
    public static function readAll(){
		$xmlFile = __DIR__ . "/../xml/quiz.xml";
		$xml = simplexml_load_file($xmlFile);

		if($xml !== false){ 
			foreach ($xml->quiz as $quiz){
				if(isset($quiz['id']) && !empty($quiz['id'])){
					$quizTitle = isset($quiz->title) ? $quiz->title : 'Titre non disponible';
					$quizImage = isset($quiz->image) ? "../../resources/cardImages/" . $quiz->image : "../../resources/cardImages/card_reseau.png";
					echo '<form action="../../Controller/routeur.php" method="get" class="quizCard">
							<img src="'.$quizImage.'">
							<div class="quizDiv">
								<a href="page_quiz.php?id='.$quiz['id'].'" class="lienQuiz">'.$quizTitle.'</a>
							</div>
						 </form>';
				}
			}
		}else{
			echo "Erreur lors du chargement du fichier XML.";
		}
	}
	
    public static function save() {
        if (!empty($_POST['questions']) && !empty($_POST['answers']) && !empty($_POST['correct_answers']) && !empty($_POST['quiz_title'])){
            $questions = $_POST['questions'];
            $answers = $_POST['answers'];
            $correctAnswers = $_POST['correct_answers'];
    
            // Récupérer le nom de la matière à partir du nom du quiz
            $quiz_title = $_POST['quiz_title'];
            $quiz_title_parts = explode('_', $quiz_title);
            $matiere = $quiz_title_parts[0];
    
            // Vérifier si la matière existe dans la table "cours"
            $query = "SELECT COUNT(*) as count FROM cours WHERE matiere = :matiere";
            $stmt = Model::$pdo->prepare($query);
            $stmt->bindParam(':matiere', $matiere);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $row['count'];
    
            if ($count > 0) {
                // La matière existe, vous pouvez procéder à la sauvegarde du quiz
    
                // Chemin vers le dossier de destination
                $uploadDirectory = __DIR__ . "/../resources/cardImages/";
    
                // Déterminer le nom du fichier de l'image téléchargée ou utiliser l'image par défaut
                if (isset($_FILES['quiz_image']) && $_FILES['quiz_image']['error'] === UPLOAD_ERR_OK) {
                    $quizImage = $_FILES['quiz_image']['name'];
                    // Chemin complet du fichier téléchargé
                    $targetFile = $uploadDirectory . basename($_FILES["quiz_image"]["name"]);
                    // Déplacer le fichier téléchargé vers le dossier de destination
                    if (!move_uploaded_file($_FILES["quiz_image"]["tmp_name"], $targetFile)) {
                        echo "Erreur lors du téléchargement de l'image.";
                    }
                } else {
                    $quizImage = 'card_reseau.png';
                }
    
                $xmlFile = __DIR__ . "/../xml/quiz.xml";
                if (file_exists($xmlFile)) {
                    $xml = simplexml_load_file($xmlFile);
                } else {
                    $xml = new SimpleXMLElement('<quiz/>');
                }
                $quizCount = $xml->count();
                $nextQuizId =($quizCount + 1);
    
                $quiz = $xml->addChild('quiz');
                $quiz->addAttribute('id', $nextQuizId);
    
                // Ajout du titre du quiz
                $quiz->addChild('title', $_POST['quiz_title']);
    
                // Ajout du lien de l'image du quiz dans le XML
                $quiz->addChild('image', $quizImage);
    
                // Connexion à la base de données
                $conn = Model::$pdo;
    
                // Requête pour ajouter une nouvelle colonne à la table score
                $query = "ALTER TABLE score ADD $quiz_title INT DEFAULT 0"; // Vous pouvez modifier le type de données selon vos besoins
    
                // Exécution de la requête
                try {
                    $conn->exec($query);
                    echo "La colonne pour le quiz '$quiz_title' a été ajoutée avec succès.";
                } catch (PDOException $e) {
                    echo "Erreur lors de l'ajout de la colonne pour le quiz '$quiz_title' : " . $e->getMessage();
                }
    
                foreach ($questions as $index => $questionText) {
                    $question = $quiz->addChild('question');
                    $question->addChild('text', $questionText);
    
                    if (isset($answers[$index]) && isset($correctAnswers[$index])) {
                        $answersNode = $question->addChild('answers');
                        foreach ($answers[$index] as $answerIndex => $answerText) {
                            $answer = $answersNode->addChild('answer', $answerText);
                            if ($correctAnswers[$index] == $answerIndex) {
                                $answer->addAttribute('correct', 'true');
                            } else {
                                $answer->addAttribute('correct', 'false');
                            }
                        }
                    }
                }
                if (file_put_contents($xmlFile, $xml->asXML()) !== false) {
                    header("Location: ../View/php/page_accueil.php");
                    echo "QCM enregistré avec succès !";
                } else {
                    echo "Erreur lors de l'enregistrement du QCM.";
                }
            } else {
                // La matière n'existe pas, renvoyez un message d'erreur
                echo "Erreur : La matière du quiz n'existe pas.";
                header("Location: ../View/php/page_creation_quiz.php");
            }
        } else {
            echo "Erreur : Les données du formulaire sont manquantes ou vides.";
        }
    }
    
    public static function addQCM(){
        $questionCount = 1; // Nombre initial de questions

        echo '<label for="question1">Question 1 :</label><br>';
        echo '<input type="text" id="question1" name="questions[0]" required><br><br>';
        
        echo '<label>R&eacute;ponses :</label><br>';
        
        echo '<label for="answer1_1">R&eacute;ponse 1 :</label>';
        echo '<input type="text" id="answer1_1" name="answers[0][0]" required>';
        echo '<input type="radio" name="correct_answers[0]" value="0" required><br>';
        
        echo '<label for="answer1_2">R&eacute;ponse 2 :</label>';
        echo '<input type="text" id="answer1_2" name="answers[0][1]" required>';
        echo '<input type="radio" name="correct_answers[0]" value="1"><br>';
        
        echo '<label for="answer1_3">R&eacute;ponse 3 :</label>';
        echo '<input type="text" id="answer1_3" name="answers[0][2]" required>';
        echo '<input type="radio" name="correct_answers[0]" value="2"><br>';
        
        echo '<label for="answer1_4">R&eacute;ponse 4 :</label>';
        echo '<input type="text" id="answer1_4" name="answers[0][3]" required>';
        echo '<input type="radio" name="correct_answers[0]" value="3"><br><br>';
        self::add();
    }

    public static function add(){
        header("Location: ../View/php/page_creation_quiz.php");
    }

    public static function supprimer() {
        if (isset($_POST['id']) || isset($_GET['id'])) {
            
            // Suppression du quiz du fichier XML
            $xmlFile = __DIR__ . "/../xml/quiz.xml";
            $xml = simplexml_load_file($xmlFile);
    
           
            $id_quiz = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
    
            $quiz_title = ''; // Initialisation de la variable

            // Recherche de la balise <quiz> avec l'attribut id correspondant à $id_quiz
            foreach ($xml->quiz as $quiz) {
                if ((string)$quiz['id'] === $id_quiz) {
                    // Récupération du titre du quiz
                    $quiz_title = (string)$quiz->title;
                    break;
                }
            }
            // Connexion à la base de données
            $conn = Model::$pdo;
    
            // Requête pour supprimer la colonne de la table score
            $query = "ALTER TABLE score DROP COLUMN $quiz_title";
    
            // Exécution de la requête
            try {
                $conn->exec($query);
                echo "La colonne pour le quiz '$quiz_title' a été supprimée avec succès.";
            } catch (PDOException $e) {
                echo "Erreur lors de la suppression de la colonne pour le quiz '$quiz_title' : " . $e->getMessage();
            }
            if ($xml !== false) {
                $quizFound = false;
                foreach ($xml->quiz as $quiz) {
                    if ((string)$quiz['id'] === $id_quiz) {
                        unset($quiz[0]);
                        $quizFound = true;
                        break;
                    }
                }
                if ($quizFound && $xml->asXML($xmlFile)) {
                    header("Location: ../View/php/page_accueil.php");
                    
                } else {
                    echo "Erreur : Quiz non trouvé ou impossible de le supprimer.";
                }
            } else {
                echo "Erreur lors de la lecture du fichier XML.";
            }
        } else {
            echo "Erreur : Identifiant du quiz non spécifié.";
        }
    }
    public static function readQuiz(){
        if (isset($_GET['id'])) {
            $id_quiz = $_GET['id'];
    
            $xmlFile = __DIR__ . "/../xml/quiz.xml";
            $xml = simplexml_load_file($xmlFile);
            if ($xml !== false) { 
                $selectedQuiz = null;
    
                foreach ($xml->quiz as $quiz) {
                    if ((string)$quiz['id'] === $id_quiz) {
                        $selectedQuiz = $quiz;
                        break;
                    }
                }
    
                if ($selectedQuiz !== null) {
                    echo "<h1>QCM : $id_quiz</h1>";
    
                    $questionIndex = 1;
                    $score = 0; // Initialiser le score à 0
    
                    echo "<form method='post'>"; // Formulaire pour soumettre les réponses
                    foreach ($selectedQuiz->question as $question) {
                        $text = (string)$question->text;
                        $answers = $question->answers->answer;
    
                        echo "<p class='question'><span>Question $questionIndex:</span><br><br> $text</p>";
                        $optionIndex = 'A';
                        if ($answers && count($answers) > 0) {
                            echo "<div class='divRep'>";
                            foreach ($answers as $answer) {
                                $isCorrect = (string)$answer['correct'] === 'true';
                                $value = $isCorrect ? 'true' : 'false'; // Valeur du bouton radio correspondant à la réponse correcte
                                echo "<div class='reponses'><input type='radio' name='reponse_$questionIndex' value='$value'> $optionIndex) <span class='reponse'>$answer</span></div>";
                                $optionIndex++;
                            }
                            echo "</div><br>";
                            echo "<hr><br>";
                            $questionIndex++;
                        } else {
                            echo "<p>Aucune r&eacute;ponse trouv&eacute;e pour cette question.</p>";
                        }
                    }
    
                    // Bouton de soumission du formulaire
					
                    echo "  <div class='centerDiv'><input id='boutonSubmitQuiz' type='submit' name='submitQuiz' value='Valider'></div><br>";
                    echo "</form>";
    
                    // Traitement des r&eacute;ponses soumises
                    if (isset($_POST['submitQuiz'])) {
                        $questionIndex = 1;
						
						echo '	<div class="centerDiv">		
								<table id="resQuiz">
									<thead>					
										<tr>
											<th>Question</th>
											<th>R&eacute;sultat</th>					
										</tr>				
									</thead>
									
									<tbody>';
								
                        foreach ($selectedQuiz->question as $question) {
							
							echo '<tr>';
							echo "<td class='rightBorder'> <span class='pQuest'> $questionIndex </span> </td>";
							
                            if (isset($_POST["reponse_$questionIndex"])) {
                                $selectedAnswer = $_POST["reponse_$questionIndex"];						
                                if ($selectedAnswer === 'true') {
                                    $score++; // Incr&eacute;menter le score si la r&eacute;ponse est correcte
                                    echo "<td> <span class='pJuste'>Juste</span> </td> ";
                                }else{
                                    echo "<td> <span class='pFaux'>Faux</span> </td>";
                                }
                            }else{
                                echo "<td> <span class='pFaux'>Non r&eacute;pondu</span> </td>";
                            }
							
							echo '</tr>';
							
                            $questionIndex++;
                        }
						
						echo '</tbody>
							</table>';
						
                        // Afficher le score
                        $totalQuestions = count($selectedQuiz->question);
                        echo "<br><h2 id='spanScore'>Votre score : $score / $totalQuestions</h2>
						
							</div><br>";
                        
                        $conn = Model::$pdo;
                        $user=$_SESSION['utilisateur_id'];
                        

                        // V&eacute;rifier si l'&eacute;tudiant existe d&eacute;jà dans la table score
                        $query = "SELECT * FROM score WHERE NumEtu = :NumEtu";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':NumEtu', $user);
                        $stmt->execute();
                        $rowCount = $stmt->rowCount();
                        
                        // Si l'&eacute;tudiant existe d&eacute;jà, mettre à jour le score, sinon ins&eacute;rer un nouveau score
                        if ($rowCount > 0) {
                            // L'&eacute;tudiant existe d&eacute;jà, donc nous devons mettre à jour le score
                            $sql = "UPDATE score SET $selectedQuiz->title = :score WHERE NumEtu = :NumEtu";
                        } else {
                            // L'&eacute;tudiant n'existe pas encore, donc nous devons ins&eacute;rer un nouveau score
                            $sql = "INSERT INTO score (NumEtu, $selectedQuiz->title) VALUES (:NumEtu, :score)";
                        }
                        
                        // Pr&eacute;paration de la requête
                        $stmt = $conn->prepare($sql);
                        
                        // Liaison des param&egrave;tres
                        $stmt->bindParam(':NumEtu', $user);
                        $stmt->bindParam(':score', $score);
                        
                        // Ex&eacute;cution de la requête
                        try {
                            $stmt->execute();
                            if ($rowCount > 0) {
                                //echo "Score mis à jour avec succ&egrave;s pour le quiz '$' dans la table score.";
                                // R&eacute;cup&eacute;rer le nom de la mati&egrave;re à partir du nom du quiz
                                $matiere = $selectedQuiz->title;

                                // R&eacute;cup&eacute;rer l'ID du cours en fonction du nom de la mati&egrave;re
                                $query = "SELECT id FROM cours WHERE matiere = :matiere";
                                $stmt = $conn->prepare($query);
                                $stmt->bindParam(':matiere', $matiere);
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $id_cours = $row['id'];

                                // Maintenant, ins&eacute;rez dans la table recommandation
                                $date = date('Y-m-d'); // R&eacute;cup&eacute;rer la date du jour
                                $query = "INSERT INTO recommandation (id_cours, id_user, date) VALUES (:id_cours, :id_user, :date)";
                                $stmt = $conn->prepare($query);
                                $stmt->bindParam(':id_cours', $id_cours);
                                $stmt->bindParam(':id_user', $user);
                                $stmt->bindParam(':date', $date);
                                $stmt->execute();
                                //echo "Recommandation ins&eacute;r&eacute;e avec succ&egrave;s dans la table recommandation.";

                            } else {
                                //echo "Score ins&eacute;r&eacute; avec succ&egrave;s pour le quiz '$' dans la table score.";
                            }
                        } catch (PDOException $e) {
                            echo "Erreur lors de l'insertion/mise à jour du score pour le quiz '$' dans la table score : " . $e->getMessage();
                        }
                        //header("Location: ../View/php/page_accueil.php");



                    }
    
                } else {
                    echo "Erreur : QCM non trouv&eacute;.";
                }
            } else {
                echo "Erreur lors de la lecture du fichier XML.";
            }
        }
    }

}
?>