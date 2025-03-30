<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

include('score_handler.php');

$db = mysqli_connect('localhost', 'root', '', 'player_database');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the username, level, and points are present in the URL parameters
if (isset($_GET['username']) && isset($_GET['level']) && isset($_GET['points'])) {
    $username = mysqli_real_escape_string($db, $_GET['username']);
    $level = mysqli_real_escape_string($db, $_GET['level']);
    $points = mysqli_real_escape_string($db, $_GET['points']);

    // Update the score table with the new data
    updateScoreTable($username, $level, $points);

    // Update the session data after a new game
    updateSessionData();
}

// Retrieve the top 10 players with the most points
if (!isset($_SESSION['topPlayers'])) {
    updateSessionData();
}

function updateScoreTable($username, $level, $points) {
    global $db;
    
    // Check if the user already has a record in the score table
    $checkQuery = "SELECT * FROM score WHERE username = '$username'";
    $checkResult = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update the existing record
        $updateQuery = "UPDATE score SET level = '$level', points = '$points' WHERE username = '$username'";
        mysqli_query($db, $updateQuery);
    } else {
        // Insert a new record
        $insertQuery = "INSERT INTO score (username, level, points) VALUES ('$username', '$level', '$points')";
        mysqli_query($db, $insertQuery);
    }
}

function updateSessionData() {
    global $db;
    $queryTopPlayers = "SELECT username, level, points FROM score ORDER BY points DESC LIMIT 10";
    $result = mysqli_query($db, $queryTopPlayers);

    // Store the result in a session variable
    $_SESSION['topPlayers'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['topPlayers'][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>      
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Board</title>
    <link rel="stylesheet" href="scoreboard.css">

</head>
<body>
<video playsinline autoplay muted loop poster="polina.jpg" id="bgvid">
        <source src="poolballs.mp4" type="video/webm">
    </video>
    <h1 class="score-title">- Quiz Game Score Board -</h1>
    <button onclick="window.location.href='homePage.php'">Back to home</button>
    <table class="score-table">
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Level</th>
            <th>Points</th>
        </tr>
        <?php
        $rank = 1;
        foreach ($_SESSION['topPlayers'] as $row) {
            echo "<tr>";
            echo "<td>{$rank}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['level']}</td>";
            echo "<td>{$row['points']}</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </table>
</body>
</html>
