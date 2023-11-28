<?php
// Include your database connection code here
require_once('dbconn.php');
session_start(); // Start the session to access session variables

// Check if the user is logged in and has a valid session
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

    // Query the database for canceled reservations
    $sql = "SELECT book_tbl.Title, reservation_tbl.BookID, reservation_tbl.ReservationID
    FROM reservation_tbl
    INNER JOIN book_tbl ON reservation_tbl.BookID = book_tbl.BookID
    WHERE Status = 'Cancelled' AND UserID = '$user_id' AND CancelBy IS NULL AND CancelAt IS NOT NULL
      AND CancelAt >= DATE_SUB(CURDATE(), INTERVAL 5 DAY)
    ORDER BY CancelAt DESC
    LIMIT 5;
    ";
    $result = mysqli_query($dbconn, $sql);

    $notifications = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = array(
            'id' => $row['ReservationID'],
            'message' => 'Your reservation for ' . $row['Title'] . ' has been cancelled.'
        );
    }

    echo json_encode($notifications);
} else {
    // Handle the case when the user is not logged in
    echo json_encode(array('message' => 'User is not logged in.'));
}

// Close the database connection
mysqli_close($dbconn);
?>
