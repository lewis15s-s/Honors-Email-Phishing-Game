<?php
session_start();

// Set a long-lasting cookie to indicate completion (valid for 1 year)
setcookie('game_completed', 'true', time() + (365 * 24 * 60 * 60), '/');

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the post-game survey
header("Location: https://forms.gle/dYhATNexemss4p667");
exit();
?>