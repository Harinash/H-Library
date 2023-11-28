<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("dbconn.php");
if($_SESSION["role"] != "Member") {
  header("Location: index.php");
  exit;
}
$updateSuccess = "";
if (!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
  header("Location: index.php"); // Redirect to login page if not logged in
  exit;
}
if (isset($_SESSION['success_message'])) {
  $updateSuccess = '<div class="position-fixed" style="top: 60px; right: 30px; z-index: 1000; width: 300px;">' .
                '<div class="alert alert-success alert-dismissible fade show" role="alert">' .
                $_SESSION['success_message'] .
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' .
                '</div>' .
                '</div>';
  // Unset the success message session variable to clear it
  unset($_SESSION['success_message']);
}
$errorAlert = "";

$user_id = $_SESSION["userid"];
if (isset($_POST['submit'])) {
  if (isset($_POST['UserEmail']))
  $check_email = mysqli_real_escape_string($dbconn, $_POST['UserEmail']);
  //Check if new email exist and its not current user's email
  $sql_email_check = "SELECT UserEmail FROM user_tbl WHERE UserEmail='$check_email' AND NOT UserID='$user_id' ";
  $result_email = mysqli_query($dbconn, $sql_email_check);
  $count = mysqli_num_rows($result_email);
  if ($count > 0) {
    $error = "Email address already in use by another user.";
        $errorAlert = "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
                    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>";
  } else {
    $username = mysqli_real_escape_string($dbconn, $_POST['Username']);
    $email = strip_tags(mysqli_real_escape_string($dbconn, $_POST['UserEmail']));
    $contact = strip_tags(mysqli_real_escape_string($dbconn, $_POST['UserContactNo']));
    if (empty($username)) {
      $error = "Username cannot be empty";
      $errorAlert = "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
                    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>";
    } else if (empty($email)) {
      $error = "Email cannot be empty";
      $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
                    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>";
    } else if (empty($contact)) {
      $error = "Contact number cannot be empty";
      $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
      <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
          $error
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    </div>";
    } else {
      if (!empty($email) || !empty($username)) {
        if (!preg_match('/^[a-zA-Z0-9_@.!]+$/', $email)) {
          $error = "Don't include single quotation in your email";
          $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
          <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
              $error
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>
      </div>";
        } else {
          if (!empty($_FILES['bookImage']['tmp_name'])) {
            $uploadDir = '../assets/img/';
            $uploadDirDB = 'assets/img/';
            $imageFileName = $_FILES['bookImage']['name'];
            $fileExtension = pathinfo($imageFileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;
            $imagePath = $uploadDir . $uniqueFileName;
            $dbImagePath = $uploadDirDB . $uniqueFileName;

            // Check if the uploaded file is an image
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileType = mime_content_type($_FILES['bookImage']['tmp_name']);
            if (!in_array($fileType, $allowedFileTypes)) {
                $error = "Invalid file type. Only JPEG, PNG, and JPG files are allowed.";
                $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
                    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>";
            }
            list($width, $height) = getimagesize($_FILES['bookImage']['tmp_name']);

            $minWidth = 600;
            $minHeight = 600;
            
            if ($width < $minWidth || $height < $minHeight) {
             echo '<script>alert("Image too small."); </script>';}
             else {
                move_uploaded_file($_FILES['bookImage']['tmp_name'], $imagePath);
            }
        }
        else {
          // If no image was uploaded, set a default image path
          $dbImagePath = 'assets/img/user.png'; // Replace with the path to your default image
      }
          $sql = "UPDATE `user_tbl` SET `Username`='$username',`UserEmail`='$email',
                 `UserContactNo`='$contact', `ProfileImage`='$dbImagePath'  
                WHERE UserId = $user_id";
          mysqli_query($dbconn, $sql);
            // Set a success message in a session variable
          $_SESSION['success_message'] = "Profile updated successfully";
          header("Location: member_profile.php?msg=Data updated successfully");
          exit();
        }
      }
    }
  }
}

$select_sql = "SELECT * FROM user_tbl WHERE UserID = $user_id";
$result_User = mysqli_query($dbconn, $select_sql);  
$row_User = mysqli_fetch_assoc($result_User);

$user_id_password = $_SESSION["userid"];
$select_sql_password = "SELECT * FROM user_tbl WHERE UserID = $user_id_password";
$result_password = mysqli_query($dbconn, $select_sql_password);
$row_password = mysqli_fetch_assoc($result_password);

if (isset($_POST["submit_new_password"])) {
  $newPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["newPassword"]));
  $hashedPassword = md5($newPassword);
  $comfirmNewPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["comfirmNewPassword"]));
  if ($newPassword != $comfirmNewPassword) {
    $error = "New Password and Re-enter New Password does not match..";
    $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
        $error
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
</div>";
  } else if (!empty($newPassword) && !empty($comfirmNewPassword)) {
    if ($newPassword == $comfirmNewPassword) {
      if (strlen($newPassword) < 8) {
        $error = "Password must be at least 8 characters long.";
        $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
        <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
            $error
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    </div>";
      } elseif (!preg_match('/[a-z]/', $newPassword)) {
        $error = "Password must contain at least one lowercase letter.";
        $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
        <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
            $error
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    </div>";
      } elseif (!preg_match('/^[ -~]+$/', $newPassword)) {
        $error = "Password can only contain alphabetical character.";
        $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
        <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
            $error
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    </div>";
      } elseif (!preg_match('/[!@#$%^&*()_+}{":?><~`\-.,\/\\|]+/', $newPassword)) {
        $error = "Password must contain at least one symbol (except single quote and semicolon).";
        $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
        <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
            $error
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    </div>";
      } else {
        mysqli_query($dbconn, "UPDATE user_tbl 
                               SET UserPassword = '$hashedPassword' 
                               WHERE UserID = '$user_id'");

        $_SESSION['success_message'] = "Password has changed";
        header("Location: member_profile.php");
        exit();
      }
    }
  } else {
    $error = "Password cannot be empty.";
    $errorAlert .= "<div class='position-fixed' style='top: 60px; right: 30px; z-index: 1000; width: 300px;'>
    <div class='alert alert-warning alert-dismissible fade show mb-0' role='alert'>
        $error
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
</div>";
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
      <h1>My Profile</h1>
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
              <img src=<?php echo htmlentities($row_User['ProfileImage']); ?> alt="Profile" class="rounded-circle">
              <h2><?php echo htmlentities($row_User['Username']) ?></h2> 
              <h3><?php echo $row_User['UserRole'] ?></h3>
          
              <!-- <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div> -->
            </div>
          </div>

        </div>

        <div class="col-xl-8">
          <?= $errorAlert ?>
          <?= $updateSuccess ?>

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <form action="" method="post">
                    <h5 class="card-title">Profile Details</h5>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Username</div>
                      <div class="col-lg-9 col-md-8" name="Username"><?php echo htmlentities($row_User['Username']) ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Email</div>
                      <div class="col-lg-9 col-md-8" name="UserEmail"><?php echo $row_User['UserEmail'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Contact Number</div>
                      <div class="col-lg-9 col-md-8" name="UserContactNo"><?php echo $row_User['UserContactNo'] ?></div>
                    </div>
                  </form>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->

                  <form action="" method="post">
                    
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="text" name="Username" value ="<?php echo htmlentities($row_User['Username']) ?>"  class="form-control">
                      </div>
                    </div>
                 
                   
                      <!-- <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label> -->
                      <div class="col-md-8 col-lg-9">
                        <input type="hidden" name="UserEmail" value ="<?php echo $row_User['UserEmail'] ?>" class="form-control">
                      </div>
                  

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Contact Number</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="text" name="UserContactNo" value ="<?php echo $row_User['UserContactNo'] ?>" class="form-control">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="file" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                      <input type="file" id="file" name="bookImage" class="form-control" accept="image/jpeg, image/png, image/jpg" >
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                      <a href="member_profile.php" class="btn btn-danger" >Cancel</a>
                    </div>
                  </form>
                  <!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  
                  <!-- Change Password Form -->
                  <form action="" method="post" enctype="multipart/form-data">

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="comfirmNewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="comfirmNewPassword" type="password" class="form-control" id="comfirmNewPassword">
                      </div>
                    </div>
<!-- 
                    <?php
                        if(isset($_POST["submit_new_password"])){

                            $userNewPassword = $_POST["newPassword"];
                            $userComfirmNewPassword = $_POST["comfirmNewPassword"];

                            if($userNewPassword != $userComfirmNewPassword){
                              echo '<script>alert("*New Password and Re-enter New Password does not match.")</script>';
                            }
                        }
                    ?> -->

                    <div class="text-center">
                      <button type="submit" name="submit_new_password" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>
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
