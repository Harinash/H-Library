<?php
require("dbconn.php");
session_start();

// $errorAlert = '';
// $errorEmpty = '';
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

    <!-- Custom CSS -->
    <!-- <style>
        .center-card {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style> -->
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
            <div class="col-md-4 center-card">
                <div class="card">
                    <div class="card-body text-center">
                        <!-- <h2>Sign In form-control</h2> -->
                        <h1>Forgot Password</h1>
                        <form action="" method="post">
                            <!-- Add your sign-in form fields here -->
                            <?php
                                $errorEmail = ''; // Separate error variable for email
                                $errorPassword = ''; // Separate error variable for password

                                if (isset($_POST["forgotPassword"])) {
                                    $user_email = $_POST["useremail"];
                                    $newPassword = $_POST["newPassword"];
                                    $hashedPassword = md5($newPassword);
                                    $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$user_email'";
                                    $checkaccount = mysqli_query($dbconn, $sql);

                                    if (mysqli_num_rows($checkaccount) == 0) {
                                        $errorEmail = 'Incorrect Email.';
                                    }

                                    else if(mysqli_num_rows($checkaccount) > 0){
                                        $userrow = mysqli_fetch_array($checkaccount);

                                        if(strlen($newPassword) < 8){
                                             $errorPassword = 'Password must be at least 8 characters long.';
                                        } 
                                        else if(!preg_match('/[a-z]/', $newPassword)){
                                            $errorPassword = "Password must contain at least one lowercase letter.";
                                        }
                                        else if(!preg_match('/^[ -~]+$/', $newPassword)){
                                            $errorPassword = "Password can only contain alphabetical character.";
                                        }
                                        else if(!preg_match('/[!@#$%^&*()_+}{":?><~`\-.,\/\\|]+/', $newPassword)){
                                            $errorPassword = "Password must contain at least one symbol (except single quote and semicolon).";
                                        }
                                        elseif (!empty($newPassword)) {
                                            $newPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["newPassword"]));
                                            mysqli_query($dbconn, "UPDATE user_tbl 
                                                                        SET UserPassword = '$hashedPassword' 
                                                                        WHERE UserEmail = '$user_email'");
                                            echo "<script>alert('Your password has changed!'); window.location.href='index.php';</script>";
                                            exit();
                                        }
                                        else {
                                            echo "<script>alert('Password change failed. Please try again'); window.location.href='forgot_password.php';</script>";
                                        }

                                    }
                                }
                            ?>

                            <div class="form-group">                             
                                <input type="email" class="input emailInput" id="email" name="useremail" placeholder="Email address" required autofocus>
                                <div class="error-message"><?php echo $errorEmail; ?></div>
                            </div>

                            <div class="form-group">
                                <input type="password" class="input" id="password" name="newPassword" placeholder="Password" minlength="8" required>
                                <div class="error-message"><?php echo $errorPassword; ?></div> 
                            </div>
                    
                            <input type="submit" class="submit" value="Reset Password" name="forgotPassword">

                            <div class="signup-link2">
                           Don't have an account? <a href="signup.php" class="signup-link">Sign up</a>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="card" style="margin-top:20px;">
                    <div class="card-body text-center">

                        <div class="signup-link3">
                            <a href="index.php" class="signup-link">Back to Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
    
    <!-- <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 center-cards">
                <div class="card">
                    <div class="card-body text-center">

                        <div class="signup-link3">
                            <a href="index.php" class="signup-link">Back to Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div> -->
</body>
</html>
