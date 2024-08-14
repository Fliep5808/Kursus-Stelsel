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

// Convert YouTube URL to embed format without autoplay
$youtube_link = $lesson['youtube_link'];
if (strpos($youtube_link, 'watch?v=') !== false) {
    $video_id = explode('watch?v=', $youtube_link)[1];
    $youtube_link = "https://www.youtube.com/embed/$video_id";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
</head>
<body>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($lesson['name']) ?></h1>
    </div>

    <div class="embed-responsive embed-responsive-16by9">
        <iframe id="player" class="embed-responsive-item" src="<?= htmlspecialchars($youtube_link) ?>" allowfullscreen></iframe>
    </div>

    <button id="playButton" class="btn btn-primary mt-3">Play</button>
    <button id="pauseButton" class="btn btn-secondary mt-3">Pause</button>

    <form action="finish_lesson.php" method="post">
        <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
        <button type="submit" class="btn btn-primary mt-3">11Finish Watching</button>
    </form>

    <script>
        var player;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                events: {
                    'onReady': onPlayerReady,
                    'onError': onPlayerError
                }
            });
        }

        function onPlayerReady(event) {
            document.getElementById('playButton').addEventListener('click', function() {
                try {
                    player.playVideo();
                } catch (error) {
                    console.error('Error playing video:', error);
                }
            });

            document.getElementById('pauseButton').addEventListener('click', function() {
                try {
                    player.pauseVideo();
                } catch (error) {
                    console.error('Error pausing video:', error);
                }
            });
        }

        function onPlayerError(event) {
            console.error('YouTube Player Error:', event.data);
        }
    </script>
</body>
</html>
