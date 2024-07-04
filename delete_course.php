<?php
// delete_course.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

include 'config.php';

$course_id = $_GET['id'];

$sql = "DELETE FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->close();

header("Location: manage_courses.php");
?>
