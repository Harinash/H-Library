<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("dbconn.php");
if($_SESSION["role"] != "Member") { /* librarian cant go in */
  header("Location: index.php");
  exit;
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer;

$user_id = $_SESSION["userid"];

  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserID = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);

  // Calculate the current date
  $currentDate = date('Y-m-d');

  // Query to select reservations that are active and past the pickup date
  $sqlSelectReservations = "SELECT ReservationID, UserID, BookID, CopyID 
                            FROM reservation_tbl 
                            WHERE UserID = $user_id
                            AND Status = 'Active' 
                            AND PickupDate < '$currentDate'";

  $resultSelectReservations = mysqli_query($dbconn, $sqlSelectReservations);

  if ($resultSelectReservations) {
      while ($rowReservation = mysqli_fetch_assoc($resultSelectReservations)) {
          $reservationID = $rowReservation['ReservationID'];
          $bookID = $rowReservation['BookID'];
          $copyID = $rowReservation['CopyID'];

          // Update reservation status to "Cancelled"
          $sqlUpdateReservation = "UPDATE reservation_tbl SET Status = 'Cancelled', CancelAt=NOW() WHERE ReservationID  = $reservationID";

          // Update book copy availability and status
          $sqlUpdateCopy = "UPDATE bookcopy_tbl SET availability = 1, Status = 'Available' WHERE CopyID = $copyID";

          mysqli_query($dbconn, $sqlUpdateReservation);
          mysqli_query($dbconn, $sqlUpdateCopy);
      }
  } else {
      echo "Error selecting reservations: " . mysqli_error($dbconn);
      // echo '<script>alert("Error selecting reservation")</script>';
  }

  $sqlSelectBorrow = "SELECT borrow_tbl.ReservationID, borrow_tbl.UserID, borrow_tbl.CopyID, book_tbl.Title, borrow_tbl.BorrowDate, borrow_tbl.DueDate 
                          FROM borrow_tbl
                          INNER JOIN reservation_tbl on borrow_tbl.ReservationID = reservation_tbl.ReservationID
                          INNER JOIN book_tbl on reservation_tbl.BookID = book_tbl.BookID
                          WHERE StatusReturn = 'Unreturned' 
                          AND borrow_tbl.DueDate = DATE_ADD('$currentDate', INTERVAL 1 DAY) AND borrow_tbl.reminderEmail = 0 AND borrow_tbl.UserID = $user_id";

$resultSelectBorrow = mysqli_query($dbconn, $sqlSelectBorrow);

while ($row = mysqli_fetch_assoc($resultSelectBorrow)) {
  $userID = $row['UserID'];
  $copyID = $row['CopyID'];
  $reservationID = $row['ReservationID'];
  $title = $row['Title'];
  $borrow = $row['BorrowDate'];
  $due = $row['DueDate'];

  // Get the user's email address from your user table
  $sqlSelectUserEmail = "SELECT UserEmail, Username FROM user_tbl WHERE UserID = $userID";
  $resultSelectUserEmail = mysqli_query($dbconn, $sqlSelectUserEmail);
  $resultSelectUserName = mysqli_query($dbconn, $sqlSelectUserEmail);
  $userEmail = mysqli_fetch_assoc($resultSelectUserEmail)['UserEmail'];
  $userName = mysqli_fetch_assoc($resultSelectUserName)['Username'];


  // Compose your email message
  $subject = "Book Return Reminder";
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'prohari35@gmail.com'; // Your Gmail email address
    $mail->Password = 'xham bvni mjgw mnow'; // Your Gmail App Password or regular Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('prohari35@gmail.com', 'H-Library');
    $mail->addAddress($userEmail, $userName);
    $mail->isHTML(true);

    $message = '<html>
    <head>
    <style>
      .bold-center {
        font-weight: bold;
        text-align: center;
      }
    </style>
    </head>
    <body>
      <h3 class="bold-center">Dear member,</h3>
      <h3 class="bold-center">This is a reminder to return the book ' . $title . ' that is due tomorrow. Please return the book on time to avoid any late penalty fees.</h3>
      <h3 class="bold-center">Thank you.</h3>
      
      <br>
      <!-- Additional borrow details can be provided here -->
      <p> This is your borrowed book details:<p>
      <p> Book Title : ' . $title. '</p>
      <p> Borrowed Date : ' . $borrow. '</p>
      <p> Due Date : ' . $due. '</p>
    </body>
    </html>';
    
    $mail->Subject = 'H-Library Reminder';
    $mail->Body = $message;

    $mail->send();
    // echo 'Reminder email sent successfully.';

    // echo '<script>alert("Reminder email sent successfully.");</script>';
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    echo '<script>alert("Mailer Error: ' . $mail->ErrorInfo . '")</script>';
  }
  // $message = "Dear user,\n\nThis is a reminder to return the book (Book ID: $bookID) that is due tomorrow. Please return the book on time to avoid any late fees.\n\nThank you.";

