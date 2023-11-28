<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user_id and book_id from POST data
    $user_id = $_POST["user_id"];
    $book_id = $_POST["book_id"];

    // Query to check if the book is already in the user's cart
    $sql = "SELECT * FROM cart_tbl WHERE UserID = $user_id AND BookID = $book_id";
    $result = mysqli_query($dbconn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // The book is already in the cart
        echo 'already_in_cart';
    } else {
        // The book is not in the cart
        echo 'not_in_cart';
    }
} else {
    // Invalid request
    echo 'invalid_request';
}
?>
