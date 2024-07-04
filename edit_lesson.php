<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$lesson_id = $_GET['id'];

// Fetch lesson details
$sql = "SELECT * FROM lessons WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$lesson = $stmt->get_result()->fetch_assoc();

// Handle form submission to update lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $youtube_link = $_POST['youtube_link'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    $sql = "UPDATE lessons SET name = ?, description = ?, youtube_link = ?, is_public = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $description, $youtube_link, $is_public, $lesson_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_lessons.php?course_id=" . $lesson['course_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit Lesson</h1>
                                    </div>
                                    <form class="user" action="edit_lesson.php?id=<?= $lesson_id ?>" method="post">
                                        <div class="form-group">
                                            <label for="name">Lesson Name:</label>
                                            <input type="text" class="form-control form-control-user" id="name" name="name" value="<?= htmlspecialchars($lesson['name']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="form-control form-control-user" id="description" name="description" required><?= htmlspecialchars($lesson['description']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="youtube_link">YouTube Link:</label>
                                            <input type="text" class="form-control form-control-user" id="youtube_link" name="youtube_link" value="<?= htmlspecialchars($lesson['youtube_link']) ?>" required>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" <?= $lesson['is_public'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_public">Public</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Update Lesson</button>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
