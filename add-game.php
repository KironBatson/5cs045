<?php

// Connect to database
include("dbconnect.php");

// Read values from the form
$game_name = $mysqli->real_escape_string($_POST['GameName']);
$game_description = $mysqli->real_escape_string($_POST['GameDescription']);
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];


// Build SQL statement
$sql = "INSERT INTO videogames(game_name, game_description, released_date, rating)
VALUE('{$game_name}', '{$game_description}', '{$game_release_date}', '{$game_rating}')";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
}

// Redirect to list
header("location: frontpage.php");

?>

