<?php
include 'config.php';

$sql = "SELECT s.*, l.first_name, l.last_name, l.email, le.name as lesson_name FROM sessions s
        JOIN listeners l ON s.listener_id = l.id
        JOIN lessons le ON s.lesson_id = le.id
        ORDER BY s.login_time DESC";
$sessions = $conn->query($sql);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">User Activity Report</h6>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Lesson</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($session = $sessions->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($session['first_name']) ?></td>
                    <td><?= htmlspecialchars($session['last_name']) ?></td>
                    <td><?= htmlspecialchars($session['email']) ?></td>
                    <td><?= htmlspecialchars($session['lesson_name']) ?></td>
                    <td><?= htmlspecialchars($session['login_time']) ?></td>
                    <td><?= htmlspecialchars($session['logout_time']) ?></td>
                    <td><?= $session['active'] ? 'Yes' : 'No' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#reportTable').DataTable();
    });
</script>
