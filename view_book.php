<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("dbconn.php");
if($_SESSION["role"] != "Member") {
  header("Location: index.php");
  exit;
}
if (!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
  header("Location: index.php"); // Redirect to login page if not logged in
  exit;
}

  $user_id = $_SESSION["userid"];

  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserID = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);

  // Check if the book_id parameter exists in the URL
if (isset($_GET['book_id'])) {
  $book_id = $_GET['book_id'];

  // Query the database to check if the book_id exists
  $query = "SELECT * FROM book_tbl WHERE BookID = $book_id"; // Modify this query as per your database structure

  $result = $dbconn->query($query);

  // If the book is not found, redirect to the home page
  if ($result->num_rows == 0) {
      header("Location: home.php"); // Change "home_page.php" to your actual home page URL
      exit(); // Make sure to exit after redirection
  }

} else {
  // Handle the case where 'book_id' is not in the URL
  header("Location: home.php"); // Change "home_page.php" to your actual home page URL
  exit(); // Make sure to exit after redirection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>H-Library</title>
  <meta content="" name="description">
  <meta content="" name="keywords"> 
  <!--JQuery Library-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Favicons -->
  <!-- <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link rel="icon" href="images/H.png" type="image/x-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <!-- <link href="assets/style.css" rel="stylesheet"> -->

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <script src="https://use.fontawesome.com/fe459689b4.js"></script>

  <style>
    .pagination{
      position: relative;
      left: 40%;
      bottom: 1%;
      display: inline;
      letter-spacing:10px;
    }


        .book-card {
            border: 1px solid #ccc; /* Change the border color and width */
            border-radius:10px;
            margin-top:10px;
        }

        .book-image {
            max-width: 100%; /* Adjust the image size as needed */
            height: auto;
        }
        .review-cards {
    margin-top: 15px; /* Adjust the value to match the original height of your review cards */
}
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="home.php" class="logo d-flex align-items-center">
        <img src="assets/img/H-Logo.png" alt="H-Library">
        <span class="d-none d-lg-block">H-Library</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
      <li class="nav-item dropdown" id="notification-count">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number" id="notification-badge"></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header" >
            <!-- You have <span class="badge rounded-pill bg-primary p-2 ms-2">View all</span> -->
            You have <span id="notification-count" class="badge rounded-pill bg-primary p-2 ms-2" style="margin-right:5px;">0</span> new notifications
            <hr style="margin-bottom:0px;">
          </li>
        <li>
            <hr class="dropdown-divider">
        </li>
    </ul>
</li>
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
            <img src=<?php echo htmlentities($row_User['ProfileImage']); ?> alt="Profile" class="rounded-circle">

            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlentities($row_User['Username']) ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
            <h6><?php echo htmlentities($row_User['Username']) ?></h6> 
              <span><?php echo $row_User['UserRole'] ?></span> 
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <a class="dropdown-item d-flex align-items-center" href="member_profile.php">
                  <i class="bi bi-person"></i>
                  <span>My Profile</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
      
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
              <a class="nav-link collapsed" href="home.php">
                  <i class="ri-home-5-line"></i><span>Home</span>
              </a>
            </li><!-- End Idea Nav -->

            <li class="nav-item">
              <a class="nav-link collapsed" href="books.php">
                  <i class="ri-book-3-line"></i><span>Books</span>
              </a>
            </li><!-- End Idea Nav -->

            <li class="nav-item">
              <a class="nav-link collapsed" href="cart.php">
                  <i class="ri-shopping-cart-2-line"></i><span>Cart</span>
              </a>
            </li><!-- End Idea Nav -->

            <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#reservations-nav" data-bs-toggle="collapse" href="#">
          <i class="ri-reserved-line"></i><span>Reservations</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="reservations-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="current_reservation.php">
            <span>Current Reservations</span>
            </a>
          </li>
          <li>
            <a href="past_reservation.php">
            <span>Past Reservations</span>   
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#borrowed-nav" data-bs-toggle="collapse" href="#">
        <i class="ri-book-read-line"></i></i><span>Borrowed</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="borrowed-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="current_issued.php">
            <span>Current Issued Books</span>
            </a>
          </li>
          <li>
            <a href="previous_issued.php">
            <span>Previous Issued Books</span>   
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      
            <!-- <li class="nav-item">
              <a class="nav-link collapsed" href="index.php">
                  <i class="ri-reserved-line"></i><span>Reservations</span>
              </a>
            </li> -->
            <!-- End Idea Nav -->

            <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#penalty-nav" data-bs-toggle="collapse" href="#">
        <i class="ri-wallet-3-line"></i></i><span>Penalty</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="penalty-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="paid_penalty.php">
            <span>Paid penalties</span>
            </a>
          </li>
          <li>
            <a href="unpaid_penalty.php">
            <span>Unpaid penalties</span>   
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->


            <!-- <li class="nav-item">
              <a class="nav-link collapsed" href="home.php">
                  <i class="ri-chat-3-line"></i><span>Review & Ratings</span>
              </a>
            </li> -->
            <?php
            echo '<li class="nav-item">';
            echo '<a class="nav-link collapsed" href="myReviews.php?id=' . $user_id . '">';
            echo '<i class="ri-chat-3-line"></i><span>Review & Ratings</span>';
            echo '</a>';
            echo '</li>'; 
        ?>


            <!-- <?php
              echo '<li class="nav-item">';
              echo '<a href="EditIdea.php?id=' .$user_id.'" class="nav-link collapsed" data-bs-target="#statistics-nav;">';
              echo '<i class="bi bi-pencil"></i><span>Edit Idea</span>';
              echo '</a>';
              echo '</li>';
            ?>
     
            <?php
                if($_SESSION['role'] == "Admin"){ //staff cannot see this
                echo'<li class="nav-heading">Pages</li>';

                echo'<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="ManageUser_admin.php">';
                        echo '<i class="bi bi-people"></i>';
                        echo '<span>Manage User</span>';
                    echo '</a>';
                echo '</li><!-- End Manage User Page Nav -->';

                echo '<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="ManageIdea_admin.php">';
                        echo '<i class="bi bi-chat-left-text"></i>';
                        echo '<span>Manage Idea</span>';
                    echo '</a>';
                echo '</li><!-- End Manage Idea Page Nav -->';
                }
            ?> -->
            
        </ul>

    </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Book Detail</h1>
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title  -->

    <section class="section profile">

    <?php
           
              $book_id = $_GET['book_id'];
              
              // Query to fetch books for the selected genre
              $sql = "SELECT book_tbl.Title, publisher_tbl.PublisherName, genre_tbl.GenreName, book_tbl.ISBN, book_tbl.BookDescription, book_tbl.BookImage, book_tbl.PublishedYear,book_tbl.Pages, author_tbl.AuthorName 
              FROM book_tbl 
              INNER JOIN genre_tbl ON book_tbl.GenreID = genre_tbl.GenreID
              INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID 
              INNER JOIN publisher_tbl ON book_tbl.PublisherID = publisher_tbl.PublisherID 
              WHERE book_tbl.BookID = '$book_id';"; 
          
              $viewResult = mysqli_query($dbconn, $sql);
          
              if ($viewResult) {
                  // Loop through the results and display book information
                  while ($row = mysqli_fetch_assoc($viewResult)) {
                      echo '<div class="card mb-3" style="max-height: 1080px; margin-left:5px;">';
                      echo '<div class="row g-0">';
                      echo '<div class="col-md-3">';
                      echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="img-fluid rounded-start" style="height:100%;">';
                      echo '</div>';
                      echo '<div class="col-md-9">';
                      echo '<div class="card-body">';
                      echo '<h5 class="card-title" style="color:#302119; font-size: 1.25rem;">' . $row['Title'] . '</h5>';
                      echo '<p class="card-text"><b>Description:</b> ' . $row['BookDescription'] . '</p>';
                      echo '<p class="card-text"><b>Publication Year:</b> ' . $row['PublishedYear'] . '</p>';
                      echo '<p class="card-text"><b>Genre:</b> ' . $row['GenreName'] . '</p>';
                      echo '<p class="card-text"><b>ISBN:</b> ' . $row['ISBN'] . '</p>';
                      echo '<p class="card-text"><b>Pages:</b> ' . $row['Pages'] . '</p>';
                      echo '<p class="card-text"><b>Author:</b> ' . $row['AuthorName'] . '</p>';
                      echo '<p class="card-text"><b>Published By:</b> ' . $row['PublisherName'] . '</p>';

                      // echo '<h4><a href="view_book.php?book_id=' . $row['BookID'] . '" class="text-decoration-none text-dark">' . $row['Title'] . '</a></h4>';
                      echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">';
                      echo 'Write Review';
                      echo '</button>';
                      
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                  }

                  // Close the database connection
                  // mysqli_close($dbconn);
              } else {
                  echo "Error: " . mysqli_error($dbconn);
              }
          ?>

          <?php
          // Include your database connection code (dbconn.php)
          require("dbconn.php");

          if (isset($_GET['book_id'])) {
              $book_id = $_GET['book_id'];

              // Update the view count for the book
              $viewsql = "UPDATE book_tbl SET ViewCount = ViewCount + 1 WHERE BookID = $book_id";

              if (mysqli_query($dbconn, $viewsql)) {
                  // Successfully updated the view count
              } else {
                  // Error updating the view count
                  echo "Error: " . mysqli_error($dbconn);
              }

              // Now, fetch and display the book details
              // Add your code here to display the book details
          } else {
              // Handle the case when 'book_id' is not set in the URL
              echo "Book not found.";
          }
          ?>


<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel"><strong>Write a Review</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Review and Rating Form -->
                <form action="" method="post">
                    <?php
                  require("dbconn.php");

                  $book_id = $_GET['book_id'];
                  if (isset($_POST["submitReview"])) {
                    $user_id = $_SESSION["userid"];
                    $userreview = $_POST["reviewText"];
                    $userrating = $_POST["rating"];
                    $review = htmlentities($userreview);
                    $anonymous = isset($_POST["anonymous"]);
                    $sqlReview = "INSERT INTO review_tbl (BookID, UserID, ReviewDescription, BookRating, ReviewAnonymous) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($dbconn, $sqlReview);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "iissi", $book_id, $user_id, $review, $userrating, $anonymous);
                        if (mysqli_stmt_execute($stmt)) {
                          echo '<script>alert("Review submitted"); window.location.href="view_book.php?book_id=' . $book_id . '";</script>';

                            //  header("Location: view_book.php?book_id=" . $book_id);
                            //  exit();
                        } else {
                            // echo '<script>alert("CANT ADD REVIEW")</script>';
                          }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo '<script>alert("Error in prepared statement")</script>';
                    }
                    
                  }
                ?>
             
                    <div class="form-group">
                        <label for="reviewText">Review:</label>
                        <textarea class="form-control" name="reviewText" rows="3" placeholder="Share your thoughts..." style="margin-top:5px;" required></textarea>
                    </div>
                    <div style="margin-top:5px;">
                    <input type="checkbox" id="anonymous" name="anonymous" class="form-check-input" value="1">
                    <label for="anonymous" class="form-check-label">Review anonymously</label>
                    </div>
                    <div class="form-group">
                        <label for="ratingSelect" style="margin-top:10px;">Rating:</label>
                        <select class="form-control" id="ratingSelect" name="rating" style="margin-top:5px;">
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submitReview">Review</button>
                  </div>
                </form>
                <!-- End Review and Rating Form -->
            </div>

        </div>
    </div>
