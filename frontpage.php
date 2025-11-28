<?php
session_start();  // Start the session

include("twig-init.php");
include("dbconnect.php");

// Run SQL query
$sql = "SELECT videogames.*, genres.genre_name 
        FROM videogames 
        LEFT JOIN genres ON videogames.genre_id = genres.genre_id
        ORDER BY game_name";
$results = mysqli_query($mysqli, $sql);

// Fetch genres
$genres = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
$genres = mysqli_fetch_all($genres, MYSQLI_ASSOC);

$genreResults = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
$genres = mysqli_fetch_all($genreResults, MYSQLI_ASSOC);

// Fetch years
$years = $mysqli->query("SELECT DISTINCT YEAR(released_date) AS year FROM videogames ORDER BY year DESC");
$years = mysqli_fetch_all($years, MYSQLI_ASSOC);

echo $twig->render("frontpage.html", [
    "session" => $_SESSION,
    "results" => mysqli_fetch_all($results, MYSQLI_ASSOC),
    "genres" => $genres,
    "years" => $years,
]);
?>
