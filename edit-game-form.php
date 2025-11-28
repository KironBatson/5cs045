<?php
session_start();
include("twig-init.php");
include("dbconnect.php");

// Get game ID from URL
$game_id = $_GET['id'];

// Fetch the game
$sql = "SELECT * FROM videogames WHERE game_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$results = $stmt->get_result();
$game = $results->fetch_assoc();

// Fetch all genres
$genreResults = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
$genres = mysqli_fetch_all($genreResults, MYSQLI_ASSOC);

// Fetch years
$yearResults = $mysqli->query("SELECT DISTINCT YEAR(released_date) AS year FROM videogames ORDER BY year DESC");
$years = mysqli_fetch_all($yearResults, MYSQLI_ASSOC);

// Render Twig template
echo $twig->render('edit-game-form.html', [
    'game' => $game,
    'genres' => $genres,
    'years' => $years
]);
?>
