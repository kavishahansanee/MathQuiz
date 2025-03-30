<?php
// Start the session only if it is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

// Declare updateScoreTable only if it's not already declared
if (!function_exists('updateScoreTable')) {
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
}

// Declare updateSessionData only if it's not already declared
if (!function_exists('updateSessionData')) {
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
}
?>
