
<?php
	session_start();

	include ("includes/connectionString.php");

	$userId = $_SESSION['userId'] ?? null;
	$consentGiven = $_SESSION['consented'] ?? false;

	if (!$userId) {
		// User tried to access the game without logging in
		header("Location: consent.php");
		exit();
	}

	if (!$consentGiven) {
		// User tried to access the game without consent
		header("Location: consent.php");
		exit();
	}

	// Track how many rounds the user has played
	$round = $_SESSION['round'] ?? 1;
	
	if (!isset($_SESSION['round'])) {
		$_SESSION['round'] = 1;
	} else {
		$_SESSION['round']++;
	}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CMP400 Honours Project</title>
	<link rel="stylesheet" href="css/style.css">
    <script src="javascript/script.js"></script>  
	<script>
	// Pass the PHP userID to JavaScript
	const userID = <?php echo json_encode($userId); ?>;
	window.userID = userID;

	// pass the PHP round counter to JavaScript
    const phpRound = <?php echo $_SESSION['round']; ?>;
    window.round = phpRound;
	</script>
</head>
<body>
<!-- Gameplay Environment: Login, Approval forms, Email client -->
<div class="border">
	<figure class="email_icon">
		<input type="image" class="mail_button" src="images/mail_icon.png" alt="Mail button"/>
		<figcaption>Email</figcaption>
	</figure>
	
	<form class="login">
		<label for="title" class="centered-label">Intranet.yourcompany.net/email</label><br>
		<label for="user">Username:</label><br>
		<input type="text" value="Joe@yourcompany.net" readonly><br>
		<label for="pword">Password:</label><br>
		<input type="password" value="pass123" readonly><br><br>
		<input type="submit" value="Submit">
	</form>

	<form class="email_client">
		<label>EZ Mail</label><br><br>
		<div id="checkingMail" class="checking-mail">Checking for new mail<span id="dots"></span></div>
		<input type="button" value="&#10071; NEW EMAIL" class="new-mail">
		<img id="randomEmail" src="" alt="Email">
		<div class = "emailButtons">
			<input type="submit" value="Delete &#x2620;" class="delete-btn">
			<input type="submit" value="Respond &#11185;" class="reply-btn">
		</div>
	</form>

	<form class="log_aprv">
		<label>Login Detected</label><br>
		<label>Approve Login?</label><br>
		<input type="submit" value="Approve" class="approve-btn">
		<input type="submit" value="Decline" class="deny-btn">
	</form>

	<form class="log_aprv_fake" style="display: none;">
		<label>Login Detected</label><br>
		<label>Approve Login?</label><br>
		<input type="submit" value="Approve" class="approve-btn-fake">
		<input type="submit" value="Decline" class="deny-btn-fake">
	</form>

</div>
</body>
</html>