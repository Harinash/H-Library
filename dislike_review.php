<?php
require("dbconn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get review_id and user_id from the request
    $review_id = $_POST['review_id'];
    $user_id = $_POST['user_id'];

    // Check if the user has already disliked this post
    $dislikeQuery = "SELECT * FROM review_dislikes WHERE ReviewID = $review_id AND UserID = $user_id";
    $dislikeResult = mysqli_query($dbconn, $dislikeQuery);

    if (mysqli_num_rows($dislikeResult) > 0) {
        // User has already disliked this post; do nothing
        echo "AlreadyDisliked"; // Indicate that the user has already disliked the post
    } else {
        // Check if the user has previously liked this post
        $likeQuery = "SELECT * FROM review_likes WHERE ReviewID = $review_id AND UserID = $user_id";
        $likeResult = mysqli_query($dbconn, $likeQuery);

        if (mysqli_num_rows($likeResult) > 0) {
            // User has already liked this post, so remove the like first
            $removeLikeQuery = "DELETE FROM review_likes WHERE ReviewID = $review_id AND UserID = $user_id";
            if (mysqli_query($dbconn, $removeLikeQuery)) {
                // Now, insert the dislike
                $insertDislikeQuery = "INSERT INTO review_dislikes (ReviewID, UserID) VALUES ($review_id, $user_id)";
                if (mysqli_query($dbconn, $insertDislikeQuery)) {
                    echo "Disliked";
                } else {
                    echo "Error";
                }
            } else {
                echo "Error";
            }
        } else {
            // User hasn't liked this post; insert a new dislike
            $insertDislikeQuery = "INSERT INTO review_dislikes (ReviewID, UserID) VALUES ($review_id, $user_id)";
            if (mysqli_query($dbconn, $insertDislikeQuery)) {
                echo "Disliked";
            } else {
                echo "Error";
            }
        }
    }
} else {
    echo "Invalid request";
}

mysqli_close($dbconn);
?>
