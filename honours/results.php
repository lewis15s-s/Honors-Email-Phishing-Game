<?php
session_start();
include ("includes/connectionString.php");

$userId = $_SESSION['userId'] ?? null;
$consentGiven = $_SESSION['consented'] ?? false;

if (!$userId) {
    // User tried to access results without logging in
    header("Location: consent.php");
    exit();
}

if (!$consentGiven) {
    // User tried to access results without consent
    header("Location: consent.php");
    exit();
}

// Recieve the users scores from the game and prepare them for presentation
$round = $_SESSION['round'] ?? 0;
$score = $_POST['score'] ?? 0;
$correct = $_POST['correct'] ?? 0;
$incorrect = $_POST['incorrect'] ?? 0;
$falsePositives = $_POST['falsePositives'] ?? 0;
$falseNegatives = $_POST['falseNegatives'] ?? 0;
$misidentifiedTypes = json_decode($_POST['misidentifiedTypes'] ?? '[]');
$bruteForceFailed = $_POST['bruteForceFailed'] ?? '0';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Phishing Results</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
	// Pass the PHP userID to JavaScript
	const userID = <?php echo json_encode($userId); ?>;
	window.userID = userID;
	</script>
</head>
<body class="results-background">
    <div class="result-container">
        <!-- Displaying the results -->
        <h1 class="round-counter">Round <?php echo $round; ?> out of 3</h1>
        <h1 class="score-heading">Your Score: <?php echo $score; ?>%</h1>
        <p class="result-summary">
            <strong>Correct:</strong> <?php echo $correct; ?><br>
            <strong>Incorrect:</strong> <?php echo $incorrect; ?><br>
            <strong>Fake Emails Approved:</strong> <?php echo $falsePositives; ?><br>
            <strong>Real Emails Deleted:</strong> <?php echo $falseNegatives; ?>
        </p>

        <?php if (empty($misidentifiedTypes) && $falseNegatives == 0) : ?>
            <p class="no-mistakes">Perfect Score! Great Job!</p>
        <?php else: ?>
            <?php if ($falseNegatives > 0): ?>
                <p class="real-email-warning">
                    ⚠️ You incorrectly deleted <?php echo $falseNegatives; ?> real email<?php echo $falseNegatives > 1 ? 's' : ''; ?>.
                    Be careful — not all legit messages look perfect!
                </p>
                <p class="real-email-tip">
                    If you're ever unsure, it's safer to leave the message and double-check with IT or a manager before taking action.
                </p>
            <?php endif; ?>

            <!-- Check what type of emails were misidentified and present the user with relevant feedback to help improve their scores -->
            <?php if (!empty($misidentifiedTypes)): ?>
                <h2 class="missed-header">Tips:</h2>
                <ul class="missed-list">
                    <?php
                        $feedback = [
                            'Grammar' => 'Watch out for poor grammar and awkward phrasing—these are common signs of potential phishing. For example: "Your account are suspended" or "Click here to reactive." Legitimate organizations usually proofread their messages. Furthermore, beware of vague, sweeping questions - it may be an attempt to get people to open up.',
                            'Outside Address' => 'Be cautious with emails from unfamiliar or foreign domains, especially those from countries like Russia (.ru), China (.cn), or Iran (.ir). While receiving malicious traffic directly from these domains is uncommon, its not impossible. If youre not in regular contact with organizations or individuals in those regions, treat such emails with extra scrutiny. The company network is mentioned early in the message—make sure all email addresses match who they claim to be, and verify any unexpected attachments or requests before engaging.',
                            'Urgency' => 'Urgent language can pressure users into making mistakes, try and stay mindful of security even if someone is rushing you to do something.',
                            'Suspicious Attachment' => 'If you receive an email with attached files that you were not expecting, or that seem irrelevant to your work, it is best not to click on them. If you are especially concerned, report the email to IT or simply delete it. Be especially vigilant with compressed ".zip" files if you do not usually recieve them and look out for executable file types like ".exe", ".bat", or ".dll" that may be hidden inside some scam emails.',
                            'Subdomain' => 'Subdomains are sites that are connected to larger domains and they are not as tightly regulated, for example: "bank.com" may be secure but a hacker could register "secure.bank.com", "login.bank.com" or "email@bank-support.com" in order to fool people. Be on the lookout for this if you are using a service you are not used to. If you are uncertain you can always just search for the main domain instead.',
                            'Secrecy' => 'If you are asked to transfer money or sensitive information, be absolutley certain the person who is asking is definetly who they claim to be. Any instructions to keep a request a secret should be cause for increased scrutiny.',
                            'Insider' => 'While very unlikely to happen, it is not impossible that a hacker could contact you from a real internal address that has been compromised. Be aware of what is contained within the emails you recieve, and if you recieve a message from someone you arent expecting consider using a 3rd party service or contacting them directly to confirm the authenticity of the email.',
                            'Typosquatting' => '"Typosquatting" or "Domain Spoofing" is the practice of registering domains with very similiar names to legitimate ones in the hope a user may mistype the fake address into their search bar, or that they dont notice the error in a link they click on. for example "mircosoft.com" or "HBSC.com" look very similiar to real domains but both have typos in them.'
                        ];
                    foreach (array_unique($misidentifiedTypes) as $type):
                    ?>
                        <li class="missed-item"><strong><?php echo ucfirst($type); ?></strong>: <?php echo $feedback[$type] ?? 'No specific tip available.'; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($bruteForceFailed === '1') : ?>
            <div class="security-warning">
                Security Warning: You approved a suspicious login attempt.<br>
                Be cautious about unexpected authorization requests!
            </div>
        <?php endif; ?>

        <div>
        <!-- If round counter is less than 3 show "try again", if round counter equals 3 show "finish" -->
            <a class="retry-button" href="desktop.php" style="display: <?php echo ($round < 3) ? 'block' : 'none'; ?>">🔁 Try Again</a>
            <a class="finish-button" href="finish.php" style="display: <?php echo ($round == 3) ? 'block' : 'none'; ?>">Finish ⇨</a>
        </div>
    </div>>
</body>
</html>
