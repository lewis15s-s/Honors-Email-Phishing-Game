<?php
session_start();

include("includes/connectionString.php");

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate and sanitize input
    if (isset($_POST['userID']) && !empty($_POST['userID'])) {
        $userID = trim($_POST['userID']);
        $_SESSION['userID'] = $userID;
        
        // Get the other form data
        $score = isset($_POST['score']) ? (int)$_POST['score'] : 0;
        $correct = isset($_POST['correct']) ? (int)$_POST['correct'] : 0;
        $incorrect = isset($_POST['incorrect']) ? (int)$_POST['incorrect'] : 0;
        $falsePositives = isset($_POST['falsePositives']) ? (int)$_POST['falsePositives'] : 0;
        $falseNegatives = isset($_POST['falseNegatives']) ? (int)$_POST['falseNegatives'] : 0;
        $bruteForceFailed = isset($_POST['bruteForceFailed']) ? (int)$_POST['bruteForceFailed'] : 0;


        // Use a prepared statement to prevent SQL injection
        $query = "INSERT INTO testUsers (userID, score, correct, incorrect, falsePositives, falseNegatives, bruteForceFailed) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "siiiiii", $userID, $score, $correct, $incorrect, $falsePositives, $falseNegatives, $bruteForceFailed);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "Results saved successfully!";
            } else {
                echo "Database error: Could not insert results. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Statement preparation failed. " . mysqli_error($conn);
        }
    } else {
        echo "userID is required.";
    }
} else {
    echo "Invalid request method.";
}
?>