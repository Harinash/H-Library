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

     /* .book {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }

        img {
            max-width: 150px;
            height: auto;
        }  */

        .book-card {
            border: 1px solid #ccc; /* Change the border color and width */
            border-radius:10px;
            margin-top:10px;
        }

        .book-image {
            max-width: 100%; /* Adjust the image size as needed */
            height: auto;
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
      <h1>Books</h1>
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

        <!-- Left side columns -->
          <!-- <div class="row"> -->
            

              
              <!-- <div id="posts-container"> -->
                    <!-- Search box and genre filter in a row -->
                    <div class="row my-4">
              <div class="col-md-6">
                  <input type="text" id="search-input" class="form-control" placeholder="Search by book name or author name" autofocus>
              </div>
            <div class="col-md-4">
                <select id="genre-filter" class="form-control">
                    <option value="">Filter by Genre</option>
                    <?php
                    // Assuming you have a database connection named $dbconn
                    $sql = "SELECT GenreName FROM genre_tbl"; // Query to fetch genres
                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['GenreName'] . '">' . $row['GenreName'] . '</option>';
                        }
                        mysqli_free_result($result); // Free the result set
                    } else {
                        echo "Error: " . mysqli_error($dbconn);
                    }
                    ?>
                  </select>
            </div>
        </div>

        <!-- Displayed books -->
          
          <div class="row" id="book-list">
            <!-- Each book is wrapped in a Bootstrap card and placed within a column -->
            
          </div>
        </div>

    <script>
    //   function resetDropdown() {
    //     $('#genre-filter').val('').trigger('change');
    // }
      $(document).ready(function () {
        // if (performance.navigation.type === 2) {
        //     // Page is loaded as a result of navigating back, reset the dropdown
        //     resetDropdown();
        // }
    // Function to update the book list based on the search query
    var user_id = <?php echo json_encode($_SESSION["userid"]); ?>;
    function updateSearchResults(query) {
            $.ajax({
                url: "search.php",
                type: "GET",
                data: { query: query, user_id: user_id },
                success: function (response) {
                    $("#book-list").html(response);
                },
                error: function () {
                    $("#book-list").html("Error fetching search results.");
                }
            });
        }

        // Function to update the book list based on the selected genre
        function updateBookListByGenre(selectedGenre) {
            $.ajax({
                url: 'filter_books.php',
                method: 'GET',
                data: { genre: selectedGenre, user_id: user_id },
                success: function (response) {
                    $('#book-list').html(response);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                }
            });
        }

        // Function to save the selected genre in session storage
        function saveSelectedGenre(selectedGenre) {
            sessionStorage.setItem('selectedGenre', selectedGenre);
        }

        // Function to retrieve the selected genre from session storage
        function getSelectedGenre() {
            return sessionStorage.getItem('selectedGenre');
        }

        // Listen for input changes in the search bar
        $("#search-input").on("input", function () {
            const query = $(this).val();
            updateSearchResults(query);
        });

        // Handle the change event of the genre dropdown
        $('#genre-filter').change(function () {
            var selectedGenre = $(this).val();
            saveSelectedGenre(selectedGenre); // Save the selected genre
            updateBookListByGenre(selectedGenre); // Update the book list
        });

        // Check if there's a selected genre stored in session storage
        var storedGenre = getSelectedGenre();
        if (storedGenre) {
            // Set the dropdown to the stored genre
            $('#genre-filter').val(storedGenre);
            // Trigger the change event to filter books based on the stored genre
            $('#genre-filter').trigger('change');
        } else {
            // Initially load all books
            updateSearchResults("");
        }
        // Get the user_id from the session

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


<!--DEFAULT GENRE 
// Handle the change event of the genre dropdown
    $('#genre-filter').change(function () {
        // Get the selected genre value
        var selectedGenre = $(this).val();

        // Send an AJAX request to fetch filtered books
        $.ajax({
            url: 'filter_books.php', // Replace with your PHP script URL
            method: 'GET',
            data: { genre: selectedGenre }, // Send the selected genre as data
            success: function (response) {
                // Update the book list container with the filtered books
                $('#book-list').html(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }); -->