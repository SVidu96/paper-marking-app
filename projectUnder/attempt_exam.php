<?php
date_default_timezone_set('Asia/Colombo'); // Set the timezone to Sri Lanka

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
    exit();
}

// Check if exam_id is set in the URL
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // Fetch exam details
    $stmtExam = $conn->prepare("SELECT * FROM exams WHERE exam_id = ?");
    $stmtExam->execute([$exam_id]);
    $exam = $stmtExam->fetch(PDO::FETCH_ASSOC);

    // Fetch questions for the exam and order them by question_number
    $stmtQuestions = $conn->prepare("SELECT * FROM question_papers WHERE exam_id = ? ORDER BY question_number");
    $stmtQuestions->execute([$exam_id]);
    $questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect to a page or display an error message if exam_id is not set
    header('location:home.php');
    exit();
}

$submitted = false; // Variable to track whether the form is submitted

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_exam'])) {
    $exam_id = $_POST['exam_id'];

    // Fetch exam details
    $stmtExam = $conn->prepare("SELECT * FROM exams WHERE exam_id = ?");
    $stmtExam->execute([$exam_id]);
    $exam = $stmtExam->fetch(PDO::FETCH_ASSOC);

    // Fetch questions for the exam
    $stmtQuestions = $conn->prepare("SELECT * FROM question_papers WHERE exam_id = ?");
    $stmtQuestions->execute([$exam_id]);
    $questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

    // Insert user's answers and questions into the database
    foreach ($questions as $index => $question) {
        $answer_key = 'answer_' . $question['question_number'];
        $user_answer = $_POST[$answer_key];

        // Ensure that the answer is not empty before inserting into the database
        if (!empty($user_answer)) {
            $stmtInsertAnswer = $conn->prepare("INSERT INTO user_answers (user_id, exam_id, question_number, question, answer) VALUES (?, ?, ?, ?, ?)");
            $stmtInsertAnswer->execute([$user_id, $exam_id, $question['question_number'], $question['question'], $user_answer]);
        }
    }

    $submitted = true; // Set the submitted variable to true after form submission
}

$current_time = time();
$end_time = strtotime($exam['time']); // Assuming the time is in a valid format, e.g., 'Y-m-d H:i:s'
$remaining_time = max(0, $end_time - $current_time);

// Calculate hours, minutes, and seconds
$remaining_time = max(0, $end_time - $current_time);

$hours = floor($remaining_time / 3600);
$minutes = floor(($remaining_time % 3600) / 60);
$seconds = $remaining_time % 60;

$submitted_datetime = date('Y-m-d H:i:s'); // Get the current date and time when the form is submitted

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $exam['title']; ?> - Exam Paper</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="dashboard">
        <h1 class="heading"><?= $exam['title']; ?> - Exam Paper</h1>

        <form id="examForm" action="" method="post" class="questionpaper" <?php if ($submitted)
            echo 'style="display: none;"'; ?>>
            <input type="hidden" name="exam_id" value="<?= $exam_id; ?>">
            <p class="exam-description"><?= $exam['description']; ?></p>
            <p class="exam-time">Time: <?= $exam['time']; ?></p>

            <hr style="height:1px;border-width:0;color:gray;background-color:gray"><br><br>

            <?php foreach ($questions as $question): ?>
                <div class="question-container">
                    <p class="question">Question <?php echo $question['question_number']; ?>:</p>
                    <p class="question"><?= $question['question']; ?></p>
                    <textarea name="answer_<?= $question['question_number']; ?>" class="box" rows="2" cols="50"
                        maxlength="400" placeholder="Your Answer" required></textarea><br><br>
                </div>
            <?php endforeach; ?>
            <br><br>

            <input type="submit" name="submit_exam" value="Submit Exam" class="btn"><br>
        </form>

        <?php if ($submitted): ?>
            <!-- Show success message when form is submitted -->
            <div id="successMessage" class="successMessage-box">
                <h2>Exam submitted successfully!</h2><br>
                <p>Submitted on: <?= $submitted_datetime; ?></p><br><br><br>
                <a href="home.php" id="exitButton" class="btnexit">Exit</a><br><br>
            </div>
        <?php endif; ?>

    </section>
    <br><br><br><br><br><br><br>

    <?php include 'components/footer.php'; ?>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
    <script>
        // Add JavaScript code to show/hide success message and form
        document.addEventListener("DOMContentLoaded", function () {
            var successMessage = document.getElementById('successMessage');
            var examForm = document.getElementById('examForm');

            if (successMessage && examForm && <?= $submitted ? 'true' : 'false' ?>) {
                successMessage.style.display = "block";
                examForm.style.display = "none";
            }
        });
    </script>

</body>

</html>