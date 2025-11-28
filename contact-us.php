<?php
session_start();
include("twig-init.php");
include("dbconnect.php");

// Fetch genres
$genreResults = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
$genres = mysqli_fetch_all($genreResults, MYSQLI_ASSOC);

// Fetch years
$yearResults = $mysqli->query("SELECT DISTINCT YEAR(released_date) AS year FROM videogames ORDER BY year DESC");
$years = mysqli_fetch_all($yearResults, MYSQLI_ASSOC);

echo $twig->render('contact-us.html', [
    'session' => $_SESSION,
    'genres' => $genres,
    'years' => $years
]);
