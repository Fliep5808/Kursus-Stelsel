<?php
session_start();
include 'config.php';

if (!isset($_SESSION['listener_id'])) {
    header("Location: login.php");
    exit();
}

$listener_id = $_SESSION['listener_id'];
$lesson_id = $_POST['lesson_id'];
$start_time = date('Y-m-d H:i:s');

// Log the start time in the sessions table
$sql = "INSERT INTO sessions (listener_id, lesson_id, login_time, active) VALUES (?, ?, ?, 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $listener_id, $lesson_id, $start_time);
$stmt->execute();
$stmt->close();

header("Location: lesson.php?id=" . $lesson_id);
exit();
?>
