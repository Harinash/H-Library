<?php
// Include your database connection code here
require_once('dbconn.php');

// Get the review ID from the GET request
$reviewId = $_GET['review_id'];

// Query the database to get the like count for the review
$sql = "SELECT COUNT(*) AS dislike_count FROM review_dislikes WHERE ReviewID = '$reviewId'";
$result = mysqli_query($dbconn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $likeCount = $row['dislike_count'];
    echo $likeCount;
} else {
    echo '0'; // Handle the case when there is an error in the database query
}

// Close the database connection
mysqli_close($dbconn);
?>
