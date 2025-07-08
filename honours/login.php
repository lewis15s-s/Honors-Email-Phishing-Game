<?php
session_start();

$consentGiven = $_SESSION['consented'] ?? false;

if (!$consentGiven) {
    // User tried to access login without consent
    header("Location: consent.php");
    exit();
}

// Log the users unique identifier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['userId'])) {
        $_SESSION['userId'] = $_POST['userId'];
        header("Location: desktop.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMP400 Honours Project</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="javascript/script.js"></script>
</head>
<body>

<div class ="border">
    <div class="info_box">
        <!-- Gameplay Brief -->
        <h1>Email Security Education Game</h1>
        
        <p>Thank you for participating in this study! In this game you will play as an employee named "Joe", you will review a series of emails and decide whether each one appears to be genuine or fraudulent.</p>
        
        <h3>To get started, please follow these steps:</h3>

        Enter the unique identifier you created in the previous survey in the box below and press continue.<br>
        Click the mail icon and then press log in to begin the game.<br>
        Review each email and decide if it is real or fake by choosing to "Reply" or "Delete."<br>
        At the end of each round, receive feedback based on your choices.<br>
        </p>
        
        <p>You will complete 10 emails per round across 3 rounds. Afterward, you'll be asked to complete a brief survey about your experience.</p>
        
        <h2>We appreciate your time and participation!</h2>
    </div>

    <form class="user_id" method="post" action="">
        <label for="user_id">Enter Your Unique ID:</label><br>
        <input type="text" name="userId" id="user_id" required><br>
        <input type="submit" class="idLog" value="Continue">
    </form>
</div>
</body>
</html>
