<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
   $tutor_id = $_COOKIE['tutor_id'];

   // Fetch total examinations
   $stmtExams = $conn->prepare("SELECT COUNT(*) as total_exams FROM exams WHERE tutor_id = ?");
   $stmtExams->execute([$tutor_id]);
   $totalExams = $stmtExams->fetch(PDO::FETCH_ASSOC)['total_exams'];

   // Fetch total unique papers (distinct exam_ids)
   $stmtPapers = $conn->prepare("SELECT COUNT(DISTINCT exam_id) as total_papers FROM question_papers WHERE tutor_id = ?");
   $stmtPapers->execute([$tutor_id]);
   $totalPapers = $stmtPapers->fetch(PDO::FETCH_ASSOC)['total_papers'];

   // Fetch total users
   $stmtUsers = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
   $stmtUsers->execute();
   $totalUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC)['total_users'];

   // Fetch total exams
   $stmtSubmissions = $conn->prepare("SELECT COUNT(*) AS number_of_groups FROM ( SELECT user_id, exam_id FROM user_answers GROUP BY user_id, exam_id ) AS grouped_data;");
   $stmtSubmissions->execute();
   $totalSubmissions = $stmtSubmissions->fetch(PDO::FETCH_ASSOC)['number_of_groups'];

} else {
   $tutor_id = '';
   header('location:login.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="heading">Dashboard</h1>

      <div class="box-container">

         <div class="box">
            <h3><?= $totalUsers; ?></h3>
            <p>Total Users</p>
            <a href="view_examinee.php" class="btn">View Examinees</a>
         </div>

         <div class="box">
            <h3><?= $totalExams; ?></h3>
            <p>Total Exams</p>
            <a href="addexams.php" class="btn">Add Exams</a>
         </div>

         <div class="box">
            <h3><?= $totalPapers; ?></h3>
            <p>Total Papers</p>
            <a href="manageexams.php" class="btn">Manage Examinations</a>
         </div>

         <div class="box">
            <h3><?php echo $totalSubmissions; ?></h3>
            <p>Total Submissions</p>
            <a href="grades.php" class="btn">View Grades</a>
         </div>
      </div>
   </section>

   <?php include '../components/footer.php'; ?>

   <script src="../js/admin_script.js"></script>

</body>

</html>