<?php
require('dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = $_POST['review_id'];
    $userId = $_POST['user_id'];

    // Check if the user has already reported this review
    $checkQuery = "SELECT * FROM reported_reviews WHERE UserID = $userId AND ReviewID = $reviewId";
    $checkResult = mysqli_query($dbconn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // The user has already reported this review
        echo "AlreadyReported";
    } else {
        // If the user has not reported this review, insert a record in the reported_reviews table
        $insertQuery = "INSERT INTO reported_reviews (UserID, ReviewID) VALUES ($userId, $reviewId)";
        if (mysqli_query($dbconn, $insertQuery)) {
            // Update the ReportCount in the review_tbl
            $updateQuery = "UPDATE review_tbl SET ReportCount = ReportCount + 1 WHERE ReviewID = $reviewId";
            if (mysqli_query($dbconn, $updateQuery)) {
                // Report submitted successfully
                echo "Reported";
            } else {
                echo "Error";
            }
        } else {
            echo "Error";
        }
    }
} else {
    // Handle invalid or missing parameters
    echo "InvalidRequest";
}
?>