</div>

<!-- Review Cards Section style="max-width: 1080px;" added padding-top-->
<!-- <div class="container mt-4"> -->
    <div class="pagetitle" style="position: relative; margin-top:50px;">
    <h1 style="display:inline-block;">Reviews</h1>
    
    <form id="filterForm" style="position: absolute; top: 0; right: 0;">
        <select name="filter" class="form-control" id="filter" onchange="filterReviews()">
        <option value="">Filter By Ratings</option> 
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>
    </form>
</div>

  </div>
    <div id="review-cards" class="review-cards">
    <?php
      require("dbconn.php");

      $book_id = $_GET['book_id'];
      $user_id = $_SESSION["userid"];

      // Fetch reviews for the specific book
      $sqlReviews = "SELECT review_tbl.ReviewID, review_tbl.ReviewDescription, review_tbl.BookRating, review_tbl.ReviewAnonymous, review_tbl.DateReview, user_tbl.Username 
                    FROM review_tbl
                     INNER JOIN user_tbl ON review_tbl.UserID = user_tbl.UserID 
                     WHERE BookID = $book_id AND review_tbl.showReview= 0";
      $resultReviews = mysqli_query($dbconn, $sqlReviews);

      if (mysqli_num_rows($resultReviews) > 0) {
          while ($row = mysqli_fetch_assoc($resultReviews)) {
              $reviewId = $row["ReviewID"];
              $reviewDescription = $row["ReviewDescription"];
              $bookRating = $row["BookRating"];
              $reviewAnonymous = $row["ReviewAnonymous"];
              $dateReview = $row["DateReview"];
              $username = $row["Username"];
              $formattedDateTime = date('d-m-Y H:i', strtotime($dateReview));
              // Convert the numeric rating to ★ icons
              $ratingStars = str_repeat("★", $bookRating) . str_repeat("☆", 5 - $bookRating);
              $likeidea_query = "SELECT COUNT(*) AS like_count FROM review_likes WHERE ReviewID = '$reviewId'";
              $likeidea_result = mysqli_query($dbconn, $likeidea_query);
              $likeidea_row = mysqli_fetch_assoc($likeidea_result);
              $dislikeidea_query = "SELECT COUNT(*) AS dislike_count FROM review_dislikes WHERE ReviewID = '$reviewId'";
              $dislikeidea_result = mysqli_query($dbconn, $dislikeidea_query);
              $dislikeidea_row = mysqli_fetch_assoc($dislikeidea_result);
              // Display the review and rating
              echo '<div class="card mb-3">';
              echo '<div class="card-body">';
              echo '<h5 class="card-title">' . ($reviewAnonymous == 1 ? "Anonymous" : $username) . '</h5>';
              echo '<p class="card-text">' . $ratingStars . '</p>';
              echo '<p class="card-text">' . $reviewDescription . '</p>';
              echo '<p class="card-text"><small class="text-muted">' . $formattedDateTime . '</small></p>';
              echo '<button class="like-button btn btn-primary" style="background-color: darkcyan; margin: 0 5px 0 0;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '"><i class="ri-thumb-up-fill"></i></button> <span id="like-count-number" style="margin-left: 5px;">' . $likeidea_row['like_count'] . '</span>';
              echo '<button class="dislike-button btn btn-primary" style="background-color: darkcyan; margin: 0 5px 0 20px;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '"><i class="ri-thumb-down-fill"></i></button> <span id="dislike-count-number" style="margin-left: 5px;">' . $dislikeidea_row['dislike_count'] . '</span>';
              // echo '<p class="card-text" id="likes-count">Likes: <span id="like-count-number">' . $likeidea_row['like_count'] . ' Likes</span></p>';
              // echo '<a href="reportReview.php?review='.$reviewId. '&user=' .$user_id.'">'; echo '<i class="bi bi-flag-fill report-button btn btn-danger position-absolute top-0 end-0" style="margin:10px 10px 0 0;">'; echo '</i>'; echo '</a>';
              echo '<button class="report-button btn btn-danger position-absolute top-0 end-0" style=" margin: 10px 10px 0 0;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '">';
              echo '    <i class="bi bi-flag-fill "></i>';
              echo '</button>';
              
              echo '</div>';
              echo '</div>';
          }
      } else {
          echo '<p>No reviews available for this book.</p>';
      }

      mysqli_close($dbconn);
      ?>
        
    </div>
