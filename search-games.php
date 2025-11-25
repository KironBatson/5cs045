<?php 
include("templates/header.php");
include("templates/nav.php");
?>
<h1 class="text-center">Search results</h1>

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

// Check if any results were found
if ($results->num_rows > 0): 
?>

<!-- Display results in a grid -->
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php while($a_row = mysqli_fetch_assoc($results)): ?>
  <div class="col">
    <div class="card h-100">
      <!-- Game cover -->
      <img src="<?= $a_row['thumbnail_path'] ?? 'uploads/placeholder.jpg' ?>" 
     class="card-img-top" 
     alt="<?= $a_row['game_name'] ?>">

      
      <div class="card-body">
        <h5 class="card-title"><?=htmlspecialchars($a_row['game_name'])?></h5>
        <p class="card-text">Released: <?=htmlspecialchars($a_row['released_date'])?><br>
        Rating: <?=htmlspecialchars($a_row['rating'])?></p>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php else: ?>
    <div class="alert alert-warning text-center" role="alert">
        No games found matching your search criteria.
    </div>
<?php endif; ?>

<br>
    
    <div class="text-center mt-3">
        <a href="frontpage.php" class="btn btn-primary">Back to all games</a>
    </div>

<?php include("templates/footer.php");?>