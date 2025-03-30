<?php 
  session_start();  
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Fruit Cards Match</title>
    <link rel="stylesheet" href="cards.css">
    <script src="cards.js"></script>
</head>
<body>
<div class="header">
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome To Quiz Game: <strong><?php echo $_SESSION['username']; ?></strong>...!</p>
        <p>"Hey players, get ready for some card-matching fun!" Jump into your main game 'Quiz'. you must match all the Cards within 15 Tries.</p>
        </div>   	
    <?php endif ?>
<h2>Tries: <span id="errors">0</span></h2>
        <div id="board">
        </div>
        <div class="submit-container">
        <button id="submitBtn">Submit</button>
        </div>
</body>
</html>