// After successfully sending the email, update the database to record that a reminder has been sent
$updateReminderSql = "UPDATE borrow_tbl SET reminderEmail = 1 WHERE ReservationID = $reservationID AND UserID = $userID";
mysqli_query($dbconn, $updateReminderSql);
}

?>

<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

// Identify the user (replace with your user identification logic)
$user_id = $_SESSION["userid"];

// Query overdue borrow records
$sqlOverdueBorrows = "SELECT BorrowID, DueDate, StatusReturn FROM borrow_tbl WHERE UserID = $user_id AND DueDate < CURDATE() AND StatusReturn='Unreturned'";
$resultOverdueBorrows = mysqli_query($dbconn, $sqlOverdueBorrows);

if ($resultOverdueBorrows) {
    while ($rowBorrow = mysqli_fetch_assoc($resultOverdueBorrows)) {
        $borrowID = $rowBorrow['BorrowID'];
        $dueDate = $rowBorrow['DueDate'];

        // Calculate penalty amount (adjust the calculation logic as needed)
        $penaltyAmount = calculatePenalty($dueDate);

        // Insert/update records in borrow_tbl
        $updatePenaltySql = "UPDATE borrow_tbl SET PenaltyAmount = $penaltyAmount WHERE BorrowID = $borrowID";
        mysqli_query($dbconn, $updatePenaltySql);
    }
} else {
    echo "Error querying overdue borrows: " . mysqli_error($dbconn);
}

