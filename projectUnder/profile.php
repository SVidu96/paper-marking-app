<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   header('location:login.php');
}

// Fetch user details from the database
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$fetch_profile) {
   header('location:login.php');
}

// Fetch additional information like likes, comments, and bookmarks if needed
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
   <title>profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="profile">

      <h1 class="heading">Profile Details</h1>

      <div class="details">

         <div class="user">
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="User Image">
            <h3><?= $fetch_profile['name']; ?></h3>
            <a href="update.php" class="inline-btn">Update Profile</a>
            <h3>Email: <?= $fetch_profile['email']; ?></h3>
            <h3>Country: <?= $fetch_profile['studentNo']; ?></h3>
            <h3>City: <?= $fetch_profile['year']; ?></h3>

         </div>

      </div>

   </section>

   <!-- footer section starts  -->

   <footer class="footer">

      &copy; copyright @ 2022 by <span>mr. Rashmika Palamandadige</span> | all rights reserved!

   </footer>

   <!-- footer section ends -->

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>