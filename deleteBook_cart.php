<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user_id and book_id from POST data
    $user_id = $_POST["user_id"];
    $cart_id = $_POST["cart_id"];

    // Query to check if the book is already in the user's cart
    $sql = "DELETE FROM cart_tbl WHERE UserID = $user_id AND CartID = $cart_id";
    $result = mysqli_query($dbconn, $sql);

    if ($result) {
        // The book is already in the cart
        echo '<script>alert("ITEMS DELETED")</script>';
    } else {
        // The book is not in the cart
        echo '<script>alert("CANT DELETE ITEMS");</script>';
    }
} else {
    // Invalid request
    echo '<script>alert("Invalid Request"); window.location.href="home.php";</script>';
}
?>