<!-- </div> -->


<script>
function filterReviews() {
  console.log('Filter changed'); // Add this line
    let filter = $('#filter').val();
    
    // Check if the selected filter is not "0" (the placeholder)
    if (filter !== "0") {
        // Send an AJAX request to fetch filtered reviews
        $.ajax({
            url: 'fetch_reviews.php',
            type: 'GET',
            data: { book_id: <?php echo $book_id; ?>, filter: filter, user_id: <?php echo $user_id; ?> },
            success: function(data) {
                $('#review-cards').html(data);
            },
            error: function() {
                alert('An error occurred while fetching reviews.');
            }
        });
    }
}
function updateLikeCount(reviewId) {
  console.log('updateLikeCount called'); // Add this line

  $.get('get_like_count.php', { review_id: reviewId, timestamp: new Date().getTime() }, function(likeCount) {
    console.log('Received likeCount: ' + likeCount); // Add this line

    $('#like-count-number').text(likeCount);
});

}

// Attach a click event handler to the like button
$(document).on('click', '.like-button', function() {
  console.log('Like button clicked'); // Add this line

    var reviewId = $(this).data('review-id');
    var userId = $(this).data('user-id');

    // Send an AJAX request to like_review.php to record the like action
    $.post('like_review.php', { review_id: reviewId, user_id: userId }, function(response) {
      console.log('Received like response: ' + response); // Add this line

    if (response.trim() === 'Liked') {
        // The like action was successful
        // Now, fetch the updated like count and update the UI
        updateLikeCount(reviewId); // Update the like count in real-time
            // Also, fetch the updated dislike count and update the UI
            updateDislikeCount(reviewId);
    } else if (response.trim() === 'AlreadyLiked') {
        // Handle the case where the user has already liked this post
        alert('You have already liked this review.');
    } else {
        // Handle other cases or errors
        alert('Error: ' + response);
    }
});

});

