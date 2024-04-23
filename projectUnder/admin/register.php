<?php

include '../components/connect.php';

if (isset($_POST['submit'])) {

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   // $profession = $_POST['profession'];
   // $profession = filter_var($profession, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $country = $_POST['country'];
   $country = filter_var($country, FILTER_SANITIZE_STRING);
   $city = $_POST['city'];
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id() . '.' . $ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/' . $rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);

   if ($select_tutor->rowCount() > 0) {
      $error[] = 'email already taken!';
   } else {
      if ($pass != $cpass) {
         $error[] = 'confirm passowrd not matched!';
      } else {
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, email, password, country, city, image) VALUES(?,?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $email, $cpass, $country, $city, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         // $message[] = 'new tutor registered! please login now';

         $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
         $verify_tutor->execute([$email, $pass]);
         $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

         if ($verify_tutor->rowCount() > 0) {
            setcookie('tutor_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
            header('location: ../admin/login.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body style="padding-left: 0;">

   <!-- register section starts  -->

   <section class="form-container">

      <form class="register" action="" method="post" enctype="multipart/form-data">
         <h3>register new</h3>
         <?php
         if (isset($message)) {
            foreach ($message as $message) {
               echo '
            <div class="message form">
               <span>' . $message . '</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
               ';
            }
         }
         ?>
         <div class="flex">
            <div class="col">
               <p>your name <span>*</span></p>
               <input type="text" name="name" placeholder="eneter your name" maxlength="50" required class="box">
               <!-- <p>your profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- select your profession</option>
               <option value="developer">developer</option>
               <option value="desginer">desginer</option>
               <option value="musician">musician</option>
               <option value="biologist">biologist</option>
               <option value="teacher">teacher</option>
               <option value="engineer">engineer</option>
               <option value="lawyer">lawyer</option>
               <option value="accountant">accountant</option>
               <option value="doctor">doctor</option>
               <option value="journalist">journalist</option>
               <option value="photographer">photographer</option>
            </select> -->
               <p>your email <span>*</span></p>
               <input type="email" name="email" placeholder="enter your email" maxlength="20" required class="box">
               <p>your country <span>*</span></p>
               <input type="text" name="country" placeholder="enter your country" maxlength="20" required class="box">
            </div>
            <div class="col">
               <p>your password <span>*</span></p>
               <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
               <p>confirm password <span>*</span></p>
               <input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required
                  class="box">
               <p>your city <span>*</span></p>
               <input type="text" name="city" placeholder="enter your city" maxlength="20" required class="box">
            </div>
         </div>
         <p>select pic <span>*</span></p>
         <input type="file" name="image" accept="image/*" required class="box">
         <p class="link">already have an account? <a href="login.php">login now</a></p>
         <input type="submit" name="submit" value="register now" class="btn">
      </form>

   </section>

   <!-- registe section ends -->












   <script>

      let darkMode = localStorage.getItem('dark-mode');
      let body = document.body;

      const enabelDarkMode = () => {
         body.classList.add('dark');
         localStorage.setItem('dark-mode', 'enabled');
      }

      const disableDarkMode = () => {
         body.classList.remove('dark');
         localStorage.setItem('dark-mode', 'disabled');
      }

      if (darkMode === 'enabled') {
         enabelDarkMode();
      } else {
         disableDarkMode();
      }

   </script>

</body>

</html>