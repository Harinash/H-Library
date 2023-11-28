<?php
// session_start();
// $dbservername = "localhost";
// $dbusername = "root";
// $dbpassword = "";
// Create connection
// $dbconn = mysqli_connect($dbservername, $dbusername, $dbpassword);
$dbconn = mysqli_connect("localhost", "root", "", "hlibrarydb");
// Check connection
if (!$dbconn) {
    echo "Connected unsuccessfully";
    die("Connection failed: " . mysqli_connect_error());
}
?>