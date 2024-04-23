<?php
ob_start();
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if (isset($_POST['viewexam'])) {
   $exam_id = $_POST['exam_id'];
   header("Location: exam_results.php?exam_id=$exam_id");
   exit; 
}

// Fetch all students from the users table
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>view content</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
    <h1 class="heading">Exams List</h1>
    <section class="exam-container">
        <?php 
    $stmtExams = $conn->prepare("SELECT e.exam_id, e.title, e.created_at FROM exams e ORDER BY e.created_at DESC");
    $stmtExams->execute();
    $exams = $stmtExams->fetchAll(PDO::FETCH_ASSOC);
    if(empty($exams)) {
    echo "<div class='Questionpaper'><p class='head'>No Exams Found</p></div>";
    }
    foreach ($exams as $exam) :
        ?>
        <div class="Questionpaper">
            <p class="head"><?php echo "{$exam['title']}"; ?></p>
            <p>Created at : <?php echo "{$exam['created_at']}"; ?></p>
            <form action="" method="post" class="exam-password-form">
                            <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">
                            <button type="submit" name="viewexam" class="btn">View Exam Results</button>
            </form>
        </div>
        <?php endforeach; ?>

    
    </section>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>