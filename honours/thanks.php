<?php
session_start();

// Check if the user has NOT completed the game
if (!isset($_COOKIE['game_completed']) || $_COOKIE['game_completed'] === 'false') {
    // Redirect to consent page
    header("Location: consent.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Study Already Completed</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="border">
        <div class="info_box">
            <!-- Final thank you for users who participated -->
            <h1>Thank You</h1>
            <p>Thank you very much for participating in this study, your time is greatly appreciated.</p>
            <p>As mentioned prior, your data will be anonymized and stored securely for up to ten years after research publication.</p>
            <p>If you have any further questions, please contact the researcher:</p>
            <p>Lewis Sexton - <strong>2103358@uad.ac.uk</strong></p>
        </div>
    </div>
</body>
</html>