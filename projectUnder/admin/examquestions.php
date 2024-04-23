<?php
ob_start();
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
    exit();
}

// Retrieve exam_id and title from the URL
$exam_id = $_GET['exam_id'] ?? '';
$selectedExamTitle = '';

if (!empty($exam_id)) {
    // Fetch the selected exam title from the exams table
    $stmt = $conn->prepare("SELECT title FROM exams WHERE exam_id = ? AND tutor_id = ?");
    $stmt->execute([$exam_id, $tutor_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $selectedExamTitle = $row['title'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Fetch the last question number for the specific exam
    $stmtLastQuestion = $conn->prepare("SELECT MAX(question_number) AS last_question_number FROM question_papers WHERE exam_id = ?");
    $stmtLastQuestion->execute([$exam_id]);
    $lastQuestionRow = $stmtLastQuestion->fetch(PDO::FETCH_ASSOC);
    $questionNumber = ($lastQuestionRow['last_question_number'] ?? 0) + 1; // Increment question number

    // Insert the question into the question_papers table with relevant information
    $stmt = $conn->prepare("INSERT INTO question_papers (tutor_id, exam_id, title, question_number, question, answer) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$tutor_id, $exam_id, $selectedExamTitle, $questionNumber, $question, $answer]);

    $success_message = "Question added successfully!";
}

// Fetch added questions
$stmtQuestions = $conn->prepare("SELECT * FROM question_papers WHERE exam_id = ? ORDER BY question_number");
$stmtQuestions->execute([$exam_id]);
$questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteQuestionId = $_POST['delete'];

    // Fetch the question number of the question to be deleted
    $stmtDelete = $conn->prepare("SELECT question_number FROM question_papers WHERE id = ?");
    $stmtDelete->execute([$deleteQuestionId]);
    $deletedQuestion = $stmtDelete->fetch(PDO::FETCH_ASSOC);
    $deletedQuestionNumber = $deletedQuestion['question_number'];

    // Perform the deletion
    $stmtDelete = $conn->prepare("DELETE FROM question_papers WHERE id = ?");
    $stmtDelete->execute([$deleteQuestionId]);

    // Update the question numbers of remaining questions
    $stmtUpdate = $conn->prepare("UPDATE question_papers SET question_number = question_number - 1 WHERE exam_id = ? AND question_number > ?");
    $stmtUpdate->execute([$exam_id, $deletedQuestionNumber]);

    // Redirect to avoid resubmission on refresh
    header("Location: examquestions.php?exam_id=$exam_id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $updateQuestionId = $_POST['update_question_id'];
    $updatedQuestion = $_POST['update_question'];
    $updatedAnswer = $_POST['update_answer'];

    // Update the question in the question_papers table with relevant information
    $stmtUpdate = $conn->prepare("UPDATE question_papers SET question = ?, answer = ? WHERE id = ?");
    $stmtUpdate->execute([$updatedQuestion, $updatedAnswer, $updateQuestionId]);

    // Redirect to avoid resubmission on refresh
    header("Location: examquestions.php?exam_id=$exam_id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Paper</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="dashboard">
        <h1 class="heading">See Paper</h1>

        <section class="selected-title">
            <?php if (!empty($selectedExamTitle)): ?>
                <p>Selected Exam: <?php echo $selectedExamTitle; ?></p>
            <?php endif; ?>
        </section>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <button class="addbtn" onclick="toggleQuestionForm()">Add Question</button>

        <div id="overlay" class="overlay"></div>

        <section id="addQuestionForm" class="form-container" style="display: none;">
            <button class="closebtn" onclick="toggleQuestionForm()">Close <i class="fas fa-times"></i></button>
            <form action="" method="post" enctype="multipart/form-data" class="paper">
                <p> Add Question</p>
                <input type="text" name="question" class="box" placeholder="Question" maxlength="255" required><br><br>
                <textarea name="answer" class="box" rows="2" cols="50" maxlength="400" placeholder="Answer"
                    required></textarea><br><br><br>
                <input type="submit" name="submit" value="Add Question" class="btn"><br>
            </form>
        </section>


        <section id="updateQuestionForm" class="form-container" style="display: none;">
            <button class="closebtn" onclick="toggleUpdateForm()">Close <i class="fas fa-times"></i></button>
            <form action="" method="post" enctype="multipart/form-data" class="paper" id="updateForm">
                <p id="updateFormTitle">Update Question </p>
                <input type="hidden" id="updateQuestionId" name="update_question_id" value="">
                <textarea id="updateQuestion" name="update_question" class="box" rows="2" cols="50" maxlength="400"
                    placeholder="Update Question" required></textarea><br><br>
                <textarea id="updateAnswer" name="update_answer" class="box" rows="2" cols="50" maxlength="400"
                    placeholder="Update Answer" required></textarea><br><br><br>
                <input type="submit" name="update_submit" value="Update Question" class="btn"><br>
            </form>
        </section>


        <section class="papers-container">
            <?php
            foreach ($questions as $q): ?>
                <div class="Questionpaper">
                    <p class="head">Question <?php echo $q['question_number']; ?></p>
                    <p><?php echo $q['question']; ?></p>
                    <p class="head">Answer</p>
                    <p><?php echo $q['answer']; ?></p>
                    <div class="buttons-container">
                        <button class="update"
                            onclick="toggleUpdateForm(<?php echo $q['id']; ?>, '<?php echo $q['question']; ?>', '<?php echo $q['answer']; ?>')">Update</button>
                        <!-- Add a form for the delete button -->
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this question?')">
                            <input type="hidden" name="delete" value="<?php echo $q['id']; ?>">
                            <button class="delete" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <br><br><br><br><br>

    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var successMessage = document.querySelector('.success-message');

            if (successMessage) {
                setTimeout(function () {
                    successMessage.style.display = "none";
                }, 3000);
            }
        });

        function toggleForm(formId) {
            var body = document.body;
            var overlay = document.getElementById('overlay');
            var form = document.getElementById(formId);

            body.classList.toggle('overlayed');
            overlay.style.display = (overlay.style.display === 'none' || overlay.style.display === '') ? 'block' : 'none';
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        function toggleQuestionForm() {
            toggleForm('addQuestionForm');
        }

        function toggleUpdateForm(questionId, question, answer) {
            var updateForm = document.getElementById('updateForm');
            var updateQuestionId = document.getElementById('updateQuestionId');
            var updateQuestion = document.getElementById('updateQuestion');
            var updateAnswer = document.getElementById('updateAnswer');

            updateQuestionId.value = questionId;
            updateQuestion.value = question;
            updateAnswer.value = answer;

            toggleForm('updateQuestionForm');
        }
    </script>

</body>

</html>