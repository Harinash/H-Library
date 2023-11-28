<?php
require("dbconn.php");

$book_id = $_GET['book_id'];
$filter = $_GET['filter'];
$user_id = $_GET['user_id'];

$sqlReviews = "SELECT review_tbl.ReviewID, review_tbl.ReviewDescription, review_tbl.BookRating, review_tbl.ReviewAnonymous, review_tbl.DateReview, user_tbl.Username
                FROM review_tbl
                INNER JOIN user_tbl ON review_tbl.UserID = user_tbl.UserID
                WHERE BookID = $book_id" . ($filter > 0 ? " AND BookRating = $filter" : "");

$resultReviews = mysqli_query($dbconn, $sqlReviews);

if (mysqli_num_rows($resultReviews) > 0) {
    while ($row = mysqli_fetch_assoc($resultReviews)) {
        $reviewDescription = $row["ReviewDescription"];
        $reviewId = $row["ReviewID"];
        $bookRating = $row["BookRating"];
        $reviewAnonymous = $row["ReviewAnonymous"];
        $dateReview = $row["DateReview"];
        $username = $row["Username"];
        $formattedDateTime = date('d-m-Y H:i', strtotime($dateReview));
        // Convert the numeric rating to ★ icons
        $ratingStars = str_repeat("★", $bookRating) . str_repeat("☆", 5 - $bookRating);
        $likeidea_query = "SELECT COUNT(*) AS like_count FROM review_likes WHERE ReviewID = '$reviewId'";
        $likeidea_result = mysqli_query($dbconn, $likeidea_query);
        $likeidea_row = mysqli_fetch_assoc($likeidea_result);
        $dislikeidea_query = "SELECT COUNT(*) AS dislike_count FROM review_dislikes WHERE ReviewID = '$reviewId'";
        $dislikeidea_result = mysqli_query($dbconn, $dislikeidea_query);
        $dislikeidea_row = mysqli_fetch_assoc($dislikeidea_result);
        // Display the review and rating
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . ($reviewAnonymous == 1 ? "Anonymous" : $username) . '</h5>';
        echo '<p class="card-text">' . $ratingStars . '</p>';
        echo '<p class="card-text">' . $reviewDescription . '</p>';
        echo '<p class="card-text"><small class="text-muted">' . $formattedDateTime . '</small></p>';
        echo '<button class="like-button btn btn-primary" style="background-color: darkcyan; margin: 0 5px 0 0;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '"><i class="ri-thumb-up-fill"></i></button> <span id="like-count-number" style="margin-left: 5px;">' . $likeidea_row['like_count'] . '</span>';
        echo '<button class="dislike-button btn btn-primary" style="background-color: darkcyan; margin: 0 5px 0 20px;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '"><i class="ri-thumb-down-fill"></i></button> <span id="dislike-count-number" style="margin-left: 5px;">' . $dislikeidea_row['dislike_count'] . '</span>';
        // echo '<p class="card-text" id="likes-count">Likes: <span id="like-count-number">' . $likeidea_row['like_count'] . '</span></p>';
        echo '<button class="report-button btn btn-danger position-absolute top-0 end-0" style=" margin: 10px 10px 0 0;" data-review-id="' . $reviewId . '" data-user-id="' . $user_id . '">';
        echo '    <i class="bi bi-flag-fill "></i>';
        echo '</button>';
        echo '</div>';
        echo '</div>';    }
} else {
    echo '<p>No reviews available for this book.</p>';
}

mysqli_close($dbconn);
?>
