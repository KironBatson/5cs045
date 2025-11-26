<?php 
include("templates/header.php");
include("templates/nav.php");
?>
<h1 class="text-center">Search results</h1>

<?php 
include("dbconnect.php");

$keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
$genre_id = (isset($_GET['genre']) && $_GET['genre'] !== "") ? (int)$_GET['genre'] : null;
$release_year = !empty($_GET['release_year']) ? (int)$_GET['release_year'] : null;

$sql = "SELECT v.*, g.genre_name 
        FROM videogames v
        LEFT JOIN genres g ON v.genre_id = g.genre_id
        WHERE 1";

$params = [];
$types = "";

// Keyword filter
if ($keywords !== '') {
    $sql .= " AND v.game_name LIKE ?";
    $params[] = "%{$keywords}%";
    $types .= "s";
}

// Genre filter
if ($genre_id !== null) {
    $sql .= " AND v.genre_id = ?";
    $params[] = $genre_id;
    $types .= "i";
}

// Release year filter
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

// Check if any results were found
if ($results->num_rows > 0): 
?>

<!-- Display results in a grid -->
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php while($a_row = mysqli_fetch_assoc($results)): ?>
  <div class="col">
    <div class="card h-100">
      <!-- Game cover -->
      <a href="game-details.php?id=<?=$a_row['game_id']?>"><img src="<?= $a_row['thumbnail_path'] ?? 'uploads/placeholder.jpg' ?>" 
     class="card-img-top" 
     alt="<?= $a_row['game_name'] ?>">

      
      <div class="card-body">
        <h5 class="card-title"><?=htmlspecialchars($a_row['game_name'])?></h5>
        <p class="card-text">Genre: <?= htmlspecialchars($a_row['genre_name'] ?? 'Unknown') ?><br>
        Release Date: <?=htmlspecialchars($a_row['released_date'])?><br>
        Rating: <?=htmlspecialchars($a_row['rating'])?></p>
        <div class="d-flex justify-content-center gap-2 mt-3">
        <a href="game-details.php?id=<?=$a_row['game_id']?>" class="btn btn-primary">View</a>
        </div>
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
    
    <div class="text-center mt-3">
        <a href="frontpage.php" class="btn btn-primary">Back to all games</a>
    </div>

<?php include("templates/footer.php");?>