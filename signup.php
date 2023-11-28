<?php
require("dbconn.php");
session_start();

// $error = "";
$errorEmail = ''; // Separate error variable for email
$errorPassword = ''; // Separate error variable for password
$errorUsername = '';
$errorContact = '';
if (isset($_POST['submit'])) {
  $username = $_POST['Username'];
  $password = strip_tags($_POST['UserPassword']);
  $hashedPassword = md5($password);
  $contact = strip_tags($_POST['UserContactNo']);
  $email = strip_tags($_POST['UserEmail']);

  $check_email = mysqli_query($dbconn, "SELECT * FROM user_tbl WHERE UserEmail = '$email'");

  if (empty($username) || !preg_match('/^[a-zA-Z0-9_@.!]+$/', $username)) {
    $errorUsername = "Enter valid Username";
  } else if (empty($password)) {
    $errorPassword  = "Don't leave your password empty";
  } else if (mysqli_num_rows($check_email) > 0) {
    $errorEmail = "Email address already exist";
  } elseif (empty($email) || !preg_match('/^[a-zA-Z0-9_@.!]+$/', $email)) {
    $errorEmail = "Please insert valid email";
  } else {
    if (!empty($password)) {
      if (strlen($password) < 8) {
        $errorPassword = "Password must be at least 8 characters long.";
      } elseif (!preg_match('/[a-z]/', $password)) {
        $errorPassword = "Password must contain at least one lowercase letter.";
      } elseif (!preg_match('/^[ -~]+$/', $password)) {
        $errorPassword = "Password can only contain alphabetical character.";
      } elseif (!preg_match('/[!@#$%^&*()_+}{":?><~`\-.,\/\\|]+/', $password)) {
        $errorPassword = "Password must contain at least one symbol (except single quote and semicolon).";
      } else {
        $sql = "INSERT INTO `user_tbl`(`Username`, `UserPassword`, `UserEmail`, `UserContactNo`, `DateRegistered`) 
                VALUES ('$username','$hashedPassword','$email','$contact', CURDATE())";

        $result = mysqli_query($dbconn, $sql);
        // header("Location: signup.php?msg=New user added successfully");  
        echo "<script>alert('Account has been created.'); window.location.href='index.php';</script>";
        exit();
      }
    }
  }
}

// $user_id = $_SESSION["userid"];
// $select_sql = "SELECT * FROM user_tbl WHERE UserID = $user_id";
// $result_User = mysqli_query($dbconn, $select_sql);
// $row_User = mysqli_fetch_assoc($result_User);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H-Library</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional Bootstrap Theme CSS -->
    <link rel="stylesheet" href="index.css" type="text/css" media="all">
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap-theme.min.css" rel="stylesheet"> -->
    <!-- Include Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="icon" href="images/H.png" type="image/x-icon">

    <style>
        .error-message {
        color: #f02849; /* Change the color to red */
        font-size: 14px; /* You can adjust the font size */
        font-weight:bold;
        text-align:left;
        /* Add more styling as needed */
    }
    </style>

</head>
<body>
<!-- <h1>H-LIBRARY</h1> -->
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 center-card2">
                <div class="card">
                    <div class="card-body text-center">
                        <!-- <h2>Sign In form-control</h2> -->
                        <h1>H-LIBRARY</h1>
                        <form action="" method="post">
                            <!-- Add your sign-in form fields here -->
                            <div class="form-group">
                                <input type="email" class="input" name="UserEmail" placeholder="Email address" required autofocus>
                                <div class="error-message"><?php echo $errorEmail; ?></div>
                            </div>
                            <!-- <div class="form-group">
                               
                                <input type="text" class="input" id="name" placeholder="Full Name" required> 
                            </div> -->
                            
                            <div class="form-group">                              
                               <input type="text" class="input" name="Username" placeholder="Username" required>
                               <div class="error-message"><?php echo $errorUsername; ?></div> 
                           </div>

                           <div class="form-group">
                               <input type="text" class="input" name="UserContactNo" placeholder="Contact Number" required>
                               <div class="error-message"><?php echo $errorContact; ?></div> 
                           </div>

                           <div class="form-group">
                               <input type="password" class="input" name="UserPassword" placeholder="Password" required minlength="8">
                               <div class="error-message"><?php echo $errorPassword; ?></div> 
                           </div>

                            <button type="submit" class="submit" name="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
                <div class="card" style="margin-top:20px;">
                    <div class="card-body text-center">

                        <div class="signup-link">
                            Have an account? <a href="index.php" class="signup-link">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
    

</body>
</html>
