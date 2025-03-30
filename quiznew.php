<?php
// Start the session only if it is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$db = mysqli_connect('localhost', 'root', '', 'player_database');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Check if the 'level' and 'points' keys are set in the $_POST array
    $level = isset($_POST['level']) ? $_POST['level'] : null;
    $points = isset($_POST['points']) ? $_POST['points'] : null;

    // Update the score table with the new data if 'level' and 'points' are set
    if ($level !== null && $points !== null) {
        updateScoreTable($username, $level, $points);
        // Update the session data after a new game
        updateSessionData();
    }
}

// Handle the timeout scenario
if (isset($_POST['timeout']) && $_POST['timeout'] === 'true') {
    // Retrieve the level and points from the session and update the score table
    $level = $_SESSION['savedLevel'];
    $points = $_SESSION['savedPoints'];
    updateScoreTable($username, $level, $points);
    // Update the session data after a timeout
    updateSessionData();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>      
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Tomato Game</title>
    <link rel="stylesheet" href="quiznew.css">
    <script src="quiznew.js"></script>
</head>
<body>
<div class="header">
    <?php  if (isset($_SESSION['username'])) : ?>
        <p>Hi <strong><?php echo $_SESSION['username']; ?></strong>...</p>
    <?php endif ?>
    <h1 class="game-title">Brace yourself for the tomato challenge!</h1>
</div>
    <div class="game-info">
        <button class="button-62" onclick="Exit()">Exit</button>
        <h2 class="game-level">Level: <span id="level">1</span> | Points: <span id="points">0</span></h2>
        <h2 class="game-Time" id="timer">Time Left: <span id="time-left">60</span> seconds</h2>
    </div>
    <div class="game-container">
        <img id="quest" src="https://www.sanfoh.com/uob/tomato/data/tfb12c83a31e98a2eebd32883ecn29.png">
        <h2 class="game-h2" id="note"></h2>
        <h2 class="game-h2">Navigate the numeric buttons _ what's the missing digit?</h2>
       
        <div id="numberButtons" class="number-buttons-container"></div>
    </div>
</body>
</html>