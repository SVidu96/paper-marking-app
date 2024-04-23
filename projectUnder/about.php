<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   // header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/home_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about.jpg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>Discover the future of efficient and streamlined paper grading with our Online Paper Marking System. 
            Our About Us page provides insight into the innovative platform that is revolutionizing the way educators assess and provide feedback on academic papers.
             Learn about our mission to enhance the grading process, saving valuable time for both teachers and students.</p>
         <!-- <a href="courses.html" class="inline-btn">our courses</a> -->
      </div>

   </div>

   <diV class="content-2">
      <p>Explore the story behind our development, understanding the driving force that led to the creation of this cutting-edge tool. 
         Delve into the features that set our system apart, from advanced AI-assisted grading to seamless collaboration between instructors. 
         Discover how our commitment to excellence in education led to the creation of a user-friendly interface that simplifies the grading workflow.</p>
         <p>Meet the passionate team behind the Online Paper Marking System, dedicated to shaping the future of education through technology. 
            Gain insight into our values, principles, and the tireless commitment to fostering a learning environment that encourages growth and success.</p>
         <p>Whether you're an educator seeking a more efficient grading solution or a student interested in understanding the benefits of this innovative system, 
            our About Us page provides a comprehensive overview of the vision and values that drive our Online Paper Marking System. Join us on the journey to transform the grading experience and elevate the educational landscape.</p>
   </div>

</section>

<!-- reviews section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>