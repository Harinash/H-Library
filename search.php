<?php
// Include your database connection code (dbconn.php)
require("dbconn.php");

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    // echo "<script>console.log('uSERIDDDDDDDDD: " . $user_id . "' );</script>";
    // if ($user_id !== null) {
    //     // Use JavaScript to display an alert
    //     echo '<script>alert("User ID: ' . $user_id . '");</script>';
    // } else {
    //     // Handle the case where user_id is not set
    //     echo '<script>alert("User ID is not set.");</script>';
    // }
    // Query to search for books by title or author name
    $sql = "SELECT 
    book_tbl.BookID, 
    book_tbl.Title, 
    book_tbl.BookImage, 
    author_tbl.AuthorName, 
    genre_tbl.GenreName,
    bookCopy_tbl.CopyID,
    bookCopy_tbl.availability  -- Include copy availability
    FROM book_tbl
    INNER JOIN author_tbl ON book_tbl.AuthorID = author_tbl.AuthorID
    INNER JOIN genre_tbl ON book_tbl.GenreID = genre_tbl.GenreID
    LEFT JOIN bookCopy_tbl ON book_tbl.BookID = bookCopy_tbl.BookID
    WHERE Title LIKE '%$searchQuery%'
    OR AuthorName LIKE '%$searchQuery%'
    GROUP BY book_tbl.BookID";

    $result = mysqli_query($dbconn, $sql);

    if ($result) {
        // Loop through the results and display book information
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-lg-3 col-md-6 col-sm-12">';
            echo '<div class="books-card" data-genre="' . $row['GenreName'] . '">';
            echo '<img src="' . $row['BookImage'] . '" alt="' . $row['Title'] . '" class="event-image">';
            echo '<div>';
            echo '<h4><a href="view_book.php?book_id=' . $row['BookID'] . '" class="text-decoration-none text-dark">' . $row['Title'] . '</a></h4>';
            echo '<p class="card-text">' . $row['AuthorName'] . '</p>';
            // echo '<a href="cart.php?book_id=' . $row['BookID'] . '" class="btn btn-primary"><i class="ri-shopping-cart-2-line"></i></a>';
            // echo '<a href="cart.php?book_id=' . $row['BookID'] . '" class="btn btn-primary addToCart" data-availability="' . $row['availability'] . '"><i class="ri-shopping-cart-2-line"></i></a>';
            echo '<a href="javascript:void(0);" class="btn btn-primary addToCart" data-bookid="' . $row['BookID'] . '" data-copyid="' . $row['CopyID'] . '" data-availability="' . $row['availability'] . '" data-userid="' . $user_id . '"><i class="ri-shopping-cart-2-line"></i></a>';
            echo '<a href="view_book.php?book_id=' . $row['BookID'] . '" class="btn btn-secondary" style="margin-left: 10px;"><i class="ri-more-fill"></i></a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        echo "
        <script type=\"text/javascript\">
        $(document).ready(function () {
            // Attach a click event handler to all elements with the class 'addToCart'
            $('.addToCart').click(function () {
                var availability = $(this).data('availability'); // Get the availability status from the data attribute
        
                if (availability === 1) {
                    var bookID = $(this).data('bookid');
                    var copyID = $(this).data('copyid');
                    var userID = $(this).data('userid'); // Retrieve user_id here
        
                    // Check the current number of books in the user's cart
                    $.ajax({
                        url: 'count_cart.php', // Create a PHP script to count the books in the cart
                        type: 'POST',
                        data: { user_id: userID },
                        success: function (response) {
                            var numBooksInCart = parseInt(response);
        
                            if (numBooksInCart >= 5) {
                                // User already has 5 books in the cart, show an error message
                                alert('You can only add up to 5 books to your cart.');
                            } else {
                                // Check if the book is already in the cart
                                $.ajax({
                                    url: 'check_cart.php', // Create a PHP script to check the cart
                                    type: 'POST',
                                    data: { user_id: userID, book_id: bookID },
                                    success: function (response) {
                                        if (response === 'already_in_cart') {
                                            // The book is already in the cart, show an error message
                                            alert('You can only add one copy of the book to your cart.');
                                        } else {
                                            // User can add the book to the cart
                                            $.ajax({
                                                url: 'add_to_cart.php', // Create a PHP script to add to the cart
                                                type: 'POST',
                                                data: { book_id: bookID, copy_id: copyID, user_id: userID },
                                                success: function (response) {
                                                    alert('Book added to cart.'); // Display a success message (you can replace this with your logic)
                                                    location.reload();
                                                },
                                                error: function () {
                                                    alert('Failed to add book to cart.');
                                                }
                                            });
                                        }
                                    },
                                    error: function () {
                                        alert('Failed to check the cart.');
                                    }
                                });
                            }
                        },
                        error: function () {
                            alert('Failed to count the cart.');
                        }
                    });
                } else {
                    // The book is not available, show an alert
                    alert('This book is not available to reserve.');
                }
            });
        });
        </script>
        ";
        
    } 
    else {
        echo "Error: " . mysqli_error($dbconn);
    }

    // Close the database connection
    // mysqli_close($dbconn);
}

?>

<!-- echo '<input type="number" name="quantity" min="1" max="2" style="width:55px; padding: 6px 12px; margin-right:10px;">'; -->
