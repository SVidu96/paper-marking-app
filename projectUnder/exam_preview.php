<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit;
}
if (isset($_POST['viewexam'])) {
    $exam_id = $_POST['exam_id'];
    $user_id = $_POST['user_id'];
    header("Location: exam_results.php?exam_id=$exam_id&user_id=$user_id");
    exit; 
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

.viewexambtn{
    margin-left:0px !important;
}

</style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Question Papers section starts  -->
<section class="dashboard">
    <h1 class="heading">Exams Preview</h1>
    <section class="exam-container">
        <?php 
    $stmtExams = $conn->prepare("SELECT e.title, e.exam_id,ua.user_id, ua.created_at FROM exams e JOIN user_answers ua ON e.exam_id = ua.exam_id AND ua.user_id = ? GROUP BY ua.exam_id, ua.user_id ORDER BY ua.created_at DESC;");
    $stmtExams->execute([$user_id]);
    $exams = $stmtExams->fetchAll(PDO::FETCH_ASSOC);
    if(empty($exams)) {
    echo "<div class='Questionpaper'><p class='head'>No Exams Found</p></div>";
    }
    foreach ($exams as $exam) :
        ?>
        <div class="Questionpaper">
            <p class="head"><?php echo "{$exam['title']}"; ?></p>
            <p>Submitted on : <?php echo "{$exam['created_at']}"; ?></p>
            <form action="" method="post" class="exam-password-form">
                            <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $exam['user_id']; ?>">
                            <button type="submit" name="viewexam" class="viewexambtn">View Exam</button>
            </form>
        </div>
        <?php endforeach; ?>

    
    </section>
</section>
<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script src="js/chat.js" defer></script>

</body>
</html>
