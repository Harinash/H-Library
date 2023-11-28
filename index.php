<?php
require("dbconn.php");

session_start();

//cannot go back to login page if user already login
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "Librarian") {
        header("Location: librarian/home.php");
        exit;
    } else if ($_SESSION["role"] == "Member") {
        header("Location: home.php");
        exit;
    } else if ($_SESSION["role"] == "Admin") {
        header("Location: admin/home.php");
        exit;
    } 
}
// $errorAlert = '';
// $errorEmpty = '';
if (isset($_POST["userlogin"])) {

    $myemail = $_POST["useremail"];
    $mypassword = $_POST["userpassword"];
    $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail' AND UserPassword = MD5('$mypassword')";
    $checkaccount = mysqli_query($dbconn, $sql);

    if ($checkaccount) {
        $userrow = mysqli_fetch_array($checkaccount);


        if (is_array($userrow)) {
            $_SESSION["userid"] = $userrow["UserID"];
            $_SESSION["username"] = $userrow["Username"];
            $_SESSION["useremail"] = $userrow["UserEmail"];
            $_SESSION["userpassword"] = $userrow["UserPassword"];
            $_SESSION["role"] = $userrow["UserRole"];
            $_SESSION["usercontactno"] = $userrow["UserContactNo"];
        } else {
            
        }
        if (
            isset($_SESSION["username"])
            && isset($_SESSION["userid"])
            && isset($_SESSION["role"])
        ) {
            if ($_SESSION["role"] == "Librarian") {
                header("Location: librarian/home.php");
                exit;
            } else if ($_SESSION["role"] == "Member") {
                header("Location: home.php");
                exit;
            } else if ($_SESSION["role"] == "Admin") {
                header("Location: admin/home.php");
                exit;
            } 
        }
    }

}


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
                        <h1>H-LIBRARY</h1>
                        <form action="" method="post">
                            <!-- Add your sign-in form fields here -->
                            <?php
                                $errorEmail = ''; // Separate error variable for email
                                $errorPassword = ''; // Separate error variable for password
                                $errorEmailPassword = '';
                                if (isset($_POST["userlogin"])) {
                                    $userEmail = $_POST["useremail"];
                                    $userPassword = $_POST["userpassword"];
                                    if (empty($userEmail)) {
                                        $errorEmail = 'Please fill in your email address.';
                                    } 
                                    
                                    if (empty($userPassword)) {
                                        $errorPassword = 'Please fill in your password.';
                                    } else {
                                        // Query to check if the email exists
                                        $emailCheckSQL = "SELECT * FROM user_tbl WHERE UserEmail = '$userEmail'";
                                        $emailCheckResult = mysqli_query($dbconn, $emailCheckSQL);
                                
                                        if (mysqli_num_rows($emailCheckResult) == 0) {
                                            // No matching email found in the database
                                            $errorEmail = 'The email address you entered is incorrect.';
                                        } else {
                                            // Query to check if the email and password combination exists
                                            $passwordCheckSQL = "SELECT * FROM user_tbl WHERE UserEmail = '$userEmail' AND UserPassword = '$userPassword'";
                                            $passwordCheckResult = mysqli_query($dbconn, $passwordCheckSQL);
                                
                                            if (mysqli_num_rows($passwordCheckResult) == 0) {
                                                // Email is correct, but password is incorrect
                                                $errorEmailPassword = 'The password you entered is incorrect.';
                                            }
                                        }
                                    }
                                }
                            ?>
                            <div class="form-group">
                                
                                <input type="email" class="input emailInput" id="" name="useremail" placeholder="Email address" autofocus>
                                <div class="error-message"><?php echo $errorEmail; ?></div>
                            </div>
                            <div class="form-group">
                               
                                <input type="password" class="input" id="" name="userpassword" placeholder="Password" > 
                                <div class="error-message"><?php echo $errorPassword; ?></div>
                                <div class="error-message"><?php echo $errorEmailPassword; ?></div>
                            </div>
                        
                

                            <input type="submit" value="Sign In" class="submit" name="userlogin">
                            <!-- <label>
                                <a href="forgot_password.php">Forgotten your password?</a>
                            </label> -->
                         
                          
                            <h5><a href="forgot_password.php">Forgotten your password?</a></h5>
                        </form>
                    </div>
                </div>
                <div class="card" style="margin-top:20px;">
                    <div class="card-body text-center">

                        <div class="signup-link">
                           Don't have an account? <a href="signup.php" class="signup-link">Sign up</a>
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

                        <div class="signup-link">
                           Don't have an account? <a href="signup.php" class="signup-link">Sign up</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div> -->
</body>
</html>

<!-- <?php
                if (isset($_POST["userlogin"])) {

                    $userEmail = $_POST["useremail"];
                    $userPassword = $_POST["userpassword"];
                    $usersql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail' AND UserPassword = '$mypassword'";
                    $userResult = mysqli_query($dbconn, $usersql);

                    if (empty($userEmail) || empty($userPassword)) {
                        $error = "*Please fill in both email and password.";
                        $errorEmpty .= '<div class="invalid-feedback">'
                            . $error .
                            '</div> <br/>';
                    }
                }
                ?>
                <?php
                if (!empty($_POST["useremail"]) && !empty($_POST["userpassword"])) {
                    $error = "Sorry, the email or password you entered is incorrect. Please try again.";
                    $errorAlert .= '<div style="background-color: #ffcccc; padding: 10px; border-radius: 5px; border: 1px solid #ff0000;">'
                        . $error .
                        '</div> <br/>';
                }
                ?>
                <?= $errorEmpty ?>
                <?= $errorAlert ?> -->