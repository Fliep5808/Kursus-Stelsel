<?php
include 'config.php';

$listener_id = $_SESSION['listener_id'];
$lesson_id = $_GET['id'];

// Check if the lesson is public or the listener has access to it
$sql = "SELECT l.* FROM lessons l
        LEFT JOIN access_control a ON l.id = a.lesson_id
        WHERE l.id = ? AND (l.is_public = 1 OR a.listener_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $lesson_id, $listener_id);
$stmt->execute();
$lesson = $stmt->get_result()->fetch_assoc();

if (!$lesson) {
    echo "You do not have access to this lesson.";
    exit();
}

// Convert YouTube URL to embed format if necessary
$youtube_link = $lesson['youtube_link'];
if (strpos($youtube_link, 'watch?v=') !== false) {
    $video_id = explode('watch?v=', $youtube_link)[1];
    $youtube_link = "https://www.youtube.com/embed/$video_id";
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($lesson['name']) ?></h1>
</div>

<div class="embed-responsive embed-responsive-16by9">
    <iframe class="embed-responsive-item" src="<?= htmlspecialchars($youtube_link) ?>" allowfullscreen></iframe>
</div>

<div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel">Session Check</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you still watching?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="continueSession">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setInterval(function () {
            $('#sessionModal').modal('show');
        }, 900000); // Show modal every 15 minutes

        $('#continueSession').click(function () {
            $('#sessionModal').modal('hide');
        });

        $('#sessionModal').on('hidden.bs.modal', function () {
            setTimeout(function () {
                alert("You have been logged out due to inactivity.");
                window.location.href = 'logout.php';
            }, 120000); // Log out after 2 minutes if no response
        });

        $(window).on('blur', function () {
            window.location.href = 'logout.php';
        });
    });
</script>
