<h1>Search results</h1>

<?php 
include("dbconnect.php");

// Read GET values from the form
$keywords = isset($_GET['keywords']) ? $mysqli->real_escape_string(trim($_GET['keywords'])) : '';
$genre_id = isset($_GET['genre']) && $_GET['genre'] !== '' ? (int)$_GET['genre'] : null;
$release_year = isset($_GET['release_year']) && $_GET['release_year'] !== '' ? (int)$_GET['release_year'] : null;

// Build base SQL query
$sql = "SELECT videogames.*, genres.genre_name 
        FROM videogames 
        LEFT JOIN genres ON videogames.genre_id = genres.genre_id
        WHERE 1"; // 1 lets us easily append AND clauses

// Add optional filters
if ($keywords !== '') {
    $sql .= " AND game_name LIKE '%{$keywords}%'";
}
if ($genre_id) {
    $sql .= " AND genre_id = {$genre_id}";
}
if ($release_year) {
    $sql .= " AND YEAR(released_date) = {$release_year}";
}

// Order by release date
$sql .= " ORDER BY released_date DESC";

$results = $mysqli->query($sql);
?>

<table>
<?php while($a_row = mysqli_fetch_assoc($results)):?>
<tr>
<td><a href="game-
details.php?id=<?=$a_row['game_id']?>"><?=$a_row['game_name']?></a></td>
<td><?=$a_row['rating']?></td>
</tr>
<?php endwhile;?>
</table>