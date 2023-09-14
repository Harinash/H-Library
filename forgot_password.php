

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
                        <form>
                            <!-- Add your sign-in form fields here -->
                            <div class="form-group">
                                
                                <input type="email" class="input emailInput" id="email" placeholder="Email address" required>
                            </div>
                            <div class="form-group">
                               
                                <input type="password" class="input" id="password" placeholder="Password" required> 
                            </div>
                            
                            <button type="submit" class="submit">Reset Password</button>
                            <div class="signup-link2">
                           Don't have an account? <a href="signup.php" class="signup-link">Sign up</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
    
    <div class="container">
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
    </div>
</body>
</html>
