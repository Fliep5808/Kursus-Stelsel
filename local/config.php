<?php
// config.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "livestream_system";

// Online Password

/*$servername = "localhost";
$username = "ewigeli2_facialrec";
$password = "4P2C4gvH3s68Y4G";
$dbname = "ewigeli2_livestream_system";
*/
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>