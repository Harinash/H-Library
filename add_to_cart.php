<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is logged in or has a valid session
    // You should have user authentication logic here

    // Get the book ID and copy ID from the POST data
    $bookID = $_POST["book_id"];
    $copyID = $_POST["copy_id"];
    $userID = $_POST["user_id"];
    // Perform any additional validation or checks here
    
    // Fetch all available copies of the book
    $sql = "SELECT CopyID FROM bookCopy_tbl WHERE BookID = $bookID AND availability = 1";
    $result = mysqli_query($dbconn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Retrieve all available copy IDs
        $availableCopies = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $availableCopies[] = $row['CopyID'];
        }

        // Select a copy randomly
        $randomCopyID = $availableCopies[array_rand($availableCopies)];

        // Insert the selected copy into the cart
        $insertSql = "INSERT INTO cart_tbl (UserID, BookID, CopyID) VALUES ($userID, $bookID, $randomCopyID)";
        $resultAdd = mysqli_query($dbconn, $insertSql);
    }
//         if (mysqli_query($dbconn, $insertSql)) {
//             // Update the availability of the selected copy to 0 (reserved)
//             $updateSql = "UPDATE bookCopy_tbl SET availability = 0 WHERE CopyID = $randomCopyID";
//             mysqli_query($dbconn, $updateSql);
            
//             // Return a success message
//             echo '<script>alert("ADDED TO CART")</script>';
//         } else {
//             // Error message if insertion fails
//             echo '<script>alert("CANT ADD TO CART");</script>';
//         }
//     } else {
//         // No available copies found, return an error message
//         echo '<script>alert("NO COPIES");</script>';
//     }
// } else {
//     // Invalid request, return an error message
//     echo '<script>alert("INVALID REQUEST"); window.location.href="home.php";</script>';
}
?>
