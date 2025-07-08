document.addEventListener('DOMContentLoaded', function () {
    const mailButton = document.querySelector('.mail_button');
    const loginForm = document.querySelector('.login');
    const approvalForm = document.querySelector('.log_aprv');
    const approvalFormFake = document.querySelector('.log_aprv_fake');
    const emailClient = document.querySelector('.email_client');
    const newMailButton = document.querySelector(".new-mail");
    const reply = document.querySelector('.reply-btn');
    const deny = document.querySelector('.delete-btn');
    const checkbox = document.getElementById('consentCheckbox');
    const continueButton = document.getElementById('continueButton');
    const goButton = document.getElementById('goButton');

    let roundEmails = [];
    let roundIndex = 0;
    // Initilaize new email recieved soundbyte
    let bloop = new Audio('./sound/bloop.mp3');    

    // Mouse click sound effect
    document.addEventListener("click", () => {
        let click = new Audio("./sound/click.mp3");
        click.play().catch(e => console.warn("Click sound failed:", e));
    });

    if (checkbox && continueButton) {
        continueButton.classList.remove('active');
    
        // Toggle active class based on checkbox
        checkbox.addEventListener('change', function () {
            continueButton.classList.toggle('active', checkbox.checked);
        });
    
        // Handle button click
        continueButton.addEventListener('click', function (e) {
            e.preventDefault();
    
            if (!continueButton.classList.contains('active')) return;
    
            // Send consent to PHP
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'consent=agree'
            }).then(() => {
                // Open Google Form in new tab
                window.open('https://forms.gle/oYKB6tWQHEarnj4H8', '_blank');
    
                // Redirect to login
                window.location.href = 'login.php';
            });
        });
    }
    
    // Toggle login form when mail button is clicked
    if (mailButton && loginForm && approvalForm && emailClient) {
        mailButton.addEventListener('click', function (e) {
            e.preventDefault();
            loginForm.style.display = loginForm.style.display === 'none' || loginForm.style.display === '' ? 'block' : 'none';
            approvalForm.style.display = 'none';
            approvalFormFake.style.display = 'none';
            if (emailClient.style.display === 'block') {
                loginForm.style.display = 'none';
            }
        });
    }

    // Show approval form on login submit
    if (loginForm && approvalForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            approvalForm.style.display = 'block';
        });
    }

    // Handle approval form input
    if (approvalForm && loginForm && emailClient) {
        approvalForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const clickedButton = e.submitter;

            if (clickedButton.value === "Approve") {
                loginForm.style.display = 'none';
                approvalForm.style.display = 'none';
                emailClient.style.display = 'block';

                if (!document.getElementById('results')) {
                    const resultsDiv = document.createElement('div');
                    resultsDiv.id = 'results';
                    resultsDiv.style.margin = '10px 0';
                    emailClient.appendChild(resultsDiv);
                }

                attemptCount = 0;
                correct = 0;
                incorrect = 0;
                falsePositives = 0;
                falseNegatives = 0;
                misidentifiedTypes = [];

                prepareNewRound();
                emailReceived();
            } else {
                loginForm.style.display = 'none';
                approvalForm.style.display = "none";
            }
        });
    }

    // Handle clicking new mail button
    if (newMailButton && reply && deny) {
        newMailButton.style.display = "none";

        newMailButton.addEventListener("click", function (e) {
            e.preventDefault();
            loadEmail();
            newMailButton.style.display = "none";
            deny.style.display = "block";
            reply.style.display = "block";
        });
    }

    // User decides if email is real or fake
    if (emailClient) {
        emailClient.addEventListener("submit", function (e) {
            e.preventDefault();
            const clickedButton = e.submitter;

            let userChoice = null;

            if (clickedButton.classList.contains("reply-btn")) {
                userChoice = 'real';
            } else if (clickedButton.classList.contains("delete-btn")) {
                userChoice = 'fake';
            }

            if (userChoice !== null) {
                const email = document.getElementById("randomEmail");
                if (email) email.style.display = "none";
                if (reply) reply.style.display = "none";
                if (deny) deny.style.display = "none";

                checkChoice(userChoice);
            }
        });
    }

    // Array containing the test images (bad emails are sorted into url, scams, attachments, etc in the file structure - this was only for convience when making and sorting them, the relevant indentifiers are the types: )
    const images = [
        { src: './images/bad/scams/1.png', isReal: false, types: ['Grammar'] },
        { src: './images/bad/scams/2.png', isReal: false, types: ['Grammar'] },
        { src: './images/bad/scams/3.png', isReal: false, types: ['Outside Address', 'Urgency'] },
        { src: './images/bad/scams/4.png', isReal: false, types: ['Outside Address', 'Grammar'] },
        { src: './images/bad/scams/5.png', isReal: false, types: ['Outside Address', 'Secrecy', 'Grammar'] },
        { src: './images/bad/scams/6.png', isReal: false, types: ['Subdomain', 'Grammar'] },
        { src: './images/bad/urls/1.png', isReal: false, types: ['Outside Address'] },
        { src: './images/bad/urls/2.png', isReal: false, types: ['Suspicious Attachment'] },
        { src: './images/bad/urls/3.png', isReal: false, types: ['Suspicious Attachment', 'Insider'] },
        { src: './images/bad/urls/4.png', isReal: false, types: ['Outside Address'] },
        { src: './images/bad/urls/5.png', isReal: false, types: ['Outside Address', 'Suspicious Attachment'] },
        { src: './images/bad/urls/6.png', isReal: false, types: ['Insider', 'Urgency', 'Typosquatting'] },
        { src: './images/bad/urls/7.png', isReal: false, types: ['Typosquatting'] },
        { src: './images/bad/subdomains/1.png', isReal: false, types: ['Subdomain'] },
        { src: './images/bad/subdomains/2.png', isReal: false, types: ['Subdomain', 'Secrecy'] },
        { src: './images/bad/subdomains/3.png', isReal: false, types: ['Subdomain', 'Urgency'] },
        { src: './images/bad/subdomains/4.png', isReal: false, types: ['Subdomain', 'Insider'] },
        { src: './images/bad/subdomains/5.png', isReal: false, types: ['Subdomain'] },
        { src: './images/bad/subdomains/6.png', isReal: false, types: ['Subdomain', 'Insider', 'Urgency'] },
        { src: './images/bad/attachments/1.png', isReal: false, types: ['Suspicious Attachment', 'Insider', 'Urgency', 'Secrecy'] },
        { src: './images/bad/attachments/2.png', isReal: false, types: ['Suspicious Attachment', 'Outside Address', 'Typosquatting'] },
        { src: './images/bad/attachments/3.png', isReal: false, types: ['Suspicious Attachment', 'Subdomain', 'Secrecy'] },
        { src: './images/good/IT/1.png', isReal: true },
        { src: './images/good/IT/2.png', isReal: true },
        { src: './images/good/IT/3.png', isReal: true },
        { src: './images/good/IT/4.png', isReal: true },
        { src: './images/good/IT/5.png', isReal: true },
        { src: './images/good/IT/6.png', isReal: true },
        { src: './images/good/HR/1.png', isReal: true },
        { src: './images/good/HR/2.png', isReal: true },
        { src: './images/good/HR/3.png', isReal: true },
        { src: './images/good/HR/4.png', isReal: true },
        { src: './images/good/HR/5.png', isReal: true },
        { src: './images/good/HR/6.png', isReal: true },
        { src: './images/good/coworker/1.png', isReal: true },
        { src: './images/good/coworker/2.png', isReal: true },
        { src: './images/good/coworker/3.png', isReal: true },
        { src: './images/good/coworker/4.png', isReal: true },
        { src: './images/good/coworker/5.png', isReal: true },
        { src: './images/good/boss/1.png', isReal: true },
        { src: './images/good/boss/2.png', isReal: true },
        { src: './images/good/boss/3.png', isReal: true },
        { src: './images/good/boss/4.png', isReal: true },
        { src: './images/good/boss/5.png', isReal: true },
        { src: './images/good/boss/6.png', isReal: true },
        { src: './images/good/boss/7.png', isReal: true },
        { src: './images/good/boss/8.png', isReal: true },
        { src: './images/good/gov/1.png', isReal: true },
        { src: './images/good/gov/2.png', isReal: true },
        { src: './images/good/gov/3.png', isReal: true },
        { src: './images/good/gov/4.png', isReal: true },
    ];

    // Preload images to ensure smooth loading for user
    images.forEach(imgObj => {
        const img = new Image();
        img.src = imgObj.src;
    });    

    // Prepare scoring trackers
    let currentEmail = null;
    let attemptCount = 0;
    const maxAttempts = 10;

    let correct = 0;
    let incorrect = 0;
    let falsePositives = 0;
    let falseNegatives = 0;
    let misidentifiedTypes = [];
    let failureCount = 0;
    let bruteForceInitialized = false;

    // The user recives and email after a short wait
    function emailReceived() {
        const checkingEl = document.getElementById('checkingMail');
        const dotsEl = document.getElementById('dots');
        const randomDelay = Math.random() * 1500 + 1000;
    
        if (checkingEl && newMailButton) {
            // Show "checking..." text
            checkingEl.style.display = "block";
            newMailButton.style.display = "none";
    
            let dotCount = 0;
            dotsEl.textContent = '';
    
            // Animate dots every 300ms
            const dotInterval = setInterval(() => {
                dotCount = (dotCount + 1) % 4;
                dotsEl.textContent = '.'.repeat(dotCount);
            }, 300);
    
            // After a few seconds, stop animation and show new mail button
            setTimeout(() => {
                clearInterval(dotInterval);
                checkingEl.style.display = "none";
                dotsEl.textContent = '';
                newMailButton.style.display = "block";
                // Play new email received soundbyte
                bloop.play().catch(e => console.warn("Bloop sound failed:", e));
            }, randomDelay);
        } 
    }

    // Select 6 good and 4 bad emails from array and randomize their order
    function prepareNewRound() {
        const realEmails = images.filter(email => email.isReal);
        const fakeEmails = images.filter(email => !email.isReal);
    
        function shuffle(arr) {
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
            return arr;
        }
    
        const selectedReal = shuffle(realEmails).slice(0, 6);
        const selectedFake = shuffle(fakeEmails).slice(0, 4);
    
        roundEmails = shuffle([...selectedReal, ...selectedFake]);
        roundIndex = 0;
    }

    // Show email
    function loadEmail() {
        if (roundIndex >= roundEmails.length) {
            prepareNewRound();
        }
    
        currentEmail = roundEmails[roundIndex];
        roundIndex++;
    
        const emailImg = document.getElementById('randomEmail');
        if (emailImg) {
            emailImg.style.display = "none";
            emailImg.onload = function () {
                emailImg.style.display = "block";
            };
            emailImg.src = currentEmail.src;
        }
    }
    
    // Check if users response was correct, if incorrect log the email type; set up brute force test; check how many emails have been shown this round
    function checkChoice(userChoice) {
        const userThinksIsReal = userChoice === 'real';
        const actualIsReal = currentEmail.isReal;

        attemptCount++;

        if (userThinksIsReal === actualIsReal) {
            correct++;
        } else {
            incorrect++;
            if (userThinksIsReal && !actualIsReal) {
                falsePositives++;
                if (currentEmail.types) {
                    misidentifiedTypes.push(...currentEmail.types);
                }
            } else if (!userThinksIsReal && actualIsReal) {
                falseNegatives++;
            }
        }

        if (attemptCount === 6) {
            bruteForceTest();
        }

        if (attemptCount >= maxAttempts) {
            setTimeout(() => {
                const score = Math.round((correct / maxAttempts) * 100);
                submitResultsToDatabase(score);
                submitScoresToResults(score);
            }, 500);
        } else {
            emailReceived();
        }
    }

    // Save results to a form and post them to database via saveScores.php
    function submitResultsToDatabase(score) {
        const formData = new FormData();
        formData.append('userID', window.userID);
        formData.append('score', score);
        formData.append('correct', correct);
        formData.append('incorrect', incorrect);
        formData.append('falsePositives', falsePositives);
        formData.append('falseNegatives', falseNegatives);
        formData.append('bruteForceFailed', failureCount > 0 ? '1' : '0');

        fetch('saveScores.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => console.log('Success:', data))
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error saving your results.');
            });
    }

    // Save results to a form and post them to results.php for feedback
    function submitScoresToResults(score) {
        const formData = new FormData();
        formData.append('score', score);
        formData.append('correct', correct);
        formData.append('incorrect', incorrect);
        formData.append('falsePositives', falsePositives);
        formData.append('falseNegatives', falseNegatives);
        formData.append('misidentifiedTypes', JSON.stringify(misidentifiedTypes));
        formData.append('bruteForceFailed', failureCount > 0 ? '1' : '0');

        fetch('results.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => document.write(data))
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting your results.');
        });
    }

    // Have a fake login approval box appear and note if the user approves it or not
    function bruteForceTest() {
        if (bruteForceInitialized) return;
        bruteForceInitialized = true;

        approvalFormFake.style.display = 'block';

        const approveBtnFake = document.querySelector('.approve-btn-fake');
        const denyBtnFake = document.querySelector('.deny-btn-fake');

        approveBtnFake.addEventListener('click', (e) => {
            e.preventDefault();
            failureCount++;
            approvalFormFake.style.display = 'none';
        });

        denyBtnFake.addEventListener('click', (e) => {
            e.preventDefault();
            approvalFormFake.style.display = 'none';
        });
    }
});