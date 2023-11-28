<?php
include "dbconn.php";
// $user_id = $_SESSION["userid"];

// Retrieve the ReservationID from the query parameter
$reservationID = $_GET['reservation_id'];
$cancel = $_GET['cancel'];
// $copyID = $_GET['copy_id'];

// Check if the user is allowed to cancel this reservation (optional)
// You can add logic here to ensure the user has the right to cancel this reservation.

// Update the reservation status to "Cancelled"
$updateReservationSql = "UPDATE reservation_tbl SET Status = 'Cancelled', CancelBy='$cancel' WHERE ReservationID = $reservationID";

// Update the book copy status to "Available" (assuming you have a CopyStatus column)
$updateCopyStatusSql = "UPDATE bookcopy_tbl
                        SET Status = 'Available', availability = 1
                        WHERE CopyID = (SELECT CopyID FROM reservation_tbl WHERE ReservationID = $reservationID)";

// Execute the SQL queries
if (mysqli_query($dbconn, $updateReservationSql) && mysqli_query($dbconn, $updateCopyStatusSql)) {
    // Cancellation was successful, you can redirect the user or display a message.
    echo '<script>alert("Reservation has been cancelled successfully."); window.location.href="current_reservation.php";</script>';
} else {
    // Handle the case where the cancellation failed.
    echo '<script>alert("Error cancelling the reservation."); window.location.href="current_reservation.php";</script>';
}
?>
