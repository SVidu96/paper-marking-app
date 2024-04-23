<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
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
function getGrade($score)
{
    if ($score == "Pending") {
        return "N/A";
    } elseif ($score >= 90) {
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
        <section class="manageusertbl">
            <?php
            $stmtGrades = $conn->prepare("SELECT u.studentNo, u.name,e.title, ua.user_id, ua.exam_id, AVG(ua.score) as total_score FROM user_answers ua JOIN exams e ON ua.exam_id = e.exam_id JOIN users u ON ua.user_id = u.id AND ua.exam_id = ? GROUP BY user_id,exam_id;");
            $stmtGrades->execute([$exam_id]);
            $totalGrades = $stmtGrades->rowCount();
            $grades = $stmtGrades->fetchAll(PDO::FETCH_ASSOC);

            if ($totalGrades > 0) {
                echo "<h1>Exam : <span>" . $grades[0]['title'] . "</span></h1>";
                ?>
                <div class="table">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                        <thead>
                            <tr>
                                <th class="text-left">Student Number</th>
                                <th class="text-left">Student Name</th>
                                <th class="text-left">Total Score</th>
                                <th class="text-left">Grade</th>
                            </tr>
                        </thead><?php
                        foreach ($grades as $grade) {
                            if (is_null($grade['total_score'])) {
                                $score = "Pending";
                            } else {
                                $score = number_format($grade['total_score'], 2);
                            }

                            ?>

                            <tbody>
                                <tr>
                                    <td><?php echo $grade['studentNo']; ?></td>
                                    <td><?php echo $grade['name']; ?></td>
                                    <td><?php echo $score ?></td>
                                    <td><?php echo getGrade($score); ?></td>
                                </tr>
                            </tbody>

                            <?php
                        }
                        ?>
                    </table>
                </div><?php
            } else {
                echo '<p class="empty">No grades found!</p>';
            }
            ?>
        </section>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>