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

// Collect input
$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
$genre_id = (isset($_GET['genre']) && $_GET['genre'] !== "") ? (int)$_GET['genre'] : null;
$release_year = !empty($_GET['release_year']) ? (int)$_GET['release_year'] : null;

// Base SQL
$sql = "SELECT v.*, g.genre_name
        FROM videogames v
        LEFT JOIN genres g ON v.genre_id = g.genre_id
        WHERE 1";

$params = [];
$types = "";

// Filters
if ($keywords !== '') {
    $sql .= " AND v.game_name LIKE ?";
    $params[] = "%{$keywords}%";
    $types .= "s";
}

if ($genre_id !== null) {
    $sql .= " AND v.genre_id = ?";
    $params[] = $genre_id;
    $types .= "i";
}

if ($release_year !== null) {
    $sql .= " AND YEAR(v.released_date) = ?";
    $params[] = $release_year;
    $types .= "i";
}

$sql .= " ORDER BY v.released_date DESC";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$results = $stmt->get_result();



echo $twig->render("search-games.html", [
    "session" => $_SESSION,
    "keywords" => $keywords,
    "results" => $results->fetch_all(MYSQLI_ASSOC),
    "genres" => $genres,
    "years" => $years,
]);
?>