<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO listeners (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM listeners";
$listeners = $conn->query($sql);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Listeners</h6>
    </div>
    <div class="card-body">
        <form action="manage_listeners.php" method="post">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Listener</button>
        </form>

        <h3 class="mt-5">Listeners</h3>
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
                <?php while ($listener = $listeners->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($listener['first_name']) ?></td>
                    <td><?= htmlspecialchars($listener['last_name']) ?></td>
                    <td><?= htmlspecialchars($listener['email']) ?></td>
                    <td><?= htmlspecialchars($listener['phone']) ?></td>
                    <td>
                        <a href="delete_listener.php?id=<?= $listener['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
