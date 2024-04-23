<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
} else {
    header('location:grades.php');
   exit();
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- teachers section starts  -->

<section class="teachers">

   <h1 class="heading">Student Grades</h1>
   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="search grades..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>
   <?php
   $stmtGrades = $conn->prepare("SELECT u.studentNo, u.name,e.title, ua.user_id, ua.exam_id, AVG(ua.score) as total_score FROM user_answers ua JOIN exams e ON ua.exam_id = e.exam_id JOIN users u ON ua.user_id = u.id AND ua.exam_id = ? GROUP BY user_id,exam_id;");
   $stmtGrades->execute([$exam_id]);
   $totalGrades = $stmtGrades->rowCount();
   $grades = $stmtGrades->fetchAll(PDO::FETCH_ASSOC);

   if($totalGrades > 0){
    echo "<h1>Exam : <span>" . $grades[0]['title'] . "</span></h1>";
      foreach($grades as $grade){
         ?>
         <div class="Questionpaper">
            <p>Student Number: <span><?php echo $grade['studentNo']; ?></span></p>
            <p>Student Name: <span><?php echo $grade['name']; ?></span></p>
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

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
   
</body>
</html>