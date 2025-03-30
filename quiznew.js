var quest = "";
var solution = -1;
var level = 1;
var points = 0;
var timer;
var timeLeft = 60; 
var maxLevels = 12;

let newgame = function () {
    level = 1;
    points = 0; 
    startLevel();
}

let handleInput = function (selectedNumber) {
    let note = document.getElementById("note");
    if (selectedNumber === solution) {
        points += 5; 
        // Increase points and go to the next level if not reached maxLevels
        if (level < maxLevels) {
            level++;
            startLevel();
            updateScore();
            note.innerHTML = "Correct! You're in Level " + level;
        } else {
            // Player completed all levels, send data to the server
            clearInterval(timer);
            note.innerHTML = "You Won The Game ";
            updateScore(); 
            setTimeout(function() {
                alert("Congratulations You Won!");
                sendDataToServer();
                window.location.href = 'homePage.php';
            }, 1000); 
        }
        
    } else {
        note.innerHTML = "Not Correct!";
    }
}


let updateScore = function () {
    // Update level and points display
    document.getElementById("level").textContent = level;
    document.getElementById("points").textContent = points;
}

let startLevel = function () {
    // Reset timer
    clearInterval(timer);

    // Set different time limits based on levels
    if (level <= 4) {
        timeLeft = 60;
    } else if (level <= 8) {
        timeLeft = 25;
    } else if (level <= 12) {
        timeLeft = 10;
    }
    updateTimerDisplay();
    // Fetch new question and solution
    fetchText();
}


let updateTimerDisplay = function () {
    document.getElementById("time-left").textContent = timeLeft;
}

let startTimer = function () {
    // Set up a timer that decreases every second
    timer = setInterval(function () {
        timeLeft--;
        updateTimerDisplay();

        if (timeLeft < 0) {
            timeLeft=0;
            // Game over, go back to the previous page
            clearInterval(timer);
            alert("Time's up! Game over.");
            sendDataToServer();
            window.location.href = 'homePage.php';
        }
        updateTimerDisplay();
    }, 1000);
}

let startQuest = function (data) {
    var parsed = JSON.parse(data);
    quest = parsed.question;
    solution = parsed.solution;

    let img = document.getElementById("quest");
    img.src = quest;

    let note = document.getElementById("note");

    // Display only at the beginning of the first level
    if (level === 1) {
        note.innerHTML = "Hurry up! Time is ticking away";
    }

    // Display number buttons
    let buttonsContainer = document.getElementById("numberButtons");
    buttonsContainer.innerHTML = "";

    for (let i = 0; i <= 9; i++) {
        let button = document.createElement("button");
        button.className = "button-62";
        button.textContent = i;
        button.addEventListener("click", function () {
            handleInput(i);
        });
        buttonsContainer.appendChild(button);
    }

    // Start the timer when a new question is displayed
    startTimer();
}

let fetchText = async function () {
    let response = await fetch('https://marcconrad.com/uob/tomato/api.php');
    let data = await response.text();
    startQuest(data);
}

let sendDataToServer = function () {
    // Send user data to the server for updating the scoreboard
    let formData = new FormData();
    formData.append('level', level);
    formData.append('points', points);

    fetch('scoreboard.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
}


let startup = function () {
    fetchText();
}

startup();

function Exit() {
    sendDataToServer();
    window.location.href = 'homePage.php';
}
