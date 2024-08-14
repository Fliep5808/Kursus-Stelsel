<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
$listener_id = isset($_GET['listener_id']) ? intval($_GET['listener_id']) : 0;

if ($lesson_id > 0 && $listener_id > 0) {
    $sql = "DELETE FROM access_control WHERE lesson_id = ? AND listener_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $lesson_id, $listener_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: manage_access.php?lesson_id=" . $lesson_id);
?>
