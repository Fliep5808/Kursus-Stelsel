<?php
// delete_lesson.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

include 'config.php';

$lesson_id = $_GET['id'];
$course_id = $_GET['course_id'];

$sql = "DELETE FROM lessons WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$stmt->close();

header("Location: manage_lessons.php?course_id=" . $course_id);
?>
