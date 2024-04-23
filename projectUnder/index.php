<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
   header('location:home.php');
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
// $select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
// $select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
// $select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/home_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

<h1 class="heading">quick options</h1>

<div class="box-container">

   <div class="box">
      <div class="flex">
      <h3 class="title">Quick Grade your Examinations...</h3>
      <p>Welcome to our Online Paper Marking System, where grading becomes a breeze! At AssessChecker, 
         we've crafted a user-friendly platform that simplifies the evaluation process for educators and 
         students alike. With automated grading, customizable rubrics, and real-time analytics, our system 
         ensures efficiency without compromising precision. Security and confidentiality are paramount, 
         guaranteeing the protection of student data. Embrace collaboration with our intuitive interface, 
         fostering a seamless exchange of feedback. Whether you're a small institution or a large university, 
         our scalable and innovative system adapts to your grading needs. Join us in shaping the future of 
         assessment – sign up today for a smarter, more efficient approach to paper marking!</p>      
      </div>
      <a href="register.php" class="inline-btn">Sign Up</a>
   </div>

   <!-- <div class="box tutor">
      <h3 class="title">Become a Tutor</h3>
      <p>If you become a tuter please sign in as a admin.</p>
      <a href="admin/register.php" class="inline-btn">get started</a>
   </div>

</div> -->


</section>
<!-- <div class="index-image">
      <img src="images/Education.jpg" alt="">
   </div> -->

<!-- courses section ends -->












<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>