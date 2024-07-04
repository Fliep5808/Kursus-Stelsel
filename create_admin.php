<?php
// create_admin.php
include 'config.php';

$username = 'admin';
$password = password_hash('yourpassword', PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Admin user created.";
?>
