<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

if (isset($_POST['user_id'])) {
    // Get the user ID from the POST data
    $user_id = $_POST['user_id'];
    
    // Query to count the number of items in the user's cart
    $sql = "SELECT COUNT(*) AS cart_count FROM cart_tbl WHERE UserID = $user_id";

    $result = mysqli_query($dbconn, $sql);

    if ($result) {
        // Fetch the cart count value
        $row = mysqli_fetch_assoc($result);
        $cart_count = $row['cart_count'];

        // Return the cart count as a response
        echo $cart_count;
    } else {
        // Error occurred while counting the cart items
        echo "Error: " . mysqli_error($dbconn);
    }
} else {
    // Invalid request, user_id not provided
    echo "Invalid request. Please provide a user_id.";
}
?>
