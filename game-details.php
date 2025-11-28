<?php
session_start();

include("twig-init.php");
include("dbconnect.php");

// Fetch genres
$genresQuery = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
$genres = $genresQuery->fetch_all(MYSQLI_ASSOC);

// Fetch years
$yearsQuery = $mysqli->query("SELECT DISTINCT YEAR(released_date) AS year FROM videogames ORDER BY year DESC");
$years = $yearsQuery->fetch_all(MYSQLI_ASSOC);

// Get ID safely
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query
$sql = "
SELECT videogames.*, genres.genre_name
FROM videogames
LEFT JOIN genres 
    ON videogames.genre_id = genres.genre_id
WHERE videogames.game_id = {$id}
";

$rst = mysqli_query($mysqli, $sql);
$a_row = mysqli_fetch_assoc($rst);

// Render twig
echo $twig->render('game-details.html', [
    'session' => $_SESSION,
    'game'    => $a_row,
    "genres" => $genres,
    "years" => $years,
]);
?>
