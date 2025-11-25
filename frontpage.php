<!--include templates header and navigation bar, also this is how to make a note outside of php tags-->
<?php 
include("templates/header.php");
include("templates/nav.php");
?>
    
<h1 class="text-center">Kiron's Games Archive</h1> <!--main heading-->

<?php
include("dbconnect.php");
// Run SQL query, * does all field names, its better optimised to chose only what's needed - change it, select field from db order by field
$sql = "SELECT * FROM videogames ORDER BY game_name";
//run query, pass it 2 things, connection used declared in dbconnect.php, and which query being run - declared above
$results = mysqli_query($mysqli, $sql);
?>

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
        
        <a href="game-details.php?id=<?=$a_row['game_id']?>" class="btn btn-primary">View</a>
        <a href="edit-game.php?id=<?=$a_row['game_id']?>" class="btn btn-outline-warning">Edit</a>
        <a href="delete-game.php?id=<?=$a_row['game_id']?>" class="btn btn-outline-danger">Delete</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<br>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addgame" >Add new game</button>

<?php include("add-game-form.php");?>
<?php include("templates/footer.php");?>