// Function to calculate penalty amount based on due date
function calculatePenalty($dueDate) {
    // Implement your penalty calculation logic here
    // For example, calculate penalty based on the number of days overdue
    // You can adjust the calculation method and rates as needed
    $currentDate = date('Y-m-d');
    $daysOverdue = (strtotime($currentDate) - strtotime($dueDate)) / (60 * 60 * 24);
    $penaltyRate = 0.50; // Penalty rate per day

    if ($daysOverdue > 0) {
        $penaltyAmount = $daysOverdue * $penaltyRate;
        return round($penaltyAmount, 2); // Round to two decimal places
    } else {
        return 0.00; // No penalty if not overdue
    }
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
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> <!--bootstrap JS-->
  <script>
$(document).ready(function () {
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
              <a class="nav-link collapsed" href="index.php">
                  <i class="ri-wallet-3-line"></i><span>Penalty</span>
              </a>
            </li> -->
            <!-- End Idea Nav -->

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
      
            <!--Category filter-->
            <!-- <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#category-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="category-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav"></ul>
            </li> -->

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
      <h1>Home</h1>
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

        <!-- Left side columns -->
          <div class="row">
            

            <!-- <div class="card-body"> -->
                <div class="row align-items-center">
                  <div class="col" id="sorting-btn">
                
                    <?php
  
                    // Query to fetch the first 5 genre names from the database
                    $sql = "SELECT GenreName FROM genre_tbl LIMIT 10"; // Replace 'genres' with your actual table name

                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {
                        $genres = array();

                        // Fetch and store genre names in an array
                        while ($row = mysqli_fetch_assoc($result)) {
                            $genres[] = $row['GenreName'];
                        }

                        // Close the database connection
                        // mysqli_close($dbconn);

                        // Loop through the genres and create links
                        foreach ($genres as $genre) {
                          $genreUrl = 'genre.php?genre=' . urlencode($genre);
                          echo '<a href="' . $genreUrl . '" class="btn btn-light btn-sorting">' . $genre . '</a>';
                            // echo '<a href="genre/' . strtolower(str_replace(' ', '_', $genre)) . '.php" class="btn btn-light btn-sorting">' . $genre . '</a>';
                        }
                    } else {
                        echo "Error: " . mysqli_error($dbconn);
                    }
                    ?>


                    <!-- <a href="submit_idea.php" class="btn btn-primary" style="background-color:#4CAF50; border-color:#4CAF50; float: right;"><i class="bi bi-file-earmark-text"></i> Submit Idea</a> -->
                  </div>
                </div>
              <!-- </div> -->
              
              <!-- <div id="posts-container"> -->
          
              <div class="container mt-5">
                <h2 class="mb-4 pagetitle">
                 Popular Now
                <span class="badge badge-custom bg-light"><a href="popular_all.php" class="badgeCol text-decoration-none">All</a></span>
                </h2>
        <div class="row">

        <?php

            $sql = "SELECT book_tbl.BookID, book_tbl.Title, book_tbl.BookImage, book_tbl.ViewCount, author_tbl.AuthorName 
                    FROM book_tbl 
                    INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
                    ORDER BY ViewCount DESC LIMIT 4";        

            $resultPop = mysqli_query($dbconn,$sql);

            if ($resultPop) {
              // Loop through the results and display book information
              while ($row = mysqli_fetch_assoc($resultPop)) {
                  echo '<div class="col-lg-3 col-md-6 col-sm-12">';
                  echo '<div class="featured-book">';
                  echo '<div class="d-flex justify-content-center">';
                  echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="event-image">';
                  echo '</div>';
                  echo '<h4 class="text-center"><a href="view_book.php?book_id=' . $row['BookID'] . '" class="text-decoration-none text-dark">' . $row['Title'] . '</a></h4>';
                  echo '<p class="text-center">' . $row['AuthorName'] . '</p>';
                  echo '</div>';
                  echo '</div>';
          
              }
          } else {
              echo "Error: " . mysqli_error($dbconn);
          }

        ?>
    
    </div>
    </div>

  <div class="container mt-5">
              <h2 class="mb-4 pagetitle">
      Highest Rated
        <span class="badge badge-custom bg-light"><a href="highest_all.php" class="badgeCol text-decoration-none">All</a></span>
      </h2>
        <div class="row">
         
        <?php

          $sql = "SELECT main.BookID, main.Title, main.BookImage, main.AuthorName, main.AverageRating
          FROM (
              SELECT 
                  book_tbl.BookID, 
                  book_tbl.Title,
                  book_tbl.BookImage,
                  author_tbl.AuthorName,
                  AVG(review_tbl.BookRating) AS AverageRating
              FROM book_tbl
              INNER JOIN review_tbl ON book_tbl.BookID = review_tbl.BookID
              INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
              GROUP BY book_tbl.BookID, book_tbl.Title, book_tbl.BookImage, author_tbl.AuthorName
              HAVING COUNT(review_tbl.ReviewID) >= 1
              ORDER BY AverageRating DESC
              LIMIT 4
          ) AS main
          ORDER BY main.AverageRating DESC";        

          $resultAvg = mysqli_query($dbconn,$sql);

          if ($resultAvg) {
            // Loop through the results and display book information
            while ($row = mysqli_fetch_assoc($resultAvg)) {
                echo '<div class="col-lg-3 col-md-6 col-sm-12">';
                echo '<div class="featured-book">';
                echo '<div class="d-flex justify-content-center">';
                echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="event-image">';
                echo '</div>';
                echo '<h4 class="text-center"><a href="view_book.php?book_id=' . $row['BookID'] . '" class="text-decoration-none text-dark">' . $row['Title'] . '</a></h4>';
                echo '<p class="text-center">' . $row['AuthorName'] . '</p>';
                echo '</div>';
                echo '</div>';

            }
          } else {
            echo "Error: " . mysqli_error($dbconn);
          }

        ?>

        </div>
          </div>
          <div class="container mt-5">
                <h2 class="mb-4 pagetitle">
                Recently Added
                <span class="badge badge-custom bg-light"><a href="recently_all.php" class="badgeCol text-decoration-none">All</a></span>
                </h2>
        <div class="row">

        <?php

              $sql = "SELECT book_tbl.BookID, book_tbl.Title, book_tbl.BookImage, book_tbl.ViewCount, author_tbl.AuthorName 
              FROM book_tbl 
              INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
              ORDER BY DateRegistered DESC LIMIT 4 ";        

            $resultPop = mysqli_query($dbconn,$sql);

            if ($resultPop) {
              // Loop through the results and display book information
              while ($row = mysqli_fetch_assoc($resultPop)) {
                  echo '<div class="col-lg-3 col-md-6 col-sm-12">';
                  echo '<div class="featured-book">';
                  echo '<div class="d-flex justify-content-center">';
                  echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="event-image">';
                  echo '</div>';
                  echo '<h4 class="text-center"><a href="view_book.php?book_id=' . $row['BookID'] . '" class="text-decoration-none text-dark">' . $row['Title'] . '</a></h4>';
                  echo '<p class="text-center">' . $row['AuthorName'] . '</p>';
                  echo '</div>';
                  echo '</div>';
          
              }
          } else {
              echo "Error: " . mysqli_error($dbconn);
          }

        ?>
  

              </div>
  
              </div>
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
