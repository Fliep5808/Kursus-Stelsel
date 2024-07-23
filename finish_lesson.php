<?php
session_start();
include 'config.php';

if (!isset($_SESSION['listener_id'])) {
    header("Location: login.php");
    exit();
}

$listener_id = $_SESSION['listener_id'];
$lesson_id = $_POST['lesson_id'];
$end_time = date('Y-m-d H:i:s');

// Log the end time in the sessions table
$sql = "UPDATE sessions SET logout_time = ?, active = 0 WHERE listener_id = ? AND lesson_id = ? AND active = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $end_time, $listener_id, $lesson_id);
$stmt->execute();
$stmt->close();

// Remove access to the lesson
$sql = "DELETE FROM access_control WHERE listener_id = ? AND lesson_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listener_id, $lesson_id);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php");
exit();
?>
