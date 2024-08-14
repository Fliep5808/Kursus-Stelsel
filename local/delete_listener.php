<?php
// delete_listener.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

include 'config.php';

$listener_id = $_GET['id'];

$sql = "DELETE FROM listeners WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listener_id);
$stmt->execute();
$stmt->close();

header("Location: manage_listeners.php");
?>
