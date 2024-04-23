<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

?>

<?php
function getGrade($score){
    if ($score >= 90) {
        return 'A+';
    } elseif ($score >= 80) {
        return 'A';
    } elseif ($score >= 75) {
        return 'A-';
    } elseif ($score >= 70) {
        return 'B+';
    } elseif ($score >= 65) {
        return 'B';
    } elseif ($score >= 60) {
        return 'B-';
    } elseif ($score >= 55) {
        return 'C+';
    } elseif ($score >= 45) {
        return 'C';
    } elseif ($score >= 40) {
        return 'C-';
    } elseif ($score >= 35) {
        return 'D+';
    } elseif ($score >= 30) {
        return 'D';
    } else {
        return 'E';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>teachers</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->

<section class="teachers">

   <h1 class="heading">Student Grades</h1>
   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="search grades..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>
   <?php
   $stmtGrades = $conn->prepare("SELECT u.studentNo, u.name,e.title, ua.user_id, ua.exam_id, AVG(ua.score) as total_score FROM user_answers ua JOIN exams e ON ua.exam_id = e.exam_id JOIN users u ON ua.user_id = u.id AND user_id = ? GROUP BY user_id,exam_id;");
   $stmtGrades->execute([$user_id]);
   $totalGrades = $stmtGrades->rowCount();
   $grades = $stmtGrades->fetchAll(PDO::FETCH_ASSOC);

   if($totalGrades > 0){
      foreach($grades as $grade){
         ?>
         <div class="Questionpaper">
            <p>Student Number: <span><?php echo $grade['studentNo']; ?></span></p>
            <p>Student Name: <span><?php echo $grade['name']; ?></span></p>
            <p>Exam Title: <span><?php echo $grade['title']; ?></span></p>
            <p>Total Score: <span><?php echo number_format($grade['total_score'], 2); ?></span></p>
            <p>Grade: <span><?php echo getGrade($grade['total_score']); ?></span></p>
         </div>
         <?php
      }
   }else{
      echo '<p class="empty">No grades found!</p>';
   }
   ?>
</section>

<!-- teachers section ends -->






























<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>