<?php
include 'config.php';

// Define the new admin credentials
$username = 'MilanB'; // Change this to your desired username
$password = 'Milan123#'; // Change this to your desired password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert the new admin user into the database
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();
$stmt->close();
$conn->close();

echo "New admin user created successfully.";
?>