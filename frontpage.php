<!--include templates header and navigation bar, also this is how to make a note outside of php tags-->
<?php 
include("templates/header.php");
include("templates/nav.php");
?>
    
<h1 class="text-center">Kiron's Games Archive</h1> <!--main heading-->
<p class="text-center">A personal compendium of all the games I've played — my ongoing work-in-progress archive. 
      Here you'll find ratings, thoughts, and essential information for each title I've explored.</p>
<?php
include("dbconnect.php");
// Run SQL query, * does all field names, its better optimised to chose only what's needed - change it, select field from db order by field
$sql = "SELECT videogames.*, genres.genre_name 
        FROM videogames 
        LEFT JOIN genres ON videogames.genre_id = genres.genre_id
        ORDER BY game_name";
//run query, pass it 2 things, connection used declared in dbconnect.php, and which query being run - declared above
$results = mysqli_query($mysqli, $sql);
?>
        
        <!-- If admin, show add new game button -->
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="d-flex justify-content-center mb-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addgame" >Add new game</button>
        </div>
        <?php endif; ?>
        <br>

<!-- Game cards in a grid layout -->
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php while($a_row = mysqli_fetch_assoc($results)): ?>
  <div class="col">
    <div class="card h-100">
      <!-- Game cover -->
      <a href="game-details.php?id=<?=$a_row['game_id']?>"><img src="<?= $a_row['thumbnail_path'] ?? 'uploads/placeholder.jpg' ?>" 
     class="card-img-top" 
     alt="<?= $a_row['game_name'] ?>">

      <!-- Card body with game details -->
      <div class="card-body">
        <h5 class="game-title"><?=htmlspecialchars($a_row['game_name'])?></h5>
        <p class="card-text">Genre: <?= htmlspecialchars($a_row['genre_name'] ?? 'Unknown') ?><br>
		Release Date: <?=htmlspecialchars($a_row['released_date'])?><br>
        Rating: <?=htmlspecialchars($a_row['rating'])?></p>
        
        <!-- Edit, View, Delete buttons (Centered) --> 
        <div class="d-flex justify-content-center gap-2 mt-3">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="edit-game-form.php?id=<?=$a_row['game_id']?>" class="btn btn-outline-warning">Edit</a>
        <?php endif; ?>
        <a href="game-details.php?id=<?=$a_row['game_id']?>" class="btn btn-primary">View</a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="delete-game.php?id=<?=$a_row['game_id']?>" class="btn btn-outline-danger">Delete</a>
        <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<br>
<?php include("add-game-form.php");?>
<?php include("templates/footer.php");?>
