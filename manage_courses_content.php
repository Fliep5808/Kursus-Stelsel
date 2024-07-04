<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO courses (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM courses";
$courses = $conn->query($sql);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Courses</h6>
    </div>
    <div class="card-body">
        <form action="manage_courses.php" method="post">
            <div class="form-group">
                <label for="name">Course Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>

        <h3 class="mt-5">Courses</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($course['name']) ?></td>
                    <td><?= htmlspecialchars($course['description']) ?></td>
                    <td>
                        <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_course.php?id=<?= $course['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        <a href="manage_lessons.php?course_id=<?= $course['id'] ?>" class="btn btn-secondary btn-sm">Manage Lessons</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
