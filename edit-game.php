<?php 
include("templates/header.php");
include("templates/nav.php");
include("dbconnect.php");

// Read the game_id from the URL
$game_id = $_GET['id'];

// Query the database for this game's details
$sql = "SELECT * FROM videogames WHERE game_id = {$game_id}";
$results = mysqli_query($mysqli, $sql);

// Convert result into an associative array
$game = mysqli_fetch_assoc($results);
?>

<h1>Edit <?=$game['game_name']?></h1>

<form action="edit-game-form.php" method="post">

<input type="hidden" name="game_id" value="<?=$game['game_id']?>">

<div class="mb-3">
    <label for="GameName" class="form-label">Game name</label>
    <input type="text" class="form-control" id="GameName" 
        name="GameName" value="<?=$game['game_name']?>">
</div>

<div class="mb-3">
    <label for="GameDescription" class="form-label">Description</label>
    <textarea class="form-control" id="GameDescription" 
        name="GameDescription" rows="5"><?=$game['game_description']?></textarea>
</div>

<div class="mb-3">
    <label for="DateReleased" class="form-label">Date released</label>
    <input type="date" class="form-control" id="DateReleased" 
        name="DateReleased" value="<?=$game['released_date']?>">
</div>

<div class="mb-3">
    <label for="GameRating" class="form-label">Rating</label>
    <input type="number" class="form-control" id="GameRating" 
        name="GameRating" value="<?=$game['rating']?>">
</div>

<input type="submit" class="btn btn-success" value="Save Changes"> <a href="frontpage.php" class="btn btn-danger">Cancel</a>

</form>

<?php include("templates/footer.php");?>
