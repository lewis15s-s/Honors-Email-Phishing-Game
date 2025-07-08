<?php
session_start();

// Check if the user has already completed the game
if (isset($_COOKIE['game_completed']) && $_COOKIE['game_completed'] === 'true') {
    // Redirect to the thanks page
    header("Location: thanks.php");
    exit();
}

// Confirm the user has consented
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['consent']) && $_POST['consent'] === 'agree') {
        $_SESSION['consented'] = true;
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
    <div class="border">
        <div class="info_box">
            <!-- Consent statement -->
            <h1>Consent Form for Participation in Research Study</h1>

            <div class="scrollable-consent">
                <p><strong>Research Title:</strong> Assessing the Effectiveness of Game-Based Learning in Cybersecurity Awareness Training</p>
                <p><strong>Researcher:</strong> Lewis Sexton (Student ID: 2103358)</p>
                <p><strong>Supervisor:</strong> Marc Kydd</p>

                <h1>What is the research about?</h1>
                <p>This study investigates the effectiveness of game-based learning in improving the ability to distinguish between fraudulent and legitimate emails. As part of the research, you will play a game that involves sorting real emails from fake ones over ten rounds. The game will provide feedback after each attempt to help you improve. You will be asked to play the game at least three times.</p>

                <h1>What will I be required to do?</h1>
                <h3>Preliminary Survey:</h3>
                <p>You will complete a short survey to assess your current knowledge of cybersecurity.</p>
                <h3>Game Participation:</h3>
                <p>You will play a game where you will classify emails as real or fake, receiving feedback to enhance your future performance. You are required to play the game at least three times.</p>
                <h3>Post-Test Survey:</h3>
                <p>After the game, you will complete a final survey about your experience with the game and any feedback you may have.</p>

                <h1>How will my data be handled?</h1>
                <p>Your data will be anonymized immediately after testing. Only Lewis Sexton and his academic advisor, Marc Kydd, will have access to the anonymized data. The data will be securely stored on Abertay University's OneDrive. Any non-anonymized data will be permanently destroyed. Your responses will remain confidential and will not allow for individual identification in any future publications or presentations.

                Abertay University will act as the Data Controller. For questions regarding data protection, contact the Data Protection Officer.
                </p>
                <p>Contact: <strong>DataProtectionOfficer@abertay.ac.uk</strong></p>

                <h1>Retention of Research Data</h1>
                <p>In compliance with Abertay University's data retention policy, anonymized data will be retained indefinitely for potential future research use. Researchers are required to store all research data for up to 10 years post-publication. Consent forms will be retained for as long as we hold information about a participant, and for a minimum of 10 years after research publication, including for research degree theses.</p>

                <h1>Further Information</h1>
                <ul>
                    <li>Lewis Sexton - <strong>2103358@uad.ac.uk</strong></li>
                    <li>Marc Kydd - <strong>m.kydd@abertay.ac.uk</strong></li>
                </ul>

                <h1>Consent Statement</h1>
                <p>Abertay University places great importance on the ethical conduct of research. By indicating your consent, you confirm that:</p>
                <ul>
                    <li>You have read and understood the participant information sheet and consent form.</li>
                    <li>You are at least 18 years old.</li>
                    <li>You understand that participation is voluntary, and you can withdraw from the study at any time without penalty or needing to provide an explanation.</li>
                    <li>You are aware of how your data will be handled and who will have access to it during the research process.</li>
                </ul>

                <p>For more details, see the <a href="https://intranet.abertay.ac.uk/public/research-ethics-privacy-notice-information/">Privacy Notice</a></p>
            </div>
        </div>
                
        <div class="consentButton">
            <input type="checkbox" id="consentCheckbox" class="consentCheck" value="consent">
            <label for="consentCheckbox">I have read and understood the above statements</label><br>
            <a id="continueButton" class="continue-button" href="#">Continue</a>
        </div>
    </div>
</body>
</html>