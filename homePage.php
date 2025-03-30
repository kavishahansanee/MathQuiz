<?php
session_start();
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Scores</title>
    <style>
body {
        background: url("images/homepg.jpg");
        background-size: cover;
        font-family: 'Times New Roman', Times, serif;
        font-size: 120%;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 90vh;
        margin: 0;
        padding: 0;
        color: rgb(254, 254, 255);
    }
.header {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 10px;
        border-radius: 5px;
        font-size: 170%;
        color: rgb(255, 251, 1); 
    }
.header p {
        margin: 0;
    }
a {
        display: block;
        text-align: center;
        margin-top: 20px;
        padding: 15px 30px;
        background-color:rgb(47, 255, 252); 
        color: black;
        text-decoration: none;
        border-radius: 5px;
    }
a:hover {
        background-color: #2153b1;
    }
 </style>
</head>
<body>
<div class="header">
    <?php  if (isset($_SESSION['username'])) : ?>
        <p>Hey, <strong><?php echo $_SESSION['username']; ?>...!</strong> Your high score awaits!</p>
    <?php endif ?>
</div>
    <div class="go-back">
            <a href="logout.php">logout</a>
            <a href="scoreboard.php">View Score</a>
            <a href="quiznew.php">Play Again</a>
    </div>
</body>
</html>
