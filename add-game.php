<?php

// Connect to database
include("dbconnect.php");

// Read values from the form, uses escape string to prevent SQL injection and allow use of special characters
$game_name = $mysqli->real_escape_string(trim($_POST['GameName']));
$game_description = $mysqli->real_escape_string(trim($_POST['GameDescription']));
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];
$game_genre = $_POST['GameGenre'] ? (int)$_POST['GameGenre'] : "NULL";

// Build SQL statement
$sql = "INSERT INTO videogames(game_name, game_description, released_date, rating, genre_id)
VALUE('{$game_name}', '{$game_description}', '{$game_release_date}', '{$game_rating}', $game_genre)";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
}

// Redirect to list
header("location: frontpage.php");

?>

