<?php include("dbconnect.php");

// Read updated values from the form
$game_id = $_POST['game_id'];
$game_name = $mysqli->real_escape_string($_POST['GameName']);
$game_description = $mysqli->real_escape_string($_POST['GameDescription']);
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];


// Build SQL UPDATE statement
$sql = "UPDATE videogames 
        SET game_name = '{$game_name}',
            game_description = '{$game_description}',
            released_date = '{$game_release_date}',
            rating = '{$game_rating}'
        WHERE game_id = {$game_id}";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
    echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
    exit();
}

// Redirect back to front page
header("location: frontpage.php");

?>
