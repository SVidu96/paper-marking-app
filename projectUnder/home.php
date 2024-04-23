<?php
include 'components/connect.php';

session_start(); // Start the session

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
    exit();
}

// Fetch exams added by the admin
$stmtExams = $conn->prepare("SELECT * FROM exams");
$stmtExams->execute();
$exams = $stmtExams->fetchAll(PDO::FETCH_ASSOC);

$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable

// Check if the user has attempted each exam
$exam_attempted = array();
$stmtExamAttempted = $conn->prepare("SELECT DISTINCT exam_id FROM user_answers WHERE user_id = ?");
$stmtExamAttempted->execute([$user_id]);
while ($row = $stmtExamAttempted->fetch(PDO::FETCH_ASSOC)) {
    $exam_attempted[] = $row['exam_id'];
}

if (isset($_POST['submit_password'])) {
    $exam_id = $_POST['exam_id'];
    $entered_password = $_POST['password'];

    // Fetch the correct password for the exam
    $stmtExamPassword = $conn->prepare("SELECT password FROM exams WHERE exam_id = ?");
    $stmtExamPassword->execute([$exam_id]);
    $exam_password = $stmtExamPassword->fetchColumn();

    // Verify the entered password
    if ($entered_password == $exam_password) {
        // Redirect to the attempt_exam page if password is correct
        header("Location: attempt_exam.php?exam_id=$exam_id");
        exit();
    } else {
        // Display error message if password is incorrect
        $error_message = "Incorrect password! Please try again.";
    }
}

if (isset($_GET['submitted']) && $_GET['submitted'] == 'true') {
    $success_message = "Exam submitted successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="dashboard">
        <h1 class="heading">Exams</h1>

        <section class="exam-container">
            <?php if (!empty($exams)): ?>
                <?php foreach ($exams as $exam): ?>
                    <div class="Questionpaper">
                        <p class="head"><?php echo $exam['title']; ?></p>
                        <p><?php echo $exam['description']; ?></p>
                        <p>Time: <?php echo $exam['time']; ?></p>
                        <?php if (isset($_GET['submitted']) && $_GET['submitted'] == 'true'): ?>
                            <p><?php echo $success_message; ?></p>
                        <?php elseif (in_array($exam['exam_id'], $exam_attempted) || isset($_SESSION['exam_attempted'])): ?>
                            <p>You have attempted this exam.</p>
                            <?php
                            // Fetch the submitted date and time from user_answers table
                            $stmtSubmittedDateTime = $conn->prepare("SELECT created_at FROM user_answers WHERE user_id = ? AND exam_id = ? ORDER BY created_at DESC LIMIT 1");
                            $stmtSubmittedDateTime->execute([$user_id, $exam['exam_id']]);
                            $submitted_datetime = $stmtSubmittedDateTime->fetchColumn();
                            ?>
                            <p>Submitted on: <?= $submitted_datetime; ?></p>
                        <?php else: ?>
                            <!-- Add form for entering exam password -->
                            <form action="" method="post" class="exam-password-form">
                                <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">
                                <input type="password" name="password" placeholder="Enter Exam Password" required>
                                <button type="submit" name="submit_password" class="attemptbtn">Attempt Exam</button>
                            </form>
                            <!-- Display error message if password is incorrect -->
                            <p class="error-message"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="exam_container">
                    <p>No exams available at the moment.</p>
                </div>
            <?php endif; ?>
        </section>
    </section>

    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>