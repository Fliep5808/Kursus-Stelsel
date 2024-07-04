<?php
include 'config.php';

$listener_id = $_SESSION['listener_id'];

$sql = "SELECT l.*, c.name as course_name FROM lessons l
        JOIN courses c ON l.course_id = c.id
        LEFT JOIN access_control a ON l.id = a.lesson_id
        WHERE l.is_public = 1 OR a.listener_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listener_id);
$stmt->execute();
$lessons = $stmt->get_result();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">
    <?php while ($lesson = $lessons->fetch_assoc()): ?>
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($lesson['name']) ?> (<?= htmlspecialchars($lesson['course_name']) ?>)</h6>
            </div>
            <div class="card-body">
                <p><?= htmlspecialchars($lesson['description']) ?></p>
                <a href="lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-primary">View Lesson</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
