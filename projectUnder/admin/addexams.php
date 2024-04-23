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

if (isset($_POST['submit'])) {
   $title = $_POST['title'];
   $time = $_POST['time'];
   $description = $_POST['description'];
   $password = $_POST['password']; // New line to retrieve the password from the form

   $stmt = $conn->prepare("INSERT INTO exams (tutor_id, title, time, description, password) VALUES (?, ?, ?, ?, ?)"); // Modified query to include the password field
   $stmt->execute([$tutor_id, $title, $time, $description, $password]); // Added password parameter to execute

   $success_message = "Exam added successfully!";
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

   <section class="video-form">

      <h1 class="heading">Add Exams</h1>

      <?php if (isset($success_message)): ?>
         <div class="success-message" id="successMessage"><?php echo $success_message; ?></div>
      <?php endif; ?><br>

      <form action="" method="post" enctype="multipart/form-data">
         <p>Exam Title <span>*</span></p>
         <input type="text" name="title" maxlength="100" required placeholder="Enter exam title" class="box">
         <p>Exam Time Limit <span>*</span></p>
         <select name="time" class="box" required>
            <option value="" selected disabled>-- select time</option>
            <option value="1 hour">1 hour</option>
            <option value="2 hour">2 hour</option>
            <option value="3 hour">3 hour</option>
         </select>
         <p>Exam Password <span>*</span></p> <!-- New input field for exam password -->
         <input type="password" name="password" required placeholder="Enter exam password" class="box">
         <p>Description <span>*</span></p>
         <textarea name="description" class="box" required placeholder="Input exam description" maxlength="1000"
            cols="30" rows="4"></textarea>
         <input type="submit" name="submit" value="Submit" class="btn"><br>
      </form>

   </section>
   <br><br><br><br><br><br>

   <?php include '../components/footer.php'; ?>

   <script src="../js/admin_script.js"></script>

   <script>
      // Add JavaScript code to hide success message after 3 seconds
      document.addEventListener("DOMContentLoaded", function () {
         var successMessage = document.getElementById('successMessage');

         if (successMessage) {
            setTimeout(function () {
               successMessage.style.display = "none";
            }, 2000);
         }
      });
   </script>

</body>

</html>