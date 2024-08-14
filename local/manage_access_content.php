<?php
include 'config.php';

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
if ($lesson_id <= 0) {
    echo "Invalid lesson ID.";
    exit();
}

// Handle form submission to add access
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $listener_id = isset($_POST['listener_id']) ? intval($_POST['listener_id']) : 0;
    if ($listener_id > 0) {
        $sql = "INSERT INTO access_control (lesson_id, listener_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $lesson_id, $listener_id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Failed to prepare statement.";
        }
    } else {
        echo "Invalid listener ID.";
    }
}

// Fetch all listeners
$sql = "SELECT * FROM listeners";
$listeners = $conn->query($sql);
if (!$listeners) {
    echo "Failed to fetch listeners.";
    exit();
}

// Fetch listeners with access
$sql = "SELECT l.* FROM listeners l
        JOIN access_control a ON l.id = a.listener_id
        WHERE a.lesson_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $access_listeners = $stmt->get_result();
    $stmt->close();
} else {
    echo "Failed to prepare statement for access listeners.";
    exit();
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Access for Lesson ID <?= htmlspecialchars($lesson_id) ?></h6>
    </div>
    <div class="card-body">
        <form action="manage_access.php?lesson_id=<?= $lesson_id ?>" method="post">
            <div class="form-group">
                <label for="listener_id">Select Listener:</label>
                <select class="form-control" id="listener_id" name="listener_id" required>
                    <?php while ($listener = $listeners->fetch_assoc()): ?>
                    <option value="<?= $listener['id'] ?>"><?= htmlspecialchars($listener['first_name'] . ' ' . $listener['last_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Access</button>
        </form>

        <h3 class="mt-5">Listeners with Access</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($listener = $access_listeners->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($listener['first_name']) ?></td>
                    <td><?= htmlspecialchars($listener['last_name']) ?></td>
                    <td><?= htmlspecialchars($listener['email']) ?></td>
                    <td><?= htmlspecialchars($listener['phone']) ?></td>
                    <td>
                        <a href="remove_access.php?lesson_id=<?= $lesson_id ?>&listener_id=<?= $listener['id'] ?>" class="btn btn-danger btn-sm">Remove Access</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
