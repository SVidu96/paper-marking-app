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

// Fetch exam data for the logged-in tutor
$stmt = $conn->prepare("SELECT * FROM exams WHERE tutor_id = ?");
$stmt->execute([$tutor_id]);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle exam deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $exam_id = $_POST['exam_id'];

    // Delete associated questions
    $stmtDeleteQuestions = $conn->prepare("DELETE FROM question_papers WHERE exam_id = ?");
    $stmtDeleteQuestions->execute([$exam_id]);

    // Perform deletion based on the $exam_id
    $stmtDelete = $conn->prepare("DELETE FROM exams WHERE exam_id = ?");
    $stmtDelete->execute([$exam_id]);

    // Redirect back to the manage_exams.php page after deletion
    header('Location: manageexams.php');
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
    <h1 class="heading">Manage Exams</h1>

    <section class="managetbl">
    <?php if (!empty($exams)): ?>
        <div class="table">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                <thead>
                    <tr>
                        <th class="text-left pl-4">Exam Title</th>
                        <th class="text-left">Time</th>
                        <th class="text-left">Description</th>  
                        <th colspan="2" class="text-center" width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td class="pl-4"><?php echo $exam['title']; ?></td>
                            <td><?php echo $exam['time']; ?></td>
                            <td><?php echo $exam['description']; ?></td>
                            <td>
                                <a href="examquestions.php?tutor_id=<?php echo $tutor_id; ?>&exam_id=<?php echo $exam['exam_id']; ?>" class="btn btn-info">Manage</a>
                            </td>
                            <td>
                                <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this exam and its questions?')">
                                    <input type="hidden" name="exam_id" value="<?php echo $exam['exam_id']; ?>">
                                    <input type="submit" name="delete" class="btn btn-danger ml-2" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
        <div class="exam_container">
        <p>No exams available at the moment.</p>
        </div>
    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
