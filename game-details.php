<?php 
include("templates/header.php");
include("templates/nav.php");

// Connect to database and run SQL query
include("dbconnect.php");

// Uses GET Method to grab id value from URL
$id = $_GET['id'];

$sql = "
SELECT videogames.*, genres.genre_name
FROM videogames
LEFT JOIN genres 
    ON videogames.genre_id = genres.genre_id
WHERE videogames.game_id = {$id}
";

$rst = mysqli_query($mysqli, $sql);
$a_row = mysqli_fetch_assoc($rst);
?>

<div class="container mt-4">
    <div class="row align-items-start">

        <!-- LEFT COLUMN: Text -->
        <div class="col-md-7">
            <h1 class="game-title"><?=$a_row['game_name']?></h1>
            <p><?=$a_row['game_description']?></p>

            <a href="frontpage.php" class="btn btn-secondary mt-3">&laquo; Back to list</a>
        </div>

        <!-- RIGHT COLUMN: Image -->
        <div class="col-md-5 text-center">
            <img src="<?= $a_row['thumbnail_path'] ?? 'uploads/placeholder.jpg' ?>" alt="<?=htmlspecialchars($a_row['game_name'])?>" 
            class="img-fluid rounded shadow">
            <p>Genre: <?= htmlspecialchars($a_row['genre_name'] ?? 'Unknown') ?><br>
		Release Date: <?=htmlspecialchars($a_row['released_date'])?><br>
        Rating: <?=htmlspecialchars($a_row['rating'])?></p>
        </div>

    </div>
</div>

<?php include("templates/footer.php");?>