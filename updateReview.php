<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("dbconn.php");
if($_SESSION["role"] != "Member") {
  header("Location: index.php");
  exit;
}
$UserID = $_GET['user'];
$ReviewID = $_GET['review'];

if (!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
  header("Location: index.php"); // Redirect to login page if not logged in
  exit;
}
  // Check if the book_id parameter exists in the URL
  if (isset($_GET['user']) && isset($_GET['review'])  ) {
  
    // Query the database to check if the book_id exists
    $query = "SELECT * FROM review_tbl WHERE ReviewID = $ReviewID AND UserID = $UserID"; // Modify this query as per your database structure
  
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
$errorAlert = "";

  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserId = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);

  $sqlReviews = "SELECT review_tbl.ReviewID, review_tbl.UserID, review_tbl.ReviewDescription, review_tbl.BookRating, review_tbl.ReviewAnonymous, review_tbl.DateReview, user_tbl.Username, book_tbl.Title, book_tbl.BookImage, author_tbl.AuthorName 
  FROM review_tbl
  INNER JOIN user_tbl ON review_tbl.UserID = user_tbl.UserID 
  INNER JOIN book_tbl on review_tbl.BookID = book_tbl.BookID
  INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
  WHERE user_tbl.UserID = $UserID AND ReviewID = $ReviewID";
  $resultReviews = mysqli_query($dbconn, $sqlReviews);
  $row_Review = mysqli_fetch_assoc($resultReviews);

  if (isset($_POST["update_review"])) {
    $review = strip_tags(mysqli_real_escape_string($dbconn, $_POST["review"]));
    $rating = strip_tags(mysqli_real_escape_string($dbconn, $_POST["rating"]));

    // Perform validation for the review (ensure it's not empty)
    if (empty($review)) {
        $error = "Review field is required..";
        $errorAlert = "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
                    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>";
    } else {
        // Update the review and rating in the database
        $sql = "UPDATE review_tbl SET ReviewDescription = '$review', BookRating = '$rating' WHERE ReviewID = $ReviewID AND UserID = $UserID";
        if (mysqli_query($dbconn, $sql)) {
            // Successfully updated the review
            echo '<script>alert("Review updated successfully."); window.location.href="myReviews.php?id=' . $UserID . '";</script>';
        } else {
            // Handle the case when the update query fails
            $error_message = "Error updating the review: " . mysqli_error($dbconn);
        }
    }
}

// Display success or error messages
if (isset($success_message)) {
    echo '<div class="alert alert-success">' . $success_message . '</div>';
} elseif (isset($error_message)) {
    echo '<div class="alert alert-danger">' . $error_message . '</div>';
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

  <!-- select CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- date picker -->
  <!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <style>
    .pagination{
      position: relative;
      left: 40%;
      bottom: 1%;
      display: inline;
      letter-spacing:10px;
    }
    .container{
        margin-left:10px;
        margin-top:10px;

    }
    .btn-add{
        margin-top:10px;
    }
    /* Adjust the width and font size of the input field */
    input[type="text"] {
        width: 100%; /* Adjust to your desired width */
        font-size: 16px; /* Adjust to your desired font size */
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

        <?php
            echo '<li class="nav-item">';
            echo '<a class="nav-link collapsed" href="myReviews.php?id=' . $user_id . '">';
            echo '<i class="ri-chat-3-line"></i><span>Review & Ratings</span>';
            echo '</a>';
            echo '</li>'; // End Idea Nav
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
      <!-- <h1>Add Book</h1> -->
      <nav>
        <!-- <ol class="breadcrumb"style="width: 200px;">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="d-flex align-items-center justify-content-center vh-80">
    <?= $errorAlert ?>

    <div class="card mx-auto" style="width: 500px;">
    <!-- <div class="card-header style="max-width:600px;"">
        <h5 class="card-title">Add New Book</h5>
    </div> -->
    <div class="card-header" style="font-size:27px; font-weight:600; color:#302119; text-align:left;">
                        Update Review
                    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3 mt-2">
                <label for="description" class="form-label">Review:</label>
                <textarea class="form-control" id="review" name="review" rows="4" cols="10"><?php echo htmlentities($row_Review['ReviewDescription']) ?></textarea>
            </div>

            <div class="mb-3">
    <label for="genre" class="form-label">Rating:</label>
    <select class="form-control" id="rating" name="rating">
        <?php
        $sql = "SELECT BookRating FROM review_tbl WHERE ReviewID = $ReviewID AND UserID = $UserID ";
        $result = mysqli_query($dbconn, $sql);

        // Map numeric ratings to star ratings
        $ratings = [
            1 => '★',
            2 => '★★',
            3 => '★★★',
            4 => '★★★★',
            5 => '★★★★★',
        ];

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $selectedRating = $row['BookRating'];
        } else {
            // Default to the lowest rating if no rating is found
            $selectedRating = 1;
        }

        // Loop through the ratings array and create options
        foreach ($ratings as $ratingValue => $ratingLabel) {
            $selected = ($selectedRating == $ratingValue) ? 'selected' : '';
            echo '<option value="' . $ratingValue . '" ' . $selected . '>' . $ratingLabel . '</option>';
        }
        ?>
    </select>
</div>

            <div class="mb-3">
                <label for="ISBN" class="form-label">Book Title:</label>
                <input type="text" class="form-control" id="Title" name="title" value="<?php echo htmlentities($row_Review['Title']) ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="ISBN" class="form-label">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlentities($row_Review['AuthorName']) ?>" readonly>
            </div>
             
   
            <div class="text-center">
                <button type="submit" name="update_review" class="btn btn-primary">Update Review</button>
                <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                <a href="myReviews.php" class="btn btn-secondary">Cancel </a>
            </div>
        </form>
    </div>
</div>
                </div>
                <script>
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
