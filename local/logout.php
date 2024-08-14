<?php
session_start();
include 'config.php';

if (isset($_SESSION['listener_id'])) {
    $listener_id = $_SESSION['listener_id'];
    $logout_time = date('Y-m-d H:i:s');

    $sql = "UPDATE sessions SET logout_time = ?, active = 0 WHERE listener_id = ? AND active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $logout_time, $listener_id);
    $stmt->execute();

    session_destroy();
    header("Location: login.php");
} else {
    header("Location: login.php");
}
?>