// Attach a click event handler to the dislike button
$(document).on('click', '.dislike-button', function() {
    var reviewId = $(this).data('review-id');
    var userId = $(this).data('user-id');

    // Send an AJAX request to dislike_review.php to record the dislike action
    $.post('dislike_review.php', { review_id: reviewId, user_id: userId }, function(response) {
        if (response.trim() === 'Disliked') {
            // The dislike action was successful
            // Now, fetch the updated dislike count and update the UI
            updateDislikeCount(reviewId); // Update the dislike count in real-time
            updateLikeCount(reviewId); // Update the like count in real-time

        } else if (response.trim() === 'AlreadyDisliked') {
            // Handle the case where the user has already disliked this post
            alert('You have already disliked this review.');
        } else {
            // Handle other cases or errors
            alert('Error: ' + response);
        }
    });
});

function updateDislikeCount(reviewId) {
    $.get('get_dislike_count.php', { review_id: reviewId, timestamp: new Date().getTime() }, function(dislikeCount) {
      console.log('Received dislikeCount: ' + dislikeCount);  
      $('#dislike-count-number').text(dislikeCount); // Update the dislike count in the UI
    });
}

$(document).ready(function () {
    // Function to fetch and display notifications
// Function to fetch and display notifications
function fetchNotifications() {
    $.get('get_notifications.php', function (data) {
        try {
            var notifications = JSON.parse(data);

            // Update the notification count
            var notificationCount = notifications.length;
            console.log('notificationCount:', notificationCount);

            $('#notification-count .badge').text(notificationCount);

            // Update the notification dropdown
            var notificationsList = $('.notifications li:not(.dropdown-header, .divider)');
            notificationsList.empty();

            for (var i = 0; i < notifications.length; i++) {
                var notification = notifications[i];
                var notificationItem = $('<li class="notification-item">');
                notificationItem.html('<i class="bi bi-exclamation-circle text-warning"></i>' +
                    '<div>' +
                    '<h4>Reservation Cancelled</h4>' +
                    '<p>' + notification.message + '</p>' +
                    '</div>');

                notificationsList.append(notificationItem);
            }
        } catch (e) {
            console.error('Error parsing JSON response:', e);
        }
    });
}

    // Fetch notifications every 30 seconds (adjust the interval as needed)
    fetchNotifications();
    setInterval(fetchNotifications, 30000); // 30 seconds
});

// Attach a click event handler to the report button
$(document).on('click', '.report-button', function() {
    var reviewId = $(this).data('review-id');
    var userId = $(this).data('user-id');

    // Send an AJAX request to reportReview.php to report the review
    $.post('reportReview.php', { review_id: reviewId, user_id: userId }, function(response) {
        if (response.trim() === 'Reported') {
            alert("Review reported successfully.");
            location.reload(); // Reload the page after reporting
        } else if (response.trim() === 'AlreadyReported') {
            alert("You have already reported this review.");
        } else {
            alert('Error: ' + response);
        }
    });
});

  </script>
  
      
          </section>


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>H-Library 2023</span></strong>. All Rights Reserved
    </div>
  </footer>
  <!-- End Footer -->

 

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  
</body>

</html>
