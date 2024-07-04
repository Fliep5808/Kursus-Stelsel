<?php
include 'config.php';

$course_id = $_GET['course_id'];

// Fetch the course name
$sql_course = "SELECT name FROM courses WHERE id = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $course_id);
$stmt_course->execute();
$course = $stmt_course->get_result()->fetch_assoc();
$course_name = $course['name'];

// Handle form submission to add a lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $youtube_link = $_POST['youtube_link'];
    if (strpos($youtube_link, 'watch?v=') !== false) {
    $video_id = explode('watch?v=', $youtube_link)[1];
    $youtube_link = "https://www.youtube.com/embed/$video_id";
    }
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    $sql = "INSERT INTO lessons (course_id, name, description, youtube_link, is_public) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $course_id, $name, $description, $youtube_link, $is_public);
    $stmt->execute();
    $stmt->close();
}

// Fetch all lessons for the course
$sql = "SELECT * FROM lessons WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$lessons = $stmt->get_result();
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Lessons for <?= htmlspecialchars($course_name) ?></h6>
    </div>
    <div class="card-body">
        <form action="manage_lessons.php?course_id=<?= $course_id ?>" method="post">
            <div class="form-group">
                <label for="name">Lesson Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="youtube_link">YouTube Link:</label>
                <input type="text" class="form-control" id="youtube_link" name="youtube_link" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_public" name="is_public">
                <label class="form-check-label" for="is_public">Public</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Lesson</button>
        </form>

        <h3 class="mt-5">Lessons</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>YouTube Link</th>
                    <th>Public</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($lesson = $lessons->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($lesson['name']) ?></td>
                    <td><?= htmlspecialchars($lesson['description']) ?></td>
                    <td><?= htmlspecialchars($lesson['youtube_link']) ?></td>
                    <td><?= $lesson['is_public'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="edit_lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_lesson.php?id=<?= $lesson['id'] ?>&course_id=<?= $course_id ?>" class="btn btn-danger btn-sm">Delete</a>
                        <a href="manage_access.php?lesson_id=<?= $lesson['id'] ?>" class="btn btn-secondary btn-sm">Manage Access</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
