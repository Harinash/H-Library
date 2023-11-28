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
  $select_sql = "SELECT * FROM user_tbl WHERE UserID = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);
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

    .container {
            display: flex;
            flex-direction: column; /* Display items vertically */
            min-height: 800px; 
        }

        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .book-image {
            max-width: 100px;
            margin-right: 10px;
        }

        .book-details {
            flex-grow: 1;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions button {
            margin-right: 10px; /* Add some spacing between buttons */
        }

        /* Style for the line and "Proceed" text */
        .proceed-section {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .proceed-text {
            font-weight: bold;
        }

        /* Style for the "Proceed" button */
        .proceed-button {
            margin-top: 10px;
        }
        .proceed-reservation {
            margin-top: 10px;
        }

        .pickup-note {
            margin-top: 20px;
            font-weight: bold;
            border: 1px solid #ccc; /* Add a border to the note card */
            padding: 10px;
        }
        .cart-empty {
    text-align: center;
    padding: 20px;
    border: 1px solid #ccc;
    background-color: #FFFFFF;
    margin-top: 10px;
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
      <h1>My Cart</h1>
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

    <div class="container col-md-9">
    <?php
$sql = "SELECT cart_tbl.UserID, cart_tbl.BookID, cart_tbl.CopyID, cart_tbl.CartID, book_tbl.Title, author_tbl.AuthorName, book_tbl.BookImage, bookcopy_tbl.availability
        FROM cart_tbl
        INNER JOIN user_tbl ON cart_tbl.UserID = user_tbl.UserID
        INNER JOIN bookcopy_tbl ON cart_tbl.CopyID = bookcopy_tbl.CopyID
        INNER JOIN book_tbl ON cart_tbl.BookID = book_tbl.BookID
        INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
        WHERE user_tbl.UserID = $user_id";

$resultCart = mysqli_query($dbconn, $sql);
$itemsDisplayed = false; // Initialize a flag to track if any items are displayed

if ($resultCart) {
    // Loop through the results and display book information
    while ($row = mysqli_fetch_assoc($resultCart)) {
        if ($row['availability'] == 1) {
            echo '<div class="cart-item">';
            echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="book-image">';
            echo '<div class="book-details">';
            echo '<h4>' . $row['Title'] . '</h4>';
            echo '<p>' . $row['AuthorName'] . '</p>';
            echo '</div>';
            echo '<div class="actions">';
            // echo '<a href="delete_item.php?cart_id=' . $row['CartID'] . '&book_id=' . $row['BookID'] . '&copy_id=' . $row['CopyID'] . '" class="btn btn-danger">Remove Book</a>';
            echo '<a href="javascript:void(0);" class="btn btn-danger remove-book" data-bookid="' . $row['BookID'] . '" data-copyid="' . $row['CopyID'] . '" data-cartid="' . $row['CartID'] . '" data-userid="' . $user_id . '">Remove Book</a>';
            echo '</div>';
            echo '</div>';
            $itemsDisplayed = true;
        } else {
            // Book copy is no longer available, remove it from the cart
            // You can call a function to handle the removal from the cart here
            // For example, removeCartItem($user_id, $row['BookID'], $row['CopyID']);
            removeCartItem($user_id, $row['BookID'], $row['CopyID'], $dbconn);
        }
    }
    // Check if no items were displayed, and display the default image and message
if (!$itemsDisplayed) {
  echo '<div class="cart-empty">';
  echo '<img src="assets/img/test1.png" alt="Default Image" class="default-image">';
  echo '</div>';
}
} else {
    echo "Error: " . mysqli_error($dbconn);
}

function removeCartItem($user_id, $book_id, $copy_id, $dbconn) {
  // Check if the user is logged in or has a valid session
  // You should have user authentication logic here

  // Ensure that $user_id, $book_id, and $copy_id are valid integers (sanitize and validate inputs)
  $user_id = intval($user_id);
  $book_id = intval($book_id);
  $copy_id = intval($copy_id);

  // Perform any additional validation or checks here

  // Check if the item exists in the cart
  $sql = "SELECT CartID FROM cart_tbl WHERE UserID = $user_id AND BookID = $book_id AND CopyID = $copy_id";
  $result = mysqli_query($dbconn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
      // Item exists in the cart, so delete it
      $row = mysqli_fetch_assoc($result);
      $cart_id = $row['CartID'];

      $deleteSql = "DELETE FROM cart_tbl WHERE CartID = $cart_id";
      if (mysqli_query($dbconn, $deleteSql)) {
          // Item successfully removed from the cart
          return true;
      } else {
          // Error occurred while removing the item
          return false;
      }
  } else {
      // Item not found in the cart
      return false;
  }
}

echo "
<script type=\"text/javascript\">

function checkCart() {
  // Send an AJAX request to count_cart.php to check the cart
  var userid = $user_id;
  $.ajax({
      url: 'countItem_cart.php', // Replace with the actual path to count_cart.php
      type: 'POST',
      data: { user_id: userid }, // Send the user ID to count_cart.php
      success: function (response) {
          var numBooksInCart = parseInt(response);

          if (numBooksInCart === 0) {
              // The cart is empty, show an alert
              alert('Your cart is empty. Please add books to your cart before proceeding.');
              window.location.href = 'books.php';
          } else {
              // The cart is not empty, proceed to the reservation page
              window.location.href = 'confirm_reservation.php';
          }
      },
      error: function () {
          alert('Failed to check the cart.');
      }
  });
}

$(document).ready(function () {
  // Attach a click event handler to all elements with the class 'remove-book'
  $('.remove-book').click(function () {
      var cartid = $(this).data('cartid');
      var userid = $(this).data('userid');

      // Ask for confirmation
      var confirmed = confirm('Are you sure you want to remove this book from the cart?');
      
      if (confirmed) {
          // User confirmed, send an AJAX request to remove the book from the cart
          $.ajax({
              url: 'deleteBook_cart.php', // Replace with the actual PHP script
              type: 'POST',
              data: { cart_id: cartid, user_id: userid },
              success: function (response) {
                  alert('Book removed from cart.');
                  window.location.href = 'cart.php';
                  // Refresh the cart or update the cart display here
              },
              error: function () {
                  alert('Failed to remove book from cart.');
              }
          });
      } else {
          // User canceled, do nothing
      }
  });

  $('.proceed-reservation').click(function (e) {
      e.preventDefault();
      checkCart();
  });
});
    </script>
    ";
?>

        <!-- <div class="cart-item">
            <img src="assets/img/book1.jpg" alt="Book 1" class="book-image">
            <div class="book-details">
                <h4>Book Title</h4>
                <p>Author: Author 1</p>
       
            </div>
            <div class="actions">
                <button class="btn btn-danger">Delete</button>
                <button class="btn btn-primary">Add More</button>
            </div>
        </div>

  
        <div class="cart-item">
            <img src="assets/img/book1.jpg" alt="Book 2" class="book-image">
            <div class="book-details">
                <h4>Another Book</h4>
                <p>Author: Author 2</p>
      
            </div>
            <div class="actions">
                <button class="btn btn-danger">Delete</button>
                <button class="btn btn-primary">Add More</button>
            </div>
        </div> -->

        <!-- Line, "Proceed" text, and button section -->
        <div class="proceed-section">
            <!-- <div class="proceed-text">Proceed to reservation?</div> -->
            <a href="confirm_reservation.php" class="btn btn-primary proceed-reservation">Proceed to reservation</a>
            <a href="books.php" class="btn btn-secondary proceed-button">Add more books</a>
        </div>
        
        <!-- Note about pickup -->
        <!-- <div class="pickup-note">
           ** Books must be picked up within 2 days or your reservations will be automatically cancelled.**<br/>Last day of pickup: [Last Pickup Date]
        </div>
    </div> -->
   
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
