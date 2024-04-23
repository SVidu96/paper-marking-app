<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit();
}

if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
} else {
    header('location:exam_preview.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Question Papers</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/chat.css">


   <style>
      .question-paper {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 20px;
    background-color: #fff;
}

.question-answer-form {
    display: flex;
    flex-direction: column;
}

.question,
.answer {
    margin-bottom: 10px;
    font-size: 13px;
}

label {
    font-weight: bold;
}

textarea {
    width: 100%;
    padding: 5px;
    margin-top: 5px;
}

textarea[readonly] {
    background-color: #f2f7f5;
    border: none;
    resize: none;
}

.exam-info {
    margin-bottom: 20px;
    background-color: #fff;
    padding: 10px;
}

.exam-info h2{
    text-align: center;
   font-size: 1.8rem;
}

.exam-info p{
    text-align: center;
   font-size: 1.5rem;
}



.papers-container{
    background-color: #f9f9f9;
   border: 1px solid #ddd;
   border-radius: 5px;
   padding: 20px;
   margin-bottom: 20px;
   width: 60%;
   margin-left: 18%;
}

.papers-container h3{
   text-align: center;
   font-size: 13px;
}

</style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Question Papers section starts  -->
<section class="dashboard">
    <?php
    // Fetch exam title, description, and time from the exam table
    $stmtExams = $conn->prepare("SELECT exam_id, title, description, time FROM exams WHERE exam_id = ?");
    $stmtExams->execute([$exam_id]);
    $exams = $stmtExams->fetchAll(PDO::FETCH_ASSOC);

    // Display each exam paper separately
    foreach ($exams as $exam) {
        echo "<div class='papers-container'>";
        echo "<div class='exam-info'>";
        echo "<h2>{$exam['title']}</h2>";
        echo "<p>{$exam['description']}</p>";
        echo "<p>{$exam['time']}</p>";
        echo "<hr style=\"height:1px;border-width:0;color:gray;background-color:gray\">";
        echo "</div>";
        

        // Fetch questions and answers for the current exam
        $stmtQuestionPapers = $conn->prepare("SELECT qa.question_number, qa.question, ua.answer FROM question_papers qa LEFT JOIN user_answers ua ON qa.exam_id = ua.exam_id AND qa.question_number = ua.question_number WHERE ua.user_id = ? AND qa.exam_id = ?");
        $stmtQuestionPapers->execute([$user_id, $exam['exam_id']]);
        $questionPapers = $stmtQuestionPapers->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any question papers available for this exam
        if (count($questionPapers) > 0) {
            foreach ($questionPapers as $paper) {
                echo "<div class='question-paper'>";
                echo "<hr>";
                echo "<div class='question-answer'>";

                // Display the question number
                echo "<div class='question'>";
                echo "<p class='paper-heading'>Question:</p>";
                echo "<p class='paper-text'>{$paper['question']}</p>";
                echo "</div>";

                // Display the answer in a textarea
                echo "<div class='answer'>";
                echo "<p class='paper-heading'>Your Answer:</p>";
                $answer = isset($paper['answer']) ? $paper['answer'] : "Not answered yet";
                echo "<textarea class='paper-text' readonly>{$answer}</textarea>";
                echo "</div>";

                echo "</div>"; // Closing div for question-answer
                echo "</div>"; // Closing div for question-paper
            }
        } else {
            echo "<h3>You didn't submit the paper yet!!!</h3>";
        }
        

        echo "</div>"; // Closing div for papers-container
    }
    
    ?>
</section>
<br><br><br>


<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script src="js/chat.js" defer></script>

</body>
</html>
