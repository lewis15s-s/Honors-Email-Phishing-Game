Title: Assessing the Effectiveness of Game-Based Learning for Cybersecurity Awareness Training

Author: Lewis Sexton 2103358@uad.ac.uk

Features: 
* Consent: Information for the tester on how their data will be used.
* Login: brief on how the game will be played, also takes the testers unique identifier for later use.
* Desktop: The main page containing the gameplay containers that will be populated by the JavaScript.
* Results: receives the scores logged by the JavaScript and shows the user relevant feedback based on their answers. The user is shown a “try again” button the first two rounds and then a “finish” button on the third round that directs to the post survey (via finish.php).
* saveScores: A handler page that receives the users scores logged by the JavaScript and posts them to a MySQL database for later analysis.
* Finish: A handler page that sets a cookie to indicate the user has completed the game, clears all the session variables, and directs the user to the post survey.
* Thanks: a final page displayed once the game is fully completed that thanks the user for participating and reminds them of who to contact should they have any further questions.

The main functionality of the interface and the backend logic is handled in the script file. Which can be found inside /javascript/script.js and is also further commented there.

If you wish to run this on your own, you will need to fill in the connectionString.php form located in /includes with your own MySQLi database details.

