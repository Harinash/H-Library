<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is logged in or has a valid session
    // You should have user authentication logic here

    // Get the user ID from the POST data
    $userID = $_POST["user_id"];
    // Perform any additional validation or checks here

    // Query to count the number of different books in the user's cart
    $sql = "SELECT COUNT(DISTINCT BookID) AS num_books FROM cart_tbl WHERE UserID = $userID";
    $result = mysqli_query($dbconn, $sql);

    if ($result) {
        // Fetch the count from the result
        $row = mysqli_fetch_assoc($result);
        $numBooksInCart = $row['num_books'];

        // Return the count as a response
        echo $numBooksInCart;
    } else {
        // Error occurred while counting the cart
        echo "0"; // Return 0 or an error code as needed
    }
} else {
    // Invalid request, return an error code
    echo "0"; // Return 0 or an error code as needed
}
?>
